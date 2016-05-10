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
                        'url' => ['/dashboard/index'],
                        'icon' => 'fa fa-dashboard',
                        //'active' => Yii::$app->request->url === Yii::$app->homeUrl
                    ],
                    [
                        'label' => Yii::t('app', 'CMS'),
                        'url' => ['/cms/page/index'],
                        'icon' => 'fa fa-sitemap',
                        'options' => [
                            'class' => 'treeview',
                        ],
                        //'visible' => Yii::$app->user->can('readPost'),
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Templates'),
                                'url' => ['/cms/template/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Pages'),
                                'url' => ['/cms/page/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Menus'),
                                'url' => ['/cms/menu/index'],
                                'icon' => 'fa fa-circle-o',
                            ]
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Catalog'),
                        'url' => ['/catalog/product/index'],
                        'icon' => 'fa fa-book',
                        'options' => [
                            'class' => 'treeview',
                        ],
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Attributes'),
                                'url' => ['/catalog/product-attribute/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Values'),
                                'url' => ['/eav/value/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Options'),
                                'url' => ['/catalog/product-option/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Product types'),
                                'url' => ['/catalog/product-type/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Categories'),
                                'url' => ['/catalog/product-category/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Brands'),
                                'url' => ['/catalog/brand/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                            [
                                'label' => Yii::t('app', 'Products'),
                                'url' => ['/catalog/product/index'],
                                'icon' => 'fa fa-circle-o',
                            ],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Search'),
                        'url' => ['/search/index/index'],
                        'icon' => 'fa fa-search',
                        'options' => [
                            'class' => 'treeview',
                        ],
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Index'),
                                'url' => ['/search/index/index'],
                                'icon' => 'fa fa-circle-o'
                            ],
                            [
                                'label' => Yii::t('app', 'Facets'),
                                'url' => ['/search/facet/index'],
                                'icon' => 'fa fa-circle-o'
                            ],
                            [
                                'label' => Yii::t('app', 'Facet sets'),
                                'url' => ['/search/facet-set/index'],
                                'icon' => 'fa fa-circle-o'
                            ]
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
                        'label' => Yii::t('app', 'File manager'),
                        'url' => ['/filesystem/file-manager'],
                        'icon' => 'fa fa-folder'
                    ],
                    [
                        'label' => Yii::t('app', 'Tasks'),
                        'url' => ['/tasks/task/index'],
                        'icon' => 'fa fa-tasks',
                        'options' => [
                            'class' => 'treeview',
                        ],
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Tasks'),
                                'url' => ['/tasks/task/index'],
                                'icon' => 'fa fa-circle-o'
                            ]
                        ],
                    ],
                ]
            ]
        ); ?>
<!--        <ul class="sidebar-menu">-->
<!--            <li class="header">MAIN NAVIGATION</li>-->
<!--            <li class="active"><a href="--><?//= Url::to(['/dashboard/index']); ?><!--"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>-->
<!--            <li class="treeview">-->
<!--                <a href="#">-->
<!--                    <i class="fa fa-sitemap"></i>-->
<!--                    <span>CMS</span>-->
<!--                    <i class="fa fa-angle-left pull-right"></i>-->
<!--                </a>-->
<!--                <ul class="treeview-menu">-->
<!--                    <li><a href="--><?//= Url::to(['/cms/page/index']); ?><!--"><i class="fa fa-circle-o"></i> Pages</a></li>-->
<!--                    <li><a href="--><?//= Url::to(['/cms/layout/index']); ?><!--"><i class="fa fa-circle-o"></i> Layouts</a></li>-->
<!--                </ul>-->
<!--            </li>-->
<!--        </ul>-->
    </section>
    <!-- /.sidebar -->
</aside>