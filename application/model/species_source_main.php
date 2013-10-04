<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_source_main extends Model
{

	var $schema = "CREATE TABLE `species_source_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `u_r_l` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";
	var $field = array("id", "name", "u_r_l", "date_created", "date_updated");
	var $validate = array(
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'u_r_l' => array(
			'not_empty' => array('This field is requiered.')
		),
		'date_created' => array(
			'time' => array('This must be a time.')
		),
		'date_updated' => array(
			'time' => array('This must be a time.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
