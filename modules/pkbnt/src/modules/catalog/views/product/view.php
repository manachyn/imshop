<?php

/**
 * Product view.
 *
 * @var yii\web\View $this View
 * @var im\catalog\models\Product $model Model
 */

$parts = [];
if ($model->type) {
    $parts[] = $model->type;
}
$parts[] = $model->title;
$this->title = implode(' ', $parts);
$this->params['breadcrumbs'] = [$this->title];
$parts = $model->manufacturer ? [$model->manufacturer->name, $model->title] : [$model->title];
$fullTitle =  implode(' ', $parts);
?>
<div class="product-details">
    <div class="row">
        <div class="col-md-4">
            <?php if ($image = $model->getImage(2)) : ?>
                <a href="<?= $model->getUrl() ?>" title="<?= $fullTitle ?>">
                    <img src="<?= $image ?>" class="media-object" title="<?= $image->title ?: $fullTitle ?>" alt="<?= $image->title ?: $fullTitle ?>">
                </a>
            <?php endif ?>
        </div>
        <div class="col-md-8">
            <div class="product-price">
                <strong><?= $model->price ?> грн.</strong>
            </div>
            <div class="product-sku">
                Код: <?= $model->sku ?>
            </div>
            <div class="product-availability">
                Наличие товара: <?= $model->availability ? 'есть' : 'нету в наличии' ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if ($attributes = $model->getEAttributes()) : ?>
                <h4>Характеристики <?= $this->title ?></h4>
                <table class="table table-bordered">
                    <?php foreach ($attributes as $attribute) : ?>
                        <tr>
                            <th><?= $attribute->getPresentation() ?></th>
                            <td><?= $attribute->getValue() . (($unit = $attribute->getUnit()) ? ' ' . $unit : '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif ?>
            <?php if ($description = preg_replace('/<br>$/s', '', $model->description)) : ?>
            <div class="product-description">
                <h4>Описание <?= $this->title ?></h4>
                <?= $description ?>
            </div>
            <?php endif ?>
        </div>
    </div>
</div>