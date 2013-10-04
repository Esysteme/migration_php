<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class articles_header extends Model
{

	var $schema = "CREATE TABLE `articles_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_articles_main` int(11) NOT NULL,
  `id_user_main` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` longtext,
  `etat` int(11) DEFAULT NULL,
  `reads` int(11) DEFAULT NULL,
  `comment` int(11) NOT NULL,
  `id_lang` char(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_articles_main` (`id_articles_main`),
  CONSTRAINT `fk_id_articles_main` FOREIGN KEY (`id_articles_main`) REFERENCES `articles_main` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_articles_main", "id_user_main", "date", "time", "title", "message", "etat", "reads", "comment", "id_lang");
	var $validate = array(
		'id_articles_main' => array(
			'reference_to' => array('The constraint to articles_main.id isn\'t respected.', 'articles_main', 'id')
		),
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'date' => array(
			'date' => array('This must be a date.')
		),
		'time' => array(
			'time' => array('This must be a time.')
		),
		'title' => array(
			'not_empty' => array('This field is requiered.')
		),
		'message' => array(
			'not_empty' => array('This field is requiered.')
		),
		'etat' => array(
			'numeric' => array('This must be an int.')
		),
		'reads' => array(
			'numeric' => array('This must be an int.')
		),
		'comment' => array(
			'numeric' => array('This must be an int.')
		),
		'id_lang' => array(
			'reference_to' => array('The constraint to lang.id isn\'t respected.', 'lang', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
