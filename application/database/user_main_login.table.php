<?php
$_TABLE['create'] ="CREATE TABLE `user_main_login` (
  `id` int(11) NOT NULL auto_increment,
  `id_user_main` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `ip` char(15) collate utf8_unicode_ci NOT NULL,
  `user_agent` varchar(250) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$_TABLE['field'] = array("id","id_user_main","date","ip","user_agent");
?>