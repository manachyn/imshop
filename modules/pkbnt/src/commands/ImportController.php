<?php

namespace im\pkbnt\commands;

use im\blog\models\Article;
use im\blog\models\ArticleCategory;
use im\blog\models\ArticleFile;
use im\blog\models\ArticleMeta;
use im\blog\models\News;
use im\blog\models\NewsCategory;
use im\blog\widgets\LastArticlesWidget;
use im\blog\widgets\LastNewsWidget;
use im\catalog\models\Product;
use im\catalog\models\ProductAttribute;
use im\catalog\models\ProductAttributeValue;
use im\catalog\models\ProductCategory;
use im\catalog\models\ProductCategoryFile;
use im\catalog\models\ProductCategoryMeta;
use im\catalog\models\ProductFile;
use im\catalog\models\ProductMeta;
use im\catalog\models\ProductType;
use im\catalog\models\widgets\ProductCategoriesList;
use im\cms\models\Banner;
use im\cms\models\BannerItem;
use im\cms\models\Menu;
use im\cms\models\MenuItem;
use im\cms\models\Page;
use im\cms\models\PageMeta;
use im\cms\models\Template;
use im\cms\models\widgets\WidgetArea;
use im\cms\models\widgets\WidgetAreaItem;
use im\cms\widgets\BannerWidget;
use im\cms\widgets\ContentWidget;
use im\cms\widgets\FormWidget;
use im\cms\widgets\GalleryWidget;
use im\cms\widgets\MenuWidget;
use im\eav\components\AttributeTypes;
use im\eav\models\Attribute;
use im\eav\models\Value;
use im\filesystem\components\FileInterface;
use im\filesystem\components\StorageConfig;
use Yii;
use yii\base\Model;
use yii\console\Controller;
use yii\db\Query;

