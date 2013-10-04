<?php
$_TABLE['create'] ="CREATE TABLE `articles_type` (
  `id` int(11) NOT NULL auto_increment,
  `image` varchar(64) NOT NULL,
  `libelle` varchar(64) NOT NULL,
  `etat` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","image","libelle","etat");
?>