<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class history_table extends Model
{

	var $schema = "CREATE TABLE `history_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_insterted` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	var $field = array("id", "name", "date_insterted");
	var $validate = array(
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'date_insterted' => array(
			'time' => array('This must be a time.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
