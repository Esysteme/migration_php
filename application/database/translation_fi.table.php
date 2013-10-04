<?php
$_TABLE['create'] ="CREATE TABLE `translation_fi` (
  `id` int(11) NOT NULL auto_increment,
  `key` char(40) NOT NULL,
  `source` char(2) NOT NULL,
  `text` text NOT NULL,
  `date_inserted` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `translate_auto` int(11) NOT NULL,
  `file_found` varchar(255) NOT NULL,
  `line_found` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `Key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","key","source","text","date_inserted","date_updated","translate_auto","file_found","line_found");
?>