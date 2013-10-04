<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class geo2 extends Model
{

	var $schema = "CREATE TABLE `geo2` (
  `lat2` float NOT NULL,
  `lng` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	var $field = array("lat2", "lng");
	var $validate = array(
		'lat2' => array(
			'decimal' => array('This must be a float.')
		),
		'lng' => array(
			'decimal' => array('This must be a float.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
