<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class range_map_coordinates extends Model
{

	var $schema = "CREATE TABLE `range_map_coordinates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_range_map_polygon` int(11) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_range_map_polygon` (`id_range_map_polygon`),
  CONSTRAINT `range_map_coordinates_ibfk_1` FOREIGN KEY (`id_range_map_polygon`) REFERENCES `range_map_polygon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=730 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_range_map_polygon", "latitude", "longitude");
	var $validate = array(
		'id_range_map_polygon' => array(
			'reference_to' => array('The constraint to range_map_polygon.id isn\'t respected.', 'range_map_polygon', 'id')
		),
		'latitude' => array(
			'decimal' => array('This must be a float.')
		),
		'longitude' => array(
			'decimal' => array('This must be a float.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
