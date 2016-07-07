-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 07, 2016 at 12:26 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.6.23-1+deprecated+dontuse+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `imshop_old`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attributes`
--

CREATE TABLE IF NOT EXISTS `tbl_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `presentation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `field_config_data` text COLLATE utf8_unicode_ci NOT NULL,
  `rules_config_data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_attributes`
--

INSERT INTO `tbl_attributes` (`id`, `name`, `presentation`, `type`, `field_config_data`, `rules_config_data`) VALUES
(1, 'product_type', 'Product type', 'app\\modules\\catalog\\models\\ProductType', 'a:1:{s:9:"fieldType";s:12:"dropDownList";}', ''),
(2, 'fabric', 'Fabric', 'string', 'a:1:{s:9:"fieldType";s:9:"textInput";}', 'a:2:{i:0;s:8:"required";i:1;s:6:"string";}'),
(3, 'size', 'Size', 'string', 'a:1:{s:9:"fieldType";s:9:"textInput";}', 'a:1:{i:0;s:8:"required";}');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attribute_values`
--

CREATE TABLE IF NOT EXISTS `tbl_attribute_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `string_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `integer_value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entity_id` (`entity_id`),
  KEY `entity_type` (`entity_type`),
  KEY `FK_attribute_values_attribute_id` (`attribute_id`),
  KEY `attribute_name` (`attribute_name`),
  KEY `string_value` (`string_value`),
  KEY `integer_value` (`integer_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banner_widgets`
--

CREATE TABLE IF NOT EXISTS `tbl_banner_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `tbl_banner_widgets`
--

INSERT INTO `tbl_banner_widgets` (`id`, `banner_id`) VALUES
(1, 0),
(2, 0),
(3, 0),
(4, 0),
(5, 0),
(6, 0),
(7, 0),
(8, 0),
(9, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brands`
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
-- Table structure for table `tbl_categories`
--

CREATE TABLE IF NOT EXISTS `tbl_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` smallint(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tree` (`tree`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `depth` (`depth`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_categories`
--

INSERT INTO `tbl_categories` (`id`, `tree`, `lft`, `rgt`, `depth`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 0, 'Root1', '', 0, 0),
(2, 2, 1, 4, 0, 'Root2', '', 0, 0),
(3, 1, 2, 3, 1, '111', '', 0, 0),
(4, 2, 2, 3, 1, '32213', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories_old`
--

CREATE TABLE IF NOT EXISTS `tbl_categories_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `root` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `lvl` smallint(5) NOT NULL,
  `name` varchar(60) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `icon_type` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `selected` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `readonly` tinyint(1) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `collapsed` tinyint(1) NOT NULL DEFAULT '0',
  `movable_u` tinyint(1) NOT NULL DEFAULT '1',
  `movable_d` tinyint(1) NOT NULL DEFAULT '1',
  `movable_l` tinyint(1) NOT NULL DEFAULT '1',
  `movable_r` tinyint(1) NOT NULL DEFAULT '1',
  `removable` tinyint(1) NOT NULL DEFAULT '1',
  `removable_all` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tbl_product_NK1` (`root`),
  KEY `tbl_product_NK2` (`lft`),
  KEY `tbl_product_NK3` (`rgt`),
  KEY `tbl_product_NK4` (`lvl`),
  KEY `tbl_product_NK5` (`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_categories_old`
--

INSERT INTO `tbl_categories_old` (`id`, `root`, `lft`, `rgt`, `lvl`, `name`, `icon`, `icon_type`, `active`, `selected`, `disabled`, `readonly`, `visible`, `collapsed`, `movable_u`, `movable_d`, `movable_l`, `movable_r`, `removable`, `removable_all`) VALUES
(1, 1, 1, 8, 0, 'Cat1', '', 1, 1, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1, 0),
(2, 2, 1, 2, 0, 'Cat22', '', 1, 1, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1, 0),
(3, 3, 1, 2, 0, 'Cat3', '', 1, 1, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1, 0),
(4, 1, 2, 7, 1, 'Cat11', '', 1, 1, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1, 0),
(5, 1, 5, 6, 2, 'Cat111', '', 1, 1, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1, 0),
(6, 1, 3, 4, 2, 'dshsdhsd', '', 1, 1, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_category_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_content_widgets`
--

CREATE TABLE IF NOT EXISTS `tbl_content_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_failed_jobs`
--

CREATE TABLE IF NOT EXISTS `tbl_failed_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `queue_name` text COLLATE utf8_unicode_ci NOT NULL,
  `descriptor` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_failed_jobs`
--

INSERT INTO `tbl_failed_jobs` (`id`, `queue`, `queue_name`, `descriptor`, `failed_at`) VALUES
(1, 'database', 'default', '{"job":"app\\\\modules\\\\queue\\\\components\\\\QueuedHandler@handle","data":{"command":"O:25:\\"app\\\\controllers\\\\SendEmail\\":3:{s:5:\\"queue\\";N;s:5:\\"delay\\";N;s:6:\\"\\u0000*\\u0000job\\";N;}"}}', 1434447588);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menus`
--

CREATE TABLE IF NOT EXISTS `tbl_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menus_items`
--

CREATE TABLE IF NOT EXISTS `tbl_menus_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) DEFAULT NULL,
  `label` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `FK_menu_item_page` (`page_id`),
  KEY `FK_menu_item_menu` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_migration`
--

INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1423399299),
('m150208_105944_create_eav_tables', 1430750693),
('m150208_114635_create_variation_tables', 1430750724),
('m150208_123637_create_catalog_tables', 1430750736),
('m150415_113756_create_seo_tables', 1430750743),
('m150616_154032_create_tasks_tables', 1434469594);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_open_graph`
--

CREATE TABLE IF NOT EXISTS `tbl_open_graph` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `site_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_open_graph_page_meta` (`meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_open_graph`
--

INSERT INTO `tbl_open_graph` (`id`, `meta_id`, `title`, `type`, `url`, `image`, `description`, `site_name`, `video`) VALUES
(2, 2, 'Title', 'Type', 'Url', 'Image', 'Description', 'Site Name', 'Video'),
(3, 3, '', '', '', '', '', '', ''),
(4, NULL, '', '', '', '', '', '', ''),
(5, 8, '', '', '', '', '', '', ''),
(6, 9, '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_options`
--

CREATE TABLE IF NOT EXISTS `tbl_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `presentation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_option_values`
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
-- Table structure for table `tbl_pages`
--

CREATE TABLE IF NOT EXISTS `tbl_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `layout_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_pages`
--

INSERT INTO `tbl_pages` (`id`, `type`, `title`, `slug`, `content`, `created_at`, `updated_at`, `status`, `layout_id`) VALUES
(2, 'page', 'Test', 'test', '<p>Test</p>', 1421138660, 1421603135, 1, ''),
(3, 'page', 'fdfgsdfdsf', 'fdfgsdfdsf', '', 1422100340, 1422100340, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pages_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_pages_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_page_meta_page` (`page_id`),
  KEY `entity_id` (`entity_id`),
  KEY `entity_type` (`entity_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `tbl_pages_meta`
--

INSERT INTO `tbl_pages_meta` (`id`, `page_id`, `entity_id`, `entity_type`, `meta_title`, `meta_description`, `meta_robots`, `custom_meta`) VALUES
(2, 2, 0, '', 'Meta title', 'Meta description', 'noindex,nofollow', '<meta name="author" content="Hege Refsnes">\r\n<meta name="keywords" content="meta tags,search engine optimization">'),
(3, 3, 0, '', '', '', '', ''),
(4, NULL, 0, '', 'sdf', 'sdf', '', 'sdf'),
(5, NULL, 0, '', 'sdfsd', 'fsdf', '', 'sdfsdf'),
(6, NULL, 0, '', 'sdf', 'fsdffsdf', '', 'sdfsdf'),
(7, NULL, 0, '', '', '', '', ''),
(8, NULL, 5, '', '', '', '', ''),
(9, NULL, 6, 'product', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
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
  PRIMARY KEY (`id`),
  KEY `FK_products_brand_id` (`brand_id`),
  KEY `FK_products_type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `sku`, `title`, `slug`, `description`, `quantity`, `price`, `status`, `brand_id`, `type_id`, `available_on`, `created_at`, `updated_at`) VALUES
(1, '1', 'Test', 'test', '', '', 0, 0, NULL, NULL, 0, 1430821808, 1431180874),
(2, '2', 'Test', 'test-2', '', '', 0, 0, NULL, NULL, 0, 1432047024, 1432047105);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products_categories`
--

CREATE TABLE IF NOT EXISTS `tbl_products_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  KEY `FK_product_categories_product_id` (`product_id`),
  KEY `FK_product_categories_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_attribute_values`
--

CREATE TABLE IF NOT EXISTS `tbl_product_attribute_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `string_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `integer_value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_attribute_values_entity_id` (`entity_id`),
  KEY `FK_product_attribute_values_attribute_id` (`attribute_id`),
  KEY `attribute_name` (`attribute_name`),
  KEY `string_value` (`string_value`),
  KEY `integer_value` (`integer_value`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_product_attribute_values`
--

INSERT INTO `tbl_product_attribute_values` (`id`, `entity_id`, `attribute_id`, `attribute_name`, `attribute_type`, `string_value`, `integer_value`) VALUES
(1, 1, 2, 'fabric', 'string', 'Cotton2', NULL),
(2, 1, 1, 'product_type', 'app\\modules\\catalog\\models\\ProductType', '', 2),
(3, 1, 3, 'size', 'string', 'XL', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_categories`
--

CREATE TABLE IF NOT EXISTS `tbl_product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` smallint(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tree` (`tree`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `depth` (`depth`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_product_categories`
--

INSERT INTO `tbl_product_categories` (`id`, `tree`, `lft`, `rgt`, `depth`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 10, 0, 'Catalog', '', 0, 0),
(2, NULL, 2, 3, 1, 'Category1', '', 0, 0),
(3, NULL, 4, 5, 1, 'Category2222', '', 0, 0),
(4, NULL, 6, 9, 1, 'Category3', '', 0, 0),
(5, NULL, 7, 8, 2, 'Sub category', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_category_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_product_category_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_meta`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_product_meta`
--

INSERT INTO `tbl_product_meta` (`id`, `entity_id`, `meta_title`, `meta_description`, `meta_robots`, `custom_meta`) VALUES
(1, 1, '', '', '', ''),
(2, 2, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_option_values`
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
-- Table structure for table `tbl_product_types`
--

CREATE TABLE IF NOT EXISTS `tbl_product_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_types_parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_product_types`
--

INSERT INTO `tbl_product_types` (`id`, `name`, `parent_id`) VALUES
(1, 'Сlothes', NULL),
(2, 'T-shirts', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_type_attributes`
--

CREATE TABLE IF NOT EXISTS `tbl_product_type_attributes` (
  `product_type_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  KEY `FK_product_type_attributes_product_type_id` (`product_type_id`),
  KEY `FK_product_type_attributes_attribute_id` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_type_options`
--

CREATE TABLE IF NOT EXISTS `tbl_product_type_options` (
  `product_type_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  KEY `FK_product_type_options_product_type` (`product_type_id`),
  KEY `FK_product_type_options_option` (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_variants`
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
-- Table structure for table `tbl_product_variant_option_values`
--

CREATE TABLE IF NOT EXISTS `tbl_product_variant_option_values` (
  `product_variant_id` int(11) NOT NULL,
  `option_value_id` int(11) NOT NULL,
  KEY `FK_product_variant_option_values_product_variant_id` (`product_variant_id`),
  KEY `FK_product_variant_option_values_option_value_id` (`option_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_profiles`
--

CREATE TABLE IF NOT EXISTS `tbl_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `avatar_url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_profile_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_profiles`
--

INSERT INTO `tbl_profiles` (`id`, `user_id`, `first_name`, `last_name`, `avatar_url`) VALUES
(1, 1, 'Administration', 'Site', ''),
(2, NULL, 'Ivan', 'Manachyn', ''),
(3, NULL, 'Ivan', 'Manachyn', ''),
(5, 5, 'Ivan', 'Manachyn', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_queue`
--

CREATE TABLE IF NOT EXISTS `tbl_queue` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `descriptor` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_queue`
--

INSERT INTO `tbl_queue` (`id`, `queue`, `descriptor`, `attempts`, `reserved`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{"job":"app\\\\modules\\\\tasks\\\\components\\\\PerformTaskJob@perform","data":{"task":"::entity::|app\\\\modules\\\\tasks\\\\models\\\\SendEmailTask|1"}}', 0, 0, NULL, 1434615913, 1434615913);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_seo_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_seo_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social_meta`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tasks`
--

CREATE TABLE IF NOT EXISTS `tbl_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_tasks`
--

INSERT INTO `tbl_tasks` (`id`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'send_email', 0, 1434615913, 1434615913);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_templates`
--

CREATE TABLE IF NOT EXISTS `tbl_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `layout_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_templates`
--

INSERT INTO `tbl_templates` (`id`, `name`, `layout_id`) VALUES
(1, '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', '0QH7lSsH_nxPe2xLaln_IV37F-TaSidH', '$2y$13$w1qNnZd9oTNxjNHczgsYmeAhQo9qKx4kTkVHTBFs2adoNFvL7Q5He', 'g87arxoIZO-C_xmXFe6PD05oxxjVtZgz_1415376132', 'admin@demo.com', 'superadmin', 1, 1415376132, 1415376132),
(2, 'ivan', 'kkqyZj4hCIERK7uI0beilRsBoBVIIvoF', '$2y$13$iCuLdLtteIwmF8bR0amTxO6tbMo/HRfojr43Thvr5m1lqbdM.KaaW', 'zmTR8lhMUpbnmIPLchmv94-KAeyxIAkZ_1415377351', 'ivan.manachin@boldendeavours.com', 'user', 1, 1415377350, 1415377350),
(3, 'ivan2', '7SIE5MY04dCSwHGB206AChn_mIRTSUJ6', '$2y$13$ZbqUz7/BDGgO6vNhLE0TvOAUN.LPidWnhpQ7fiZ3ZDbYOcOVlI2Ra', 'eisYcjskdJlURo8rO31PreX0wQA73m-n_1415377469', 'ivan2.manachin@boldendeavours.com', 'user', 1, 1415377469, 1415377469),
(4, 'ivan3', 'ZjW5A8wFqe69toCARTbqku-ySjC6HYCR', '$2y$13$QI4ov8HHsdvH2hqy1.fm5urXf4yZY2CZzxm/iZ5FH4cYOZVBQurxu', 'uIgbWndadrdgoXWQIy9YW5fAH20b7IxQ_1415378462', 'ivan3.manachin@boldendeavours.com', 'user', 1, 1415378461, 1415378461),
(5, 'ivan4', 'iV_j58qd6xP8vOToFwsHmjcxUsuVXN36', '$2y$13$zvlOg8oQDBeAxGtKTey5e.hNjsSQqfVOA9uzdIf08VNe8TP/uX7aa', 'POCosKdu0houJrUcDpJQ_E0jmPy7fwdT_1415378625', 'ivan4.manachin@boldendeavours.com', 'user', 1, 1415378625, 1415378625);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_variants`
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
-- Table structure for table `tbl_variant_option_values`
--

CREATE TABLE IF NOT EXISTS `tbl_variant_option_values` (
  `variant_id` int(11) NOT NULL,
  `option_value_id` int(11) NOT NULL,
  KEY `FK_variant_option_values_variant_id` (`variant_id`),
  KEY `FK_variant_option_values_option_value_id` (`option_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_widgets`
--

CREATE TABLE IF NOT EXISTS `tbl_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `banner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_widgets`
--

INSERT INTO `tbl_widgets` (`id`, `widget_type`, `content`, `banner_id`) VALUES
(1, 'content', '1', 0),
(2, 'banner', '', 0),
(3, 'banner', '', 0),
(4, 'content', '2', 0),
(5, 'content', 'gffdgdfg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_widget_areas`
--

CREATE TABLE IF NOT EXISTS `tbl_widget_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `template_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `layout_id` (`template_id`),
  KEY `owner_id` (`owner_id`),
  KEY `owner_type` (`owner_type`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_widget_areas`
--

INSERT INTO `tbl_widget_areas` (`id`, `code`, `template_id`, `owner_id`, `owner_type`, `display`, `created_at`, `updated_at`) VALUES
(1, 'sidebar', 1, 0, '', 3, 1434119189, 1434120100),
(2, 'footer', 1, 0, '', 3, 1434119190, 1434120101);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_widget_area_widgets`
--

CREATE TABLE IF NOT EXISTS `tbl_widget_area_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_id` int(11) NOT NULL,
  `widget_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `widget_area_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `widget_id` (`widget_id`),
  KEY `widget_type` (`widget_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `tbl_widget_area_widgets`
--

INSERT INTO `tbl_widget_area_widgets` (`id`, `widget_id`, `widget_type`, `owner_id`, `owner_type`, `widget_area_id`, `sort`) VALUES
(15, 5, '', 0, '', 1, 1),
(16, 1, '', 0, '', 1, 2),
(17, 2, '', 0, '', 1, 3),
(18, 3, '', 0, '', 2, 1),
(19, 4, '', 0, '', 2, 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_attribute_values`
--
ALTER TABLE `tbl_attribute_values`
  ADD CONSTRAINT `FK_attribute_values_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_menus_items`
--
ALTER TABLE `tbl_menus_items`
  ADD CONSTRAINT `FK_menu_item_menu` FOREIGN KEY (`menu_id`) REFERENCES `tbl_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_menu_item_page` FOREIGN KEY (`page_id`) REFERENCES `tbl_pages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_open_graph`
--
ALTER TABLE `tbl_open_graph`
  ADD CONSTRAINT `FK_open_graph_page_meta` FOREIGN KEY (`meta_id`) REFERENCES `tbl_pages_meta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_option_values`
--
ALTER TABLE `tbl_option_values`
  ADD CONSTRAINT `FK_option_values_option_id` FOREIGN KEY (`option_id`) REFERENCES `tbl_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_pages_meta`
--
ALTER TABLE `tbl_pages_meta`
  ADD CONSTRAINT `FK_page_meta_page` FOREIGN KEY (`page_id`) REFERENCES `tbl_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD CONSTRAINT `FK_products_brand_id` FOREIGN KEY (`brand_id`) REFERENCES `tbl_brands` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_products_type_id` FOREIGN KEY (`type_id`) REFERENCES `tbl_product_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_products_categories`
--
ALTER TABLE `tbl_products_categories`
  ADD CONSTRAINT `FK_product_categories_category_id` FOREIGN KEY (`category_id`) REFERENCES `tbl_categories_old` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_categories_product_id` FOREIGN KEY (`product_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_attribute_values`
--
ALTER TABLE `tbl_product_attribute_values`
  ADD CONSTRAINT `FK_product_attribute_values_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_attribute_values_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_meta`
--
ALTER TABLE `tbl_product_meta`
  ADD CONSTRAINT `FK_product_meta_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_option_values`
--
ALTER TABLE `tbl_product_option_values`
  ADD CONSTRAINT `FK_product_option_values_option_id` FOREIGN KEY (`option_id`) REFERENCES `tbl_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_types`
--
ALTER TABLE `tbl_product_types`
  ADD CONSTRAINT `FK_product_types_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `tbl_product_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_type_attributes`
--
ALTER TABLE `tbl_product_type_attributes`
  ADD CONSTRAINT `FK_product_type_attributes_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_type_attributes_product_type_id` FOREIGN KEY (`product_type_id`) REFERENCES `tbl_product_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_type_options`
--
ALTER TABLE `tbl_product_type_options`
  ADD CONSTRAINT `FK_product_type_options_option` FOREIGN KEY (`option_id`) REFERENCES `tbl_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_type_options_product_type` FOREIGN KEY (`product_type_id`) REFERENCES `tbl_product_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_variants`
--
ALTER TABLE `tbl_product_variants`
  ADD CONSTRAINT `FK_product_variants_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_variant_option_values`
--
ALTER TABLE `tbl_product_variant_option_values`
  ADD CONSTRAINT `FK_product_variant_option_values_option_value_id` FOREIGN KEY (`option_value_id`) REFERENCES `tbl_product_option_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_variant_option_values_product_variant_id` FOREIGN KEY (`product_variant_id`) REFERENCES `tbl_product_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_profiles`
--
ALTER TABLE `tbl_profiles`
  ADD CONSTRAINT `FK_profile_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_variant_option_values`
--
ALTER TABLE `tbl_variant_option_values`
  ADD CONSTRAINT `FK_variant_option_values_option_value_id` FOREIGN KEY (`option_value_id`) REFERENCES `tbl_option_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_variant_option_values_variant_id` FOREIGN KEY (`variant_id`) REFERENCES `tbl_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
