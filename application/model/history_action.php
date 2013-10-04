<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class history_action extends Model
{

	var $schema = "CREATE TABLE `history_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `action` varchar(80) NOT NULL,
  `point` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8";
	var $field = array("id", "title", "action", "point");
	var $validate = array(
		'title' => array(
			'not_empty' => array('This field is requiered.')
		),
		'action' => array(
			'not_empty' => array('This field is requiered.')
		),
		'point' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
