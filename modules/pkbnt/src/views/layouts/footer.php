<?php

/**
 * Footer layout.
 *
 * @var \yii\web\View $this View
 */

?>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <?= $this->render('//layouts/bottom-menu-left') ?>
            </div>
            <div class="col-md-3">
                <?= $this->render('//layouts/bottom-menu-right') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                &copy; <?= date('Y') ?> Вишневый сад. Все права защищены.
            </div>
        </div>
    </div>
</footer>