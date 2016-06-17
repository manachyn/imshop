<?php

/**
 * Header layout.
 *
 * @var \yii\web\View $this View
 */

use im\search\widgets\SearchWidget;

$assetsPublishUrl = Yii::$app->assetManager->getPublishedUrl('@im/pkbnt/assets');

?>
<div class="header">
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-xs-8 contacts">
                    <ul class="list horizontal">
                        <li><i class="fa fa-phone"></i> 044 123 45 67</li>
                        <li><i class="fa fa-envelope"></i> info@domain.com</li>
                    </ul>
                </div>
                <div class="col-xs-4">
                    <div class="pull-right social-icons">
                        <ul class="list horizontal">
                            <li><a href="#" class="facebook"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#" class="twitter"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="middle-bar">
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 logo">
                        <a href="<?= Yii::$app->homeUrl ?>"><img src="<?= $assetsPublishUrl ?>/images/logo.jpg"></a>
                    </div>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-xs-6 col-sm-12">
                                <div class="auth-bar pull-right">
                                    <a href="" class="btn btn-link">Login</a><a href="" class="btn btn-link">Register</a>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-12">
                                <div class="search-bar pull-right">
                                    <?= SearchWidget::widget(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>