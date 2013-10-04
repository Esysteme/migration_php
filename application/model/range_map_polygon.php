<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class range_map_polygon extends Model
{

	var $schema = "CREATE TABLE `range_map_polygon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_main` int(11) NOT NULL,
  `id_species_sub` int(11) NOT NULL,
  `id_range_map_main` int(11) NOT NULL,
  `id_range_map_legend` int(11) NOT NULL,
  `km_squared` float NOT NULL,
  `placemark` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_species_main` (`id_species_main`),
  KEY `id_species_sub` (`id_species_sub`),
  KEY `id_range_map_legend` (`id_range_map_legend`),
  KEY `id_range_map_main` (`id_range_map_main`),
  CONSTRAINT `range_map_polygon_ibfk_3` FOREIGN KEY (`id_range_map_main`) REFERENCES `range_map_main` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `range_map_polygon_ibfk_1` FOREIGN KEY (`id_species_main`) REFERENCES `species_main` (`id`),
  CONSTRAINT `range_map_polygon_ibfk_2` FOREIGN KEY (`id_species_sub`) REFERENCES `species_sub` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_main", "id_species_sub", "id_range_map_main", "id_range_map_legend", "km_squared", "placemark");
	var $validate = array(
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'id_species_sub' => array(
			'reference_to' => array('The constraint to species_sub.id isn\'t respected.', 'species_sub', 'id')
		),
		'id_range_map_main' => array(
			'reference_to' => array('The constraint to range_map_main.id isn\'t respected.', 'range_map_main', 'id')
		),
		'id_range_map_legend' => array(
			'reference_to' => array('The constraint to range_map_legend.id isn\'t respected.', 'range_map_legend', 'id')
		),
		'km_squared' => array(
			'decimal' => array('This must be a float.')
		),
		'placemark' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
