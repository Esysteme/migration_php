<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_sub extends Model
{

	var $schema = "CREATE TABLE `species_sub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_main` int(11) NOT NULL,
  `id_species_pictures` int(11) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `scientific_name` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ScientificName` (`scientific_name`),
  UNIQUE KEY `Id` (`id`,`id_species_main`),
  KEY `FK_IdSpeciesMain` (`id_species_main`),
  CONSTRAINT `FK_id_species_main` FOREIGN KEY (`id_species_main`) REFERENCES `species_main` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=260271 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_main", "id_species_pictures", "is_valid", "scientific_name", "date_created", "date_updated");
	var $validate = array(
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'scientific_name' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
