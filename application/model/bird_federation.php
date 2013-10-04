<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class bird_federation extends Model
{

	var $schema = "CREATE TABLE `bird_federation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `website` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	var $field = array("id", "tag", "name", "website");
	var $validate = array(
		'tag' => array(
			'not_empty' => array('This field is requiered.')
		),
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'website' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
