<?php

namespace im\elfinder;

use elFinder;
use elFinderVolumeDriver;
use im\filesystem\components\flysystem\plugins\UrlPlugin;
use League\Flysystem\Adapter\AbstractFtpAdapter;
use League\Flysystem\Dropbox\DropboxAdapter;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Util;
use League\Glide\Http\UrlBuilderFactory;

class FlysystemDriver extends elFinderVolumeDriver
{
    /**
     * @inheritdoc
     */
    protected $driverId = 'fls';

    /**
     * @var Filesystem $fs
     */
    protected $fs;

    /**
     * @var \League\Glide\Http\UrlBuilder $urlBuilder
     */
    protected $urlBuilder = null;

    /**
     * @var ImageManager $imageManager
     */
    protected $imageManager = null;

    /**
     * Extend options with required fields
     */
    public function __construct()
    {
        $opts = [
            'filesystem' => null,
            'glideURL' => null,
            'glideKey' => null,
            'imageManager' => null
        ];
        $this->options = array_merge($this->options, $opts);
    }

    /**
     * @inheritdoc
     */
    public function mount(array $opts)
    {
        // If path is not set, use the root
        if (!isset($opts['path']) || $opts['path'] === '') {
            $opts['path'] = '/';
        }

        return parent::mount($opts);
    }

    /**
     * Find the icon based on the used Adapter
     *
     * @return string
     */
    protected function getIcon()
    {
        try {
            $adapter = $this->fs->getAdapter();
        } catch (\Exception $e) {
            $adapter = null;
        }
        if ($adapter instanceof AbstractFtpAdapter) {
            $icon = 'volume_icon_ftp.png';
        } elseif ($adapter instanceof DropboxAdapter) {
            $icon = 'volume_icon_dropbox.png';
        } else {
            $icon = 'volume_icon_local.png';
        }
        $parentUrl = defined('ELFINDER_IMG_PARENT_URL') ? (rtrim(ELFINDER_IMG_PARENT_URL, '/') . '/') : '';

        return $parentUrl . 'img/' . $icon;
    }

