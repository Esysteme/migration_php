<?php
$_TABLE['create'] ="CREATE TABLE `articles_main` (
  `id` int(11) NOT NULL auto_increment,
  `etat` int(11) default NULL,
  `id_cat` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `id_lang` char(5) NOT NULL,
  `date_posted` datetime NOT NULL,
  `date_validated` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","etat","id_cat","id_module","id_type","id_lang","date_posted","date_validated");
?>