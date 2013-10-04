<?php
$_TABLE['create'] ="CREATE TABLE `species_action` (
  `id` int(11) NOT NULL auto_increment,
  `id_gliale_table` int(30) NOT NULL,
  `cf_line` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `id_species_action_type` int(11) NOT NULL,
  `param` text NOT NULL,
  `date` datetime NOT NULL,
  `cf_etat` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_gliale_table","cf_line","id_user_main","id_species_action_type","param","date","cf_etat","point");
?>