<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class link__species_authorities__species_main extends Model
{

	var $schema = "CREATE TABLE `link__species_authorities__species_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_authorities` int(11) NOT NULL,
  `id_species_main` int(11) NOT NULL,
  `id_scientific_name_translation__common_name` int(11) NOT NULL,
  `id_scientific_name_translation__scientific_name` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_species_authorities` (`id_species_authorities`,`id_species_main`),
  KEY `id_scientific_name_translation__scientific_name` (`id_scientific_name_translation__scientific_name`),
  KEY `id_scientific_name_translation__common_name` (`id_scientific_name_translation__common_name`),
  KEY `id_species_main` (`id_species_main`),
  CONSTRAINT `link__species_authorities__species_main_ibfk_4` FOREIGN KEY (`id_species_main`) REFERENCES `species_main` (`id`),
  CONSTRAINT `link__species_authorities__species_main_ibfk_1` FOREIGN KEY (`id_species_authorities`) REFERENCES `species_authorities` (`id`),
  CONSTRAINT `link__species_authorities__species_main_ibfk_2` FOREIGN KEY (`id_scientific_name_translation__common_name`) REFERENCES `scientific_name_translation` (`id`),
  CONSTRAINT `link__species_authorities__species_main_ibfk_3` FOREIGN KEY (`id_scientific_name_translation__scientific_name`) REFERENCES `scientific_name_translation` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_authorities", "id_species_main", "id_scientific_name_translation__common_name", "id_scientific_name_translation__scientific_name");
	var $validate = array(
		'id_species_authorities' => array(
			'reference_to' => array('The constraint to species_authorities.id isn\'t respected.', 'species_authorities', 'id')
		),
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'id_scientific_name_translation__common_name' => array(
			'reference_to' => array('The constraint to scientific_name_translation__common_name.id isn\'t respected.', 'scientific_name_translation__common_name', 'id')
		),
		'id_scientific_name_translation__scientific_name' => array(
			'reference_to' => array('The constraint to scientific_name_translation__scientific_name.id isn\'t respected.', 'scientific_name_translation__scientific_name', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
