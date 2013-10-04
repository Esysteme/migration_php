<?php
$_TABLE['create'] ="CREATE TABLE `species_picture_info` (
  `id` int(11) NOT NULL auto_increment,
  `libelle` varchar(35) NOT NULL,
  `type` int(11) NOT NULL,
  `detail` varchar(80) NOT NULL,
  `cf_order` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","libelle","type","detail","cf_order");
?>