-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 07, 2016 at 12:28 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.6.23-1+deprecated+dontuse+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `polikarbonatvs`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_articles`
--

CREATE TABLE IF NOT EXISTS `tbl_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `image_id` int(11) DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`),
  KEY `FK_articles_image_id` (`image_id`),
  KEY `FK_articles_video_id` (`video_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_articles`
--

INSERT INTO `tbl_articles` (`id`, `title`, `slug`, `content`, `status`, `image_id`, `video_id`, `created_at`, `updated_at`) VALUES
(1, 'Развитие поликарбонатного рынка в Украине', 'article4.htm', '<p>Материалы из&nbsp;поликарбоната, в&nbsp;2009&nbsp;г. по&nbsp;мнению специалистов, во&nbsp;многих отраслях и&nbsp;в&nbsp;том числе в&nbsp;строительстве будут пользоваться повышенным спросом.<br />\r\nГоворя о&nbsp;мировых производителях поликарбоната, специалисты отмечают, что в&nbsp;их&nbsp;среде наблюдается процесс консолидации, что обусловлено, помимо прочего, стремлением расширить сферу применения указанных материалов.<br />\r\nЧто касается материалов из&nbsp;поликарбоната отечественного производства, то&nbsp;в&nbsp;ближайшее время их&nbsp;ожидать не&nbsp;следует. Причина понятная&nbsp;&mdash; недостаток финансовых ресурсов.<br />\r\nПо&nbsp;оценке специалистов, в&nbsp;настоящее время затраты на&nbsp;разработку и&nbsp;внедрение в&nbsp;производство одного нового вида пластмасс, а&nbsp;для наших производителей таковым будет считаться поликарбонат, превышает $150&nbsp;млн.<br />\r\nОчевидно, что производить новые материалы сегодня, да&nbsp;и&nbsp;в&nbsp;ближайшем будущем, способны лишь мощные многонациональные компании, обладающие достаточными финансовыми ресурсами, позволяющими вкладывать необходимые средства в&nbsp;создание и&nbsp;внедрение новых технологий.<br />\r\nВместе с&nbsp;этим украинский рынок настроен на&nbsp;широкое использование поликарбонатных материалов. Мировая практика многолетнего использования сотового поликарбоната в&nbsp;архитектуре (торговые и&nbsp;спортивные центры, зимние сады и&nbsp;оранжереи, выставочные залы и&nbsp;крытые переходы) показала его преимущества, и&nbsp;эти преимущества будут с&nbsp;успехом реализовываться украинскими дизайнерами и&nbsp;архитекторами. По&nbsp;мнению специалистов, применение этого материала в&nbsp;жилищном строительстве (и&nbsp;ремонте существующего жилищного фонда) будет возрастать.</p>\r\n\r\n<p>Ещё совсем недавно единственным материалом для создания светопрозрачных конструкций служило стекло. В&nbsp;настоящее время ситуация изменилась, и&nbsp;для этих целей используют поликарбонатные листы. Конечно, этот материал нельзя считать альтернативой стеклу, скорее всего это удачное дополнение, позволяющее создавать качественные и&nbsp;выразительные, с&nbsp;архитектурной точки зрения, объекты.</p>\r\n\r\n<p>Строительный комплекс использует различные виды поликарбоната, в&nbsp;том числе и&nbsp;монолитные листы. Однако, благодаря повышенной прочности, возможности создания арочных и&nbsp;купольных конструкций, хорошей тепло- и&nbsp;звукоизоляции, красивому внешнему виду, наряду с&nbsp;прозрачностью и&nbsp;равномерным рассеиванием света особенно популярным в&nbsp;строительстве сегодня становится структурный (сотовый) поликарбонат. Именно об&nbsp;этом сегменте рынка и&nbsp;будем, в&nbsp;большей степени, говорить при дальнейшем рассмотрении украинского рынка поликарбонатных листов.</p>\r\n\r\n<p>Рассматривая ситуацию на&nbsp;украинском рынке поликарбонатных листов, специалисты отмечают, что кардинальные изменения здесь произошли в&nbsp;2003&nbsp;г.</p>\r\n\r\n<p>Уже в&nbsp;2004&nbsp;г. на&nbsp;украинском рынке было реализовано почти 1 тыс. 219 т. продукции отечественного производителя. В&nbsp;2005&nbsp;г. объем реализации был увеличен на&nbsp;20% и&nbsp;составил и&nbsp;составил 1 тыс. 471 т.</p>\r\n\r\n<p>В&nbsp;2006&nbsp;г. объем реализации составит около 2 тыс. т. поликарбонатных листов.</p>\r\n\r\n<p>Однако, даже, несмотря на&nbsp;это, значительную часть (70%) украинского рынка занимают изделия зарубежных производителей. При этом лидерские позиции удерживает продукция китайского производства. Поликарбонатным листам &laquo;Made in&nbsp;China&raquo; принадлежит 39% нашего рынка. В&nbsp;первом полугодии текущего года было реализовано 1 тыс. 394,5 т. китайского поликарбоната.</p>\r\n\r\n<p>В&nbsp;связи с&nbsp;этим, значительно сократилась часть рынка, принадлежавшая ранее европейским производителям. По&nbsp;оценкам экспертов, все они занимают 31% рынка (в&nbsp;первом полугодии 2006&nbsp;г. было реализовано 821 т. поликарбонатных листов).</p>\r\n\r\n<p>В&nbsp;целом, говоря о&nbsp;продукции зарубежных производителей, специалисты отмечают, что сегодня поликарбонатные листы импортируются из:</p>\r\n\r\n<ul>\r\n	<li>Италии (сотовый поликарбонат Daulux, производства компании Роlyu Italiana Spa);</li>\r\n	<li>Германии (поликарбонатные плиты Macrolon концерна Bayer Sheet Europe);</li>\r\n	<li>Израиля (сотовый поликарбонат Polygal производства компании Ро1уgal industries Ltd, поликарбонатная система светопрозрачных покрытий Danpalon и&nbsp;система Соntrolite компании Dan Pal).</li>\r\n</ul>\r\n\r\n<p>По&nbsp;оценкам экспертов в&nbsp;первом полугодии 2006&nbsp;г. было реализовано 216 т&nbsp;&mdash; итальянского, 209 т&nbsp;&mdash; немецкого и&nbsp;198 т. &mdash;&nbsp;израильского поликарбоната. Сегодня продукция этих производителей удерживает, соответственно 9%, 8% и&nbsp;7% украинского рынка, в&nbsp;первом полугодии текущего года объем реализации их&nbsp;продукции составил 198 т.</p>\r\n\r\n<p>Оценивая популярность торговых марок поликарбонатных листов среди потребителей, многие специалисты отмечают, что наибольшим спросом сегодня пользуются такие как: Plastilux (Китай), Stronex (Украина), Daulux (Италия), Macrolon (Германия), Polygal (Израиль) . При этом в&nbsp;Украине к&nbsp;наиболее популярным товарным позициям относятся 4-х, 6-ти и&nbsp;8-ми мм&nbsp;сотовые листы.</p>\r\n\r\n<p>Специалисты отмечают, что сотовые поликарбонатные листы относятся, к&nbsp;так называемым системным продуктам. Это значит, что для создания качественной конструкции, кроме самого листа, необходимы ещё различные аксессуары, использующиеся при монтаже. С&nbsp;учетом этого говорить только о&nbsp;стоимости листов будет не&nbsp;совсем корректно. И&nbsp;для того, чтобы не&nbsp;вводить потенциальных заказчиков в&nbsp;заблуждение, рассмотрим примерную стоимость каждого элемента системы в&nbsp;отдельности. И&nbsp;так, в&nbsp;зависимости от&nbsp;торговой марки, толщины и&nbsp;цвета 1&nbsp;м&sup2; сотового поликарбонатного листа обойдется застройщику от&nbsp;32 до&nbsp;140 грн. При этом, по&nbsp;мнению специалистов, поликарбонат можно условно разделить на&nbsp;три ценовых диапазона. К&nbsp;наиболее дорогим изделиям относятся Makrolon и&nbsp;Daulux. Средний ценовой диапазон&nbsp;&mdash; Stronex, Polygal. К&nbsp;недорогой продукции относятся поликарбонатные листы азиатских, в&nbsp;том числе и&nbsp;китайских производителей, отличающиеся при этом и&nbsp;неплохим качеством.</p>\r\n\r\n<p>К&nbsp;стоимости поликарбонатных листов необходимо ещё добавить и&nbsp;стоимость стыковочных и&nbsp;торцевых монтажных поликарбонатных профилей. Цена стыковочного изделия колеблется от&nbsp;12 до&nbsp;40 грн. за&nbsp;погонный метр, торцевые обойдутся дешевле, их&nbsp;стоимость составляет около 10грн. за&nbsp;погонный метр. При монтаже используется также лента и&nbsp;крепеж. Соответственно стоимость</p>\r\n\r\n<p>перфорированной ленты шириной 25&nbsp;мм составляет около 180 грн за&nbsp;рулон (33&nbsp;м.п.), неперфорированной&nbsp;&mdash; 120 грн за&nbsp;рулон (50&nbsp;м. п.). Что касается крепежа, то&nbsp;и&nbsp;он&nbsp;значительно влияет на&nbsp;качество светопрозрачной конструкции. И&nbsp;хотя стоимость этих изделий, вызывает желание сэкономить (только одна термошайба для поликарбонатных листов обойдется около 3 грн), искать &laquo;нетрадиционные&raquo; способы крепежа специалисты не&nbsp;рекомендуют.</p>\r\n\r\n<p>Подобные расчеты показывают, что для получения качественной конструкции необходимы незначительные финансовые затраты. Именно это является для многих заказчиков основополагающим фактором при выборе поликарбонатных листов для кровельной системы.</p>\r\n\r\n<p>Для повышения конкурентоспособности многие производители работают над расширением ассортимента своей продукции. Новинками на&nbsp;украинском рынке являются 16&nbsp;мм (Х/3, Н/6) и&nbsp;20&nbsp;мм (Н/6) сотовые поликарбонатные листы, способных выдерживать большие нагрузки и&nbsp;энергосберегающих поликарбонатных листов.</p>\r\n\r\n<p>Мировая практика многолетнего использования сотового поликарбоната в&nbsp;архитектуре (торговые и&nbsp;спортивные центры, зимние сады и&nbsp;оранжереи, выставочные залы и&nbsp;крытые переходы) показала его преимущества, и&nbsp;эти преимущества будут с&nbsp;успехом реализовываться украинскими дизайнерами и&nbsp;архитекторами.</p>\r\n\r\n<p>По&nbsp;мнению специалистов, применение этого материала украинским строительным комплексом будет возрастать в&nbsp;среднем на&nbsp;25&ndash;30% в&nbsp;год. Учитывая интерес застройщиков к&nbsp;этому материалу, некоторые производители поликарбонатных листов, обладающие достаточными финансовыми ресурсами, планируют расширять свое производство.</p>\r\n', 1, 5, NULL, 1466086021, 1466506142);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_article_files`
--

CREATE TABLE IF NOT EXISTS `tbl_article_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filesystem` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `mime_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_article_files`
--

