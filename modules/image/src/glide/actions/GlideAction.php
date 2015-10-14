<?php

namespace im\image\glide\actions;


use Yii;
use yii\base\Action;
use yii\base\NotSupportedException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class GlideAction extends Action
{
    /**
     * @var string
     */
    public $component = 'glide';

    /**
     * @param $path
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws NotSupportedException
     */
    public function run($server, $path, Request $request)
    {
        if (!$this->getServer($server)->sourceFileExists($path)) {
            throw new NotFoundHttpException;
        }
        if ($this->getComponent()->signKey) {
            $request = Request::create(Yii::$app->request->getUrl());
            if (!$this->validateRequest($request)) {
                throw new BadRequestHttpException;
            };
        }

//        try {
//            $this->getServer()->outputImage($path, Yii::$app->request->get());
//        } catch (\Exception $e) {
//            throw new NotSupportedException;
//        }
    }

    /**
     * Returns glide server by name.
     *
     * @param string $server
     * @return \League\Glide\Server
     */
    protected function getServer($server)
    {
        return $this->getComponent()->getServer($server);
    }

    /**
     * @return \im\image\glide\Glide
     */
    protected function getComponent()
    {
        return Yii::$app->get($this->component);
    }

    /**
     * @param $request
     * @return bool
     */
    public function validateRequest($request)
    {
        return $this->getComponent()->validateRequest($request);
    }
}
