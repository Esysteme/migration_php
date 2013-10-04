<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class table_history extends Model
{

	var $schema = "CREATE TABLE `table_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) NOT NULL,
  `structure` bit(1) NOT NULL,
  `data` bit(1) NOT NULL,
  `date_data_updated` datetime NOT NULL,
  `date_structure_updated` datetime NOT NULL,
  `date_insterted` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_name` (`table_name`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8";
	var $field = array("id", "table_name", "structure", "data", "date_data_updated", "date_structure_updated", "date_insterted", "date_updated");
	var $validate = array(
		'table_name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'structure' => array(
			'not_empty' => array('This field is requiered.')
		),
		'data' => array(
			'not_empty' => array('This field is requiered.')
		),
		'date_data_updated' => array(
			'time' => array('This must be a time.')
		),
		'date_structure_updated' => array(
			'time' => array('This must be a time.')
		),
		'date_insterted' => array(
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
