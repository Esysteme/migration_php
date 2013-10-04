<?php
$_TABLE['create'] ="CREATE TABLE `user_group_main` (
  `id` int(11) NOT NULL auto_increment,
  `id_user_group_type` int(11) NOT NULL,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `t_a_g` char(10) collate utf8_unicode_ci NOT NULL,
  `country` char(2) collate utf8_unicode_ci NOT NULL,
  `logo` varchar(50) collate utf8_unicode_ci NOT NULL,
  `u_r_l` varchar(200) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$_TABLE['field'] = array("id","id_user_group_type","name","t_a_g","country","logo","u_r_l");
?>