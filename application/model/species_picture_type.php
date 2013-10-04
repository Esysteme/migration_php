<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_picture_type extends Model
{

	var $schema = "CREATE TABLE `species_picture_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";
	var $field = array("id", "title", "date_created");
	var $validate = array(
		'title' => array(
			'not_empty' => array('This field is requiered.')
		),
		'date_created' => array(
			'time' => array('This must be a time.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
