<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_family extends Model
{

	var $schema = "CREATE TABLE `species_family` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_order` int(11) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `common_name` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`),
  UNIQUE KEY `common_name` (`common_name`),
  KEY `FK_IdSpeciesOrder` (`id_species_order`),
  CONSTRAINT `FK_id_species_order` FOREIGN KEY (`id_species_order`) REFERENCES `species_order` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4835 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_order", "is_valid", "scientific_name", "common_name", "date_created", "date_updated");
	var $validate = array(
		'id_species_order' => array(
			'reference_to' => array('The constraint to species_order.id isnt respected.', 'species_order', 'id')
		),
		'scientific_name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'common_name' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
