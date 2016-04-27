<?php

namespace im\filesystem\helpers;

class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * Returns the target path as relative reference from the base path.
     *
     * @param string $basePath The base path
     * @param string $targetPath The target path
     *
     * @return string The relative target path
     */
    public static function getRelativePath($basePath, $targetPath)
    {
        if ($basePath === $targetPath) {
            return '';
        }
        $sourceDirs = explode('/', isset($basePath[0]) && '/' === $basePath[0] ? substr($basePath, 1) : $basePath);
        $targetDirs = explode('/', isset($targetPath[0]) && '/' === $targetPath[0] ? substr($targetPath, 1) : $targetPath);
        array_pop($sourceDirs);
        $targetFile = array_pop($targetDirs);
        foreach ($sourceDirs as $i => $dir) {
            if (isset($targetDirs[$i]) && $dir === $targetDirs[$i]) {
                unset($sourceDirs[$i], $targetDirs[$i]);
            } else {
                break;
            }
        }
        $targetDirs[] = $targetFile;
        $path = str_repeat('../', count($sourceDirs)).implode('/', $targetDirs);
        return '' === $path || '/' === $path[0]
        || false !== ($colonPos = strpos($path, ':')) && ($colonPos < ($slashPos = strpos($path, '/')) || false === $slashPos)
            ? "./$path" : $path;
    }
} 