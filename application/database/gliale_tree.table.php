<?php
$_TABLE['create'] ="CREATE TABLE `gliale_tree` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL,
  `cf_link` varchar(50) collate utf8_unicode_ci NOT NULL,
  `cf_order` int(11) NOT NULL,
  `module_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `script_name` varchar(150) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$_TABLE['field'] = array("id","id_parent","cf_link","cf_order","module_name","script_name");
?>