<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_taxon_author extends Model
{

	var $schema = "CREATE TABLE `species_taxon_author` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	var $field = array("id", "name");
	var $validate = array(
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
