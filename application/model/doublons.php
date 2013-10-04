<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class doublons extends Model
{

	var $schema = "CREATE TABLE `doublons` (
  `photo_id` varchar(100) DEFAULT NULL,
  KEY `photo_id` (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("photo_id");
	var $validate = array(
		'photo_id' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
