<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_to_introduce extends Model
{

    var $schema = "CREATE TABLE `species_to_introduce` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `species_kingdom` varchar(50) NOT NULL,
  `species_phylum` varchar(50) NOT NULL,
  `species_class` varchar(50) NOT NULL,
  `species_order` varchar(50) NOT NULL,
  `species_family` varchar(50) NOT NULL,
  `species_main` varchar(50) NOT NULL,
  `species_sub` varchar(50) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `id_reference` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
    var $field = array("id", "species_kingdom", "species_phylum", "species_class", "species_order", "species_family", "species_main", "species_sub", "is_valid", "id_reference");
    var $validate = array(
        'species_kingdom' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'species_phylum' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'species_class' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'species_order' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'species_family' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'species_main' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'species_sub' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'is_valid' => array(
            'numeric' => array('This must be an int.')
        ),
        'id_reference' => array(
            'reference_to' => array('The constraint to reference.id isn\'t respected.', 'reference', 'id

	')
        ),
    );

    function get_validate()
    {
        return $this->validate;
    }

}
