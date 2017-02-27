<?php

/**
 * Header layout.
 *
 * @var \yii\web\View $this View
 */

use im\search\widgets\SearchWidget;

?>
<div class="header">
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-xs-8 slogan">
                    <h2>Теплицы - поликарбонат продажа - строительство конструкций</h2>
                </div>
                <div class="col-xs-4">
                    <div class="pull-right social-icons">
                        <ul class="list horizontal">
                            <li><a href="https://www.facebook.com/polikarbonatvs" class="facebook"><i class="fa fa-facebook"></i></a></li>
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
                    <div class="col-sm-3 logo">
                        <a href="<?= Yii::$app->homeUrl ?>"><img src="<?= Yii::$app->assetManager->publish('@im/pkbnt/assets/images/logo.jpg')[1] ?>"></a>
                    </div>
                    <div class="col-sm-9">
                        <div class="row header-row">
                            <div class="col-md-12">
                                <div class="search-bar pull-right">
                                    <?= SearchWidget::widget(); ?>
                                </div>
                                <div class="contacts pull-right">
                                    <span>044</span> 223-36-07
                                </div>
                            </div>
                        </div>
                        <div class="row header-row">
                            <div class="col-sm-12">
                                <?= $this->render('//layouts/top-menu') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>