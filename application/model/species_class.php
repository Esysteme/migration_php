<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_class extends Model
{

	var $schema = "CREATE TABLE `species_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_phylum` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`),
  KEY `FK_IdSpeciesPhylum` (`id_species_phylum`),
  CONSTRAINT `FK_id_species_phylum` FOREIGN KEY (`id_species_phylum`) REFERENCES `species_phylum` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_phylum", "scientific_name", "is_valid", "date_created", "date_updated");
	var $validate = array(
		'id_species_phylum' => array(
			'reference_to' => array('The constraint to species_phylum.id isn\'t respected.', 'species_phylum', 'id')
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