INSERT INTO `tbl_article_files` (`id`, `filesystem`, `path`, `title`, `size`, `mime_type`, `created_at`, `updated_at`) VALUES
(4, 'local', '/news/news1.htm.jpg', NULL, 2728, 'image/jpeg', 1466506056, 1466506056),
(5, 'local', '/articles/article4.htm.jpg', NULL, 7557, 'image/jpeg', 1466506142, 1466506142);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_article_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_article_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `entity_id` (`entity_id`),
  KEY `entity_type` (`entity_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_article_meta`
--

INSERT INTO `tbl_article_meta` (`id`, `entity_id`, `entity_type`, `meta_title`, `meta_keywords`, `meta_description`, `meta_robots`, `custom_meta`) VALUES
(1, 1, 'article', 'Развитие поликарбонатного рынка в Украине', '', 'Материалы из поликарбоната, в 2009 г. по мнению специалистов, во многих отраслях и в том числе в строительстве будут пользоваться повышенным спросом. Говоря о мировых производителях поликарбоната, специалисты отмечают, что в их среде наблюдается', '', ''),
(5, 1, 'news', 'Октябрьские скидки на сотовый поликарбонат. -30% на все бренды!', 'Сотовый поликарбонат, скидки на поликарбонат, акция', 'Акция на сотовый поилкарбонат', '', '');

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
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `template_id` int(11) DEFAULT NULL,
  `facet_set_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft_rgt` (`lft`,`rgt`),
  KEY `depth` (`depth`),
  KEY `tree` (`tree`),
  KEY `slug` (`slug`),
  KEY `name` (`name`),
  KEY `FK_categories_image_id` (`image_id`)
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_category_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
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
  `key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `context` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key` (`key`),
  KEY `context` (`context`)
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
  `predefined_values` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `entity_type` (`entity_type`)
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
  KEY `FK_eav_entity_values_attribute_id` (`attribute_id`),
  KEY `FK_eav_entity_values_value_id` (`value_id`),
  KEY `attribute_name` (`attribute_name`),
  KEY `string_value` (`string_value`),
  KEY `value_entity_id` (`value_entity_id`)
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
  `value_entity_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_eav_product_values_entity_id` (`entity_id`),
  KEY `FK_eav_product_values_attribute_id` (`attribute_id`),
  KEY `FK_eav_product_values_value_id` (`value_id`),
  KEY `attribute_name` (`attribute_name`),
  KEY `string_value` (`string_value`),
  KEY `value_entity_id` (`value_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eav_values`
--

CREATE TABLE IF NOT EXISTS `tbl_eav_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `presentation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facets`
--

CREATE TABLE IF NOT EXISTS `tbl_facets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entity_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `index_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `from` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `interval` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `operator` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `multivalue` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_facets`
--

INSERT INTO `tbl_facets` (`id`, `name`, `label`, `entity_type`, `attribute_name`, `index_name`, `from`, `to`, `interval`, `type`, `operator`, `multivalue`) VALUES
(1, '', 'Тип поиска', '', '', '', '', '', '', 'searchable_types_facet', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facet_ranges`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_ranges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facet_id` int(11) NOT NULL,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `include_lower_bound` tinyint(1) DEFAULT '1',
  `include_upper_bound` tinyint(1) DEFAULT '0',
  `display` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_facet_sets`
--

INSERT INTO `tbl_facet_sets` (`id`, `name`) VALUES
(1, 'Search page facets');

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

--
-- Dumping data for table `tbl_facet_set_facets`
--

INSERT INTO `tbl_facet_set_facets` (`set_id`, `facet_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_facet_terms`
--

CREATE TABLE IF NOT EXISTS `tbl_facet_terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facet_id` int(11) NOT NULL,
  `term` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) DEFAULT NULL,
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
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `include_lower_bound` tinyint(1) DEFAULT '1',
  `include_upper_bound` tinyint(1) DEFAULT '0',
  `display` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_facet_values_facet_id` (`facet_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_facet_values`
--

INSERT INTO `tbl_facet_values` (`id`, `type`, `facet_id`, `term`, `from`, `to`, `include_lower_bound`, `include_upper_bound`, `display`, `sort`) VALUES
(1, 'searchable_types_facet_term', 1, 'product', '', '', 1, 0, 'Каталог', 1),
(2, 'searchable_types_facet_term', 1, 'product_category', '', '', 1, 0, 'Категории', 2),
(3, 'searchable_types_facet_term', 1, 'page', '', '', 1, 0, 'Страницы', 3),
(4, 'searchable_types_facet_term', 1, 'news', '', '', 1, 0, 'Новости', 4),
(5, 'searchable_types_facet_term', 1, 'article', '', '', 1, 0, 'Статьи', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_galleries`
--

CREATE TABLE IF NOT EXISTS `tbl_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gallery_items`
--

CREATE TABLE IF NOT EXISTS `tbl_gallery_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) NOT NULL,
  `filesystem` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_gallery_items_gallery_id` (`gallery_id`),
  KEY `sort` (`sort`)
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
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `type` (`type`)
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
  `full_text_search` tinyint(1) DEFAULT '0',
  `boost` int(11) DEFAULT NULL,
  `suggestions` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_type` (`index_type`)
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_menus`
--

INSERT INTO `tbl_menus` (`id`, `name`, `location`, `created_at`, `updated_at`) VALUES
(1, 'Top menu', 'top', 1466343359, 1466343359);

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
  `target_blank` tinyint(1) DEFAULT '0',
  `css_classes` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `rel` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `visibility` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `items_display` tinyint(4) NOT NULL,
  `items_css_classes` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `icon_id` int(11) DEFAULT NULL,
  `active_icon_id` int(11) DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lft_rgt` (`lft`,`rgt`),
  KEY `depth` (`depth`),
  KEY `tree` (`tree`),
  KEY `name` (`label`),
  KEY `menu_id_status` (`menu_id`,`status`),
  KEY `FK_menu_items_icon_id` (`icon_id`),
  KEY `FK_menu_items_active_icon_id` (`active_icon_id`),
  KEY `FK_menu_items_video_id` (`video_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `tbl_menu_items`
--

INSERT INTO `tbl_menu_items` (`id`, `tree`, `lft`, `rgt`, `depth`, `menu_id`, `label`, `title`, `url`, `target_blank`, `css_classes`, `rel`, `page_id`, `status`, `visibility`, `items_display`, `items_css_classes`, `icon_id`, `active_icon_id`, `video_id`) VALUES
(1, 1, 1, 2, 0, 1, 'Главная', '', '/', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(2, 2, 1, 2, 0, 1, 'Поликарбонат', '', 'materials', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(3, 3, 1, 2, 0, 1, 'Теплицы', '', 'constructions/hothouses', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(4, 4, 1, 2, 0, 1, 'Конструкции', '', 'constructions', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(5, 5, 1, 2, 0, 1, 'Акции', '', 'discounts', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(6, 6, 1, 2, 0, 1, 'Контакты', '', 'contacts', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(7, 7, 1, 18, 0, 1, 'Каталог', '', 'catalog', 0, '', '', NULL, 1, '', 3, '', NULL, NULL, NULL),
(8, 7, 2, 3, 1, 1, 'Подоконники', '', 'category34.htm', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(9, 7, 4, 5, 1, 1, 'Фасадные материалы', '', 'category35.htm', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(10, 7, 6, 7, 1, 1, 'Поликарбонаты', '', 'category2.htm', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(11, 7, 8, 9, 1, 1, 'Теплицы', '', 'category3.htm', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(12, 7, 10, 11, 1, 1, 'Выбор обогревателя', '', 'category33.htm', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(13, 7, 12, 13, 1, 1, 'Акрил (оргстекло)', '', 'category43.htm', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(14, 7, 14, 15, 1, 1, 'Профилированный ПВХ Ondex', '', 'category44.htm', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL),
(15, 7, 16, 17, 1, 1, 'Террасная доска', '', 'category54.htm', 0, '', '', NULL, 1, '', 0, '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu_item_files`
--

CREATE TABLE IF NOT EXISTS `tbl_menu_item_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_item_id` int(11) DEFAULT NULL,
  `attribute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `filesystem` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_migration`
--

INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1465981526),
('m141023_154713_create_cms_tables', 1465981546),
('m141204_151109_create_config_tables', 1465981529),
('m150208_105944_create_eav_tables', 1465981565),
('m150208_114635_create_variation_tables', 1465981575),
('m150208_123637_create_catalog_tables', 1465981596),
('m150415_113756_create_seo_tables', 1465981555),
('m150806_091325_create_search_tables', 1466350232),
('m160616_085344_create_blog_tables', 1466070864),
('m160701_153553_create_galleries_tables', 1467388037);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_news`
--

CREATE TABLE IF NOT EXISTS `tbl_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `announce` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `image_id` int(11) DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`),
  KEY `FK_news_image_id` (`image_id`),
  KEY `FK_news_video_id` (`video_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_news`
--

INSERT INTO `tbl_news` (`id`, `title`, `slug`, `announce`, `content`, `status`, `image_id`, `video_id`, `created_at`, `updated_at`) VALUES
(1, 'Октябрьские скидки на сотовый поликарбонат. -30% на все бренды!', 'news1.htm', 'Только в октябре сезонные скидки на сотовый поликарбонат всех толщин, цветов и производителей до 30%! Спешите, количество товара ограничено.', '<p>Компания Вишневый Сад предлагает своим клиентам<strong> октябрьскую распродажу</strong> сотового поликарбоната! Только у нас Вы можете приобрести сотовый поликарбонат за наилучшую цену с доставкой по Киеву. Мы предлагаем широкий ассортимент толщин, цветов. Благодаря этому Вы можете подобрать наиболее подходящий для Вас материал и заплатить за него минимальную цену. Обращайтесь к нам за дополнительной консультацией, будем рады Вам помочь.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Всегда в наличии сотовый поликарбонат для теплиц, навесов, автомобильных навесов, прочих конструкций арочного и скатного типов. Комплектующие к сотовому поликарбонату Вы также можете приобрести у нас.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n', 1, 4, NULL, 1466095402, 1466506056);

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
  `tree` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `template_id` int(11) DEFAULT NULL,
  `facet_set_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft_rgt` (`lft`,`rgt`),
  KEY `depth` (`depth`),
  KEY `tree` (`tree`),
  KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Dumping data for table `tbl_pages`
--

INSERT INTO `tbl_pages` (`id`, `tree`, `lft`, `rgt`, `depth`, `type`, `title`, `slug`, `content`, `status`, `template_id`, `facet_set_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 0, 'page', 'Прозрачная кровля', 'index', '<p><strong class="bl2"><span class="tx1">Внимание!</span> Поликарбонат POLYSIDE качество выше - <a href="/prices/" target="_self" title="Цены на поликарбонат">цена</a> ниже!</strong></p>\r\n\r\n<p>Компания &quot;Алион Групп&quot;&nbsp;предлагает универсальный&nbsp;строительный листовой пластик&mdash; поликарбонат сотовый, монолитный и профили&nbsp;тм &laquo;POLYSIDE&raquo; (Израиль), &laquo;Polygal&raquo; (Израиль), &laquo;Plastilux&raquo; (Россия), &quot;Алексдорф&quot; и другие. Также компания&nbsp;производит&nbsp;и продает дачные теплицы (сборные конструкции)&nbsp;для садоводства и тепличного хозяйства.</p>\r\n\r\n<p><img alt="" src="http://polikarbonatvs.com.ua/ufiles/Image/poly_color.jpg" style="height:139px; width:729px" /></p>\r\n\r\n<h2>Светопрозрачные конструкции и прозрачная кровля</h2>\r\n\r\n<p><a href="/constructions/krovlya/" target="_self"><img alt="Светопрозрачные конструкции и прозрачная кровля" class="imgBorderLeft" src="/ufiles/Image/krovlya1.jpg" style="float:left" /></a> Наша компания готова предложить Вам широкий спектр услуг данной области. Заказав у нас изготовление светопрозрачной конструкции, Вы получите возможность красиво и качественно оформить свой дом, офис, магазин, заплатив за это вполне разумные деньги. А сама светопрозрачная конструкция придаст зданию неповторимый вид и будет радовать Вас долгое время. <a href="/constructions/krovlya/">Подробнее</a></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2>Теплицы и парники</h2>\r\n\r\n<p><a href="/constructions/hothouses/" target="_self"><img alt="Теплицы и парники" class="imgBorderLeft" src="http://polikarbonatvs.com.ua/ufiles/Image/hothous1.jpg" style="float:left; height:100px; width:133px" /></a>Наша компания изготовит для Вас любую теплицу из поликарбоната и осуществит её монтаж. Наши специалисты подберут модель теплицы, максимально соответствующую вашим целям и с учётом ваших потребностей и желаний. Кроме того,&nbsp;мы в кратчайшие сроки осуществит поставку и монтаж парника, теплицы из поликарбоната или оранжереи, которые будут полностью соответствовать Вашим требованиям. Поликарбонат идеально сочетает в себе светопропускающую способность и прочностные качества, что делает его незаменимым для изготовления теплиц и парников.&nbsp;<a href="/constructions/hothouses/">Подробнее</a></p>\r\n\r\n<h2>Металлоконструкции, заборы, ворота и ограждения</h2>\r\n\r\n<p><a href="/constructions/metal/" target="_self"><img alt="Металлоконструкции" class="imgBorderLeft" src="/ufiles/Image/vrata2.jpg" style="float:left" /></a></p>\r\n\r\n<p>Металлоконструкции - это основа для Вашего строительства. Помимо изготовления светопрозрачных конструкций из поликарбоната мы специализируемся на производстве металлоконструкций любой сложности. Решетки,&nbsp;металлоограждения (балконные, лестничные), металлокаркасы элементов мебели, лестницы, лестничные марши, металлические ворота и заборы - все это Вы можете заказать у нас. <a href="/constructions/metal/">Подробнее</a></p>\r\n\r\n<p><span class="bg1">Доставка, монтаж, сервис, гарантия 10 лет</span></p>\r\n\r\n<h3>Бренды</h3>\r\n\r\n<p><a href="/articles/article12.htm" target="_self" title="Как выбрать поликарбонат"><img alt="Поликарбонат как выбрать" class="imgBorder" src="/ufiles/Image/PPolikarbonat_foto.jpg" style="float:left; height:101px; margin-right:27px; width:130px" /></a>POLIGAL, POLYSIDE, MAKROLON, &nbsp;NOVATRO, MARLON, PLASTILUX, POLYNEX, EUROLUX, MAKROLUX, AKTUAL, ALEXDORF, VIZOR, HAIGAO,&nbsp;STRONEX, SUNEX&nbsp;и другие.<br />\r\n&bull; Мы предлагаем: <a href="/catalog/" target="_parent" title="стройматериалы и хозяйственные товары">материалы для строительства в каталоге товаров</a></p>\r\n', 1, 5, NULL, 1465983896, 1466356559),
(2, 2, 1, 2, 0, 'page', 'Контакты', 'contacts', '<p>Здесь Вы можете купить поликарбонат, купить теплицу&nbsp;и заказать монтаж&nbsp;металлоконструкций.</p>\r\n<div style="TEXT-ALIGN: center"><span style="FONT-WEIGHT: bold"><span style="FONT-WEIGHT: bold">Компания ООО"Алион-групп"</span></span></div>\r\n<p></p>\r\n<p><br>Украина, Киев &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Донецкая обл.<br>г. Вишнёвый, ул. Промышленная 5 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;г.Славянск,ул. Машчерметовская 47<br></p>\r\n<table style="BORDER-COLLAPSE: collapse; WIDTH: 100%">\r\n<tbody>\r\n<tr>\r\n<td width="50%">\r\n<ul>\r\n<li>тел:&nbsp;(044) 223-36-07 \r\n</li><li>тел: (067) 410-41-00 \r\n</li><li>тел: (050) 200-44-04 &nbsp;\r\n</li><li>тел: (093) 706-99-55 <span style="FONT-WEIGHT: bold">Пенопласт</span></li></ul></td>\r\n<td>\r\n<ul>\r\n<li>&nbsp;тел: (050)9-033-022 \r\n</li><li>&nbsp;тел: (098)9-033-022</li></ul></td></tr></tbody></table>\r\n<p>График работы &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; График работы &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<br>Пн-Пт: с 9:00 до 18:00 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Пн-Пт: с 9:00 до 18:00<br>Сб: c 10:00 до 16:00 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Сб-Вс: выходной<br>Вс: выходной.</p>\r\n<p><span style="FONT-WEIGHT: bold">Купить поликарбонат в&nbsp;Украине:</span></p>\r\n<p>\r\n</p><table style="BORDER-COLLAPSE: collapse; WIDTH: 100%">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<p>» <a title="Поликарбонат Ровно" href="/rovno/">Поликарбонат Ровно<br></a>» <a href="/luck/">Поликарбонат Луцк<br></a>» <a href="/lviv/">Поликарбонат Львов<br></a>» <a href="/zhitomir/">Поликарбонат Житомир</a></p>\r\n<p>» <a href="/doneckaya/">Донецкая обл. г. Славянск<br></a></p></td>\r\n<td><br>» <a href="/ivano-frankivsk/">Поликарбонат Ивано-Франковск</a><br>» <a href="/chernivtsi/">Поликарбонат Черновцы<br></a>» <a href="/hmelnickiy/">Поликарбонат Хмельницкий<br></a>» <a href="/ternopil/">Поликарбонат Тернополь</a></td></tr></tbody></table><p></p>\r\n\r\n\r\n\r\n<h1>Обратная связь</h1>\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n<script>\r\n\r\nfunction isEmail(sEmail) {\r\n	sEmail = sEmail.replace( new RegExp(''/\\(.*?\\)/''), '''' );\r\n	var oRegExp = /^[A-Za-z0-9][-\\w]*(\\.[A-Za-z0-9][-\\w]*)*@[A-Za-z0-9][-\\w]*(\\.[A-Za-z0-9][-\\w]*)*\\.[a-zA-Z]$/;\r\n	return oRegExp.test(sEmail);\r\n}\r\n\r\nfunction checkForm(form) \r\n{\r\n	var SendItem= 0;\r\n	var AlertMessage;\r\n	\r\n\r\n\r\n	if (form.email.value == "" || form.email.value.indexOf('' '',0)==0)\r\n	{SendItem= 1; AlertMessage = ''Для того, чтобы мы могли связаться с вами\\nнеобходимо указать email'';}	\r\n			\r\nif (form.tel.value == "" || form.tel.value.indexOf('' '',0)==0)\r\n	{SendItem= 1; AlertMessage = ''Для того, чтобы мы могли связаться с вами\\nнеобходимо указать телефон'';}	\r\n\r\n  if (!SendItem) {\r\n		form.submit();\r\n	} else {\r\n		alert(AlertMessage);\r\n  }\r\n}\r\n\r\n\r\n</script>\r\n     \r\n<form method="post" action="/contacts/?action=post&amp;nocash" name="feedbackForm" enctype="application/x-www-form-urlencoded">		\r\n	        <table class="feedback">     \r\n			<tbody><tr>\r\n              <td width="240">Здесь Вы можите задать вопрос или оформить заказ:</td>\r\n              <td width="200"><textarea style="width: 320px;" name="comments" rows="5" cols="50"></textarea></td>\r\n            </tr>	\r\n		  </tbody></table>		\r\n  	  	  		\r\n		<br><br>\r\n		<b>Контактная информация:</b>\r\n		<br><br>\r\n        <table class="feedback">     \r\n		    <tbody><tr>\r\n              <td width="240">Ваше имя:</td>\r\n              <td width="200"><input name="name" size="50" type="TEXT"></td>\r\n            </tr>			\r\n			\r\n			<tr>\r\n              <td>Ваш E-mail:<span class="required">*</span></td>\r\n              <td><input name="email" size="50" type="TEXT"></td>\r\n            </tr>	\r\n			\r\n		    <tr>\r\n              <td>Контактный телефон:<span class="required">*</span></td>\r\n              <td><input name="tel" size="50" type="TEXT"></td>\r\n            </tr>\r\n					\r\n			\r\n			<tr>\r\n              <td colspan="2" height="30"><br><br>\r\n			  <em class="bg1"><span class="required">*</span> — поля, обязательные для заполнения&nbsp;&nbsp;</em>			  \r\n			  </td>              \r\n            </tr>\r\n			\r\n				<tr>\r\n              <td colspan="2"><br>\r\n				  <input name="subm" onclick="checkForm(this.form);" value="Отправить" type="button">	<br><br>\r\n                </td>\r\n            </tr>\r\n		  </tbody></table>				           \r\n          </form>\r\n\r\n \r\n\r\n\r\n', 1, NULL, NULL, 1465983916, 1466347036),
(3, 1, 2, 3, 1, 'page', 'Subbbb', 'subbbb', '', 1, NULL, NULL, 1465983949, 1465990203),
(12, 12, 1, 2, 0, 'page', 'Поликарбонат', 'materials', '<p><strong class="bl2"><span class="tx1">Внимание!</span> Цены на поликарбонат в Киеве снижены! Компания &quot;Алион Групп&quot; предлагает поликарбонат POLYSIDE высокого европейского качества по <span class="tx2"><a href="/prices/">самой низкой цене</a> в Украине!</span></strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<table style="border-collapse:collapse; width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td><img alt="Поликарбонат Polyside Израиль" src="/ufiles/Image/Polyside1.jpg" style="height:30px; width:128px" /></td>\r\n			<td><img alt="Поликарбонат Polygal Израиль" src="/ufiles/Image/polygal_opt.jpg" style="height:30px; width:104px" /></td>\r\n			<td><img alt="Поликарбонат Plastilux Россия" src="/ufiles/Image/Plastilux.jpg" style="height:30px; width:110px" /></td>\r\n			<td><img alt="" src="/ufiles/Image/alexdorf1.gif" style="height:38px; width:106px" /></td>\r\n			<td><img alt="Поликарбонат Polynex Китай" src="/ufiles/Image/Polynex.jpg" style="height:24px; width:96px" /></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><span style="color:red">Если Вы найдете дешевле, позвоните, мы предоставим Вам лучшую цену! (044) 223-36-07</span></p>\r\n\r\n<p>Компания &laquo;АЛИОН ГРУПП&raquo; реализует универсальный строительный листовой пластик &ndash; надежный и прочный поликарбонат различных видов от лучших мировых производителей: ТМ <em>&laquo;Polygal&raquo;</em> (Полигаль, Израиль), <em>&laquo;Alexdorf&raquo;</em> (Алексдорф, Китай), <em>&laquo;Plastilux&raquo;</em> (Пластилюкс, Россия) и другие. В нашем ассортименте представлен широкий выбор материалов из поликарбоната по доступным ценам в Киеве и по всей Украине. Качественная продажа поликарбоната для приусадебных участков и других объектов различных масштабов и квадратуры.</p>\r\n\r\n<p>Компания импортирует и продает строительные пластиковые материалы ведущих производителей мира и специализируется на оптово-розничной продаже кровельных материалов. Мы поможем Вам определиться с выбором поликарбоната, пластиковых материалов под наиболее подходящие Вашим требованиям пожелания. Чтобы купить поликарбонат оптом Вам достаточно позвонить нам по телефонам, представленным в контактах.</p>\r\n\r\n<h2>Виды поликарбоната и что мы предлагаем?<br />\r\n&nbsp;</h2>\r\n\r\n<p><a href="/materials/monolit/" target="_self"><img alt="Монолитный поликарбонат" class="imgBorderLeft" src="/ufiles/Image/monolitnyi1.jpg" style="float:left" /></a><strong>Монолитный поликарбонат</strong></p>\r\n\r\n<p>Материал, обладающий высочайшей прочностью, гибкостью, прозрачностью и низкой горючестью. Он широко применяется в архитектуре, строительстве и промышленности. Поликарбонатные плиты&nbsp; имеют защитный слой, предохраняющий их от воздействия солнечной радиации. Листы данного вида поликарбоната имеют малый вес (вдвое легче стекла аналогичной толщины), хорошую прозрачность, стойкость к воздействиям окружающей среды, а также большинства химических веществ и соединений. <a href="/materials/monolit/" target="_self" title="монолитный поликарбонат">Подробнее </a></p>\r\n\r\n<p><br />\r\n<a href="/materials/sotoviy/" target="_self"><img alt="Сотовый поликарбонат" class="imgBorderLeft" src="/ufiles/Image/sot2.jpg" style="float:left" /></a><strong>Сотовый поликарбонат</strong></p>\r\n\r\n<p>Поликарбонатные листы производят методом экструзии из высококачественного поликарбонатного сырья ведущих производителей в соответствии с европейскими стандартами качества ISO. Они устойчивы к УФ-излучению, обладают хорошими теплоизоляционными свойствами, высоким светопропусканием и ударопрочностью, а также исключительными термоизолирующими свойствами. Панели из такого поликарбоната применяются в строительстве и архитектуре, когда требуется высокая степень термоизоляции, а также максимальная прочность и устойчивость к высоким нагрузкам.&nbsp; <a href="/materials/sotoviy/" target="_self" title="Сотовый поликарбонат">Подробнее</a></p>\r\n\r\n<p><a href="/materials/ribbed/" target="_self"><img alt="Рифлёный и волнистый поликарбонат" class="imgBorderLeft" src="/ufiles/Image/poly2.jpg" style="float:left" /></a><strong>Рифленый и&nbsp;волнистый поликарбонат</strong></p>\r\n\r\n<p>Обладая высокой ударопрочностью и 98% защитой от вредных УФ лучей наряду с особой структурой, листы таких популярных видов поликарбоната могут использоваться для остекления крыш любых объектов. Их выносливость и устойчивость к плохим погодным условиям впечатляет: при проведении имитационного испытания даже градины диаметром 20 мм, которые били по материалу со скоростью 21 м/с, не смогли разбить лист. <a href="/materials/ribbed/" target="_blank" title="Рифлёный и волнистый поликарбонат">Подробнее</a></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><a href="/materials/prifile/" target="_self"><img alt="Профилированный поликарбонат" class="imgBorderLeft" src="/ufiles/Image/polygal_profiles3.jpg" style="float:left" /></a> <strong>Соединительные поликарбонатные профили</strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>С покрытием анти-конденсат наиболее эффективен для применения в теплицах. Он отличается от других материалов тем, что при появлении конденсата на внутренней стороне листа конденсатные капли, благодаря специальному слою, равномерно распределяются по поверхности, образуя прозрачную пленку. Эта конденсатная пленка повышает освещенность в теплице на 2%, т.е. светопропускание при образовании конденсата составляет 92%. Для теплиц применяется 2 типа профиля: &quot;омега&quot; и &quot;грека&quot;. Профиль &quot;омега&quot; имеет ряд существенных преимуществ для применения в теплицах, так как его форма помогает повысить освещенность в теплице в утренние и вечерние часы за счет эффективного отражения и преломления световых волн определенной длины. <a href="/materials/prifile/" target="_self" title="Профилированый поликарбонат">Подробнее</a></p>\r\n\r\n<h2><br />\r\nНужно купить поликарбонат в Киеве и по Украине? Продажа поликарбоната от мировых производителей по лучшим ценам &ndash; оптом и в розницу</h2>\r\n\r\n<p>Преимущества поликарбоната очевидны. Этот полимер прочный, надежный, а при правильной эксплуатации &ndash; долговечен. Применение поликарбоната достигло своего резонанса в различных областях. Его используют как частные лица, заказывая конструкцию (навес) для своего автомобиля, бассейна и многого другого, так и крупные производственные компании, использующие поликарбонат для теплиц или в рекламных целях.</p>\r\n\r\n<p>Заказчик, принявший решение купить поликарбонат в Киеве или другом любом городе Украины может не сомневаться в качестве продукции, так как мы осуществляем поставки исключительно от импортных производителей, которые успешно себя зарекомендовали среди профессионального сообщества. Отметим, что продажа поликарбоната всех видов в Украине &ndash; наше ключевое направление, в котором мы долгое время специализируемся. Нужно заказать поликарбонат оптом или в розницу? Не проблема. Звоните нам и мы постараемся максимально быстро и выгодно для Вас подобрать наилучший вариант применения поликарбоната для Ваших целей и задач.</p>\r\n\r\n<h2>Поликарбонат цены подойдут всем &ndash; от частного лица до крупной организации</h2>\r\n\r\n<p>Мы уже долгое время работаем на украинском рынке. Поэтому, наша цена поликарбоната ни в коем случае не ударит по Вашему карману. Вы, непременно, уже видели вначале сайта, что мы предлагаем обратить к нам, если Вы найдете цену, которая ниже, чем у нас. Это позволяет нам оставаться выгодным, а главное &ndash; надежным партнером для всех наших Заказчиков.</p>\r\n\r\n<p><strong>Как правильно выбрать поликарбонат и на что необходимо обратить внимание?</strong></p>\r\n\r\n<p>На наш взгляд, первое, на что необходимо обратить внимание &ndash; это опыт и технологическая оснастка производителя. Вот почему, наша задача состоит в том, чтобы импортировать поликарбонат исключительно из зарубежных стран, что позволяет предоставить Вам наилучший выбор. Производителей много, но уровень производства у всех разный, не смотря на то, что технологий производства практически идентичная у всех представителей данного сегмента.</p>\r\n\r\n<p><em>Покупая поликарбонат у нас, Вы получаете проверенное временем качество и гарантию на 10 лет на всю продукцию!</em></p>\r\n', 1, NULL, NULL, 1466345071, 1466346212),
(13, 13, 1, 4, 0, 'page', 'Конструкции', 'constructions', '<p><img class="imgBorder" alt="" src="/ufiles/Image/volnistiy.jpg" align="left">Компания "АЛИОН ГРУПП"&nbsp;также занимается изготовлением металлоконструкций с элементами ковки и других материалов. Мы производим полный комплекс работ по проектированию, изготовлению и монтажу изделий любой сложности. Компания специализируется на профессиональном изготовлении и монтаже светопрозрачных <span style="FONT-WEIGHT: bold">конструкций из поликарбоната</span> и металла.&nbsp;Компания создает из металлоконструкций и поликарбоната любые светопрозрачные конструкции, как готовые, так и под заказ.</p>\r\n<h4 align="left">Мы предлагаем</h4>\r\n<table style="BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; WIDTH: 100%; BORDER-COLLAPSE: collapse; BORDER-TOP: medium none; BORDER-RIGHT: medium none" align="left">\r\n<tbody>\r\n<tr>\r\n<td style="BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BORDER-TOP: medium none; BORDER-RIGHT: medium none">\r\n<ul>\r\n<li><a href="/constructions/hothouses/"><span style="FONT-WEIGHT: bold">теплицы&nbsp;из поликарбоната</span></a><span style="FONT-WEIGHT: bold">;&nbsp; </span>\r\n</li><li><a title="теплицы промышленные" href="http://polikarbonatvs.com.ua/constructions/industrial-hothouses/" target="_self"><span style="FONT-WEIGHT: bold">теплицы промышленные&nbsp;под ключ:</span></a><span style="FONT-WEIGHT: bold"></span>\r\n</li><li><a href="/services/sale-hothouses/"><span style="FONT-WEIGHT: bold">теплицы дачные</span></a><span style="FONT-WEIGHT: bold">; </span>\r\n</li><li><a href="http://polikarbonatvs.com.ua/constructions/krovlya/"><span style="FONT-WEIGHT: bold">навесы&nbsp;из поликарбоната; </span></a>\r\n</li><li><a href="http://polikarbonatvs.com.ua/constructions/krovlya/"><span style="FONT-WEIGHT: bold">навесы для автомобиля;</span></a><span style="FONT-WEIGHT: bold"></span>\r\n</li><li><a href="http://polikarbonatvs.com.ua/constructions/krovlya/"><span style="FONT-WEIGHT: bold">козырьки из поликарбоната; </span></a>\r\n</li><li><a href="http://polikarbonatvs.com.ua/constructions/krovlya/"><span style="FONT-WEIGHT: bold">павильоны для бассейнов; </span></a>\r\n</li><li><a href="http://polikarbonatvs.com.ua/constructions/metal/"><span style="FONT-WEIGHT: bold">ворота, ограждения, заборы; </span></a>\r\n</li><li><a href="http://polikarbonatvs.com.ua/constructions/krovlya/"><span style="FONT-WEIGHT: bold">зимний сад;</span></a></li></ul></td>\r\n<td style="BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BORDER-TOP: medium none; BORDER-RIGHT: medium none">\r\n<ul>\r\n<li><a href="/constructions/krovlya/">остекление крыш промышленных объектов; </a>\r\n</li><li><a href="/constructions/krovlya/gallery-translucent/" target="_self">зенитные фонари, кровля;</a> \r\n</li><li><a href="/constructions/krovlya/">прозрачные переходы, входные группы; </a>\r\n</li><li><a href="/constructions/krovlya/">светопрозрачные торговые комплексы; </a>\r\n</li><li><a href="/constructions/krovlya/">наружные рекламные конструкции; </a>\r\n</li><li><a href="/constructions/krovlya/">навесные фасады; </a>\r\n</li><li><a href="/constructions/metal/">утепленные балконные ограждения; </a>\r\n</li><li><a href="/constructions/metal/">террасы из металлоконструкций; </a>\r\n</li><li><a href="/constructions/krovlya/">ларьки и многое другое.</a> </li></ul></td></tr></tbody></table>\r\n<p><span class="bg1">Доставка, монтаж, сервис, гарантия 10 лет.</span></p><br>\r\n\r\n\r\n\r\n\r\n\r\n<h1>Металлические навесы и козырьки</h1>\r\n\r\n\r\n<p>Металлические навесы и козырьки - это <span style="FONT-WEIGHT: normal"><span style="FONT-WEIGHT: normal">светопрозрачная конструкция</span> </span>на основе металлической рамы и сотового поликарбоната (пластик). Такой козырёк не закрывает естественное освещение и одновременно улучшает внешний вид и безопасность пребывания в том месте, где установлен. <span style="FONT-WEIGHT: normal"><span style="FONT-WEIGHT: normal">Металлическая рама</span> </span>обеспечивает необходимую прочность долгие годы жизни козырька, а также, благодаря дизайнерским возможностям служит украшением фасада здания. Сегодня популярно в качестве <span style="FONT-WEIGHT: bold"><a title="Кровля для козырька" href="/constructions/krovlya/" target="_self">кровли для козырька</a></span> использовать листовой поликарбонат со свето - пропускающей способностью.<br>Мы создаем архитектурные козырьки и <a title="Навесы и козырьки" href="/constructions/krovlya/" target="_self">навесы</a> различного типа и конструкций. Козырьки повышают безопасность, а также улучшают эстетичный вид сооружения. Применение таких конструкций широко используется&nbsp;&nbsp;для балконов &nbsp;и входных дверей. Компания изготавливает стандартные и нестандартные козырьки, навесы и <span style="FONT-WEIGHT: bold"><a title="Автонавесы, навесы для автомобилей" href="/constructions/krovlya/" target="_self">автонавесы</a></span>&nbsp;главным достоинством которых является легкость конструкции и простота монтажа, при этом мы делаем защитные навесы с различным светопропусканием и со 100% защитой от осадков и солнца.<br><span style="FONT-WEIGHT: bold"><a title="Козырьки из поликарбоната" href="/constructions/krovlya/" target="_self">Козырьки из поликарбоната</a></span> превращают любой вход - в парадный. При этом, являясь важным элементом декора. Навесы и козырьки из поликарбоната выполняют и чисто защитные функции. Используемый нами поликарбонат обладает высокой огнеустойчивостью — он не только не воспламеняется в открытом огне, но и при температурном разрушении не представляет опасности для жизни, что выгодно отличает его от других материалов используемых при сооружении навесов и козырьков. Козырьки издавна считаются неотъемлемыми атрибутами фасадов, как городских строений, так и частных владений. <br>Металлические козырьки облагораживают внешний вид, придавая завершенность и законченность внешнему образу здания. Оригинальный дизайн и изящные линии козырьков, выполненных из металла, подарят любому офисному, жилому или загородному дому свой особенный стиль и привлекательность. Металлические козырьки защищают от снега, дождя и ветки деревьев. <br><span style="FONT-WEIGHT: normal"><span style="FONT-WEIGHT: normal">Металлический каркас</span>,</span> покрытый поликарбонатом обеспечивают комфортное и безопасное проживание людей в том или ином здании, а также невероятно преображают внешний вид архитектурного объекта. Козырьки создают безопасное пространство вокруг себя, прекрасно защищая вход в здание от всевозможных осадков, также козырьки оберегают жителей от падения с крыш сосулек зимой и иных предметов летом. Надежно закрепленные козырьки (на кронштейнах, цепях или металлических столбах) прослужат невероятно долгий срок вам и вашему дому.</p>\r\n<p>Установка козырьков: козырек, монтаж занимает от 1-3 часов, в том случаи, если уже готовая стандартная <span style="FONT-WEIGHT: normal"><span style="FONT-WEIGHT: normal">конструкция козырька</span>. <br></span>Установка навесов: навес, монтаж осуществляется от 1 до 3 дней. В зависимости от сложности конструкторских решений. Весь процесс в общем, с проектированием производится в течении 7 дней. <br>Наша компания «Вишневый Сад Плюс» гарантирует качество монтажных работ, <span style="FONT-WEIGHT: bold"><a title="металлоконструкции" href="/constructions/metal/" target="_self">металлоконструкции</a></span> и <a title="Поликарбонат, пластиковые материалы" href="/materials/" target="_self">поликарбонат</a>, из которого изготавливаем <span style="FONT-WEIGHT: bold"><a title="Галерея конструкций навесов и козырьков" href="/constructions/krovlya/canopies/" target="_self">конструкции навесов</a></span>.</p>\r\n<h4>Почему с нами выгодно сотрудничать</h4>\r\n<p>•&nbsp;Мы имеем большой опыт в решении задач&nbsp;любой сложности.&nbsp;<br>• Применяем комплексный подход в работе. <br>• Вы экономите своё время.&nbsp;<br>&nbsp;&nbsp;(заказав все в одном месте:&nbsp;проекты, материалы, стройбригады, доставка, монтаж)&nbsp;<br>• Наши цены и качество Вас приятно удивят. <br>• Гарантируем надежность продукции. </p>\r\n<p><span style="FONT-WEIGHT: bold">Чтобы заказать&nbsp;любую конструкцию, Вам достаточно&nbsp;просто</span> позвонить нам. </p>\r\n<p align="center"><a title="Контакты: телефоны компании, обратная связь" href="/contacts/" target="_self"><img alt="Задать вопрос" src="/ufiles/Image/Vopros.PNG" border="0" height="81" width="193"></a></p>\r\n\r\n\r\n\r\n\r\n', 1, NULL, NULL, 1466345352, 1466346051),
(14, 13, 2, 3, 1, 'page', 'Теплицы, парники из поликарбоната', 'hothouses', '<p><img alt="" class="imgBorderLeft" src="/ufiles/Image/hothous1.jpg" style="float:left" /><span style="text-align:start">Компания&nbsp;</span><strong style="text-align:start">Вишнёвый Сад</strong><span style="text-align:start">&nbsp;производит &nbsp;различные виды арочных теплиц и парников. В производстве используются только проверенные временем&nbsp;</span><strong style="text-align:start">качественные материалы</strong><span style="text-align:start">, а именно цельнометаллическую квадратную трубу,&nbsp;профильную трубу различных размеров с горячим заводским оцинкованием, что обеспечивает защиту от коррозии материала до рекордных 50 лет. Выбор материала для накрытия теплиц и парников предлагается от не дорогих тепличных&nbsp;пленок, до высокопрочного сотового поликарбоната с гарантией до 14 лет.</span></p>\r\n\r\n<p>&nbsp;&nbsp;<img alt="" src="http://polikarbonatvs.com.ua/ufiles/Image/icon12.png" style="color:rgb(0, 0, 0); font-size:12px; line-height:18px" />&nbsp; &nbsp;<strong><a href="http://polikarbonatvs.com.ua/contacts/">Производство</a> &nbsp; &nbsp; &nbsp;</strong><img alt="" src="http://polikarbonatvs.com.ua/ufiles/Image/icon12.png" style="color:rgb(0, 0, 0); font-size:12px; line-height:18px" />&nbsp; &nbsp;<strong><a href="http://polikarbonatvs.com.ua/contacts/">Доставка</a></strong></p>\r\n\r\n<p>&nbsp;&nbsp;<img alt="" src="http://polikarbonatvs.com.ua/ufiles/Image/icon12.png" style="color:rgb(0, 0, 0); font-size:12px; line-height:18px" />&nbsp; &nbsp;<strong><a href="http://polikarbonatvs.com.ua/contacts/">Продажа</a> &nbsp; </strong><span class="Apple-tab-span"> </span>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<img alt="" src="http://polikarbonatvs.com.ua/ufiles/Image/icon12.png" style="color:rgb(0, 0, 0); font-size:12px; line-height:18px" />&nbsp; &nbsp;<strong><a href="http://polikarbonatvs.com.ua/contacts/">Установка</a></strong></p>\r\n\r\n<div>\r\n<p class="MsoNormal" style="text-align:center"><strong>Горячие оцинкование - наилучший выбор</strong></p>\r\n</div>\r\n\r\n<div>\r\n<p class="MsoNormal">Для наших теплиц мы используем квадратную трубу с заводским горячим оцинкованием, ее преимущество в том, что изделие окунают в ванну с расплавленным цинком температура которого не менее 460*с . Это позволяет обработать трубу не только снаружи, но и внутри, продлевая <strong>срок службы не менее чем на 50 лет</strong>. Запомните, заводское оцинкование, на сегодняшний день, наиболее надежное&nbsp;&nbsp;в условиях влажной среды,&nbsp;&nbsp;в отличии, от крашеных каркасов, которые в течении первых трех лет начинают разрушаться изнутри так как неустойчивы к агрессивной влажной среде и удобрениям, которые используют в тепличном хозяйстве</p>\r\n\r\n<p class="MsoNormal" style="text-align:left"><strong style="text-align:center">Теплица Премиум &quot;Славянка&quot;</strong></p>\r\n\r\n<p class="MsoNormal">&nbsp;Для этой теплицы используется накрытие поликарбонатом как Российского, так и Израильского производства с <strong>гарантией не менее 10 лет</strong>. Поликарбонат идеально подходит для такого вида конструкций, так как имеет воздушную прослойку, что позволяет тем самым сохранять тепло и аккумулируя ее внутри изделия. Различная толщина покрытия(4,6,8,10мм) даёт возможность полностью адаптировать Вашу теплицу под тот вид тепличного хозяйства, которым Вы решили заняться.&nbsp;</p>\r\n\r\n<p class="MsoNormal"><strong style="text-align:center">Теплица Эконом &quot;Славянка&quot;</strong></p>\r\n\r\n<p class="MsoNormal">Конструкция и комплектация&nbsp; теплицы эконом идентичная теплице Премиум. Отличия между материалом премиум и эконом, в его сроке службы. В зависимости от погодных условий и места установки накрытие прослужит около 5 лет. Этот вариант теплицы идеально подходит для тех, кто в первые, сталкивается с тепличным хозяйством.</p>\r\n\r\n<p class="MsoNormal"><strong style="text-align:center">Теплица под плёнку &quot;Славянка&quot;</strong></p>\r\n</div>\r\n\r\n<div>Этот вид теплиц создан для тех, у кого нет возможности заказать поликарбонатное накрытие, но при этом, теплица нужна уже сегодня. Плёночного накрытия хватит на несколько сезонов. В будущем, Вы сможете заказать накрытие класса премиум или эконом по <strong>индивидуальной цене</strong>, которая Вас приятно удивит.</div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div>С размерами, вариантами и стоимостью теплиц Вы можете ознакомиться <strong><a href="http://polikarbonatvs.com.ua/prices/">тут</a></strong></div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<p><strong>Нужно купить теплицу в Киеве или любом другом городе Украины? Звоните сейчас! Вас ждут лучшие&nbsp;цены! (050) 338-28-38.<br />\r\n<br />\r\n<em>Стоит отметить, что п</em></strong><em>родажа теплиц и монтаж осуществляются в короткие сроки квалифицированными специалистами имеющих большой опыт в </em><em><em><em>изготовлении и строительстве теплиц и парников</em>.</em><br />\r\n<br />\r\nТакже Вы можете собрать теплицу своими руками. Мы готовы помочь Вам в этом!</em></p>\r\n\r\n<h2>Теплица из сотового поликарбоната обладает значительными преимуществами перед парниками из стекла:</h2>\r\n\r\n<ol>\r\n	<li>Светопропускание двухслойной панели - 80%. Причем преобладающая часть световых лучей проходит в рассеянном виде.Свет, пропускаемый стеклом или однослойными листами других материалов, не рассеивается. Солнечные лучи, падающие на лист, практически не меняют своего направления по отношению к его плоскости. Отклонение лучей и изменение их направления ничтожно малы. В результате солнечные лучи попадают только на верхнюю часть растений, тогда как нижняя часть остается в тени. Полная освещенность растений очень важна, поскольку ее отсутствие приводит к заболеваниям растений и к их увяданию. У панелей ячеистой конструкции (верхний слой, нижний слой и ребра между ними) рассеивание света значительно выше. Солнечные лучи &quot;оседают&quot; на верхнем и нижнем листах и на ребрах и &quot;выходят&quot; из панели в разных направлениях. Лучи, проходящие через панель под разными углами, попадают на стены и другие поверхности, отражаются от них и доходят до всех частей растений.</li>\r\n	<li>&quot;Жесткие&quot; ультрафиолетовые лучи (диапазон менее 390 нм), которые являются наиболее разрушительными для растений, практически не проходят через панель. Пропускание лучей, благоприятных для растений - оптимально. Пропускание лучей, расположенных в крайней части инфракрасной зоны спектра (&quot;горячие лучи&quot; более 5000 нм), минимально, вследствие чего тепло, излучаемое внутренними объектами, такими как земля, оборудование и сами растения, остается внутри теплицы (&quot;эффект теплицы&quot;).</li>\r\n	<li>Теплоизоляция сотовых панелей почти в 3 раза лучше, чем у стекла. Например, теплопроводность панели сотового поликарбоната толщиной 8мм сравнима с теплопроводностью окна с двойным остеклением, а толщиной 16мм - с тройным.</li>\r\n	<li>Поликарбонатные панели пригодны к применению в диапазоне температур от -40 до +100 градусов. Данный диапазон температур поликарбонатные панели способны выдерживать в течение длительного времени. При кратковременном воздействии поликарбонат может выдержать и более низкие температуры.</li>\r\n	<li>Поликарбонат отличает высокая ударопрочность и поэтому теплице не страшны ни град, ни брошенный камень.</li>\r\n</ol>\r\n\r\n<ol>\r\n</ol>\r\n\r\n<ol>\r\n</ol>\r\n\r\n<h2>Теплицы из поликарбоната в Украине, прибыль и выгода дачных теплиц</h2>\r\n\r\n<p><strong>Теплица из поликарбоната</strong> является наилучшим выбором и наиболее оптимальна по соотношению &laquo;полученный результат/вложенные средства&raquo;. Существует такие сорта помидор, которые плодоносят в течении года.&nbsp;Урожайность от 40-60 кг&nbsp;с одного куста в месяц.&nbsp;(Шесть кустов&nbsp;размещаем&nbsp;в 6-метровой теплице. Теперь 40 кг умножаем на 6 кустов и&nbsp;получаем 240кг в мес. Теперь 240кг х 12 мес. получаем: 2880 кг, умножаем на 3 грн. получаем: 8640 грн. в год.)&nbsp; Все зависит от культуры выращивания и сорта. Всем известно, сколько сегодня оцениваются овощи: огурцы, помидоры, редиска петрушка, лук, базилик, капуста, щавель, клубника и т.д. Что уже говорить о экзотических&nbsp; культурах. Итого: теплица&nbsp;3х6&nbsp;дает возможность обеспечить себя&nbsp;и получать&nbsp;хорошую прибыль. Все зависит от того, по каким ценам вы собираетесь продавать.&nbsp; Можно не ограничиваться одной теплицей и&nbsp;поставить на своем участке несколько теплиц и&nbsp;таким образом увеличить&nbsp;прибыль от своих дачных теплиц. Для тех, кто хочет заниматься более серьезным тепличным бизнесом, предлагаем заказать у нас <strong><a href="/constructions/industrial-hothouses/" target="_self" title="Строительство промышленных теплиц">строительство промышленной теплицы</a></strong>,</p>\r\n\r\n<h4>Правила эксплуатации</h4>\r\n\r\n<p>Не разводите вблизи теплицы открытый огонь, хотя воспламенение этих листов значительно ниже, чем листов из стекловолокна или оргстекла. Отличительным свойством листов Polygal является их способность к замедленному возгоранию и отсутствие эмиссии ядовитых газов.Мыть листы поликарбоната можно водой или мягкими моющими растворами. Не забывайте, что наружная поверхность панели покрыта слоем, защищающим от ультрафиолетового излучения. Этот слой поглощает ультрафиолетовую часть солнечного спектра и обеспечивает постоянство механических и оптических свойств в течение многих лет. Поэтому не используйте абразивные и чистящие средства.</p>\r\n\r\n<p><span>В теплицах и парниках из поликарбоната постоянно поддерживается микроклимат, благоприятный для роста растений и увеличения урожая. Кроме того, теплицы из поликарбоната выдерживают все снеговые и ветровые нагрузки. Арочный парник&nbsp;и имеет очень эстетичный внешний вид.&nbsp;</span>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Мы доставим теплицу, парник&nbsp;в любую точку Украины.&nbsp; Цена на теплицы&nbsp;у Нас в Киеве низкая.&nbsp;Купить&nbsp;теплицу можно любых размеров: 3х4, 3х6, 3х8,&nbsp;4х6, 4х8, 4х10, 20, 30 и т.д. с добавлением секций в длину.&nbsp;Также можно заказать нестандартные теплицы под </strong><a href="/contacts/" target="_self" title="Заказать теплицу"><strong>заказ</strong></a><strong>.</strong></p>\r\n\r\n<p><br />\r\n<span>&bull; </span><a href="http://polikarbonatvs.com.ua/services/sale-hothouses/" target="_blank" title="Продажа теплиц и краткое описание"><span>продажа теплиц</span></a><span>&nbsp;</span><span>&nbsp; &bull; </span><a href="http://polikarbonatvs.com.ua/articles/article5.htm" target="_blank" title="Все о теплицах - статьи"><span>статьи о теплицах</span></a><span>&nbsp; &bull;&nbsp; <a href="http://polikarbonatvs.com.ua/ufiles/Image/Instrukciya.doc">скачать инструкцию по монтажу теплицы</a>&nbsp;(2.05 Мб)&nbsp;</span></p>\r\n\r\n<p><span class="bg1">Доставка, монтаж, сервис, гарантия 10 лет.</span></p>\r\n\r\n<h2>Какие преимущества и достоинства у наших теплиц?</h2>\r\n\r\n<p>Собственное&nbsp;изготовление теплиц дает нам возможность создавать продукт, который мы купили бы сами. Надежность и долговечность конструкции, использование новейших достижений в области покрытий из сотового поликарбоната, создание экономически выгодного продукта. Наш качественный подход к обслуживанию каждого нашего клиента. Все эти факторы дают нам возможность пообещать, что покупка теплиц из поликарбоната,&nbsp;на сайте: &nbsp;<em>&quot;Вишневый Сад&quot;</em> - это надежное, долговечное и правильное решение.<br />\r\n<br />\r\nНаш сайт &quot;Вишневый Сад&quot; предлагает Вам <strong>купить любые теплицы из поликарбоната</strong>, а также мы поможем осуществить монтаж любой приобретенной Вами теплицы. Теплицы из поликарбоната, которые предлагает купить Вам компания &quot;Алион Групп&quot; полностью удовлетворит Ваши требования к цене, качеству и внешнему виду изделия.<br />\r\n<br />\r\nВ последний год не только в Киеве но и Украине стало популярным здоровое питание, люди уже не хотят есть фастфуды, генно-модифицированные овощи и фрукты, и прочие продукты содержащие различные химические вкусовые добавки. Именно поэтому многие задумались о необходимости завести свой маленький участок, на котором можно выращивать домашние овощи и фрукты, и самим контролировать качество продуктов. Поэтому все чаще и чаще люди стали приобретать теплицы из поликарбоната, которые даже в зимнее время позволяют иметь свежие овощи и фрукты на своем столе.<br />\r\n<br />\r\nТакже <strong>теплицы из поликарбоната</strong> идеально подходят для выращивания рассады различных овощных и цветочных культур.<br />\r\n<br />\r\nПоликарбонатные теплицы&nbsp;обладают значительными преимуществами перед теплицами из стекла, и тем более перед теплицами из недолговечного полиэтилена:<br />\r\nВо-первых, теплицы из поликарбоната пропускают 80% солнечных лучей, при этом большая часть из них падает рассеянным светом. Лучи, пропускаемые через стекло или полиэтилен не рассеиваются.<br />\r\n<br />\r\nВо-вторых - теплоизоляция у теплиц из поликарбоната в три раза лучше, чем у теплиц из стекла. Теплицы из поликарбоната &quot;работают&quot; в широком диапазоне температур: от -40 до +100 градусов! Указанные температуры теплицы выдерживают очень долгое время, а при кратковременном воздействии, поликарбонат может выдержать и более низкие температуры.<br />\r\nВ-третьих - ударопрочность теплиц из поликарбоната значительно выше. Такие теплицы легко выдерживают удары града, и даже выдержат удар от брошенного камня.<br />\r\nТеплицы из поликарбоната можно использовать не только для домашних нужд, а также для ведения собственного бизнеса. Если Вы хотите выращивать и продавать рассаду, овощи, фрукты, цветы - Вам не обойтись без таких теплиц. Естественно, чем больше теплиц Вы сможете обработать, тем больший оборот получите.</p>\r\n\r\n<h2>Какие цены на теплицы мы предлагаем?</h2>\r\n\r\n<p>&nbsp;<strong>Цены на теплицы из поликарбоната</strong> у нас низкие, скачайте <a href="/prices" target="_parent" title="Цены на теплицы, прайсы">прайс</a> смотрите и сравнивайте сами. Звоните нашим специалистам, мы поможем подобрать Вам нужные размеры и форму теплиц, установим теплицу, и даже, сможем изготовить для нее фундамент. Теплицы из поликарбоната - это высокое качество и низкая цена - что может быть лучше и выгоднее? Звоните, и заказывайте! (044) 22-333-80</p>\r\n\r\n<div class="hd1 hdb">\r\n<h3>Статьи по теме</h3>\r\n</div>\r\n\r\n<div class="nb1">\r\n<ul class="t21">\r\n	<li><a href="/articles/article13.htm">Выращивание томатов (помидоры)</a></li>\r\n	<li><a href="/articles/article14.htm">Вентиляция, влажность воздуха в теплице. автоматика проветривания</a></li>\r\n	<li><a href="/articles/article16.htm">Как выбрать теплицу из поликарбоната?</a></li>\r\n	<li><a href="/articles/article18.htm">Поликарбонат цены и размеры</a></li>\r\n	<li><a href="/articles/article17.htm">Как подготовить теплицу к зиме?</a></li>\r\n	<li><a href="/articles/article19.htm">Размеры листов поликарбоната. Формирование цены</a></li>\r\n	<li><a href="/articles/article9.htm">Освещение в теплицах: роскошь или необходимость?</a></li>\r\n	<li><a href="/articles/article21.htm">Прозрачный сотовый поликарбонат - область использования</a></li>\r\n	<li><a href="/articles/article6.htm">Отопление теплиц</a></li>\r\n</ul>\r\n\r\n<ul class="t21">\r\n	<li><a href="/articles/article20.htm">Поликарбонат цена и размеры листа, область применения</a></li>\r\n	<li><a href="/articles/article11.htm">Уникальная теплица-термос из поликарбоната</a></li>\r\n	<li><a href="/articles/article12.htm">Как выбрать поликарбонат по качеству и цене?</a></li>\r\n	<li><a href="/articles/article8.htm">О поликарбонате</a></li>\r\n	<li><a href="/articles/article10.htm">Затенение в теплицах</a></li>\r\n	<li><a href="/articles/article3.htm">Применение прозрачной кровли</a></li>\r\n	<li><a href="/articles/article7.htm">Основные формы теплиц</a></li>\r\n	<li><a href="/articles/article5.htm">Статья о поликарбонатных теплицах</a></li>\r\n	<li><a href="/articles/article4.htm">Развитие поликарбонатного рынка в Украине</a></li>\r\n</ul>\r\n</div>\r\n', 1, NULL, NULL, 1466346368, 1466346904),
(15, 15, 1, 2, 0, 'news_list_page', 'Новости компании', 'news', '', 1, NULL, NULL, 1466415441, 1466505774),
(16, 16, 1, 2, 0, 'articles_list_page', 'Статьи', 'articles', '', 1, NULL, NULL, 1466505965, 1466505965),
(18, 18, 1, 2, 0, 'search_page', 'Поиск', 'search', '', 1, 7, 1, 1466589563, 1466679257);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_page_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_page_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `tbl_page_meta`
--

INSERT INTO `tbl_page_meta` (`id`, `entity_id`, `meta_title`, `meta_keywords`, `meta_description`, `meta_robots`, `custom_meta`) VALUES
(1, 1, 'Polyside поликарбонат, (Полисайд), Plastilux (Пластилюкс) - и другая прозрачная кровля в Киеве - компания Вишневый сад', 'прозрачная кровля, листовой пластик, поликарбонат, светопрозрачные конструкции, изготовление, производство, монтаж, строительство', 'Продажа прозрачной кровли: Поликарбонат Алексдорф, Пластилюкс, Полигаль, Полисайд в Киеве и по Украине. Самые низкие цены! Изготовление светопрозрачных конструкций, прозрачной кровли, продажа листового пластика - Вишневый Сад', '', ''),
(2, 2, '', '', '', '', ''),
(3, 3, '', '', '', '', ''),
(12, 12, '', '', '', '', ''),
(13, 13, '', '', '', '', ''),
(14, 14, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE IF NOT EXISTS `tbl_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `quantity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
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

INSERT INTO `tbl_products` (`id`, `sku`, `title`, `slug`, `short_description`, `description`, `quantity`, `price`, `status`, `brand_id`, `type_id`, `available_on`, `created_at`, `updated_at`) VALUES
(1, '252', 'Отливы пластиковые', 'product246.htm', 'Отливы пвх бесшумны, надежны в условиях перепадов температур и устойчивы к загрязнению. Пластиковые отливы устойчивы к воздействию ультрафиолетового излучения и агрессивных сред, не подвержены коррозии.', '<h4>Описание Отливы пластиковые</h4>\r\n\r\n<h3>Защита для окон&nbsp;- отливы ПВХ пластиковые!</h3>\r\n\r\n<p><span class="apple-style-span"><span style="color:#3e3f3f">Если Вы установили пластиковые окна, то <strong>пластиковые отливы</strong> &ndash; оптимальное решение проблемы гидроизоляции внешнего монтажного зазора между окном и стеной. А единство используемого материала придаст оконному проему завершенный вид. <strong>Отливы ПВХ</strong> устойчевы к перепадам температур. </span></span></p>\r\n\r\n<p><span class="bl1">Продажа: пластиковые отливы купить&nbsp;для окон можно качественные по доступной цене в Киеве.<br />\r\nКупить отливы ПВХ по тел: (044) 223-33-80, (067) 410-410-0</span></p>\r\n', '', 16.80, 1, NULL, NULL, 0, 1466500204, 1466504495),
(2, '251', 'Откосы пластиковые', 'product245.htm', 'Естественно, при установке красивого окна, оконные откосы должны выглядеть так же красиво и, как принято говорить, дополнять вид окна. При дальнейшей эксплуатации поверхность откосов не должна терять свой первоначальный вид, т.е. не должна трескаться, отслаиваться, менять свой цвет, царапаться, промокать. ', '<h4>Описание Откосы пластиковые</h4>\r\n\r\n<p><strong>Пластиковые откосы ПВХ</strong> отличается от гипсокартона прежде всего своим внешним видом, т.е. красивой ровной и гладкой поверхностью. Они как одно целое с пластиковыми оконными ПВХ-профилями! <strong>Пластики ПВХ</strong> характеризуются отличной влагостойкостью, пароизоляцией, химической стойкостью, а вспененные пластики дополнительно обладают хорошей теплоизоляцией. Это во многих случаях сразу решает проблему появления конденсата на откосах! Для большинства белых листовых пластиков ПВХ характерна хорошая стойкость к ультрафиолету. <strong>Откосы пластиковые ПВХ </strong>решат Вашу проблему.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><span class="bl1">&bull; Предлагаем откосы пластиковые купить в Киеве недорого.<br />\r\n&bull; Пластиковые откосы цена ПВХ продажа по тел: (044) 223-33-80, (067) 410-410-0</span></p>\r\n\r\n<p>&nbsp;</p>\r\n', '', 43.60, 1, NULL, NULL, 0, 1466500305, 1466504376);

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

--
-- Dumping data for table `tbl_products_categories`
--

INSERT INTO `tbl_products_categories` (`product_id`, `category_id`) VALUES
(2, 2),
(1, 2);

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
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `template_id` int(11) DEFAULT NULL,
  `facet_set_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft_rgt` (`lft`,`rgt`),
  KEY `depth` (`depth`),
  KEY `tree` (`tree`),
  KEY `name` (`name`),
  KEY `FK_product_categories_image_id` (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_product_categories`
--

INSERT INTO `tbl_product_categories` (`id`, `tree`, `lft`, `rgt`, `depth`, `name`, `slug`, `description`, `content`, `image_id`, `status`, `template_id`, `facet_set_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 10, 0, 'Каталог', 'catalog', '', '', NULL, 1, 6, NULL, 1466350299, 1466505284),
(2, NULL, 2, 3, 1, 'Подоконники', 'category34.htm', 'Все для окон: подоконники премиум класса, пластиковые отливы ПВХ, пластиковые откосы ПВХ, подоконники Данке качественные.', '<p>Современные пластиковые окна устанавливают в любое помещение: офис, дом, квартиру. Для оформления пластиковых окон внутри помещений используют <strong>пластиковые подоконники</strong>. Они органично вписываются в современные интерьеры и не акцентируют на себе внимание.<br />\r\n<br />\r\n<span class="cat_descr"><strong>Хотим познакомить вас с совершенно новый, не имеющий аналогов, пластиковый подоконник торговой марки &laquo;Данке&raquo;.</strong></span><br />\r\n<span class="cat_descr">Благодаря своим уникальным характеристикам и прочности <strong>подоконник ТМ &quot;Данке&quot;&nbsp;</strong>успешно используется в качестве рабочих поверхностей кухонь.<br />\r\n<br />\r\n<strong>Качество не уступает граниту, камню, мрамору и другим сверхтвердым материалам!<br />\r\n<br />\r\nВнешний вид подоконника имитирует камень, дерево, мрамор, гранит и другие материалы.</strong><br />\r\n<br />\r\n<span class="bl2">Время массивных и громоздких подоконников давно прошло и на смену им пришли современные легкие и изящные изделия из пластика.</span><br />\r\nТакие изделия менее подвержены термальной деформации и исключают рассыхание материала, а также не требуют дополнительного покрытия и устойчивы к воздействию прямых солнечных лучей.<br />\r\n<br />\r\n<strong>Материал</strong><br />\r\nЭкологически чистый материал для изготовления пластиковых подоконников оказывает высокое сопротивление химически активным веществам и горению. Состав материала не предусматривает вредных добавок и обеспечивает устойчивость к различным воздействиям.<br />\r\n<br />\r\n<strong>Монтаж и эксплуатация</strong><br />\r\nМонтаж такого изделия достаточно легкий и исключает стыки и швы. Еще одним преимуществом подоконников является легкий уход: уборка влажной тканью или губкой.<br />\r\nЕсли нет возможности установить подоконник сразу после покупки, их хранят на ровной горизонтальной поверхности, что позволяет избежать изгибов. Опасны для пластиковых подоконников могут быть удары и открытый огонь.<br />\r\n<br />\r\n<strong>Срок службы и другие характеристики</strong><br />\r\nПравильно установленные пластиковые подоконники могут прослужить не одно десятилетие при достаточно неприхотливом уходе. Они значительно превосходят свои деревянные аналоги по эксплуатационным характеристикам и намного дешевле обходятся при покупке.&nbsp;<br />\r\n<br />\r\nПроизводители из Германии&nbsp;позаботились о широком выборе качественных пластиковых подоконников. Их установка не вызывает каких-либо затруднений. Кроме того, их эстетичный вид позволяет сделать интерьер интересным и современным.</span></p>\r\n', 2, 1, NULL, NULL, 1466350610, 1466505030),
(3, NULL, 4, 9, 1, 'Фасадные материалы', 'category35.htm', 'Все для утепления фасада: Пенопласт, смеси для утепления фасада, смеси для декоративной штукатурки, смеси для склеивания пенопласта. Штукатурка акриловая, структурная и силиконовая. Армировочная сетка штукатурная, термодюбели, эмульсии грунтовка, краски фасадные.', '<p>Фасадные материалы</p>\r\n\r\n<p>Компания АЛИОН ГРУПП предлагает в широком ассортимете материалы для утепления фасадов, стен в Киеве и других городах Украины. Наши утеплители производятся из высококачественных материалов.&nbsp;</p>\r\n\r\n<p>Утеплители помогут вам провести работы по наружному утеплению. Также в ассортименте широкий спектр отделочных материалов, фасадных красок, декоративных штукатурок, грунтовок, комплектующих для проведения работ по утеплению стены и фасада.</p>\r\n\r\n<p><strong>О наружном утеплении фасадов</strong></p>\r\n\r\n<p><em>В последнее время&nbsp;в Украине&nbsp;все более популярным становится наружное утепление фасадов. Это не удивительно,поскольку постоянно дорожают энергоресурсы. Конечно наиболее актуальным является утепление современных домов,так как в этих домах стоят счетчики тепла.<br />\r\n<br />\r\nНо и в старых зданиях этот метод довольно эффективен. Несомненными плюсами наружного утепления является его универсальность,зимой держит тепло,летом-прохладу,так же, в отличии от внутренних систем утепления,не занимает внутреннее пространство помещения,и является экологически чистым.<br />\r\n<br />\r\nПри качественном монтаже стоимость может составлять от $20 до $35 за один квадратный метр. Но если вы зимой активно пользуетесь отопительными электроприборами,а летом кондиционером,то эти деньги окупятся в течении 1-2 сезонов.<br />\r\n<br />\r\nВ основе технологии лежат пенополистирольные плиты которые крепятся непосредственно на стену,с помощью специального клея.Затем наносится и армируется слой водоотталкивающей штукатурки,обрабатывается грунтовкой и красится. Монтаж 30-40 квадратных метров (средняя двухкомнатная квартира)занимает около двух-трех дней. Срок эксплуатации при правильно соблюденной технологии монтажа составляет до 25 лет.</em></p>\r\n', 1, 1, NULL, NULL, 1466350653, 1466504947),
(4, NULL, 5, 6, 2, 'Пенопласт (пенополистирол)', 'penoplast-penopolistirol', '', '', 3, 1, NULL, NULL, 1466505088, 1466505525),
(5, NULL, 7, 8, 2, 'Смеси для утепления', 'category37.htm', '', '', 4, 1, NULL, NULL, 1466505131, 1466505539);

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_product_category_files`
--

INSERT INTO `tbl_product_category_files` (`id`, `filesystem`, `path`, `title`, `size`, `mime_type`, `created_at`, `updated_at`) VALUES
(1, 'local', '/categories/category35.htm.jpg', '', 10939, 'image/jpeg', 1466504947, 1466504947),
(2, 'local', '/categories/category34.htm.jpg', '', 6409, 'image/jpeg', 1466505030, 1466505030),
(3, 'local', '/categories/penoplast-penopolistirol.jpg', '', 2642, 'image/jpeg', 1466505525, 1466505525),
(4, 'local', '/categories/category37.htm.jpg', '', 11478, 'image/jpeg', 1466505539, 1466505539);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_category_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_product_category_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_category_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_product_category_meta`
--

INSERT INTO `tbl_product_category_meta` (`id`, `entity_id`, `meta_title`, `meta_keywords`, `meta_description`, `meta_robots`, `custom_meta`) VALUES
(1, 1, '', '', '', '', ''),
(2, 2, 'Подоконики для окон, отливы, откосы, пластики ПВХ - Вишневый Сад', 'Подоконники', 'Все для окон магазин откосов, пвх, отливов, подокоников в Киеве по скандально низким ценам!!!', '', ''),
(3, 3, '', '', '', '', ''),
(4, 4, '', '', '', '', ''),
(5, 5, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_files`
--

CREATE TABLE IF NOT EXISTS `tbl_product_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `attribute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `filesystem` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_files_product_id` (`product_id`),
  KEY `attribute` (`attribute`),
  KEY `sort` (`sort`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_product_files`
--

INSERT INTO `tbl_product_files` (`id`, `product_id`, `attribute`, `filesystem`, `path`, `title`, `size`, `mime_type`, `created_at`, `updated_at`, `sort`, `type`) VALUES
(1, 2, 'images', 'local', '/products/2/product245.htm.jpg', 'Украина Откосы пластиковые', 8378, 'image/jpeg', 1466504175, 1466504377, NULL, 1),
(2, 2, 'images', 'local', '/products/2/product245.htm.png', '', 29214, 'image/png', 1466504377, 1466504377, NULL, 0),
(3, 1, 'images', 'local', '/products/1/product246.htm.jpg', 'Украина Отливы пластиковые', 13115, 'image/jpeg', 1466504458, 1466504495, NULL, 1),
(4, 1, 'images', 'local', '/products/1/product246.htm.jpeg', '', 4804, 'image/jpeg', 1466504495, 1466504495, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_meta`
--

CREATE TABLE IF NOT EXISTS `tbl_product_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `meta_title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `meta_robots` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `custom_meta` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_meta_entity_id` (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_product_meta`
--

INSERT INTO `tbl_product_meta` (`id`, `entity_id`, `meta_title`, `meta_keywords`, `meta_description`, `meta_robots`, `custom_meta`) VALUES
(1, 2, 'Откосы ПВХ пластиковые откосы купить в Киеве продажа', 'Откосы, пвх, пластик, купить, продажа, пластики пвх, теплоизоляция', 'Оптово розничная продажа откосов ПВХ. Пластик ПВХ купить у нас выгодно.', '', ''),
(4, 1, 'Отливы ПВХ | Пластиковые отливы купить Киев | Отливы цена', 'Отливы пластиковые', 'Продажа пластиковых отливов по доступной цене в Киеве. Лучшая цена Отливы ПВХ. Качественные отливы пластиковые купить (044) 223-36-07', '', '');

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
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `site_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `site` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creator` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meta_id` (`meta_id`),
  KEY `meta_type` (`meta_type`),
  KEY `social_type` (`social_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=42 ;

--
-- Dumping data for table `tbl_social_meta`
--

INSERT INTO `tbl_social_meta` (`id`, `meta_id`, `meta_type`, `social_type`, `title`, `type`, `url`, `image`, `description`, `site_name`, `video`, `card`, `site`, `creator`) VALUES
(1, 1, 'page_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(2, 1, 'page_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(3, 2, 'page_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(4, 2, 'page_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(5, 3, 'page_meta', 'open_graph', '', '', '', '', '', '', '', '', '', ''),
(6, 3, 'page_meta', 'twitter_card', '', '', '', '', '', '', '', '', '', ''),
(7, 1, 'article_meta', 'open_graph', 'sdfsd', 'sdfsdf', 'sdf', 'sdfsd', 'sdfsdfsdf', 'sdfsdf', 'sdfsdfsdf', '', '', ''),
(8, 1, 'article_meta', 'twitter_card', 'sdfsdf', '', '', 'sdfsdfsfd', 'sdsds', '', '', 'sdfsd', 'fsdfsdf', 'sfsdsdf'),
(15, 5, 'article_meta', 'open_graph', 'ertre', 'tretrt', 'rtert', 'ertr', 'etert', 'ret', 'ertert', '', '', ''),
(16, 5, 'article_meta', 'twitter_card', 'ertret', '', '', 'erttr', 'ertet', '', '', 'reter', 'tretret', 'retre'),
(18, 12, 'page_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(19, 12, 'page_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(20, 13, 'page_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(21, 13, 'page_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(22, 14, 'page_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(23, 14, 'page_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(24, 1, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(25, 1, 'product_category_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(26, 2, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(27, 2, 'product_category_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(28, 3, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(29, 3, 'product_category_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(30, 1, 'product_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(31, 1, 'product_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(32, 2, 'product_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(33, 2, 'product_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(34, 3, 'product_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(35, 3, 'product_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(36, 4, 'product_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(37, 4, 'product_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(38, 4, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(39, 4, 'product_category_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', ''),
(40, 5, 'product_category_meta', 'open_graph', '', '', '', '', '', '', '', NULL, NULL, NULL),
(41, 5, 'product_category_meta', 'twitter_card', '', NULL, NULL, '', '', NULL, NULL, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_templates`
--

CREATE TABLE IF NOT EXISTS `tbl_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `layout_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `default` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `layout_id` (`layout_id`),
  KEY `default` (`default`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tbl_templates`
--

INSERT INTO `tbl_templates` (`id`, `name`, `layout_id`, `default`) VALUES
(5, 'Home page template', 'main', 0),
(6, 'Catalog template', 'main', 0),
(7, 'Search page template', 'main', 0);

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
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `banner_id` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `display_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tbl_widgets`
--

INSERT INTO `tbl_widgets` (`id`, `widget_type`, `title`, `content`, `banner_id`, `depth`, `display_count`) VALUES
(3, 'last_news', NULL, NULL, NULL, NULL, 10),
(4, 'last_articles', NULL, NULL, NULL, NULL, 20),
(6, 'content', 'Мы предлагаем', '<ul class="submenu">\r\n	<li><a href="/akril/">Акрил</a></li>\r\n	<li><a href="/gazobeton/">Газобетон</a></li>\r\n	<li><a href="/constructions/metal/">Металлоконструкции</a></li>\r\n	<li><a href="/constructions/krovlya/">Навесы из поликарбоната</a></li>\r\n	<li><a href="/belorusskij-polikarbonat/">Белорусский поликарбонат</a></li>\r\n	<li><a href="/uteplenie-fasada-penoplastom/">Системы утепления фасада</a></li>\r\n	<li><a href="/constructions/hothouses/">Теплицы</a></li>\r\n	<li><a href="/constructions/industrial-hothouses/">Теплицы промышленные</a></li>\r\n</ul>\r\n', NULL, NULL, NULL),
(7, 'categories', 'Каталог товаров', NULL, NULL, NULL, NULL),
(8, 'facets', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_widget_areas`
--

CREATE TABLE IF NOT EXISTS `tbl_widget_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
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
-- Dumping data for table `tbl_widget_areas`
--

INSERT INTO `tbl_widget_areas` (`id`, `code`, `template_id`, `owner_id`, `owner_type`, `display`, `created_at`, `updated_at`) VALUES
(4, 'bottom', 5, NULL, NULL, 3, 1466269834, 1466357540),
(5, 'sidebar', 5, NULL, NULL, 3, 1466269834, 1466357541),
(6, 'bottom', 6, NULL, NULL, 3, 1466505197, 1466506991),
(7, 'sidebar', 6, NULL, NULL, 3, 1466505197, 1466506991),
(8, 'bottom', 7, NULL, NULL, 3, 1466668428, 1466668428),
(9, 'sidebar', 7, NULL, NULL, 3, 1466668428, 1466668428);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_widget_area_widgets`
--

CREATE TABLE IF NOT EXISTS `tbl_widget_area_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_id` int(11) NOT NULL,
  `widget_area_id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_widget_area_widgets_widget_id` (`widget_id`),
  KEY `FK_widget_area_widgets_widget_area_id` (`widget_area_id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Dumping data for table `tbl_widget_area_widgets`
--

INSERT INTO `tbl_widget_area_widgets` (`id`, `widget_id`, `widget_area_id`, `owner_id`, `owner_type`, `sort`) VALUES
(16, 3, 4, NULL, NULL, 1),
(17, 4, 4, NULL, NULL, 2),
(18, 6, 5, NULL, NULL, 1),
(23, 7, 7, NULL, NULL, 1),
(24, 8, 9, NULL, NULL, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_articles`
--
ALTER TABLE `tbl_articles`
  ADD CONSTRAINT `FK_articles_image_id` FOREIGN KEY (`image_id`) REFERENCES `tbl_article_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_articles_video_id` FOREIGN KEY (`video_id`) REFERENCES `tbl_article_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD CONSTRAINT `FK_categories_image_id` FOREIGN KEY (`image_id`) REFERENCES `tbl_category_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_category_meta`
--
ALTER TABLE `tbl_category_meta`
  ADD CONSTRAINT `FK_category_meta_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_eav_entity_values`
--
ALTER TABLE `tbl_eav_entity_values`
  ADD CONSTRAINT `FK_eav_entity_values_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_eav_entity_values_value_id` FOREIGN KEY (`value_id`) REFERENCES `tbl_eav_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_eav_product_values`
--
ALTER TABLE `tbl_eav_product_values`
  ADD CONSTRAINT `FK_eav_product_values_attribute_id` FOREIGN KEY (`attribute_id`) REFERENCES `tbl_eav_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_eav_product_values_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_eav_product_values_value_id` FOREIGN KEY (`value_id`) REFERENCES `tbl_eav_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `tbl_gallery_items`
--
ALTER TABLE `tbl_gallery_items`
  ADD CONSTRAINT `FK_gallery_items_gallery_id` FOREIGN KEY (`gallery_id`) REFERENCES `tbl_galleries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_menu_items`
--
ALTER TABLE `tbl_menu_items`
  ADD CONSTRAINT `FK_menu_items_active_icon_id` FOREIGN KEY (`active_icon_id`) REFERENCES `tbl_menu_item_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_menu_items_icon_id` FOREIGN KEY (`icon_id`) REFERENCES `tbl_menu_item_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_menu_items_menu_id` FOREIGN KEY (`menu_id`) REFERENCES `tbl_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_menu_items_video_id` FOREIGN KEY (`video_id`) REFERENCES `tbl_menu_item_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_news`
--
ALTER TABLE `tbl_news`
  ADD CONSTRAINT `FK_news_image_id` FOREIGN KEY (`image_id`) REFERENCES `tbl_article_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_news_video_id` FOREIGN KEY (`video_id`) REFERENCES `tbl_article_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
-- Constraints for table `tbl_product_categories`
--
ALTER TABLE `tbl_product_categories`
  ADD CONSTRAINT `FK_product_categories_image_id` FOREIGN KEY (`image_id`) REFERENCES `tbl_product_category_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_category_meta`
--
ALTER TABLE `tbl_product_category_meta`
  ADD CONSTRAINT `FK_product_category_meta_entity_id` FOREIGN KEY (`entity_id`) REFERENCES `tbl_product_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product_files`
--
ALTER TABLE `tbl_product_files`
  ADD CONSTRAINT `FK_product_files_product_id` FOREIGN KEY (`product_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
