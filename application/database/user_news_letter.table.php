<?php
$_TABLE['create'] ="CREATE TABLE `user_news_letter` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(50) collate utf8_unicode_ci NOT NULL,
  `i_p` varchar(15) collate utf8_unicode_ci NOT NULL,
  `user_agent` varchar(200) collate utf8_unicode_ci NOT NULL,
  `date_inserted` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `Email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$_TABLE['field'] = array("id","email","i_p","user_agent","date_inserted");
?>