<?php

/* @var $this yii\web\View */
/* @var $widget \im\cms\widgets\Menu */
/* @var $parent \im\cms\models\MenuItem */
/* @var $items \im\cms\models\MenuItem[] */
/* @var $level int */

?>

<?php if ($items) : ?>
    <ul class="dropdown-menu">
        <li>
            <div class="navbar-mega-content">
                <div class="row">
                    <?php foreach ($items as $item) : ?>
                    <div>
                        <?= $this->render($widget->itemView, ['model' => $item, 'level' => $level]) ?>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
        </li>
    </ul>
<?php endif ?>