<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_tree_name extends Model
{

	var $schema = "CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `species_tree_name` AS select `a`.`id` AS `id`,`g`.`scientific_name` AS `kingdom`,`f`.`scientific_name` AS `phylum`,`e`.`scientific_name` AS `class`,`d`.`scientific_name` AS `order2`,`c`.`scientific_name` AS `family`,`b`.`scientific_name` AS `genus`,`a`.`scientific_name` AS `species_` from ((((((`species_main` `a` join `species_genus` `b` on((`a`.`id_species_genus` = `b`.`id`))) join `species_family` `c` on((`b`.`id_species_family` = `c`.`id`))) join `species_order` `d` on((`c`.`id_species_order` = `d`.`id`))) join `species_class` `e` on((`d`.`id_species_class` = `e`.`id`))) join `species_phylum` `f` on((`e`.`id_species_phylum` = `f`.`id`))) join `species_kingdom` `g` on((`f`.`id_species_kingdom` = `g`.`id`)))";
	var $field = array("id", "kingdom", "phylum", "class", "order2", "family", "genus", "species_");
	var $validate = array(
		'kingdom' => array(
			'not_empty' => array('This field is requiered.')
		),
		'phylum' => array(
			'not_empty' => array('This field is requiered.')
		),
		'class' => array(
			'not_empty' => array('This field is requiered.')
		),
		'order2' => array(
			'not_empty' => array('This field is requiered.')
		),
		'family' => array(
			'not_empty' => array('This field is requiered.')
		),
		'genus' => array(
			'not_empty' => array('This field is requiered.')
		),
		'species_' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
