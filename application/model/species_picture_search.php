<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_picture_search extends Model
{

	var $schema = "CREATE TABLE `species_picture_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_main` int(11) NOT NULL,
  `tag_search` varchar(100) NOT NULL,
  `language` char(5) NOT NULL,
  `total_found` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_species_main` (`id_species_main`),
  KEY `id_user_main` (`id_user_main`),
  CONSTRAINT `species_picture_search_ibfk_1` FOREIGN KEY (`id_species_main`) REFERENCES `species_main` (`id`),
  CONSTRAINT `species_picture_search_ibfk_2` FOREIGN KEY (`id_user_main`) REFERENCES `user_main` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_main", "tag_search", "language", "total_found", "id_user_main", "date");
	var $validate = array(
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'tag_search' => array(
			'not_empty' => array('This field is requiered.')
		),
		'language' => array(
			'not_empty' => array('This field is requiered.')
		),
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}

/*

chargeur telephone
pc
*/