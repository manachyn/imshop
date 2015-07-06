<?php
/**
 * Sidebar layout.
 */

use im\adminlte\widgets\Menu;
use yii\helpers\Url;

/* @var $this \yii\web\View */

$assetsPublishUrl = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $assetsPublishUrl ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Ivan Manachyn</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?= Menu::widget(
            [
                'options' => [
                    'class' => 'sidebar-menu'
                ],
                'items' => [
                    [
                        'label' => Yii::t('app', 'Dashboard'),
                        'url' => ['/backend/dashboard/index'],
                        'icon' => 'fa fa-dashboard',
                        //'active' => Yii::$app->request->url === Yii::$app->homeUrl
                    ],
                    [
                        'label' => Yii::t('app', 'CMS'),
                        'url' => ['/cms/backend/page/index'],
                        'icon' => 'fa fa-sitemap',
                        'options' => [
                            'class' => 'treeview',
                        ],
                        //'visible' => Yii::$app->user->can('readPost'),
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Pages'),
                                'url' => ['/cms/backend/page/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Layouts'),
                                'url' => ['/cms/backend/layout/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Catalog'),
                        'url' => ['/catalog/backend/product/index'],
                        'icon' => 'fa fa-book',
                        'options' => [
                            'class' => 'treeview',
                        ],
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Attributes'),
                                'url' => ['/catalog/backend/product-attribute/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Options'),
                                'url' => ['/catalog/backend/product-option/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Product types'),
                                'url' => ['/catalog/backend/product-type/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Categories'),
                                'url' => ['/catalog/backend/product-category/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Brands'),
                                'url' => ['/catalog/backend/brand/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Products'),
                                'url' => ['/catalog/backend/product/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Users'),
                        'url' => ['/users/backend'],
                        'icon' => 'fa fa-user',
                        'options' => [
                            'class' => 'treeview',
                        ],
                        'items' => [
                            [
                                'label' => Yii::t('app', 'User'),
                                'url' => ['/users/backend'],
                                'icon' => 'fa fa-circle-o'
                            ],
//                            [
//                                'label' => Yii::t('app', 'Role'),
//                                'url' => ['/role/index'],
//                                'icon' => 'fa fa-lock',
//                            ],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Tasks'),
                        'url' => ['/tasks/backend/task/index'],
                        'icon' => 'fa fa-tasks',
                        'options' => [
                            'class' => 'treeview',
                        ],
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Tasks'),
                                'url' => ['/tasks/backend/task/index'],
                                'icon' => 'fa fa-circle-o'
                            ]
                        ],
                    ],
                ]
            ]
        ); ?>
<!--        <ul class="sidebar-menu">-->
<!--            <li class="header">MAIN NAVIGATION</li>-->
<!--            <li class="active"><a href="--><?//= Url::to(['/backend/dashboard/index']); ?><!--"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>-->
<!--            <li class="treeview">-->
<!--                <a href="#">-->
<!--                    <i class="fa fa-sitemap"></i>-->
<!--                    <span>CMS</span>-->
<!--                    <i class="fa fa-angle-left pull-right"></i>-->
<!--                </a>-->
<!--                <ul class="treeview-menu">-->
<!--                    <li><a href="--><?//= Url::to(['/cms/backend/page/index']); ?><!--"><i class="fa fa-circle-o"></i> Pages</a></li>-->
<!--                    <li><a href="--><?//= Url::to(['/cms/backend/layout/index']); ?><!--"><i class="fa fa-circle-o"></i> Layouts</a></li>-->
<!--                </ul>-->
<!--            </li>-->
<!--        </ul>-->
    </section>
    <!-- /.sidebar -->
</aside>