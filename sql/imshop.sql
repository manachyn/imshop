-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 14 2015 г., 16:47
-- Версия сервера: 5.5.44-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.13

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
-- Структура таблицы `tbl_category_files`
--

CREATE TABLE IF NOT EXISTS `tbl_category_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `attribute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `filesystem` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `filesystem` (`filesystem`),
  KEY `path` (`path`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`),
  KEY `sort` (`sort`),
  KEY `category_id` (`category_id`)
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Дамп данных таблицы `tbl_eav_product_values`
--

INSERT INTO `tbl_eav_product_values` (`id`, `entity_id`, `attribute_id`, `attribute_name`, `attribute_type`, `value_id`, `string_value`, `integer_value`) VALUES
(4, 8, 5, 'dimensions', 'string', 0, 'dsfsdfsdf', NULL),
(5, 8, 6, 'sdfdsf', 'string', 0, 'sdfsdfsdf', NULL),
(6, 11, 4, 'type', 'value', 4, '', NULL),
(7, 11, 5, 'dimensions', 'value', 0, '', NULL),
(8, 10, 4, 'type', 'value', 0, '', NULL),
(9, 10, 5, 'dimensions', 'value', 0, '', NULL),
(10, 17, 4, 'type', 'string', 0, '', NULL),
(11, 17, 5, 'dimensions', 'string', 0, '', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_eav_values`
--

CREATE TABLE IF NOT EXISTS `tbl_eav_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `presentation` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `tbl_eav_values`
--

INSERT INTO `tbl_eav_values` (`id`, `attribute_id`, `presentation`, `value`) VALUES
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
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_id` int(11) DEFAULT NULL,
  `attribute_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `from` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `interval` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `multivalue` tinyint(1) NOT NULL DEFAULT '1',
  `operator` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_facets_attribute_id` (`attribute_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `tbl_facets`
--

INSERT INTO `tbl_facets` (`id`, `name`, `label`, `entity_type`, `attribute_id`, `attribute_name`, `from`, `to`, `interval`, `type`, `multivalue`, `operator`) VALUES
(1, 'price1', '', 'product', NULL, 'price', '10', '100', '10', 'interval', 1, 'and'),
(2, 'type', '', 'product', NULL, 'eAttributes.type', '', '', '', 'terms', 1, 'and'),
(3, 'price2', '', 'product', NULL, 'price', '', '', '', 'range', 1, 'and'),
(4, 'status', '', 'product', NULL, 'status', '', '', '', 'terms', 1, 'and'),
(5, 'status2', '', 'product', NULL, 'status', '', '', '', 'terms', 1, 'and'),
(6, 'price3', '', 'product', NULL, 'price', '', '', '', 'range', 1, 'and');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_facet_ranges`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_ranges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facet_id` int(11) NOT NULL,
  `lower_bound` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `upper_bound` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `include_lower_bound` tinyint(1) DEFAULT '1',
  `include_upper_bound` tinyint(1) DEFAULT '0',
  `display` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_facet_ranges_facet_id` (`facet_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `tbl_facet_ranges`
--

INSERT INTO `tbl_facet_ranges` (`id`, `facet_id`, `lower_bound`, `upper_bound`, `include_lower_bound`, `include_upper_bound`, `display`, `sort`) VALUES
(1, 3, '0', '50', 1, 0, '', 1),
(2, 3, '50', '100', 1, 0, 'From 50 to 100', 2),
(3, 1, '20', '30', 1, 0, 'More then 20', 3),
(5, 1, '30', '40', 1, 0, '', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_facet_sets`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `tbl_facet_sets`
--

INSERT INTO `tbl_facet_sets` (`id`, `name`) VALUES
(1, 'Set1');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_facet_set_facets`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_set_facets` (
  `set_id` int(11) NOT NULL,
  `facet_id` int(11) NOT NULL,
  KEY `FK_facet_set_facets_set_id` (`set_id`),
  KEY `FK_facet_set_facets_facet_id` (`facet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `tbl_facet_set_facets`
--

INSERT INTO `tbl_facet_set_facets` (`set_id`, `facet_id`) VALUES
(1, 1),
(1, 3),
(1, 2),
(1, 4),
(1, 5),
(1, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_facet_terms`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facet_id` int(11) NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_facet_terms_facet_id` (`facet_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `tbl_facet_terms`
--

INSERT INTO `tbl_facet_terms` (`id`, `facet_id`, `term`, `display`, `sort`) VALUES
(8, 4, '1', 'Active', 1),
(9, 4, '0', 'Inactive', 2);

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
(1, 'products_index', 'product', 'elastic', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_index_attributes`
--

CREATE TABLE IF NOT EXISTS `tbl_index_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `index_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `index_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `tbl_index_attributes`
--

INSERT INTO `tbl_index_attributes` (`id`, `index_type`, `name`, `index_name`, `type`) VALUES
(1, 'product', 'status', '', 'integer'),
(3, 'product', 'price', '', 'integer'),
(5, 'product', 'eAttributes.type', 'type', 'string');

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
  `template_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `tbl_pages`
--

INSERT INTO `tbl_pages` (`id`, `type`, `title`, `slug`, `content`, `status`, `created_at`, `updated_at`, `template_id`) VALUES
(1, 'page', 'fghfg', 'index', '<p>Index content</p>\r\n', 1, 1442477644, 1444723830, 4),
(2, 'search_page', 'Search results', 'search-results', '<p>Search results</p>', 1, 1442846796, 1442846796, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `tbl_page_meta`
--

INSERT INTO `tbl_page_meta` (`id`, `entity_id`, `meta_title`, `meta_description`, `meta_robots`, `custom_meta`) VALUES
(1, 1, '', '', '', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

--
-- Дамп данных таблицы `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `sku`, `title`, `slug`, `description`, `quantity`, `price`, `status`, `brand_id`, `type_id`, `available_on`, `created_at`, `updated_at`, `images_data`, `video_data`, `dvideo_id`) VALUES
(5, 'P5', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg', '', '', 67, 1, NULL, NULL, 0, 1437549798, 1444728168, '["fgd-dfgdf-dfg-1.png","fgd-dfgdf-dfg-2.png"]', 'fgd-dfgdf-dfg-1.png', 0),
(6, 'P6', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg-2', '', '', 84, 1, NULL, NULL, 0, 1437549912, 1437549912, '["fgd-dfgdf-dfg-2-1.png","fgd-dfgdf-dfg-2-2.png"]', 'fgd-dfgdf-dfg-2-1.png', 0),
(7, 'P7', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg-3', '', '', 30, 1, NULL, NULL, 0, 1437549938, 1437549938, '["fgd-dfgdf-dfg-3-1.png","fgd-dfgdf-dfg-3-2.png"]', 'fgd-dfgdf-dfg-3-1.png', 0),
(8, 'P8', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg-4', '', '', 66, 1, NULL, 3, 0, 1437550071, 1438946430, '["fgd-dfgdf-dfg-4-1.png","fgd-dfgdf-dfg-4-2.png"]', 'fgd-dfgdf-dfg-4-1.png', 0),
(9, 'P9', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg-5', '', '', 51, 1, NULL, NULL, 0, 1437550204, 1437550204, '["fgd-dfgdf-dfg-5-1.png","fgd-dfgdf-dfg-5-2.png"]', 'fgd-dfgdf-dfg-5-1.png', 0),
(10, 'P10', 'fgd dfgdf dfg', 'fgd-dfgdf-dfg-6', '', '', 49, 1, NULL, 2, 0, 1437550228, 1438961963, '["fgd-dfgdf-dfg-6-1.png","fgd-dfgdf-dfg-6-2.png"]', 'fgd-dfgdf-dfg-6-1.png', 0),
(11, 'P11', 'err werewr', 'err-werewr', '', '', 83, 1, NULL, 2, 0, 1437550631, 1438961816, '["err-werewr-1.png","err-werewr-2.png"]', 'err-werewr-1.png', 0),
(12, 'P12', 'err werewr', 'err-werewr-2', '', '', 79, 1, NULL, NULL, 0, 1437550726, 1437550729, '["err-werewr-2-1.png","err-werewr-2-2.png"]', 'err-werewr-2-1.png', 0),
(13, 'P13', 'err werewr', 'err-werewr-3', '', '', 46, 1, NULL, NULL, 0, 1437551003, 1437551011, '["err-werewr-3-1.png","err-werewr-3-2.png"]', 'err-werewr-3-1.png', 0),
(14, 'P14', 'err werewr', 'err-werewr-4', '', '', 74, 1, NULL, NULL, 0, 1437551048, 1437551050, '["err-werewr-4-1.png","err-werewr-4-2.png"]', 'err-werewr-4-1.png', 0),
(15, 'P15', 'err werewr', 'err-werewr-5', '', '', 40, 1, NULL, NULL, 0, 1437551076, 1437551084, '["err-werewr-5-1.png","err-werewr-5-2.png"]', 'err-werewr-5-1.png', 0),
(16, 'P16', 'err werewr', 'err-werewr-6', '', '', 60, 1, NULL, NULL, 0, 1437551145, 1437551150, '["err-werewr-6-1.png","err-werewr-6-2.png"]', 'err-werewr-6-1.png', 0),
(17, 'P17', 'err werewr', 'err-werewr-7', '', '', 80, 1, NULL, 2, 0, 1437551338, 1438962080, '["err-werewr-7-1.png","err-werewr-7-2.png"]', 'err-werewr-7-1.png', 0),
(19, 'P19', 'fgsdfsdfs', 'fgsdfsdfs', '', '', 29, 1, NULL, NULL, 0, 1437554866, 1437554866, 'a:1:{i:0;s:0:"";}', 'O:20:"yii\\web\\UploadedFile":5:{s:4:"name";s:54:"Снимок экрана от 2015-07-08 16:48:55.png";s:8:"tempName";s:14:"/tmp/phpuTuZ14";s:4:"type";s:9:"image/png";s:4:"size";i:464847;s:5:"error";i:0;}', 0),
(20, 'P20', 'sdfs 4trsdc sdfsdf', 'sdfs-4trsdc-sdfsdf', '', '', 78, 1, NULL, NULL, 0, 1437555505, 1437555505, 'a:2:{i:0;O:25:"im\\filesystem\\models\\File":7:{s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";s:4:"path";s:41:"/products/images/sdfs-4trsdc-sdfsdf-1.png";s:23:"\0yii\\base\\Model\0_errors";N;s:27:"\0yii\\base\\Model\0_validators";N;s:25:"\0yii\\base\\Model\0_scenario";s:7:"default";s:27:"\0yii\\base\\Component\0_events";a:0:{}s:30:"\0yii\\base\\Component\0_behaviors";N;}i:1;O:25:"im\\filesystem\\models\\File":7:{s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";s:4:"path";s:41:"/products/images/sdfs-4trsdc-sdfsdf-2.png";s:23:"\0yii\\base\\Model\0_errors";N;s:27:"\0yii\\base\\Model\0_validators";N;s:25:"\0yii\\base\\Model\0_scenario";s:7:"default";s:27:"\0yii\\base\\Component\0_events";a:0:{}s:30:"\0yii\\base\\Component\0_behaviors";N;}}', 'O:25:"im\\filesystem\\models\\File":7:{s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";s:4:"path";s:41:"/products/videos/sdfs-4trsdc-sdfsdf-1.png";s:23:"\0yii\\base\\Model\0_errors";N;s:27:"\0yii\\base\\Model\0_validators";N;s:25:"\0yii\\base\\Model\0_scenario', 0),
(21, 'P21', 'dsf sdf sdf sdfsd dfs ', 'dsf-sdf-sdf-sdfsd-dfs', '', '', 21, 1, NULL, NULL, 0, 1437555645, 1437555645, 'a:1:{i:0;O:25:"im\\filesystem\\models\\File":7:{s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";s:4:"path";s:44:"/products/images/dsf-sdf-sdf-sdfsd-dfs-1.png";s:23:"\0yii\\base\\Model\0_errors";N;s:27:"\0yii\\base\\Model\0_validators";N;s:25:"\0yii\\base\\Model\0_scenario";s:7:"default";s:27:"\0yii\\base\\Component\0_events";a:0:{}s:30:"\0yii\\base\\Component\0_behaviors";N;}}', 'O:25:"im\\filesystem\\models\\File":7:{s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";s:4:"path";s:44:"/products/videos/dsf-sdf-sdf-sdfsd-dfs-1.png";s:23:"\0yii\\base\\Model\0_errors";N;s:27:"\0yii\\base\\Model\0_validators";N;s:25:"\0yii\\base\\Model\0_scenario";s:7:"default";s:27:"\0yii\\base\\Component\0_events";a:0:{}s:30:"\0yii\\base\\Component\0_behaviors";N;}', 0),
(22, 'P22', 'sdfsd sdf sdf sdfsdf', 'sdfsd-sdf-sdf-sdfsdf', '', '', 42, 1, NULL, NULL, 0, 1437555866, 1437555866, 'a:1:{i:0;O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:43:"/products/images/sdfsd-sdf-sdf-sdfsdf-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}}', 'O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:43:"/products/videos/sdfsd-sdf-sdf-sdfsdf-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}', 0),
(23, 'P23', 'sdfsdf sdfsdf', 'sdfsdf-sdfsdf', '', '', 49, 1, NULL, NULL, 0, 1437558324, 1437558324, '', '', 0),
(24, 'P24', 'sad yutyu', 'sad-yutyu', '', '', 17, 1, NULL, NULL, 0, 1437558586, 1437558586, '', '', 0),
(25, 'P25', 'sd frewerewr', 'sd-frewerewr', '', '', 18, 1, NULL, NULL, 0, 1437558825, 1437558825, '', '', 3),
(26, 'P26', 'wewqe wqeqwqwe', 'wewqe-wqeqwqwe', '', '', 31, 1, NULL, NULL, 0, 1437573147, 1437573147, 'a:1:{i:0;O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:37:"/products/images/wewqe-wqeqwqwe-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}}', 'O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:37:"/products/videos/wewqe-wqeqwqwe-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}', 0),
(27, 'P27', 'sdfsdf sdfs fs sdf', 'sdfsdf-sdfs-fs-sdf', '', '', 93, 1, NULL, NULL, 0, 1437574899, 1437574899, 'a:1:{i:0;O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:52:"/products/images/{model.id}-sdfsdf-sdfs-fs-sdf-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}}', 'O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:52:"/products/videos/{model.id}-sdfsdf-sdfs-fs-sdf-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}', 0),
(28, 'P28', 'hgjf fg fhgfj', 'hgjf-fg-fhgfj', '', '', 89, 1, NULL, NULL, 0, 1437575301, 1437575301, 'a:1:{i:0;O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:39:"/products/images/28-hgjf-fg-fhgfj-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}}', 'O:25:"im\\filesystem\\models\\File":2:{s:4:"path";s:39:"/products/videos/28-hgjf-fg-fhgfj-1.png";s:38:"\0im\\filesystem\\models\\File\0_filesystem";s:5:"local";}', 0),
(29, 'dsffds', 'fsdfsdfsdfsdf', 'fsdfsdfsdfsdf', '', '', 70, 1, NULL, NULL, 0, 1441278747, 1441890484, '', '', 0),
(30, 'gh fghf fghf', 'gh fghfgh', 'gh-fghfgh', '', '', 72, 0, NULL, NULL, 0, 1441279743, 1441279744, '', '', 0);

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

--
-- Дамп данных таблицы `tbl_products_categories`
--

INSERT INTO `tbl_products_categories` (`product_id`, `category_id`) VALUES
(5, 17),
(5, 12),
(5, 11);

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
  `template_id` int(11) NOT NULL,
  `video_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft_rgt` (`lft`,`rgt`),
  KEY `depth` (`depth`),
  KEY `tree` (`tree`),
  KEY `name` (`name`),
  KEY `description` (`description`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

--
-- Дамп данных таблицы `tbl_product_categories`
--

INSERT INTO `tbl_product_categories` (`id`, `tree`, `lft`, `rgt`, `depth`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`, `image_id`, `template_id`, `video_data`) VALUES
(11, NULL, 1, 26, 0, 'Catalog', 'catalog', '', 1, 1442235217, 1444725373, 0, 5, ''),
(12, NULL, 2, 19, 1, '1', '1', '', 1, 1442235228, 1442235228, 0, 0, ''),
(13, NULL, 20, 25, 1, '2', '2', '', 1, 1442235236, 1442235236, 0, 0, ''),
(14, NULL, 21, 22, 2, '2.1', '21', '', 1, 1442235246, 1442235246, 0, 0, ''),
(15, NULL, 23, 24, 2, '2.2', '22', '', 1, 1442235253, 1442235253, 0, 0, ''),
(16, NULL, 5, 6, 2, '1.2', '12', '', 1, 1442235264, 1442501789, 25, 0, ''),
(17, NULL, 3, 4, 2, '1.1', '11', '', 1, 1442235273, 1442501755, 23, 0, ''),
(18, NULL, 7, 8, 2, '3', '3', '', 1, 1442399223, 1442502969, 29, 0, ''),
(19, NULL, 9, 10, 2, '4', '4', '', 1, 1442399231, 1442501801, 26, 0, ''),
(20, NULL, 12, 13, 3, '5', '5', '', 1, 1442399239, 1442501806, 0, 0, ''),
(21, NULL, 11, 14, 2, '6', '6', '', 1, 1442399250, 1442502983, 30, 0, ''),
(22, NULL, 15, 16, 2, '7', '7', '', 1, 1442399260, 1442501820, 27, 0, ''),
(23, NULL, 17, 18, 2, '8', '8', '', 1, 1442399271, 1442501830, 28, 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_product_category_files`
--

CREATE TABLE IF NOT EXISTS `tbl_product_category_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `attribute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `filesystem` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `filesystem` (`filesystem`),
  KEY `path` (`path`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`),
  KEY `sort` (`sort`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

--
-- Дамп данных таблицы `tbl_product_category_files`
--

INSERT INTO `tbl_product_category_files` (`id`, `category_id`, `attribute`, `filesystem`, `path`, `title`, `size`, `mime_type`, `created_at`, `updated_at`, `sort`) VALUES
(19, 53, '', 'local', '/categories/jljlkjkljk-1.png', '', 217723, 'image/png', 1441986202, 1441986202, 0),
(21, 0, '', 'local', '/categories/lklhjlhkjl.png', '', 207200, 'image/png', 1442215392, 1442215392, 0),
(22, 0, '', 'local', '/categories/my-test-category.png', '', 217723, 'image/png', 1442215792, 1442215792, 0),
(23, 0, '', 'local', '/categories/11.png', '', 153999, 'image/png', 1442501755, 1442501755, 0),
(24, 0, '', 'local', '/categories/12.png', '', 168333, 'image/png', 1442501777, 1442501777, 0),
(25, 0, '', 'local', '/categories/12-1.png', '', 167236, 'image/png', 1442501789, 1442501789, 0),
(26, 0, '', 'local', '/categories/4.png', '', 153794, 'image/png', 1442501801, 1442501801, 0),
(27, 0, '', 'local', '/categories/7.png', '', 100483, 'image/png', 1442501820, 1442501820, 0),
(28, 0, '', 'local', '/categories/8.png', '', 178168, 'image/png', 1442501830, 1442501830, 0),
(29, 0, '', 'local', '/categories/3.png', '', 143480, 'image/png', 1442502969, 1442502969, 0),
(30, 0, '', 'local', '/categories/6.png', '', 163905, 'image/png', 1442502983, 1442502983, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

--
-- Дамп данных таблицы `tbl_product_category_meta`
--

INSERT INTO `tbl_product_category_meta` (`id`, `entity_id`, `meta_title`, `meta_description`, `meta_robots`, `custom_meta`) VALUES
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
(23, 23, '', '', '', '');

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
  `sort` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `filesystem` (`filesystem`),
  KEY `path` (`path`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`),
  KEY `sort` (`sort`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `tbl_product_files`
--

INSERT INTO `tbl_product_files` (`id`, `product_id`, `attribute`, `filesystem`, `path`, `title`, `size`, `mime_type`, `created_at`, `updated_at`, `sort`, `type`) VALUES
(1, 29, 'images', 'local', '/products/29/fsdfsdfsdfsdf.png', '', 153999, 'image/png', 1441882670, 1441890484, 0, 1),
(2, 29, 'images', 'local', '/products/29/fsdfsdfsdfsdf-1.png', '', 168333, 'image/png', 1441882670, 1441890484, 0, 1),
(3, 29, 'images', 'local', '/products/29/fsdfsdfsdfsdf-2.png', '', 167236, 'image/png', 1441882670, 1441890485, 0, 1),
(4, 29, 'images', 'local', '/products/29/fsdfsdfsdfsdf-3.png', '', 120459, 'image/png', 1441882670, 1441890485, 0, 1),
(5, 29, 'images', 'local', '/products/29/fsdfsdfsdfsdf-4.png', '', 217723, 'image/png', 1441889026, 1441890485, 0, 1),
(6, 29, 'images', 'local', '/products/29/fsdfsdfsdfsdf-5.png', '', 390088, 'image/png', 1441890260, 1441890485, 0, 1),
(7, 29, 'images', 'local', '/products/29/fsdfsdfsdfsdf.jpg', '', 522783, 'image/jpeg', 1441890260, 1441890485, 0, 1),
(8, 29, 'images', 'local', '/products/29/fsdfsdfsdfsdf-1.jpg', '', 538919, 'image/jpeg', 1441890260, 1441890485, 0, 1),
(9, 29, 'images', 'local', '/products/29/fsdfsdfsdfsdf-6.png', '', 28845, 'image/png', 1441890485, 1441890485, 0, 1),
(10, 29, 'images', 'local', '/products/29/fsdfsdfsdfsdf-7.png', '', 10564, 'image/png', 1441890485, 1441890485, 0, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;

--
-- Дамп данных таблицы `tbl_product_meta`
--

INSERT INTO `tbl_product_meta` (`id`, `entity_id`, `meta_title`, `meta_description`, `meta_robots`, `custom_meta`) VALUES
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
(19, 19, '', '', '', ''),
(20, 20, '', '', '', ''),
(21, 21, '', '', '', ''),
(22, 22, '', '', '', ''),
(23, 23, '', '', '', ''),
(24, 24, '', '', '', ''),
(25, 25, '', '', '', ''),
(26, 27, '', '', '', ''),
(27, 28, '', '', '', ''),
(28, 29, '', '', '', ''),
(29, 30, '', '', '', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=117 ;

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
(54, 27, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(55, 28, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(56, 28, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(57, 29, 'product_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(58, 29, 'product_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(59, NULL, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(60, NULL, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(61, NULL, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(62, NULL, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(63, NULL, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(64, NULL, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(65, NULL, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(66, NULL, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(67, NULL, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(68, NULL, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(69, 1, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(70, 1, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(71, 2, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(72, 2, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(73, 3, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(74, 3, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(75, 4, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(76, 4, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(77, 5, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(78, 5, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(79, 6, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(80, 6, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(81, 7, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(82, 7, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(83, 8, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(84, 8, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(85, 9, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(86, 9, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(87, 10, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(88, 10, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(89, 11, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(90, 11, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(91, 12, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(92, 12, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(93, 13, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(94, 13, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(95, 14, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(96, 14, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(97, 15, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(98, 15, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(99, 16, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(100, 16, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(101, 17, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(102, 17, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(103, 18, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(104, 18, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(105, 19, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(106, 19, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(107, 20, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(108, 20, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(109, 21, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(110, 21, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(111, 22, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(112, 22, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(113, 23, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(114, 23, 'product_category_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(115, 1, 'page_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(116, 1, 'page_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `tbl_templates`
--

INSERT INTO `tbl_templates` (`id`, `name`, `layout_id`) VALUES
(4, 'Page template', ''),
(5, 'Category page template', '');

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
  `depth` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `tbl_widgets`
--

INSERT INTO `tbl_widgets` (`id`, `widget_type`, `content`, `banner_id`, `depth`) VALUES
(8, 'content', '123', 0, 0),
(9, 'banner', '', 0, 0),
(10, 'categories', '', 0, 0),
(11, 'banner', '', 0, 0),
(12, 'facets', '', 0, 0),
(13, 'content', 'gffdg dgdfgfdg', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_widget_areas`
--

CREATE TABLE IF NOT EXISTS `tbl_widget_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `template_id` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `tbl_widget_areas`
--

INSERT INTO `tbl_widget_areas` (`id`, `code`, `template_id`, `owner_id`, `owner_type`, `display`, `created_at`, `updated_at`) VALUES
(6, 'sidebar', 4, NULL, '', 3, 1442475541, 1444724127),
(7, 'footer', 4, NULL, '', 3, 1442475541, 1444724127),
(8, 'sidebar', 5, NULL, '', 3, 1442475562, 1443438013),
(9, 'footer', 5, NULL, '', 3, 1442475562, 1443438013);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=64 ;

--
-- Дамп данных таблицы `tbl_widget_area_widgets`
--

INSERT INTO `tbl_widget_area_widgets` (`id`, `widget_id`, `widget_area_id`, `owner_id`, `owner_type`, `sort`) VALUES
(57, 10, 8, NULL, '', 1),
(58, 12, 8, NULL, '', 2),
(59, 11, 9, NULL, '', 1),
(62, 13, 6, NULL, '', 1),
(63, 11, 7, NULL, '', 1);

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
-- Ограничения внешнего ключа таблицы `tbl_facet_set_facets`
--
ALTER TABLE `tbl_facet_set_facets`
  ADD CONSTRAINT `FK_facet_set_facets_facet_id` FOREIGN KEY (`facet_id`) REFERENCES `tbl_facets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_facet_set_facets_set_id` FOREIGN KEY (`set_id`) REFERENCES `tbl_facet_sets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_facet_terms`
--
ALTER TABLE `tbl_facet_terms`
  ADD CONSTRAINT `FK_facet_terms_facet_id` FOREIGN KEY (`facet_id`) REFERENCES `tbl_facets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `FK_products_categories_category_id` FOREIGN KEY (`category_id`) REFERENCES `tbl_product_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
