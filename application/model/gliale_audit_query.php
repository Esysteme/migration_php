<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class gliale_audit_query extends Model
{

	var $schema = "CREATE TABLE `gliale_audit_query` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `query` varchar(255) NOT NULL,
  `time_execution` float NOT NULL,
  `affected_rows` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=209 DEFAULT CHARSET=utf8";
	var $field = array("id", "date", "query", "time_execution", "affected_rows", "user");
	var $validate = array(
		'date' => array(
			'time' => array('This must be a time.')
		),
		'query' => array(
			'not_empty' => array('This field is requiered.')
		),
		'time_execution' => array(
			'decimal' => array('This must be a float.')
		),
		'affected_rows' => array(
			'numeric' => array('This must be an int.')
		),
		'user' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
