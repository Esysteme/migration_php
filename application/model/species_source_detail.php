<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_source_detail extends Model
{

	var $schema = "CREATE TABLE `species_source_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_main` int(11) NOT NULL,
  `id_species_sub` int(11) NOT NULL,
  `id_species_source_main` int(11) NOT NULL,
  `reference_url` varchar(200) NOT NULL,
  `reference_id` varchar(20) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IdSource` (`id_species_source_main`,`reference_id`)
) ENGINE=MyISAM AUTO_INCREMENT=654 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_main", "id_species_sub", "id_species_source_main", "reference_url", "reference_id", "date_created", "date_updated");
	var $validate = array(
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'id_species_source_main' => array(
			'reference_to' => array('The constraint to species_source_main.id isn\'t respected.', 'species_source_main', 'id')
		),
		'reference_url' => array(
			'not_empty' => array('This field is requiered.')
		),
		'reference_id' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
