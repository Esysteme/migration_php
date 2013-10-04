<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_alias_author extends Model
{

	var $schema = "CREATE TABLE `species_alias_author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date_inserted` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `from` (`from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	var $field = array("id", "from", "to", "date_inserted");
	var $validate = array(
		'from' => array(
			'not_empty' => array('This field is requiered.')
		),
		'to' => array(
			'not_empty' => array('This field is requiered.')
		),
		'date_inserted' => array(
			'time' => array('This must be a time.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
