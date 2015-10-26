<?php

namespace im\elfinder;

use Aws\S3\S3Client;
use elFinderVolumeDriver;
use InvalidArgumentException;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Config;
use League\Glide\Urls\UrlBuilderFactory;

class S3Driver extends elFinderVolumeDriver
{
    /**
     * @inheritdoc
     */
    protected $driverId = 's3s';

    /**
     * @var S3Client
     */
    protected $s3Client;

    /**
     * @var AwsS3Adapter
     */
    protected $s3Adapter;

    /**
     * @var \League\Glide\Urls\UrlBuilder $urlBuilder
     */
    protected $urlBuilder = null;

    /**
     * @var array
     */
    protected static $resultMap = [
        'Body'          => 'contents',
        'ContentLength' => 'size',
        'ContentType'   => 'mimetype',
        'Size'          => 'size',
    ];

    /**
     * Extend options with required fields
     */
    public function __construct()
    {
        $opts = [
            'key' => null,
            'secret' => null,
            'region' => null,
            'version' => 'latest',
            'bucket' => null,
            'prefix' => null
        ];
        $this->options = array_merge($this->options, $opts);
        $this->options['mimeDetect'] = 'internal';
    }

    /**
     * @inheritdoc
     */
    public function mount(array $opts)
    {
        if (!isset($opts['path']) || $opts['path'] === '') {
            $opts['path'] = '/';
        }

        return parent::mount($opts);
    }

