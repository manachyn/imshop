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
            <div class="col-md-6">
                &copy; <?= date('Y') ?> Вишневый сад. Все права защищены.
            </div>
            <div class="col-md-6">
                <?= $this->render('//layouts/bottom-menu') ?>
            </div>
        </div>
    </div>
</footer>