    /**
     * @inheritdoc
     */
    protected function init()
    {
        $this->fs = $this->options['filesystem'];
        if (!($this->fs instanceof FilesystemInterface) && !($this->fs instanceof \creocoder\flysystem\Filesystem)) {
            return $this->setError('A filesystem instance is required');
        }
        $this->fs->addPlugin(new UrlPlugin());
        isset($this->options['icon']) ?: $this->options['icon'] = $this->getIcon();
        $this->root = $this->options['path'];
        if ($this->options['glideURL']) {
            $this->urlBuilder = UrlBuilderFactory::create($this->options['glideURL'], $this->options['glideKey']);
        }
        if ($this->options['imageManager']) {
            $this->imageManager = $this->options['imageManager'];
        } else {
//            $this->imageManager = new ImageManager();
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function _dirname($path)
    {
        return Util::dirname($path) ?: '/';
    }

    /**
     * @inheritdoc
     */
    protected function _normpath($path)
    {
        return $path;
    }

    /**
     * @inheritdoc
     */
    protected function _stat($path)
    {
        $stat = array(
            'size' => 0,
            'ts' => time(),
            'read' => true,
            'write' => true,
            'locked' => false,
            'hidden' => false,
            'mime' => 'directory',
        );

        // If root, just return from above
        if ($this->root == $path) {
            $stat['name'] = $this->root;
            return $stat;
        }

        // If not exists, return empty
//        if ( !$this->fs->has($path)) {
//            return array();
//        }
//
//        $meta = $this->fs->getMetadata($path);

        try {
            $meta = $this->fs->getMetadata($path);
        } catch (FileNotFoundException $e) {
            $path = $path . '/';
            try {
                $meta = $this->fs->getMetadata($path);
            } catch (FileNotFoundException $e) {
                return array();
            }
        }

        // Get timestamp/size
        $stat['ts'] = isset($meta['timestamp'])? $meta['timestamp'] : $this->fs->getTimestamp($path);
        $stat['size'] = isset($meta['size'])? $meta['size'] : $this->fs->getSize($path);

        // Check if file, if so, check mimetype
        if ($meta['type'] == 'file') {
            $stat['mime'] = isset($meta['mimetype'])? $meta['mimetype'] : $this->fs->getMimetype($path);
            $imgMimes = ['image/jpeg', 'image/png', 'image/gif'];
            if ($this->urlBuilder && in_array($stat['mime'], $imgMimes)) {
                $stat['url'] = $this->urlBuilder->getUrl($path, ['ts' => $stat['ts']]);
                $stat['tmb'] = $this->urlBuilder->getUrl($path, [
                    'ts' => $stat['ts'],
                    'w' => $this->tmbSize,
                    'h' => $this->tmbSize,
                    'fit' => $this->options['tmbCrop'] ? 'crop' : 'contain',
                ]);
            }
        }

        if (!isset($stat['url']) && $this->fs->getUrl()) {
            $stat['url'] = 1;
        }

        return $stat;
    }

    /**
     * @inheritdoc
     */
    protected function _subdirs($path)
    {
        $ret = false;
        foreach ($this->fs->listContents($path) as $meta) {
            if ($meta['type'] !== 'file') {
                $ret = true;
                break;
            }
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    protected function _dimensions($path, $mime)
    {
        $ret = false;
        if ($imgsize = $this->getImageSize($path, $mime)) {
            $ret = $imgsize['dimensions'];
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    protected function _scandir($path)
    {
        $paths = array();
        foreach ($this->fs->listContents($path, false) as $object) {
            $paths[] = $object['path'];
        }

        return $paths;
    }

    /**
     * @inheritdoc
     */
    protected function _fopen($path, $mode = "rb")
    {
        return $this->fs->readStream($path);
    }

    /**
     * @inheritdoc
     */
    protected function _fclose($fp, $path='')
    {
        return @fclose($fp);
    }

    /**
     * @inheritdoc
     */
    protected function _mkdir($path, $name)
    {
        $path = $this->_joinPath($path, $name);
        if ($this->fs->createDir($path)) {
            return $path;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _mkfile($path, $name)
    {
        $path = $this->_joinPath($path, $name);
        if ($this->fs->write($path, '')) {
            return $path;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _copy($source, $target, $name)
    {
        return $this->fs->copy($source, $this->_joinPath($target, $name));
    }

    /**
     * @inheritdoc
     */
    protected function _move($source, $target, $name)
    {
        $path = $this->_joinPath($target, $name);
        if ($this->fs->rename($source, $path)) {
            return $path;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _unlink($path)
    {
        return $this->fs->delete($path);
    }

    /**
     * @inheritdoc
     */
    protected function _rmdir($path)
    {
        return $this->fs->deleteDir($path);
    }

    /**
     * @inheritdoc
     */
    protected function _save($fp, $dir, $name, $stat)
    {
        $path = $this->_joinPath($dir, $name);
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $config = [];
        if (isset(self::$mimetypes[$ext])) {
            $config['mimetype'] = self::$mimetypes[$ext];
        }
        if ($this->fs->putStream($path, $fp, $config)) {
            return $path;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _getContents($path)
    {
        return $this->fs->read($path);
    }

    /**
     * @inheritdoc
     */
    protected function _filePutContents($path, $content)
    {
        return $this->fs->put($path, $content);
    }

    /**
     * @inheritdoc
     */
    protected function _basename($path) {
        return basename($path);
    }

    /**
     * @inheritdoc
     */
    protected function _joinPath($dir, $name)
    {
        return Util::normalizePath($dir.$this->separator.$name);
    }

    /**
     * @inheritdoc
     */
    protected function _relpath($path)
    {
        return $path;
    }

    /**
     * @inheritdoc
     */
    protected function _abspath($path)
    {
        return $path;
    }

    /**
     * @inheritdoc
     */
    protected function _path($path)
    {
        return $path;
    }

    /**
     * @inheritdoc
     */
    protected function _inpath($path, $parent)
    {
        return $path == $parent || strpos($path, $parent.'/') === 0;
    }

    /**
     * @inheritdoc
     */
    protected function _symlink($source, $targetDir, $name)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _extract($path, $arc)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _archive($dir, $files, $name, $arc)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _checkArchivers()
    {
        return;
    }

    /**
     * @inheritdoc
     */
    protected function _chmod($path, $mode)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function resize($hash, $width, $height, $x, $y, $mode = 'resize', $bg = '', $degree = 0)
    {
        if ($this->commandDisabled('resize')) {
            return $this->setError(elFinder::ERROR_PERM_DENIED);
        }
        if (($file = $this->file($hash)) == false) {
            return $this->setError(elFinder::ERROR_FILE_NOT_FOUND);
        }
        if (!$file['write'] || !$file['read']) {
            return $this->setError(elFinder::ERROR_PERM_DENIED);
        }
        $path = $this->decode($hash);
        if (!$this->canResize($path, $file)) {
            return $this->setError(elFinder::ERROR_UNSUPPORT_TYPE);
        }
        if (!$image = $this->imageManager->make($this->_getContents($path))) {
            return false;
        }
        switch($mode) {
            case 'propresize':
                $image->resize($width, $height, function($constraint){
                    $constraint->aspectRatio();
                });
                break;
            case 'crop':
                $image->crop($width, $height, $x, $y);
                break;
            case 'fitsquare':
                $image->fit($width, $height, null, 'center');
                break;
            case 'rotate':
                $image->rotate($degree);
                break;
            default:
                $image->resize($width, $height);
                break;
        }
        $result = (string) $image->encode();
        if ($result && $this->_filePutContents($path, $result)) {
            $stat = $this->stat($path);
            $stat['width'] = $image->width();
            $stat['height'] = $image->height();
            return $stat;
        }

        return false;
    }

    /**
     * Return image size
     *
     * @param string $path
     * @param string $mime
     * @return array|bool
     */
    public function getImageSize($path, $mime = '')
    {
        $size = false;
        if ($mime === '' || strtolower(substr($mime, 0, 5)) === 'image') {
            if ($data = $this->_getContents($path)) {
                if ($size = @getimagesizefromstring($data)) {
                    $size['dimensions'] = $size[0].'x'.$size[1];
                }
            }
        }

        return $size;
    }

    /**
     * Return content URL
     *
     * @param string $hash file hash
     * @param array $options options
     * @return string
     **/
    public function getContentUrl($hash, $options = array())
    {
        if (($file = $this->file($hash)) == false || !$file['url'] || $file['url'] == 1) {
            $path = $this->decode($hash);
            return $this->fs->getUrl($path);
        }

        return $file['url'];
    }
}