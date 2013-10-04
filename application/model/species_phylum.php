<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_phylum extends Model
{

	var $schema = "CREATE TABLE `species_phylum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_kingdom` int(11) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`),
  KEY `FK_id_species_kingdom` (`id_species_kingdom`),
  CONSTRAINT `FK_id_species_kingdom` FOREIGN KEY (`id_species_kingdom`) REFERENCES `species_kingdom` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_kingdom", "is_valid", "scientific_name", "date_created", "date_updated");
	var $validate = array(
		'id_species_kingdom' => array(
			'reference_to' => array('The constraint to species_kingdom.id isn\'t respected.', 'species_kingdom', 'id')
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
