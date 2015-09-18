<?php

/**
 * Theme home layout.
 *
 * @var \yii\web\View $this View
 * @var string $content Content
 */

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

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-4">

                </div>
                <div class="col-sm-12">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </section>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>