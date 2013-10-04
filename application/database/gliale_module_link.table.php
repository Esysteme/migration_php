<?php
$_TABLE['create'] ="CREATE TABLE `gliale_module_link` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL,
  `id_gliale_module_main` int(11) NOT NULL,
  `link_php` varchar(255) NOT NULL,
  `link_rewrited` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_parent","id_gliale_module_main","link_php","link_rewrited","is_active");
?>