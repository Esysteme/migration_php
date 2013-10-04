<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class forum_category extends Model
{

	var $schema = "CREATE TABLE `forum_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) CHARACTER SET utf8 DEFAULT 'New Category',
  `disp_position` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1";
	var $field = array("id", "name", "disp_position");
	var $validate = array(
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'disp_position' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
