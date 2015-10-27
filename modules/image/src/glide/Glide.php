<?php

namespace im\image\glide;

use League\Glide\Server;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Request;

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

    /**
     * Returns server by name.
     *
     * @param string $name
     * @throws InvalidParamException
     * @return Server
     */
    public function getServer($name)
    {
        if (!isset($this->servers[$name])) {
            throw new InvalidParamException("Glide server '$name' does not exists.");
        }
        if (!$this->servers[$name] instanceof Server) {
            $this->servers[$name] = ServerFactory::create($this->servers[$name]);
        }

        return $this->servers[$name];
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
     * Returns signature.
     *
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

    /**
     * @param \yii\web\Request $request
     * @return bool
     */
    public function validateParams(Request $request)
    {
        if ($this->signKey !== null) {
            $httpSignature = $this->getHttpSignature();
            $path = urldecode(parse_url($request->getUrl(), PHP_URL_PATH));
            parse_str(parse_url($request->getUrl(), PHP_URL_QUERY), $urlParams);
            if (isset($urlParams['_'])) {
                unset($urlParams['_']);
            }
            try {
                $httpSignature->validateRequest($path, $urlParams);
            } catch (SignatureException $e) {
                return false;
            }
        }

        return true;
    }
} 