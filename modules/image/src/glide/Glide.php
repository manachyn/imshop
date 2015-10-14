<?php

namespace im\image\glide;

use League\Glide\Server;
use League\Glide\Signatures\SignatureFactory;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Glide extends Component
{
    /**
     * @var array
     */
    public $servers = [];

    /**
     * @var string
     */
    public $signKey;

    /**
     * @var \League\Glide\Signatures\Signature
     */
    protected $httpSignature;

    public function getServer($name)
    {
        if (!isset($this->servers[$name])) {
            throw new InvalidParamException("Glide server '$name' does not exists.");
        }
        if (!$this->servers[$name] instanceof Server) {
            $this->servers[$name] = ServerFactory::create($this->servers[$name]);
        }
    }

    public function createUrl(array $params, $scheme = false)
    {
        $route = ArrayHelper::getValue($params, 0);
        $resultUrl = Url::to($params, true);
        $path = parse_url($resultUrl, PHP_URL_PATH);
        parse_str(parse_url($resultUrl, PHP_URL_QUERY), $urlParams);
        $signature = $this->getHttpSignature()->generateSignature($path, $urlParams);
        $params['s'] = $signature;
        $params[0] = $route;

        return Url::to($params, $scheme);
    }

    /**
     * @return \League\Glide\Signatures\Signature
     * @throws InvalidConfigException
     */
    public function getHttpSignature()
    {
        if ($this->httpSignature === null) {
            if ($this->signKey === null) {
                throw new InvalidConfigException;
            }
            $this->httpSignature = SignatureFactory::create($this->signKey);
        }

        return $this->httpSignature;
    }
} 