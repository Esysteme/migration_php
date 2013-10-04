<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class bird_ring extends Model
{

	var $schema = "CREATE TABLE `bird_ring` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_bird_breeder` int(11) NOT NULL,
  `id_species_main` int(11) NOT NULL,
  `id_species_sub` int(11) DEFAULT NULL,
  `id_bird_gender` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `size` char(2) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_bird_breeder` (`id_bird_breeder`),
  KEY `id_species_main` (`id_species_main`),
  KEY `id_species_sub` (`id_species_sub`),
  KEY `id_bird_gender` (`id_bird_gender`),
  CONSTRAINT `bird_ring_ibfk_1` FOREIGN KEY (`id_bird_breeder`) REFERENCES `bird_breeder` (`id`),
  CONSTRAINT `bird_ring_ibfk_2` FOREIGN KEY (`id_species_main`) REFERENCES `species_main` (`id`),
  CONSTRAINT `bird_ring_ibfk_3` FOREIGN KEY (`id_bird_gender`) REFERENCES `bird_gender` (`id`),
  CONSTRAINT `bird_ring_ibfk_4` FOREIGN KEY (`id_species_sub`) REFERENCES `species_sub` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	var $field = array("id", "id_bird_breeder", "id_species_main", "id_species_sub", "id_bird_gender", "year", "size", "number");
	var $validate = array(
		'id_bird_breeder' => array(
			'reference_to' => array('The constraint to bird_breeder.id isn\'t respected.', 'bird_breeder', 'id')
		),
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'id_species_sub' => array(
			'reference_to' => array('The constraint to species_sub.id isn\'t respected.', 'species_sub', 'id')
		),
		'id_bird_gender' => array(
			'reference_to' => array('The constraint to bird_gender.id isn\'t respected.', 'bird_gender', 'id')
		),
		'year' => array(
			'not_empty' => array('This field is requiered.')
		),
		'size' => array(
			'not_empty' => array('This field is requiered.')
		),
		'number' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