    /**
     * @inheritdoc
     */
    protected function init()
    {
        if (!$this->options['key']
            ||  !$this->options['secret']
            ||  !$this->options['region']
            ||  !$this->options['bucket']) {
            return $this->setError('Required options undefined.');
        }

        $this->s3Client = new S3Client([
            'credentials' => [
                'key' => $this->options['key'],
                'secret' => $this->options['secret'],
            ],
            'region' => $this->options['region'],
            'version' => $this->options['version']
        ]);

        $this->s3Adapter = new AwsS3Adapter($this->s3Client, $this->options['bucket'], $this->options['prefix'] ?: '');
        $this->root = $this->options['path'];
        $this->rootName = 's3';

        if ($this->options['glideURL']) {
            $this->urlBuilder = UrlBuilderFactory::create($this->options['glideURL'], $this->options['glideKey']);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();
        $this->mimeDetect = 'internal';
    }

    /**
     * @inheritdoc
     */
    protected function _dirname($path)
    {
        return $this->dirName($path) ?: '/';
    }

    /**
     * @inheritdoc
     */
    protected function _basename($path)
    {
        return basename($path);
    }

    /**
     * @inheritdoc
     */
    protected function _joinPath($dir, $name)
    {
        return $dir . $this->separator . $name;
    }

    /**
     * @inheritdoc
     */
    protected function _normpath($path)
    {
        $tmp =  preg_replace("/^\//", "", $path);
        $tmp =  preg_replace("/\/\//", "/", $tmp);
        $tmp =  preg_replace("/\/$/", "", $tmp);

        return $tmp;
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
        return $path == $parent || strpos($path, $parent . '/') === 0;
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

        if ($this->root == $path) {
            $stat['name'] = $this->root;
            return $stat;
        }

        $path = $this->_normpath($path);
        $meta = $this->s3Adapter->getMetadata($path);
        $meta = $meta === false ? $this->s3Adapter->getMetadata($path . '/') : $meta;

        if ($meta === false) {
            return [];
        }

        $stat['ts'] = isset($meta['timestamp'])? $meta['timestamp'] : $this->s3Adapter->getTimestamp($path);
        $stat['size'] = isset($meta['size'])? $meta['size'] : $this->s3Adapter->getSize($path);

        if ($meta['type'] == 'file') {
            $stat['mime'] = isset($meta['mimetype']) ? $meta['mimetype'] : $this->s3Adapter->getMimetype($path);
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

//        if (!isset($stat['url']) && $this->s3Adapter->getUrl()) {
//            $stat['url'] = 1;
//        }

        return $stat;
    }

    /**
     * @inheritdoc
     */
    protected function _subdirs($path)
    {
        $ret = false;
        foreach ($this->listContents($path) as $meta) {
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
        if ($imgSize = $this->getImageSize($path, $mime)) {
            $ret = $imgSize['dimensions'];
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    protected function _scandir($path)
    {
        $paths = array();
        foreach ($this->listContents($path, false) as $object) {
            $paths[] = $object['path'];
        }

        return $paths;
    }

    /**
     * @inheritdoc
     */
    protected function _fopen($path, $mode = "rb")
    {
        $path = $this->_normpath($path);

        if ((bool) $this->s3Adapter->has($path)) {
            if (!$object = $this->s3Adapter->readStream($path)) {
                return false;
            }

            return $object['stream'];
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _fclose($fp, $path = '')
    {
        return @fclose($fp);
    }

    /**
     * @inheritdoc
     */
    protected function _mkdir($path, $name)
    {
        $path = $this->_joinPath($path, $name);
        $path = $this->_normpath($path);

        $config = new Config([]);
        if ($this->s3Adapter->createDir($path, $config)) {
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
        $path = $this->_normpath($path);

        if (!(bool) $this->s3Adapter->has($path)) {
            $config = new Config([]);
            if ($this->s3Adapter->write($path, '', $config)) {
                return $path;
            }
        }

        return false;
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
    protected function _copy($source, $targetDir, $name)
    {
        $source = $this->_normpath($source);
        $targetDir = $this->_normpath($this->_joinPath($targetDir, $name));

        if ((bool) $this->s3Adapter->has($source) && !(bool) $this->s3Adapter->has($targetDir)) {
            return $this->s3Adapter->copy($source, $targetDir);
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _move($source, $targetDir, $name)
    {
        $source = $this->_normpath($source);
        $path = $this->_normpath($this->_joinPath($targetDir, $name));

        if ((bool) $this->s3Adapter->has($source) && !(bool) $this->s3Adapter->has($path)) {
            if ($this->s3Adapter->rename($source, $path)) {
                return $path;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _unlink($path)
    {
        $path = $this->_normpath($path);

        if ((bool) $this->s3Adapter->has($path)) {
            return $this->s3Adapter->delete($path);
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _rmdir($path)
    {
        $path = $this->_normpath($path);

        if ($path === '') {
            throw new \Exception('Root directories can not be deleted.');
        }

        return $this->s3Adapter->deleteDir($path);
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

        if (!is_resource($fp)) {
            throw new InvalidArgumentException(__METHOD__.' expects argument #2 to be a valid resource.');
        }

        $path = $this->_normpath($path);
        $config = new Config($config);

        if (ftell($fp) !== 0 && stream_get_meta_data($fp)['seekable']) {
            rewind($fp);
        }

        if ((bool) $this->s3Adapter->has($path)) {
            if ((bool) $this->s3Adapter->updateStream($path, $fp, $config)) {
                return $path;
            }
        }

        if ((bool) $this->s3Adapter->writeStream($path, $fp, $config)) {
            return $path;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _getContents($path)
    {
        $path = $this->_normpath($path);

        if ((bool) $this->s3Adapter->has($path)) {
            if (!($object = $this->s3Adapter->read($path))) {
                return false;
            }
            return $object['contents'];
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _filePutContents($path, $content)
    {
        $path = $this->_normpath($path);
        $config = new Config([]);

        if ((bool) $this->s3Adapter->has($path)) {
            return (bool) $this->s3Adapter->update($path, $content, $config);
        }

        return (bool) $this->s3Adapter->write($path, $content, $config);
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
     * Return image size
     *
     * @param string $path
     * @param string $mime
     * @return array|bool
     */
    protected function getImageSize($path, $mime = '')
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

    public function listContents($directory = '', $recursive = false)
    {
        $directory = $this->_normpath($directory);
        $contents = $this->s3Adapter->listContents($directory, $recursive);
        $mapper = function ($entry) use ($directory, $recursive) {

            if ($entry['path'] === false
                && (! empty($directory) && strpos($entry['path'], $directory) === false)) {
                return false;
            }

            $entry = $entry + $this->pathInfo($entry['path']);

            if ($recursive === false && $this->dirName($entry['path']) !== $directory) {
                return false;
            }

            return $entry;
        };

        $listing = array_values(array_filter(array_map($mapper, $contents)));

        usort($listing, function ($a, $b) {
            return strcasecmp($a['path'], $b['path']);
        });

        return $listing;
    }

    /**
     * Get normalized path info.
     *
     * @param string $path
     * @return array path info
     */
    protected function pathInfo($path)
    {
        $pathInfo = pathinfo($path) + compact('path');
        $pathInfo['dirname'] = $this->normalizeDirName($pathInfo['dirname']);

        return $pathInfo;
    }

    /**
     * Normalize a dir name return value.
     *
     * @param string $dirName
     *
     * @return string normalized dir name
     */
    protected function normalizeDirName($dirName)
    {
        if ($dirName === '.') {
            return '';
        }

        return $dirName;
    }

    /**
     * Get a normalized dir name from a path.
     *
     * @param string $path
     *
     * @return string dirName
     */
    protected function dirName($path)
    {
        return static::normalizeDirName(dirname($path));
    }
}