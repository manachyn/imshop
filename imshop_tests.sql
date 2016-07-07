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
-- Database: `imshop_tests`
--

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
-- Table structure for table `tbl_category_files`
--

CREATE TABLE IF NOT EXISTS `tbl_category_files` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
  KEY `FK_category_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_config`
--

CREATE TABLE IF NOT EXISTS `tbl_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `component` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `context` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `component` (`component`),
  KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eav_attributes`
--

CREATE TABLE IF NOT EXISTS `tbl_eav_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `presentation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `field_config_data` text COLLATE utf8_unicode_ci NOT NULL,
  `rules_config_data` text COLLATE utf8_unicode_ci NOT NULL,
  `predefined_values` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `entity_type` (`entity_type`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eav_entity_values`
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
-- Table structure for table `tbl_eav_product_values`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eav_values`
--

CREATE TABLE IF NOT EXISTS `tbl_eav_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `presentation` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_entity_files`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facets`
--

CREATE TABLE IF NOT EXISTS `tbl_facets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_id` int(11) DEFAULT NULL,
  `attribute_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `index_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `from` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `interval` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `multivalue` tinyint(1) NOT NULL DEFAULT '1',
  `operator` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `route_param` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_facets_attribute_id` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facet_ranges`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facet_sets`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facet_set_facets`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_set_facets` (
  `set_id` int(11) NOT NULL,
  `facet_id` int(11) NOT NULL,
  KEY `FK_facet_set_facets_set_id` (`set_id`),
  KEY `FK_facet_set_facets_facet_id` (`facet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facet_terms`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facet_id` int(11) NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_facet_terms_facet_id` (`facet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facet_values`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `facet_id` int(11) NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lower_bound` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `upper_bound` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `include_lower_bound` tinyint(1) DEFAULT '1',
  `include_upper_bound` tinyint(1) DEFAULT '0',
  `display` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_facet_values_facet_id` (`facet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_files`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_forms`
--

CREATE TABLE IF NOT EXISTS `tbl_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_form_fields`
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
-- Table structure for table `tbl_indexes`
--

CREATE TABLE IF NOT EXISTS `tbl_indexes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `service` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_index_attributes`
--

CREATE TABLE IF NOT EXISTS `tbl_index_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `index_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `index_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `full_text_search` tinyint(1) NOT NULL DEFAULT '0',
  `boost` int(5) NOT NULL,
  `suggestions` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menus`
--

CREATE TABLE IF NOT EXISTS `tbl_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menus_items`
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
-- Table structure for table `tbl_menu_items`
--

CREATE TABLE IF NOT EXISTS `tbl_menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `target_blank` tinyint(1) NOT NULL DEFAULT '0',
  `css_classes` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `rel` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `visibility` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `items_display` tinyint(1) NOT NULL,
  `items_css_classes` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `icon_id` int(11) NOT NULL,
  `active_icon_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft_rgt` (`lft`,`rgt`),
  KEY `depth` (`depth`),
  KEY `tree` (`tree`),
  KEY `label` (`label`),
  KEY `menu_id_status` (`menu_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu_item_files`
--

CREATE TABLE IF NOT EXISTS `tbl_menu_item_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_item_id` int(11) NOT NULL,
  `attribute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `filesystem` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `filesystem` (`filesystem`),
  KEY `path` (`path`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`),
  KEY `menu_item_id` (`menu_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `status` tinyint(1) DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `facet_set_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`),
  KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `facet_set_id` (`facet_set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page_meta`
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
  `images_data` text COLLATE utf8_unicode_ci NOT NULL,
  `video_data` text COLLATE utf8_unicode_ci NOT NULL,
  `dvideo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_products_brand_id` (`brand_id`),
  KEY `FK_products_type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products_categories`
--

CREATE TABLE IF NOT EXISTS `tbl_products_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  KEY `FK_products_categories_product_id` (`product_id`),
  KEY `FK_products_categories_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_categories`
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
  `template_id` int(11) DEFAULT NULL,
  `facet_set_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lft_rgt` (`lft`,`rgt`),
  KEY `depth` (`depth`),
  KEY `tree` (`tree`),
  KEY `name` (`name`),
  KEY `description` (`description`),
  KEY `facet_set_id` (`facet_set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_category_files`
--

CREATE TABLE IF NOT EXISTS `tbl_product_category_files` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
  KEY `FK_product_category_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_files`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
-- Table structure for table `tbl_seo_meta`
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
-- Table structure for table `tbl_templates`
--

CREATE TABLE IF NOT EXISTS `tbl_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `layout_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `layout_id` (`layout_id`),
  KEY `default` (`default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
  `depth` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_widget_areas`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_widget_area_widgets`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_category_meta`
--
ALTER TABLE `tbl_category_meta`
  ADD CONSTRAINT `FK_category_meta_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_eav_entity_values`
--
ALTER TABLE `tbl_eav_entity_values`
  ADD CONSTRAINT `FK_attribute_values_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_eav_product_values`
--
ALTER TABLE `tbl_eav_product_values`
  ADD CONSTRAINT `FK_product_attribute_values_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_product_attribute_values_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_entity_files`
--
ALTER TABLE `tbl_entity_files`
  ADD CONSTRAINT `FK_entity_files_file_id` FOREIGN KEY (`file_id`) REFERENCES `tbl_files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_facets`
--
ALTER TABLE `tbl_facets`
  ADD CONSTRAINT `FK_facets_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_facet_ranges`
--
ALTER TABLE `tbl_facet_ranges`
  ADD CONSTRAINT `FK_facet_ranges_facet_id` FOREIGN KEY (`facet_id`) REFERENCES `tbl_facets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_facet_set_facets`
--
ALTER TABLE `tbl_facet_set_facets`
  ADD CONSTRAINT `FK_facet_set_facets_facet_id` FOREIGN KEY (`facet_id`) REFERENCES `tbl_facets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_facet_set_facets_set_id` FOREIGN KEY (`set_id`) REFERENCES `tbl_facet_sets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_facet_terms`
--
ALTER TABLE `tbl_facet_terms`
  ADD CONSTRAINT `FK_facet_terms_facet_id` FOREIGN KEY (`facet_id`) REFERENCES `tbl_facets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_facet_values`
--
ALTER TABLE `tbl_facet_values`
  ADD CONSTRAINT `FK_facet_values_facet_id` FOREIGN KEY (`facet_id`) REFERENCES `tbl_facets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_form_fields`
--
ALTER TABLE `tbl_form_fields`
  ADD CONSTRAINT `FK_form_fields_form_id` FOREIGN KEY (`form_id`) REFERENCES `tbl_forms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_menus_items`
--
ALTER TABLE `tbl_menus_items`
  ADD CONSTRAINT `FK_menu_item_menu` FOREIGN KEY (`menu_id`) REFERENCES `tbl_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_menu_item_page` FOREIGN KEY (`page_id`) REFERENCES `tbl_pages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_option_values`
--
ALTER TABLE `tbl_option_values`
  ADD CONSTRAINT `FK_option_values_option_id` FOREIGN KEY (`option_id`) REFERENCES `tbl_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_page_meta`
--
ALTER TABLE `tbl_page_meta`
  ADD CONSTRAINT `FK_page_meta_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `FK_products_categories_category_id` FOREIGN KEY (`category_id`) REFERENCES `tbl_product_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_products_categories_product_id` FOREIGN KEY (`product_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_category_meta`
--
ALTER TABLE `tbl_product_category_meta`
  ADD CONSTRAINT `FK_product_category_meta_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_product_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `FK_product_type_attributes_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
-- Constraints for table `tbl_variant_option_values`
--
ALTER TABLE `tbl_variant_option_values`
  ADD CONSTRAINT `FK_variant_option_values_option_value_id` FOREIGN KEY (`option_value_id`) REFERENCES `tbl_option_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_variant_option_values_variant_id` FOREIGN KEY (`variant_id`) REFERENCES `tbl_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_widget_areas`
--
ALTER TABLE `tbl_widget_areas`
  ADD CONSTRAINT `FK_widget_areas_template_id` FOREIGN KEY (`template_id`) REFERENCES `tbl_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_widget_area_widgets`
--
ALTER TABLE `tbl_widget_area_widgets`
  ADD CONSTRAINT `FK_widget_area_widgets_widget_area_id` FOREIGN KEY (`widget_area_id`) REFERENCES `tbl_widget_areas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_widget_area_widgets_widget_id` FOREIGN KEY (`widget_id`) REFERENCES `tbl_widgets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
