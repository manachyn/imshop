-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 28 2015 г., 18:55
-- Версия сервера: 5.5.44-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `imshop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_brands`
--

CREATE TABLE IF NOT EXISTS `tbl_brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_categories`
--

CREATE TABLE IF NOT EXISTS `tbl_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft_rgt` (`lft`,`rgt`),
  KEY `depth` (`depth`),
  KEY `tree` (`tree`),
  KEY `name` (`name`),
  KEY `description` (`description`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_category_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_category_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_category_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_eav_attributes`
--

CREATE TABLE IF NOT EXISTS `tbl_eav_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `presentation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `field_config_data` text COLLATE utf8_unicode_ci NOT NULL,
  `rules_config_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `entity_type` (`entity_type`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `tbl_eav_attributes`
--

INSERT INTO `tbl_eav_attributes` (`id`, `name`, `presentation`, `type`, `entity_type`, `field_config_data`, `rules_config_data`) VALUES
(4, 'type', 'Тип', 'string', 'product', 'a:1:{s:9:"fieldType";s:9:"textInput";}', 's:0:"";'),
(5, 'dimensions', 'Габариты', 'string', 'product', 'a:1:{s:9:"fieldType";s:9:"textInput";}', 's:0:"";'),
(6, 'sdfdsf', 'sdfsdfsdfsdfsdf', 'string', 'product', 'a:1:{s:9:"fieldType";s:9:"textInput";}', 's:0:"";');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_eav_entity_values`
--

CREATE TABLE IF NOT EXISTS `tbl_eav_entity_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value_id` int(11) NOT NULL,
  `string_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value_entity_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entity_id` (`entity_id`),
  KEY `entity_type` (`entity_type`),
  KEY `FK_attribute_values_attribute_id` (`attribute_id`),
  KEY `attribute_name` (`attribute_name`),
  KEY `string_value` (`string_value`),
  KEY `integer_value` (`value_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_eav_product_values`
--

CREATE TABLE IF NOT EXISTS `tbl_eav_product_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value_id` int(11) NOT NULL,
  `string_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `integer_value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_attribute_values_entity_id` (`entity_id`),
  KEY `FK_product_attribute_values_attribute_id` (`attribute_id`),
  KEY `attribute_name` (`attribute_name`),
  KEY `string_value` (`string_value`),
  KEY `integer_value` (`integer_value`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `tbl_eav_product_values`
--

INSERT INTO `tbl_eav_product_values` (`id`, `entity_id`, `attribute_id`, `attribute_name`, `attribute_type`, `value_id`, `string_value`, `integer_value`) VALUES
(1, 1, 4, 'type', 'string', 0, 'sdfsdfsdf', NULL),
(2, 1, 5, 'dimensions', 'string', 0, 'sdfsdfsdsdfsdf', NULL),
(4, 8, 5, 'dimensions', 'string', 0, 'dsfsdfsdf', NULL),
(5, 8, 6, 'sdfdsf', 'string', 0, 'sdfsdfsdf', NULL),
(6, 11, 4, 'type', 'value', 4, '', NULL),
(7, 11, 5, 'dimensions', 'value', 0, '', NULL),
(8, 10, 4, 'type', 'value', 0, '', NULL),
(9, 10, 5, 'dimensions', 'value', 0, '', NULL),
(10, 17, 4, 'type', 'string', 0, '', NULL),
(11, 17, 5, 'dimensions', 'string', 0, '', NULL),
(12, 18, 4, 'type', 'value', 4, '', NULL),
(13, 18, 5, 'dimensions', 'value', 0, '', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_eav_values`
--

CREATE TABLE IF NOT EXISTS `tbl_eav_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `presentation` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `tbl_eav_values`
--

INSERT INTO `tbl_eav_values` (`id`, `attribute_id`, `value`, `presentation`) VALUES
(1, 4, 'Двухкамерные', 'two-compartment'),
(2, 4, 'Однокамерные', 'single-door'),
(3, 5, '100x100', '100x100'),
(4, 4, 'Полуавтомат', 'semiautomatic');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_entity_files`
--

CREATE TABLE IF NOT EXISTS `tbl_entity_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `attribute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `file_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `entity_id_entity_type` (`entity_id`,`entity_type`),
  KEY `attribute` (`attribute`),
  KEY `FK_entity_files_file_id` (`file_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `tbl_entity_files`
--

INSERT INTO `tbl_entity_files` (`id`, `entity_id`, `entity_type`, `attribute`, `file_id`, `sort`) VALUES
(1, 23, '', '', 1, 0),
(2, 24, '', '', 2, 0),
(3, 25, '', '', 4, 0),
(5, 51, '', '', 29, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_facets`
--

CREATE TABLE IF NOT EXISTS `tbl_facets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_id` int(11) DEFAULT NULL,
  `attribute_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_facets_attribute_id` (`attribute_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `tbl_facets`
--

INSERT INTO `tbl_facets` (`id`, `name`, `entity_type`, `attribute_id`, `attribute_name`, `type`) VALUES
(1, 'Range facet', 'product', NULL, 'price', 'range');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_facet_ranges`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_ranges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facet_id` int(11) NOT NULL,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_include` tinyint(1) DEFAULT '1',
  `to_include` tinyint(1) DEFAULT '0',
  `display` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_facet_ranges_facet_id` (`facet_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `tbl_facet_ranges`
--

INSERT INTO `tbl_facet_ranges` (`id`, `facet_id`, `from`, `to`, `from_include`, `to_include`, `display`, `sort`) VALUES
(1, 1, '0', '10', 1, 0, 'From 0 to 10', 1),
(2, 1, '10', '20', 1, 0, 'From 10 to 20', 2),
(3, 1, '20', '', 1, 0, 'More then 20', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_files`
--

CREATE TABLE IF NOT EXISTS `tbl_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filesystem` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `filesystem` (`filesystem`),
  KEY `path` (`path`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;

--
-- Дамп данных таблицы `tbl_files`
--

INSERT INTO `tbl_files` (`id`, `filesystem`, `path`, `title`, `size`, `mime_type`, `created_at`, `updated_at`) VALUES
(1, 'local', '/products/images/d-sdfsdf-sdfsdf-1.png', '', 1644823, 'image/png', 1437558441, 1437558441),
(2, 'local', '/products/images/d-sad-yutyu-1.png', '', 101155, 'image/png', 1437558730, 1437558730),
(3, 'local', '/products/videos/d-sd-frewerewr-1.png', '', 464847, 'image/png', 1437558841, 1437558841),
(4, 'local', '/products/images/d-sd-frewerewr-1.png', '', 1644823, 'image/png', 1437558842, 1437558842),
(27, 'local', '/categories/gfdgfdgdf-df-gdfgdsdf-dsf.png', '', 207200, 'image/png', 1438692797, 1438692797),
(28, 'local', '/categories/fg-dfgdfg-dfg-dgdf.png', '', 207200, 'image/png', 1438704879, 1438704879),
(29, 'local', '/categories/gdfgdf-1.png', 'Titleeeeee', 207200, 'image/png', 1438759512, 1438759512);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_forms`
--

CREATE TABLE IF NOT EXISTS `tbl_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_form_fields`
--

CREATE TABLE IF NOT EXISTS `tbl_form_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `hint` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `options_data` text COLLATE utf8_unicode_ci,
  `rules_data` text COLLATE utf8_unicode_ci,
  `items` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_form_fields_form_id` (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_indexes`
--

CREATE TABLE IF NOT EXISTS `tbl_indexes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `service` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `tbl_indexes`
--

INSERT INTO `tbl_indexes` (`id`, `name`, `type`, `service`, `status`) VALUES
(1, 'products_index', 'product', 'elastic', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_index_attributes`
--

CREATE TABLE IF NOT EXISTS `tbl_index_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `index_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `tbl_index_attributes`
--

INSERT INTO `tbl_index_attributes` (`id`, `index_type`, `name`, `type`) VALUES
(1, 'product', 'status', ''),
(2, 'product', 'eAttributes.type', ''),
(3, 'product', 'eAttributes.dimensions', '');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_menus`
--

CREATE TABLE IF NOT EXISTS `tbl_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_menus_items`
--

CREATE TABLE IF NOT EXISTS `tbl_menus_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) DEFAULT NULL,
  `label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `FK_menu_item_page` (`page_id`),
  KEY `FK_menu_item_menu` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_migration`
--

INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1437398890),
('m141023_154713_create_cms_tables', 1440749395),
('m150208_105944_create_eav_tables', 1437398915),
('m150208_114635_create_variation_tables', 1437398926),
('m150208_123637_create_catalog_tables', 1437398986),
('m150415_113756_create_seo_tables', 1437398936),
('m150427_145906_create_form_builder_tables', 1437398893),
('m150721_121000_create_filesystem_tables', 1437484206),
('m150806_091325_create_search_tables', 1440166665);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_options`
--

CREATE TABLE IF NOT EXISTS `tbl_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `presentation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_option_values`
--

CREATE TABLE IF NOT EXISTS `tbl_option_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_option_values_option_id` (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_pages`
--

CREATE TABLE IF NOT EXISTS `tbl_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `layout_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_page_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_page_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_page_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_products`
--

CREATE TABLE IF NOT EXISTS `tbl_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `brand_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `available_on` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `images_data` text COLLATE utf8_unicode_ci NOT NULL,
  `video_data` text COLLATE utf8_unicode_ci NOT NULL,
  `dvideo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_products_brand_id` (`brand_id`),
  KEY `FK_products_type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Дамп данных таблицы `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `sku`, `title`, `slug`, `description`, `quantity`, `price`, `status`, `brand_id`, `type_id`, `available_on`, `created_at`, `updated_at`, `images_data`, `video_data`, `dvideo_id`) VALUES
(1, 'fgdfg', 'dfgdfgdfgdfg', 'dfgdfgdfgdfg', '', '', 0, 1, NULL, 2, 0, 1437487473, 1438943206, '["dfgdfgdfgdfg-1.png","dfgdfgdfgdfg-2.png"]', '', 0),
(2, 'dfsdfsdf', 'sdfsdfsdf', 'sdfsdfsdf', '', '', 0, 1, NULL, NULL, 0, 1437548999, 1437548999, '["sdfsdfsdf-1.png","sdfsdfsdf-2.png","sdfsdfsdf-3.png"]', 'sdfsdfsdf-1.png', 0),
(3, '', 'g fgd fgdfg fg', 'g-fgd-fgdfg-fg', '', '', 0, 1, NULL, NULL, 0, 1437549563, 1437549563, '["g-fgd-fgdfg-fg-1.png","g-fgd-fgdfg-fg-2.png"]', 'g-fgd-fgdfg-fg-1.png', 0),
(4, '', 'g fgd fgdfg fg', 'g-fgd-fgdfg-fg-2', '', '', 0, 1, NULL, NULL, 0, 1437549699, 1437549699, '["g-fgd-fgdfg-fg-2-1.png","g-fgd-fgdfg-fg-2-2.png"]', 'g-fgd-fgdfg-fg-2-1.png', 0),
(5, '', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg', '', '', 0, 1, NULL, NULL, 0, 1437549798, 1437549798, '["fgd-dfgdf-dfg-1.png","fgd-dfgdf-dfg-2.png"]', 'fgd-dfgdf-dfg-1.png', 0),
(6, '', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg-2', '', '', 0, 1, NULL, NULL, 0, 1437549912, 1437549912, '["fgd-dfgdf-dfg-2-1.png","fgd-dfgdf-dfg-2-2.png"]', 'fgd-dfgdf-dfg-2-1.png', 0),
(7, '', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg-3', '', '', 0, 1, NULL, NULL, 0, 1437549938, 1437549938, '["fgd-dfgdf-dfg-3-1.png","fgd-dfgdf-dfg-3-2.png"]', 'fgd-dfgdf-dfg-3-1.png', 0),
(8, '8', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg-4', '', '', 0, 1, NULL, 3, 0, 1437550071, 1438946430, '["fgd-dfgdf-dfg-4-1.png","fgd-dfgdf-dfg-4-2.png"]', 'fgd-dfgdf-dfg-4-1.png', 0),
(9, '', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg-5', '', '', 0, 1, NULL, NULL, 0, 1437550204, 1437550204, '["fgd-dfgdf-dfg-5-1.png","fgd-dfgdf-dfg-5-2.png"]', 'fgd-dfgdf-dfg-5-1.png', 0),
(10, '10', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg-6', '', '', 0, 1, NULL, 2, 0, 1437550228, 1438961963, '["fgd-dfgdf-dfg-6-1.png","fgd-dfgdf-dfg-6-2.png"]', 'fgd-dfgdf-dfg-6-1.png', 0),
(11, '11', 'err werewr', 'err-werewr', '', '', 0, 1, NULL, 2, 0, 1437550631, 1438961816, '["err-werewr-1.png","err-werewr-2.png"]', 'err-werewr-1.png', 0),
(12, '', 'err werewr', 'err-werewr-2', '', '', 0, 1, NULL, NULL, 0, 1437550726, 1437550729, '["err-werewr-2-1.png","err-werewr-2-2.png"]', 'err-werewr-2-1.png', 0),
(13, '', 'err werewr', 'err-werewr-3', '', '', 0, 1, NULL, NULL, 0, 1437551003, 1437551011, '["err-werewr-3-1.png","err-werewr-3-2.png"]', 'err-werewr-3-1.png', 0),
(14, '', 'err werewr', 'err-werewr-4', '', '', 0, 1, NULL, NULL, 0, 1437551048, 1437551050, '["err-werewr-4-1.png","err-werewr-4-2.png"]', 'err-werewr-4-1.png', 0),
(15, '', 'err werewr', 'err-werewr-5', '', '', 0, 1, NULL, NULL, 0, 1437551076, 1437551084, '["err-werewr-5-1.png","err-werewr-5-2.png"]', 'err-werewr-5-1.png', 0),
(16, '', 'err werewr', 'err-werewr-6', '', '', 0, 1, NULL, NULL, 0, 1437551145, 1437551150, '["err-werewr-6-1.png","err-werewr-6-2.png"]', 'err-werewr-6-1.png', 0),
(17, '17', 'err werewr', 'err-werewr-7', '', '', 0, 1, NULL, 2, 0, 1437551338, 1438962080, '["err-werewr-7-1.png","err-werewr-7-2.png"]', 'err-werewr-7-1.png', 0),
(18, '18', 'err werewr', 'err-werewr-8', '', '', 0, 1, NULL, 2, 0, 1437551510, 1439191521, '["err-werewr-8-1.png","err-werewr-8-2.png"]', 'err-werewr-8-1.png', 0),
(19, '', 'fgsdfsdfs', 'fgsdfsdfs', '', '', 0, 1, NULL, NULL, 0, 1437554866, 1437554866, 'a:1:{i:0;s:0:"";}', 'O:20:"yii\\web\\UploadedFile":5:{s:4:"name";s:54:"Снимок экрана от 2015-07-08 16:48:55.png";s:8:"tempName";s:14:"/tmp/phpuTuZ14";s:4:"type";s:9:"image/png";s:4:"size";i:464847;s:5:"error";i:0;}', 0),
(20, '', 'sdfs 4trsdc sdfsdf', 'sdfs-4trsdc-sdfsdf', '', '', 0, 1, NULL, NULL, 0, 1437555505, 1437555505, 'a:2:{i:0;O:25:"im\\filesystem\\models\\File":7:{s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";s:4:"path";s:41:"/products/images/sdfs-4trsdc-sdfsdf-1.png";s:23:"\0yii\\base\\Model\0_errors";N;s:27:"\0yii\\base\\Model\0_validators";N;s:25:"\0yii\\base\\Model\0_scenario";s:7:"default";s:27:"\0yii\\base\\Component\0_events";a:0:{}s:30:"\0yii\\base\\Component\0_behaviors";N;}i:1;O:25:"im\\filesystem\\models\\File":7:{s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";s:4:"path";s:41:"/products/images/sdfs-4trsdc-sdfsdf-2.png";s:23:"\0yii\\base\\Model\0_errors";N;s:27:"\0yii\\base\\Model\0_validators";N;s:25:"\0yii\\base\\Model\0_scenario";s:7:"default";s:27:"\0yii\\base\\Component\0_events";a:0:{}s:30:"\0yii\\base\\Component\0_behaviors";N;}}', 'O:25:"im\\filesystem\\models\\File":7:{s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";s:4:"path";s:41:"/products/videos/sdfs-4trsdc-sdfsdf-1.png";s:23:"\0yii\\base\\Model\0_errors";N;s:27:"\0yii\\base\\Model\0_validators";N;s:25:"\0yii\\base\\Model\0_scenario', 0),
(21, '', 'dsf sdf sdf sdfsd dfs ', 'dsf-sdf-sdf-sdfsd-dfs', '', '', 0, 1, NULL, NULL, 0, 1437555645, 1437555645, 'a:1:{i:0;O:25:"im\\filesystem\\models\\File":7:{s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";s:4:"path";s:44:"/products/images/dsf-sdf-sdf-sdfsd-dfs-1.png";s:23:"\0yii\\base\\Model\0_errors";N;s:27:"\0yii\\base\\Model\0_validators";N;s:25:"\0yii\\base\\Model\0_scenario";s:7:"default";s:27:"\0yii\\base\\Component\0_events";a:0:{}s:30:"\0yii\\base\\Component\0_behaviors";N;}}', 'O:25:"im\\filesystem\\models\\File":7:{s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";s:4:"path";s:44:"/products/videos/dsf-sdf-sdf-sdfsd-dfs-1.png";s:23:"\0yii\\base\\Model\0_errors";N;s:27:"\0yii\\base\\Model\0_validators";N;s:25:"\0yii\\base\\Model\0_scenario";s:7:"default";s:27:"\0yii\\base\\Component\0_events";a:0:{}s:30:"\0yii\\base\\Component\0_behaviors";N;}', 0),
(22, '', 'sdfsd sdf sdf sdfsdf', 'sdfsd-sdf-sdf-sdfsdf', '', '', 0, 1, NULL, NULL, 0, 1437555866, 1437555866, 'a:1:{i:0;O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:43:"/products/images/sdfsd-sdf-sdf-sdfsdf-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}}', 'O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:43:"/products/videos/sdfsd-sdf-sdf-sdfsdf-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}', 0),
(23, '', 'sdfsdf sdfsdf', 'sdfsdf-sdfsdf', '', '', 0, 1, NULL, NULL, 0, 1437558324, 1437558324, '', '', 0),
(24, '', 'sad yutyu', 'sad-yutyu', '', '', 0, 1, NULL, NULL, 0, 1437558586, 1437558586, '', '', 0),
(25, '', 'sd frewerewr', 'sd-frewerewr', '', '', 0, 1, NULL, NULL, 0, 1437558825, 1437558825, '', '', 3),
(26, '', 'wewqe wqeqwqwe', 'wewqe-wqeqwqwe', '', '', 0, 1, NULL, NULL, 0, 1437573147, 1437573147, 'a:1:{i:0;O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:37:"/products/images/wewqe-wqeqwqwe-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}}', 'O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:37:"/products/videos/wewqe-wqeqwqwe-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}', 0),
(27, '', 'sdfsdf sdfs fs sdf', 'sdfsdf-sdfs-fs-sdf', '', '', 0, 1, NULL, NULL, 0, 1437574899, 1437574899, 'a:1:{i:0;O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:52:"/products/images/{model.id}-sdfsdf-sdfs-fs-sdf-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}}', 'O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:52:"/products/videos/{model.id}-sdfsdf-sdfs-fs-sdf-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}', 0),
(28, '', 'hgjf fg fhgfj', 'hgjf-fg-fhgfj', '', '', 0, 1, NULL, NULL, 0, 1437575301, 1437575301, 'a:1:{i:0;O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:39:"/products/images/28-hgjf-fg-fhgfj-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}}', 'O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:39:"/products/videos/28-hgjf-fg-fhgfj-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_products_categories`
--

CREATE TABLE IF NOT EXISTS `tbl_products_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  KEY `FK_products_categories_product_id` (`product_id`),
  KEY `FK_products_categories_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_categories`
--

CREATE TABLE IF NOT EXISTS `tbl_product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `video_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft_rgt` (`lft`,`rgt`),
  KEY `depth` (`depth`),
  KEY `tree` (`tree`),
  KEY `name` (`name`),
  KEY `description` (`description`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=52 ;

--
-- Дамп данных таблицы `tbl_product_categories`
--

INSERT INTO `tbl_product_categories` (`id`, `tree`, `lft`, `rgt`, `depth`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`, `image_id`, `video_data`) VALUES
(1, NULL, 1, 36, 0, 'Бытовая техника', 'bytovaya-tehnika', '', 1, 1437407041, 1437407606, 0, ''),
(2, NULL, 2, 9, 1, 'Крупная бытовая техника ', 'krupnaa-bytovaya-tehnika', '', 1, 1437407816, 1437407825, 0, ''),
(3, NULL, 10, 17, 1, 'Встраиваемая техника', 'vstraivaemaya-tehnika', '', 1, 1437407901, 1437407906, 0, ''),
(4, NULL, 18, 25, 1, 'Климатическая техника', 'klimaticeskaya-tehnika', '', 1, 1437407958, 1437407961, 0, ''),
(5, NULL, 26, 33, 1, 'Мелкая бытовая техника', 'melkaya-bytovaya-tehnika', '', 1, 1437408062, 1437408069, 0, ''),
(6, NULL, 3, 4, 2, 'Холодильники', 'holodilniki', '', 1, 1437408276, 1437408276, 0, ''),
(7, NULL, 5, 6, 2, 'Стиральные машины', 'stiralnye-mashiny', '', 1, 1437408298, 1437408306, 0, ''),
(8, NULL, 7, 8, 2, 'Плиты', 'plity', '', 1, 1437408328, 1437408328, 0, ''),
(9, NULL, 11, 12, 2, 'Духовые шкафы ', 'duhovye-shkafy', '', 1, 1437408426, 1437408440, 0, ''),
(10, NULL, 13, 14, 2, 'Варочные поверхности', 'varochnye-poverhnosti', '', 1, 1437408467, 1437408480, 0, ''),
(11, NULL, 15, 16, 2, 'Вытяжки', 'vytazhki', '', 1, 1437408504, 1437408508, 0, ''),
(12, NULL, 19, 20, 2, 'Бойлеры', 'bojlery', '', 1, 1437408542, 1437408542, 0, ''),
(13, NULL, 21, 22, 2, 'Котлы', 'kotly', '', 1, 1437408680, 1437408680, 0, ''),
(14, NULL, 23, 24, 2, 'Кондиционеры', 'kondicionery', '', 1, 1437408698, 1437408698, 0, ''),
(15, NULL, 27, 28, 2, 'Техника для кухни', 'tehnika-dla-kuhni', '', 1, 1437408727, 1437408727, 0, ''),
(16, NULL, 29, 30, 2, 'Товары по уходу за домом', 'tovary-po-uhodu-za-domom', '', 1, 1437408753, 1437408753, 0, ''),
(17, NULL, 31, 32, 2, 'Красота, здоровье', 'krasota-zdorove', '', 1, 1437408767, 1437408767, 0, ''),
(18, NULL, 34, 35, 1, 'fg ds sdfsdf', 'fg-ds-sdfsdf', '', 1, 1438672364, 1438675289, 0, ''),
(19, 19, 1, 2, 0, 'Test Category', 'test-category', '', 1, 1438678886, 1438678886, 0, ''),
(20, 20, 1, 2, 0, 'fdgdf dfg dfg dfg', 'fdgdf-dfg-dfg-dfg', '', 1, 1438678965, 1438678965, 0, ''),
(21, 21, 1, 2, 0, 'gfg dfgdfgdfg', 'gfg-dfgdfgdfg', '', 1, 1438679718, 1438679718, 0, ''),
(22, 22, 1, 2, 0, 'jhg fgh jh', 'jhg-fgh-jh', '', 1, 1438679885, 1438679885, 0, ''),
(24, 24, 1, 2, 0, 'My test category', 'my-test-category', '', 1, 1438683785, 1438683785, 0, ''),
(37, 37, 1, 2, 0, 'GFDGFDGDF  DF GDFGdsdf dsf', 'gfdgfdgdf-df-gdfgdsdf-dsf', '', 1, 1438692793, 1438692845, 27, ''),
(48, 48, 1, 2, 0, 'fg dfgdfg dfg dgdf', 'fg-dfgdfg-dfg-dgdf', '', 1, 1438704853, 1438704881, 28, ''),
(49, 49, 1, 2, 0, 'gdfgdfg dfgdfg', 'gdfgdfg-dfgdfg', '', 1, 1438759236, 1438759236, 0, ''),
(51, 51, 1, 2, 0, 'gdfgdf', 'gdfgdf', '', 1, 1438759422, 1438784808, 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_category_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_product_category_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_category_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_files`
--

CREATE TABLE IF NOT EXISTS `tbl_product_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `attribute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `filesystem` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `filesystem` (`filesystem`),
  KEY `path` (`path`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_product_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

--
-- Дамп данных таблицы `tbl_product_meta`
--

INSERT INTO `tbl_product_meta` (`id`, `entity_id`, `meta_title`, `meta_description`, `meta_robots`, `custom_meta`) VALUES
(1, 1, '', '', '', ''),
(2, 2, '', '', '', ''),
(3, 3, '', '', '', ''),
(4, 4, '', '', '', ''),
(5, 5, '', '', '', ''),
(6, 6, '', '', '', ''),
(7, 7, '', '', '', ''),
(8, 8, '', '', '', ''),
(9, 9, '', '', '', ''),
(10, 10, '', '', '', ''),
(11, 11, '', '', '', ''),
(12, 12, '', '', '', ''),
(13, 13, '', '', '', ''),
(14, 14, '', '', '', ''),
(15, 15, '', '', '', ''),
(16, 16, '', '', '', ''),
(17, 17, '', '', '', ''),
(18, 18, '', '', '', ''),
(19, 19, '', '', '', ''),
(20, 20, '', '', '', ''),
(21, 21, '', '', '', ''),
(22, 22, '', '', '', ''),
(23, 23, '', '', '', ''),
(24, 24, '', '', '', ''),
(25, 25, '', '', '', ''),
(26, 27, '', '', '', ''),
(27, 28, '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_option_values`
--

CREATE TABLE IF NOT EXISTS `tbl_product_option_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_option_values_option_id` (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_types`
--

CREATE TABLE IF NOT EXISTS `tbl_product_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_types_parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `tbl_product_types`
--

INSERT INTO `tbl_product_types` (`id`, `name`, `parent_id`) VALUES
(1, 'Холодильники', NULL),
(2, 'Стиральные машины', NULL),
(3, 'Плиты', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_type_attributes`
--

CREATE TABLE IF NOT EXISTS `tbl_product_type_attributes` (
  `product_type_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  KEY `FK_product_type_attributes_product_type_id` (`product_type_id`),
  KEY `FK_product_type_attributes_attribute_id` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `tbl_product_type_attributes`
--

INSERT INTO `tbl_product_type_attributes` (`product_type_id`, `attribute_id`) VALUES
(2, 5),
(2, 4),
(3, 5),
(3, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_type_options`
--

CREATE TABLE IF NOT EXISTS `tbl_product_type_options` (
  `product_type_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  KEY `FK_product_type_options_product_type` (`product_type_id`),
  KEY `FK_product_type_options_option` (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_variants`
--

CREATE TABLE IF NOT EXISTS `tbl_product_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `presentation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sku` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `master` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_variants_entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_variant_option_values`
--

CREATE TABLE IF NOT EXISTS `tbl_product_variant_option_values` (
  `product_variant_id` int(11) NOT NULL,
  `option_value_id` int(11) NOT NULL,
  KEY `FK_product_variant_option_values_product_variant_id` (`product_variant_id`),
  KEY `FK_product_variant_option_values_option_value_id` (`option_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_seo_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_seo_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `entity_id` (`entity_id`),
  KEY `entity_type` (`entity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_social_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_social_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_id` int(11) DEFAULT NULL,
  `meta_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `social_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `site_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `card` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `site` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `creator` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `meta_id` (`meta_id`),
  KEY `meta_type` (`meta_type`),
  KEY `social_type` (`social_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

--
-- Дамп данных таблицы `tbl_social_meta`
--

INSERT INTO `tbl_social_meta` (`id`, `meta_id`, `meta_type`, `social_type`, `title`, `type`, `url`, `image`, `description`, `site_name`, `video`, `card`, `site`, `creator`) VALUES
(1, 1, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(2, 1, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(3, 2, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(4, 2, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(5, 3, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(6, 3, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(7, 4, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(8, 4, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(9, 5, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(10, 5, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(11, 6, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(12, 6, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(13, 7, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(14, 7, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(15, 8, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(16, 8, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(17, 9, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(18, 9, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(19, 10, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(20, 10, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(21, 11, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(22, 11, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(23, 12, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(24, 12, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(25, 13, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(26, 13, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(27, 14, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(28, 14, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(29, 15, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(30, 15, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(31, 16, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(32, 16, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(33, 17, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(34, 17, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(35, 18, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(36, 18, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(37, 19, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(38, 19, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(39, 20, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(40, 20, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(41, 21, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(42, 21, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(43, 22, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(44, 22, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(45, 23, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(46, 23, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(47, 24, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(48, 24, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(49, 25, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(50, 25, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(51, 26, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(52, 26, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(53, 27, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(54, 27, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_templates`
--

CREATE TABLE IF NOT EXISTS `tbl_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `layout_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `layout_id` (`layout_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `tbl_templates`
--

INSERT INTO `tbl_templates` (`id`, `name`, `layout_id`) VALUES
(1, 'Main templ', '');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_variants`
--

CREATE TABLE IF NOT EXISTS `tbl_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `presentation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `master` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `entity_id` (`entity_id`),
  KEY `entity_type` (`entity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_variant_option_values`
--

CREATE TABLE IF NOT EXISTS `tbl_variant_option_values` (
  `variant_id` int(11) NOT NULL,
  `option_value_id` int(11) NOT NULL,
  KEY `FK_variant_option_values_variant_id` (`variant_id`),
  KEY `FK_variant_option_values_option_value_id` (`option_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_widgets`
--

CREATE TABLE IF NOT EXISTS `tbl_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `banner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `tbl_widgets`
--

INSERT INTO `tbl_widgets` (`id`, `widget_type`, `content`, `banner_id`) VALUES
(1, 'content', 'fdsgdfgdfgdfg', 0),
(2, 'banner', '', 0),
(3, 'banner', '', 0),
(4, 'content', 'gffdgdfgdfg', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_widget_areas`
--

CREATE TABLE IF NOT EXISTS `tbl_widget_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `template_id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `display` tinyint(1) DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_widget_areas_template_id` (`template_id`),
  KEY `code` (`code`),
  KEY `owner_id` (`owner_id`),
  KEY `owner_type` (`owner_type`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `tbl_widget_areas`
--

INSERT INTO `tbl_widget_areas` (`id`, `code`, `template_id`, `owner_id`, `owner_type`, `display`, `created_at`, `updated_at`) VALUES
(1, 'sidebar', 1, NULL, '', 3, 1440749938, 1440761771),
(2, 'footer', 1, NULL, '', 3, 1440749939, 1440761772);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_widget_area_widgets`
--

CREATE TABLE IF NOT EXISTS `tbl_widget_area_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_id` int(11) NOT NULL,
  `widget_area_id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_widget_area_widgets_widget_id` (`widget_id`),
  KEY `FK_widget_area_widgets_widget_area_id` (`widget_area_id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;

--
-- Дамп данных таблицы `tbl_widget_area_widgets`
--

INSERT INTO `tbl_widget_area_widgets` (`id`, `widget_id`, `widget_area_id`, `owner_id`, `owner_type`, `sort`) VALUES
(31, 1, 1, NULL, '', 1),
(32, 3, 1, NULL, '', 2),
(33, 2, 2, NULL, '', 1),
(34, 4, 2, NULL, '', 2);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `tbl_category_meta`
--
ALTER TABLE `tbl_category_meta`
  ADD CONSTRAINT `FK_category_meta_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_eav_entity_values`
--
ALTER TABLE `tbl_eav_entity_values`
  ADD CONSTRAINT `FK_attribute_values_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_eav_product_values`
--
ALTER TABLE `tbl_eav_product_values`
  ADD CONSTRAINT `FK_product_attribute_values_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_attribute_values_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_entity_files`
--
ALTER TABLE `tbl_entity_files`
  ADD CONSTRAINT `FK_entity_files_file_id` FOREIGN KEY (`file_id`) REFERENCES `tbl_files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_facets`
--
ALTER TABLE `tbl_facets`
  ADD CONSTRAINT `FK_facets_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_facet_ranges`
--
ALTER TABLE `tbl_facet_ranges`
  ADD CONSTRAINT `FK_facet_ranges_facet_id` FOREIGN KEY (`facet_id`) REFERENCES `tbl_facets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_form_fields`
--
ALTER TABLE `tbl_form_fields`
  ADD CONSTRAINT `FK_form_fields_form_id` FOREIGN KEY (`form_id`) REFERENCES `tbl_forms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_menus_items`
--
ALTER TABLE `tbl_menus_items`
  ADD CONSTRAINT `FK_menu_item_menu` FOREIGN KEY (`menu_id`) REFERENCES `tbl_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_menu_item_page` FOREIGN KEY (`page_id`) REFERENCES `tbl_pages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_option_values`
--
ALTER TABLE `tbl_option_values`
  ADD CONSTRAINT `FK_option_values_option_id` FOREIGN KEY (`option_id`) REFERENCES `tbl_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_page_meta`
--
ALTER TABLE `tbl_page_meta`
  ADD CONSTRAINT `FK_page_meta_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD CONSTRAINT `FK_products_brand_id` FOREIGN KEY (`brand_id`) REFERENCES `tbl_brands` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_products_type_id` FOREIGN KEY (`type_id`) REFERENCES `tbl_product_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_products_categories`
--
ALTER TABLE `tbl_products_categories`
  ADD CONSTRAINT `FK_products_categories_category_id` FOREIGN KEY (`category_id`) REFERENCES `tbl_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_products_categories_product_id` FOREIGN KEY (`product_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_product_category_meta`
--
ALTER TABLE `tbl_product_category_meta`
  ADD CONSTRAINT `FK_product_category_meta_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_product_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_product_meta`
--
ALTER TABLE `tbl_product_meta`
  ADD CONSTRAINT `FK_product_meta_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_product_option_values`
--
ALTER TABLE `tbl_product_option_values`
  ADD CONSTRAINT `FK_product_option_values_option_id` FOREIGN KEY (`option_id`) REFERENCES `tbl_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_product_types`
--
ALTER TABLE `tbl_product_types`
  ADD CONSTRAINT `FK_product_types_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `tbl_product_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_product_type_attributes`
--
ALTER TABLE `tbl_product_type_attributes`
  ADD CONSTRAINT `FK_product_type_attributes_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_type_attributes_product_type_id` FOREIGN KEY (`product_type_id`) REFERENCES `tbl_product_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_product_type_options`
--
ALTER TABLE `tbl_product_type_options`
  ADD CONSTRAINT `FK_product_type_options_option` FOREIGN KEY (`option_id`) REFERENCES `tbl_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_type_options_product_type` FOREIGN KEY (`product_type_id`) REFERENCES `tbl_product_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_product_variants`
--
ALTER TABLE `tbl_product_variants`
  ADD CONSTRAINT `FK_product_variants_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_product_variant_option_values`
--
ALTER TABLE `tbl_product_variant_option_values`
  ADD CONSTRAINT `FK_product_variant_option_values_option_value_id` FOREIGN KEY (`option_value_id`) REFERENCES `tbl_product_option_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_variant_option_values_product_variant_id` FOREIGN KEY (`product_variant_id`) REFERENCES `tbl_product_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_variant_option_values`
--
ALTER TABLE `tbl_variant_option_values`
  ADD CONSTRAINT `FK_variant_option_values_option_value_id` FOREIGN KEY (`option_value_id`) REFERENCES `tbl_option_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_variant_option_values_variant_id` FOREIGN KEY (`variant_id`) REFERENCES `tbl_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_widget_areas`
--
ALTER TABLE `tbl_widget_areas`
  ADD CONSTRAINT `FK_widget_areas_template_id` FOREIGN KEY (`template_id`) REFERENCES `tbl_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_widget_area_widgets`
--
ALTER TABLE `tbl_widget_area_widgets`
  ADD CONSTRAINT `FK_widget_area_widgets_widget_area_id` FOREIGN KEY (`widget_area_id`) REFERENCES `tbl_widget_areas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_widget_area_widgets_widget_id` FOREIGN KEY (`widget_id`) REFERENCES `tbl_widgets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
