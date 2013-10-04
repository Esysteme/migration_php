<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_rubric_subspecies extends Model
{

	var $schema = "CREATE TABLE `species_rubric_subspecies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `id_species_kingdom` int(11) NOT NULL,
  `id_species_phylum` int(11) NOT NULL,
  `id_species_class` int(11) NOT NULL,
  `id_species_order` int(11) NOT NULL,
  `id_species_family` int(11) NOT NULL,
  `id_species_genus` int(11) NOT NULL,
  `id_species_main` int(11) NOT NULL,
  `id_species_sub` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "name", "id_species_kingdom", "id_species_phylum", "id_species_class", "id_species_order", "id_species_family", "id_species_genus", "id_species_main", "id_species_sub");
	var $validate = array(
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'id_species_kingdom' => array(
			'reference_to' => array('The constraint to species_kingdom.id isn\'t respected.', 'species_kingdom', 'id')
		),
		'id_species_phylum' => array(
			'reference_to' => array('The constraint to species_phylum.id isn\'t respected.', 'species_phylum', 'id')
		),
		'id_species_class' => array(
			'reference_to' => array('The constraint to species_class.id isn\'t respected.', 'species_class', 'id')
		),
		'id_species_order' => array(
			'reference_to' => array('The constraint to species_order.id isn\'t respected.', 'species_order', 'id')
		),
		'id_species_family' => array(
			'reference_to' => array('The constraint to species_family.id isn\'t respected.', 'species_family', 'id')
		),
		'id_species_genus' => array(
			'reference_to' => array('The constraint to species_genus.id isn\'t respected.', 'species_genus', 'id')
		),
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'id_species_sub' => array(
			'reference_to' => array('The constraint to species_sub.id isn\'t respected.', 'species_sub', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
