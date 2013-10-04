<?php

namespace Application\Model;
use \Glial\Synapse\Model;

class link__species_picture_id__species_picture_search extends Model
{
var $schema = "CREATE TABLE `link__species_picture_id__species_picture_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_picture_id` int(11) NOT NULL,
  `id_species_picture_search` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_species_picture_id` (`id_species_picture_id`,`id_species_picture_search`),
  KEY `id_species_picture_search` (`id_species_picture_search`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

var $field = array("id","id_species_picture_id","id_species_picture_search","date");

var $validate = array(
	'id_species_picture_id' => array(
		'reference_to' => array('The constraint to species_picture_id.id isn\'t respected.','species_picture_id', 'id')
	),
	'id_species_picture_search' => array(
		'reference_to' => array('The constraint to species_picture_search.id isn\'t respected.','species_picture_search', 'id')
	),
);

function get_validate()
{
return $this->validate;
}
}
