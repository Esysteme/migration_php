<?php
$_TABLE['create'] ="CREATE TABLE `articles_header` (
  `id` int(11) NOT NULL auto_increment,
  `id_articles_main` int(11) NOT NULL,
  `id_user_main` int(11) default NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `title` varchar(255) default NULL,
  `message` longtext,
  `etat` int(11) default NULL,
  `reads` int(11) default NULL,
  `comment` int(11) NOT NULL,
  `id_lang` char(5) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_id_articles_main` (`id_articles_main`),
  CONSTRAINT `fk_id_articles_main` FOREIGN KEY (`id_articles_main`) REFERENCES `articles_main` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_articles_main","id_user_main","date","time","title","message","etat","reads","comment","id_lang");
?>