/**
 * Class ImportController
 * @package im\pkbnt\commands
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class ImportController extends Controller
{
    /**
     * @var \yii\db\Connection
     */
    protected $connection;

    /**
     * @var \im\filesystem\components\FilesystemComponent
     */
    protected $filesystemComponent;

    /**
     * Import banners
     */
    public function actionBanners()
    {
        $this->truncateTables(['{{%banners}}', '{{%banner_items}}']);

        foreach ($this->getBannersQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                $file = Yii::getAlias('@www/ufiles/banners/site_rus/' . $item['filename']);
                if (is_file($file)) {
                    $banner = new Banner();
                    $banner->id = $item['id'];
                    $banner->name = $item['name'];
                    $banner->status = $item['active'];
                    $banner->save();
                    $bannerItem = $this->saveBannerItem($banner, $file);
                    $bannerItem['link'] = $item['url'];
                    $bannerItem['sort'] = 1;
                    $banner->items = [$bannerItem];
                    $banner->save(false);
                } else {
                    echo "Banner file not found: {$file}", PHP_EOL;
                }
            }
        }
    }

    /**
     * Import sidebar menus
     */
    public function actionSidebarMenus()
    {
        $this->truncateTables(['{{%menus}}', '{{%menu_items}}']);
        foreach ($this->getSidebarMenusQuery()->batch(100, $this->getConnection()) as $menus) {
            foreach ($menus as $menuData) {
                $menu = new Menu($menuData);
                $menu->save();
                foreach ($this->getMenuItemsQuery($menuData['id'])->batch(100, $this->getConnection()) as $menuItems) {
                    foreach ($menuItems as $menuItemData) {
                        $menuItem = new MenuItem($menuItemData);
                        $menuItem->save();
                        $menuItem->makeRoot();
                        $menu->link('items', $menuItem);
                    }
                }
            }
        }
    }

    /**
     * Import menus
     */
    public function actionMenus()
    {
        $topMenu = new Menu(['name' => 'Верхнее меню', 'location' => 'top']);
        $topMenu->save();
        $bottomMenu = new Menu(['name' => 'Нижнее меню', 'location' => 'bottom']);
        $bottomMenu->save();
        $menus = [$topMenu, $bottomMenu];
        foreach ($this->getMenusQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($menus as $menu) {
                foreach ($items as $item) {
                    $menuItem = new MenuItem($item);
                    $menuItem->save();
                    $menuItem->makeRoot();
                    $menu->link('items', $menuItem);
                }
            }
        }
    }

    /**
     * Import widgets
     */
    public function actionWidgets()
    {
        $this->truncateTables(['{{%widgets}}', '{{%widget_area_widgets}}']);
        foreach ($this->getWidgetsQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                switch ($item['module_id']) {
                    case 9:
                        $this->saveBannerWidget($item);
                        break;
                    case 50:
                        $this->saveMenuWidget($item);
                        break;
                    case 7:
                        //$this->saveArticleWidget($item);
                        break;
                    case 19:
                        $this->saveGalleryWidget($item);
                        break;
                    case 51:
                        $this->saveArticleCatWidget($item);
                        break;
                    case 52:
                        $this->saveFeedbackWidget($item);
                        break;
                    case 5:
                        $this->saveNewsWidget($item);
                        break;
                    case 3:
                        //$this->savePagesWidget($item); // Sitemap
                        break;
                    case 17:
                        $this->saveCatalogWidget($item);
                        break;
                    default:
                        echo "Unsupported module {$item['module_id']}", PHP_EOL;
                }
            }
        }
        $this->saveContactsWidget();
    }

    /**
     * Import article categories
     */
    public function actionArticleCategories()
    {
        $this->truncateTables(['{{%article_categories}}']);
        foreach ($this->getArticleCategoriesQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                $category = new ArticleCategory($item);
                $category->makeRoot();
            }
        }
    }

    /**
     * Import news categories
     */
    public function actionNewsCategories()
    {
        $this->truncateTables(['{{%news_categories}}']);
        foreach ($this->getNewsCategoriesQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                $category = new NewsCategory($item);
                $category->makeRoot();
            }
        }
    }

    /**
     * Import articles
     */
    public function actionArticles()
    {
        Article::deleteAll();
        $this->truncateTables(['{{%articles}}', '{{%article_meta}}', '{{%article_files}}', '{{%articles_categories}}']);
        foreach ($this->getArticlesQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                /** @var Article $article */
                $article = Yii::createObject(Article::class);
                $article->attributes = $item;
                $article->id = $item['id'];
                $article->created_at = $item['created_at'];
                $article->categories = [$item['category_id']];
                $article->save();

                $articleMeta = new ArticleMeta();
                $articleMeta->attributes = $item;
                $articleMeta->entity_type = 'article';
                $article->link('metaRelation', $articleMeta);

                $file = Yii::getAlias('@www/ufiles/articlecat/site_rus/' . $item['image']);
                if (is_file($file)) {
                    $image = $this->saveArticleImage($article, $file);
                    $image->save();
                    $article->link('imageRelation', $image);
                }
            }
        }
    }

    /**
     * Import news
     */
    public function actionNews()
    {
        Article::deleteAll();
        $this->truncateTables(['{{%news}}', '{{%news_categories_pivot}}']);
        foreach ($this->getNewsQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                /** @var News $news */
                $news = Yii::createObject(News::class);
                $news->attributes = $item;
                $news->id = $item['id'];
                $news->created_at = $item['created_at'];
                $news->categories = [$item['category_id']];
                $news->save();

                $newsMeta = new ArticleMeta();
                $newsMeta->attributes = $item;
                $newsMeta->entity_type = 'news';
                $news->link('metaRelation', $newsMeta);

                $file = Yii::getAlias('@www/ufiles/news/site_rus/' . $item['image']);
                if (is_file($file)) {
                    $image = $this->saveArticleImage($news, $file);
                    $image->save();
                    $news->link('imageRelation', $image);
                }
            }
        }
    }

    /**
     * Import product categories
     */
    public function actionProductCategories()
    {
        ProductCategory::deleteAll();
        $this->truncateTables([
            '{{%product_categories}}',
            '{{%product_category_meta}}',
            '{{%product_category_files}}',
            '{{%products_categories}}'
        ]);
        foreach ($this->getProductCategoriesQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                $this->importProductCategory($item['id']);
            }
        }
    }

    /**
     * Import product types
     */
    public function actionProductTypes()
    {
        $this->truncateTables(['{{%product_types}}']);
        foreach ($this->getProductTypesQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                $productType = new ProductType();
                $productType->attributes = $item;
                $productType->id = $item['id'];
                $productType->save();
            }
        }
    }

    /**
     * Import products
     */
    public function actionProducts()
    {
        Product::deleteAll();
        $this->truncateTables([
            '{{%products}}',
            '{{%product_meta}}',
            '{{%products_categories}}',
            '{{%eav_product_values}}',
            '{{%product_option_values}}',
            '{{%product_variants}}',
            '{{%product_variant_option_values}}',
            '{{%product_files}}'
        ]);
        foreach ($this->getProductsQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                /** @var Product $product */
                $product = Yii::createObject(Product::class);
                $product->attributes = $item;
                $product->id = $item['id'];
                $product->save();

                $productMeta = new ProductMeta();
                $productMeta->attributes = $item;
                $product->link('metaRelation', $productMeta);
                $category = ProductCategory::findOne($item['category_id']);
                if ($category) {
                    $product->link('categoriesRelation', $category);
                }

                $file = Yii::getAlias('@www/ufiles/catalog/site_rus/' . $item['image1']);
                if (is_file($file)) {
                    $image = $this->saveProductImage($product, $file, 1);
                    $product->link('imagesRelation', $image);

                }
                $file = Yii::getAlias('@www/ufiles/catalog/site_rus/' . $item['image2']);
                if (is_file($file)) {
                    $image = $this->saveProductImage($product, $file, 2);
                    $product->link('imagesRelation', $image);

                }
                $file = Yii::getAlias('@www/ufiles/catalog/site_rus/' . $item['image3']);
                if (is_file($file)) {
                    $image = $this->saveProductImage($product, $file, 3);
                    $product->link('imagesRelation', $image);

                }
            }
        }
    }

    /**
     * Import EAV attributes
     */
    public function actionEavAttributes()
    {
        ProductAttribute::deleteAll();
        $this->truncateTables([
            '{{%eav_attributes}}',
            '{{%eav_values}}',
            '{{%product_type_attributes}}'
        ]);
        foreach ($this->getEavAttributesQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                $attribute = new ProductAttribute();
                $attribute->attributes = $item;
                $attribute->id = $item['id'];
                $attribute->save();
                $productType = ProductType::findOne($item['product_type_id']);
                if ($productType) {
                    $productType->link('eAttributesRelation', $attribute);
                }
                $values = $this->getEavAttributeValuesQuery()->where(['property_id' => $attribute->id])
                             ->all($this->getConnection());
                if ($values) {
                    foreach ($values as $valueData) {
                        $value = new Value($valueData);
                        $attribute->link('valuesRelation', $value);
                    }
                    $attribute->predefined_values = true;
                    $attribute->save();
                }
            }
        }
    }

    /**
     * Import EAV product values
     */
    public function actionEavProductValues()
    {
        ProductAttributeValue::deleteAll();
        $this->truncateTables([
            '{{%eav_product_values}}'
        ]);
        foreach ($this->getEavProductValuesQuery()->batch(100, $this->getConnection()) as $items) {
            foreach ($items as $item) {
                $attribute = ProductAttribute::findOne($item['attribute_id']);
                if ($attribute) {
                    $productValue = new ProductAttributeValue();
                    $product = Product::findOne($item['entity_id']);
                    if ($product) {
                        $productValue->setEntity($product);
                        $productValue->attribute_id = $item['attribute_id'];
                        $productValue->attribute_name = $attribute->name;
                        $value = Value::findOne(['attribute_id' => $attribute->id, 'value' => $item['value']]);
                        if ($value) {
                            $productValue->attribute_type = 'value';
                            $productValue->value_id = $value->id;
                        } else {
                            $productValue->attribute_type = AttributeTypes::STRING_TYPE;
                            $productValue->string_value = $item['value'];
                        }
                        $productValue->save();
                    }
                }
            }
        }
    }

    /**
     * @param int $id
     * @return bool
     */
    protected function isProductCategoryImported($id)
    {
        return (new Query())
            ->from('{{%product_categories}}')
            ->where(['id' => $id])
            ->exists();
    }

    /**
     * @param int $id
     * @return ProductCategory
     */
    protected function importProductCategory($id)
    {
        if (!$this->isProductCategoryImported($id)) {
            $item = $this->getProductCategoriesQuery()->where(['id' => $id])->one($this->getConnection());
            /** @var ProductCategory $category */
            $category = Yii::createObject(ProductCategory::class);
            $category->attributes = $item;
            $category->id = $item['id'];
            if ($item['parent_id']) {
                $parent = $this->importProductCategory(['id' => $item['parent_id']]);
                $category->appendTo($parent);
            } else {
                $category->makeRoot();
            }

            $categoryMeta = new ProductCategoryMeta();
            $categoryMeta->attributes = $item;
            $category->link('metaRelation', $categoryMeta);

            $file = Yii::getAlias('@www/ufiles/catalog/site_rus/categories/' . $item['image']);
            if (is_file($file)) {
                $image = $this->saveProductCategoryImage($category, $file);
                $image->save();
                $category->link('imageRelation', $image);
            }
        } else {
            /** @var ProductCategory $category */
            $category = Yii::createObject(ProductCategory::class);
            $category = $category::findOne($id);
        }

        return $category;
    }

    /**
     * Import pages
     */
    public function actionPages()
    {
        Page::deleteAll();
        $this->truncateTables([
            '{{%pages}}',
            '{{%page_meta}}'
        ]);
        foreach ($this->getPagesQuery()->batch(100, $this->getConnection()) as $pages) {
            foreach ($pages as $pageData) {
                $this->createPageTemplate($pageData['id']);
                $page = Yii::createObject(Page::class);
                $page->attributes = $pageData;
                $page->id = $pageData['id'];
                $page->created_at = strtotime($pageData['created_at']);
                $page->makeRoot();

                $pageMeta = new PageMeta();
                $pageMeta->attributes = $pageData;
                $page->link('metaRelation', $pageMeta);
            }
        }

        foreach ($this->getPagesQuery()->batch(100, $this->getConnection()) as $pages) {
            foreach ($pages as $pageData) {
                if ($pageData['parent_id']) {
                    /** @var Page $page */
                    $page = Page::findOne($pageData['id']);
                    $parent = Page::findOne($pageData['parent_id']);
                    $page->appendTo($parent);
                }
            }
        }
    }

    protected function createPageTemplate($pageId)
    {
        $widgets = $this->getWidgetsQuery()->where(['page_id' => 19])->all($this->getConnection());
        //$contactWidget = ContentWidget::find()->orderBy(['id' => SORT_DESC])->one();
        $template = new Template(['name' => 'Template ' . $pageId, 'layout_id' => 'main']);
        $template->save();
        $beforeContent = new WidgetArea(['code' => 'beforeContent', 'template_id' => $template->id, 'display' => 3]);
        $afterContent = new WidgetArea(['code' => 'afterContent', 'template_id' => $template->id, 'display' => 3]);
        $sidebar = new WidgetArea(['code' => 'sidebar', 'template_id' => $template->id, 'display' => 3]);
        $beforeContent->save();
        $afterContent->save();
        $sidebar->save();
        $widgetAreas = [
            1 => 1,
            0 => 2,
            2 => 3,
            3 => 4,
            5 => 5,
            4 => 6
        ];
        $widgetsByArea = [];
        $contentAreas = [];
        foreach ($widgets as $widget) {
            $widgetsByArea[$widgetAreas[$widget['field_number']]][] = $widget;
            if ($widget['action'] == 'showarticle' && !in_array($widgetAreas[$widget['field_number']], $contentAreas)) {
                $contentAreas[] = $widgetAreas[$widget['field_number']];
            }
        }
        sort($contentAreas);
        $content = '';
        foreach ($contentAreas as $contentArea) {
            $contentAreaWidgets = $widgetsByArea[$contentArea];
            usort($contentAreaWidgets, function($a, $b) {
                if ($a['block_rank'] == $b['block_rank']) {
                    return 0;
                }
                return ($a['block_rank'] < $b['block_rank']) ? -1 : 1;
            });
            foreach ($contentAreaWidgets as $widget) {
                if ($widget['action'] == 'showarticle') {
                    $article = $this->getArticle($widget['property1']);
                    if ($article) {
                        $content .= $article['text'];
                    }
                } else {
                    $content .=
                }
            }
        }

        $a = 1;
    }

    /**
     * @param $id
     * @return array
     */
    protected function getArticle($id)
    {
        $query = new Query();
        $query->from('site_rus_articles')->where(['id' => $id]);

        return $query->one($this->getConnection());
    }

    /**
     * @param array $data
     * @return BannerWidget|ContentWidget
     */
    protected function saveBannerWidget(array $data)
    {
        $banner = $this->getBannerQuery($data['property1'])->one($this->getConnection());
        if ($banner['type'] == 1) {
            $widget = new BannerWidget();
            $widget->model_id = $banner['id'];
        } else {
            $widget = new ContentWidget();
            $widget->content = $banner['text'];
        }
        $widget->id = $data['id'];
        $widget->title = $banner['name'];
        $widget->save();

        return $widget;
    }

    /**
     * @param array $data
     * @return MenuWidget
     */
    protected function saveMenuWidget(array $data)
    {
        $widget = new MenuWidget();
        $widget->model_id = $data['property1'];
        $widget->id = $data['id'];
        $widget->save();

        return $widget;
    }

    /**
     * @param array $data
     * @return GalleryWidget
     */
    protected function saveGalleryWidget(array $data)
    {
        $widget = new GalleryWidget();
        $widget->model_id = $data['property3'];
        $widget->id = $data['id'];
        $widget->save();

        return $widget;
    }

    /**
     * @param array $data
     * @return LastArticlesWidget
     */
    protected function saveArticleCatWidget(array $data)
    {
        $widget = new LastArticlesWidget();
        $widget->id = $data['id'];
        if ($data['property1']) {
            $widget->category_id = $data['property1'];
        }
        if ($data['property5']) {
            $widget->category_id = $data['property5'];
        }
        switch ($data['property4']) {
            case 'full':
                $widget->template = 1;
                break;
            case 'titles1':
                $widget->template = 2;
                break;
            case 'titles3':
                $widget->template = 3;
                break;
        }
        $widget->save();

        return $widget;
    }

    /**
     * @param array $data
     * @return FormWidget
     */
    protected function saveFeedbackWidget(array $data)
    {
        $widget = new FormWidget();
        $widget->id = $data['id'];
        $widget->model_id = 1;
        $widget->save();

        return $widget;
    }

    /**
     * @param array $data
     * @return LastNewsWidget
     */
    protected function saveNewsWidget(array $data)
    {
        $widget = new LastNewsWidget();
        $widget->id = $data['id'];
        $widget->category_id = $data['property1'];
        $widget->save();

        return $widget;
    }

    /**
     * @param array $data
     * @return ProductCategoriesList|null
     */
    protected function saveCatalogWidget(array $data)
    {
        if ($data['action'] == 'catalogfullmenu') {
            $widget = new ProductCategoriesList();
            $widget->id = $data['id'];
            $widget->title = 'Каталог товаров';
            $widget->save();
            return $widget;
        } else {
            return null;
        }
    }

    /**
     * @return ContentWidget
     */
    protected function saveContactsWidget()
    {
        $config = $this->getSiteConfig();
        $widget = new ContentWidget();
        $widget->content = $config['contact_string'];
        $widget->save();

        return $widget;
    }

    /**
     * @return Query
     */
    protected function getPagesQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'parent_id',
            'title',
            'link AS slug',
            'date AS created_at',
            'active AS status',
            'alt_title AS meta_title',
            'keywords AS meta_keywords',
            'description AS meta_description',
        ])->from('site_rus_pages');

        return $query;
    }

    /**
     * @return Query
     */
    protected function getBannersQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'name',
            'filename',
            'url',
            'active'
        ])
            ->from('site_rus_banners')
            ->where(['type' => 1]);

        return $query;
    }

    /**
     * @return Query
     */
    protected function getMenusQuery()
    {
        $query = new Query();
        $query->select([
            'title AS label',
            'link AS url',
            'active AS status'
        ])
            ->from('site_rus_pages')
            ->where(['parent_id' => 0])
            ->orderBy(['page_rank' => SORT_DESC]);

        return $query;
    }

    /**
     * @return Query
     */
    protected function getSidebarMenusQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'name'
        ])
            ->from('site_rus_menus');

        return $query;
    }

    /**
     * @param int $menuId
     * @return Query
     */
    protected function getMenuItemsQuery($menuId)
    {
        $query = new Query();
        $query->select([
            'variant AS label',
            'address AS url'
        ])
            ->from('site_rus_menu_items')
            ->where(['menu_id' => $menuId])
            ->orderBy(['v_rank' => SORT_ASC]);

        return $query;
    }

    /**
     * @return Query
     */
    protected function getWidgetsQuery()
    {
        $query = new Query();
        $query
            ->from('site_rus_pages_blocks')
            ->orderBy(['block_rank' => SORT_ASC]);

        return $query;
    }

    /**
     * @return Query
     */
    protected function getSiteConfig()
    {
        $query = new Query();
        $query->from('sites');

        return $query->one($this->getConnection());
    }

    /**
     * @param int $category
     * @return Query
     */
    protected function getBannerQuery($category)
    {
        $query = new Query();
        $query
            ->from('site_rus_banners')
            ->where(['category_id' => $category, 'active' => 1]);

        return $query;
    }

    /**
     * @return Query
     */
    protected function getArticleCategoriesQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'title AS name'
        ])
            ->from('site_rus_articlecat_categories');

        return $query;
    }

    /**
     * @return Query
     */
    protected function getNewsCategoriesQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'title AS name'
        ])
            ->from('site_rus_news_categories');

        return $query;
    }

    /**
     * @return Query
     */
    protected function getArticlesQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'title',
            'announce',
            'body AS content',
            "CONCAT('article', id, '.htm') as slug",
            'active as status',
            'UNIX_TIMESTAMP(date) AS created_at',
            'pagetitle AS meta_title',
            'keywords AS meta_keywords',
            'description AS meta_description',
            'img AS image',
            'category_id'
        ])
            ->from('site_rus_articlecat');

        return $query;
    }

    /**
     * @return Query
     */
    protected function getNewsQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'title',
            'announce',
            'body AS content',
            "CONCAT('news', id, '.htm') as slug",
            'active as status',
            'UNIX_TIMESTAMP(date) AS created_at',
            'keywords AS meta_keywords',
            'description AS meta_description',
            'img AS image',
            'category_id'
        ])
            ->from('site_rus_news');

        return $query;
    }

    /**
     * @return Query
     */
    protected function getProductCategoriesQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'active AS status',
            'title AS name',
            "CONCAT('category', id, '.htm') as slug",
            'announce AS description',
            'description AS content',
            'parent_id',
            'img as image',
            'page_title AS meta_title',
            'keywords AS meta_keywords',
            'page_description AS meta_description',
        ])
            ->from('site_rus_catalog_categories');

        return $query;
    }

    /**
     * @return Query
     */
    protected function getProductTypesQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'group_title AS name'
        ])
            ->from('site_rus_catalog_groups');

        return $query;
    }

    /**
     * @return Query
     */
    protected function getProductsQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'category_id',
            'group_id AS type_id',
            'product_code AS sku',
            'product_title AS title',
            "CONCAT('product', id, '.htm') as slug",
            'product_inf AS short_description',
            'product_description AS description',
            '(price_1 / 100) AS price',
            'image_middle AS image1',
            'image_big AS image2',
            'image_popup AS image3',
            'product_availability AS availability',
            'active AS status',
            'page_title AS meta_title',
            'description AS meta_description',
            'keywords AS meta_keywords',
            'UNIX_TIMESTAMP(add_date) AS created_at',
            'UNIX_TIMESTAMP(change_date) AS updated_at',
        ])
            ->from('site_rus_catalog_products');

        return $query;
    }

    /**
     * @return Query
     */
    protected function getEavAttributesQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'group_id AS product_type_id',
            'property_title AS presentation',
            'property_name AS name',
            'property_suffix AS unit',
        ])
            ->from('site_rus_catalog_properties_table');

        return $query;
    }

    /**
     * @return Query
     */
    protected function getEavAttributeValuesQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'value AS value',
            'value AS presentation'
        ])
            ->from('site_rus_catalog_properties_def_values');

        return $query;
    }

    /**
     * @return Query
     */
    protected function getEavProductValuesQuery()
    {
        $query = new Query();
        $query->select([
            'id',
            'product_id AS entity_id',
            'property_id AS attribute_id',
            'value'
        ])
            ->from('site_rus_catalog_property_values');

        return $query;
    }

    /**
     * @param array $tables
     */
    protected function truncateTables(array $tables)
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
        foreach ($tables as $table) {
            Yii::$app->db->createCommand()->truncateTable($table)->execute();
        }
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();
    }

    /**
     * @param Model $owner
     * @param string $storageConfig
     * @param FileInterface $file
     * @return mixed
     */
    protected function saveFile($owner, $storageConfig, FileInterface $file)
    {
        $attributes = $owner->getBehaviors()['files']->attributes;
        $storageConfig = new StorageConfig($attributes[$storageConfig]);
        /** @var StorageConfig $storageConfig */
        $path = $storageConfig->resolveFilePath($file->getBasename(), $owner);
        if (isset($storageConfig->events['beforeSave']) && $storageConfig->events['beforeSave'] instanceof \Closure) {
            call_user_func($storageConfig->events['beforeSave'], $file, $path, $storageConfig->filesystem);
        }
        if ($path = $this->getFilesystemComponent()->saveFile($file, $storageConfig->filesystem, $path, true)) {
            $file->setPath($path);
            $file->setFilesystemName($storageConfig->filesystem);
        }
    }

    /**
     * @param Banner $banner
     * @param string $filePath
     * @return BannerItem
     */
    protected function saveBannerItem(Banner $banner, $filePath)
    {
        $bannerItem = new BannerItem($this->getFileInfo($filePath));
        $this->saveFile($banner, 'uploadedItems', $bannerItem);

        return $bannerItem;
    }

    /**
     * @param Article $article
     * @param string $filePath
     * @return ArticleFile
     */
    protected function saveArticleImage(Article $article, $filePath)
    {
        $file = new ArticleFile($this->getFileInfo($filePath));
        $this->saveFile($article, 'uploadedImage', $file);

        return $file;
    }

    /**
     * @param ProductCategory $productCategory
     * @param string $filePath
     * @return ProductCategoryFile
     */
    protected function saveProductCategoryImage(ProductCategory $productCategory, $filePath)
    {
        $file = new ProductCategoryFile($this->getFileInfo($filePath));
        $this->saveFile($productCategory, 'uploadedImage', $file);

        return $file;
    }

    /**
     * @param Product $product
     * @param string $filePath
     * @param int $type
     * @return ProductFile
     */
    protected function saveProductImage(Product $product, $filePath, $type = 0)
    {
        $file = new ProductFile($this->getFileInfo($filePath));
        $file->type = $type;
        $file->attribute = 'images';
        $this->saveFile($product, 'uploadedImages', $file);

        return $file;
    }

    /**
     * @param string $filePath
     * @return array
     */
    protected function getFileInfo($filePath)
    {
        return [
            'path' => $filePath,
            'mime_type' => finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filePath),
            'size' => filesize($filePath)
        ];
    }

    /**
     * @return \yii\db\Connection
     */
    protected function getConnection()
    {
        if (!$this->connection) {
            $this->connection = Yii::$app->get('db2');
        }

        return $this->connection;
    }

    /**
     * @return \im\filesystem\components\FilesystemComponent
     */
    protected function getFilesystemComponent()
    {
        if (!$this->filesystemComponent) {
            $this->filesystemComponent = Yii::$app->get('filesystem');
        }

        return $this->filesystemComponent;
    }
}