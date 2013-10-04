<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_genus extends Model
{

	var $schema = "CREATE TABLE `species_genus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_family` int(11) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`),
  KEY `FK_IdSpeciesFamily` (`id_species_family`),
  CONSTRAINT `FK_id_species_family` FOREIGN KEY (`id_species_family`) REFERENCES `species_family` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38424 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_family", "is_valid", "scientific_name", "date_created", "date_updated");
	var $validate = array(
		'id_species_family' => array(
			'reference_to' => array('The constraint to species_family.id isnt respected.', 'species_family', 'id')
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
