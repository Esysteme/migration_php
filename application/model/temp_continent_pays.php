<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class temp_continent_pays extends Model
{

	var $schema = "CREATE TABLE `temp_continent_pays` (
  `cont` char(2) NOT NULL,
  `iso` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("cont", "iso");
	var $validate = array(
		'cont' => array(
			'not_empty' => array('This field is requiered.')
		),
		'iso' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
