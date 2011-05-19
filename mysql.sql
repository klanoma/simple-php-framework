SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `browser_title` varchar(255) NOT NULL,
  `nav_title` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `intro_content` text,
  `content` text,
  `meta_keywords` text,
  `meta_description` text,
  `visible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL,
  `template` varchar(255) NOT NULL DEFAULT 'generic',
  `parent_id` bigint(20) DEFAULT NULL,
  `admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `redirect` varchar(255) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `browser_title`, `nav_title`, `title`, `url`, `intro_content`, `content`, `meta_keywords`, `meta_description`, `visible`, `sort`, `template`, `parent_id`, `admin`, `redirect`, `modified`) VALUES
(1, 'Home', 'Home', 'Home', 'home', '', '', '', '', 0, 1, 'home', NULL, 0, NULL, '2011-03-05 02:38:45'),
(2, '404', '404', '404', '404', '404', '', NULL, NULL, 0, 2, 'generic', NULL, 0, NULL, '2011-01-17 22:29:45'),
(3, 'About Us', 'About Us', 'About Us', 'about', '<p>copy</p>', '', NULL, NULL, 1, 2, 'generic', NULL, 0, NULL, '2011-03-05 02:39:18'),
(4, 'Contact Us', 'Contact Us', 'Contact Us', 'contact', '', '', NULL, NULL, 1, 3, 'generic', NULL, 0, NULL, '2011-03-05 02:39:42');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `data` text NOT NULL,
  `updated_on` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `data`, `updated_on`) VALUES
(1, '', 1299318441);

-- --------------------------------------------------------

--
-- Table structure for table `url_cache`
--

DROP TABLE IF EXISTS `url_cache`;
CREATE TABLE IF NOT EXISTS `url_cache` (
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dt_refreshed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dt_expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `url_cache`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nid` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(65) NOT NULL DEFAULT '',
  `password` varchar(65) NOT NULL DEFAULT '',
  `level` enum('user','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--

