<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class geolocalisation_continent extends Model
{

	var $schema = "CREATE TABLE `geolocalisation_continent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso` char(2) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8";
	var $field = array("id", "iso", "name");
	var $validate = array(
		'iso' => array(
			'not_empty' => array('This field is requiered.')
		),
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
