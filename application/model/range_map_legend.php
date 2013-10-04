<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class range_map_legend extends Model
{

	var $schema = "CREATE TABLE `range_map_legend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  `color` char(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8";
	var $field = array("id", "libelle", "color");
	var $validate = array(
		'libelle' => array(
			'not_empty' => array('This field is requiered.')
		),
		'color' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
