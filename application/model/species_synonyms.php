<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_synonyms extends Model
{

    var $schema = "CREATE TABLE `species_synonyms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_table` int(11) NOT NULL,
  `id_row` int(11) NOT NULL,
  `iso` char(2) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IdRow` (`id_row`),
  KEY `ISO` (`iso`,`name`(40))
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
    var $field = array("id", "id_table", "id_row", "iso", "name");
    var $validate = array(
        'id_table' => array(
            'reference_to' => array('The constraint to table.id isn\'t respected.', 'table', 'id')
        ),
        'id_row' => array(
            'reference_to' => array('The constraint to row.id isn\'t respected.', 'row', 'id')
        ),
        'iso' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'name' => array(
            'not_empty' => array('This field is requiered.')
        ),
    );

    function get_validate()
    {
        return $this->validate;
    }

}
