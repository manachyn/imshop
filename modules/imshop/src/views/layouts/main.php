<?php

/**
 * Theme main layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

use im\cms\widgets\WidgetArea;
use im\imshop\widgets\FlashMessages;

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('//layouts/head') . "\n" ?>
</head>
<body>
<?php $this->beginBody(); ?>
<?= FlashMessages::widget(); ?>
<?= $this->render('//layouts/header') ?>
<?= $this->render('//layouts/top-menu') ?>
<section id="slider">
    <div class="owl-carousel owl-theme" id="home-carousel" data-component="carousel">
        <div style="background-image: url(images/slider/01.jpg)" class="item">
            <div class="caption container">
                <div class="row">
                    <div class="col-sm-7">
                        <h1>You are entire </h1>
                        <h2>creative world</h2>
                        <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor. Aenean sollicitudin, lorem quis bibendum auctor.</p>
                    </div>
                </div>
            </div>
        </div>
        <div style="background-image: url(images/slider/02.jpg)" class="item">
            <div class="caption container">
                <div class="row">
                    <div class="col-sm-7">
                        <h1>You are entire </h1>
                        <h2>creative world</h2>
                        <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor. Aenean sollicitudin, lorem quis bibendum auctor.</p>
                    </div>
                </div>
            </div>
        </div>
        <div style="background-image: url(images/slider/03.jpg)" class="item">
            <div class="caption container">
                <div class="row">
                    <div class="col-sm-7">
                        <h1>You are entire </h1>
                        <h2>creative world</h2>
                        <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor. Aenean sollicitudin, lorem quis bibendum auctor.</p>
                    </div>
                </div>
            </div>
        </div>
        <div style="background-image: url(images/slider/04.jpg)" class="item">
            <div class="caption container">
                <div class="row">
                    <div class="col-sm-7">
                        <h1>You are entire </h1>
                        <h2>creative world</h2>
                        <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor. Aenean sollicitudin, lorem quis bibendum auctor.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active" style="background-image: url(images/slider/01.jpg)">
                <div class="carousel-caption">
                    <h1>You are entire </h1>
                    <h2>creative world</h2>
                    <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor. Aenean sollicitudin, lorem quis bibendum auctor.</p>
                </div>
            </div>
            <div class="item" style="background-image: url(images/slider/02.jpg)">
                <div class="carousel-caption">
                    <h1>You are entire </h1>
                    <h2>creative world</h2>
                    <p>This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor. Aenean sollicitudin, lorem quis bibendum auctor.</p>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; IMShop <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>