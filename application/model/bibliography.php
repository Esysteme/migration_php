<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class bibliography extends Model
{

	var $schema = "CREATE TABLE `bibliography` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "test");
	var $validate = array(
		'test' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
