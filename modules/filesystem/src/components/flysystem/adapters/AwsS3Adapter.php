<?php

namespace im\filesystem\components\flysystem\adapters;

use League\Flysystem\AdapterInterface;

class AwsS3Adapter extends \League\Flysystem\AwsS3v3\AwsS3Adapter
{
    /**
     * Gets url by file path
     *
     * @param $path
     * @return string
     */
    public function getUrl($path)
    {
        if ($this->getRawVisibility($path) === AdapterInterface::VISIBILITY_PRIVATE) {
            $command = $this->s3Client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key' => $this->applyPathPrefix($path)
            ]);
            $request = $this->s3Client->createPresignedRequest($command, '+5 minutes');
            $url = (string) $request->getUri();
        } else {
            $url = $this->s3Client->getObjectUrl($this->bucket, $this->applyPathPrefix($path));
        }

        return $url;
    }
} 