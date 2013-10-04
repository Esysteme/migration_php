<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_translation extends Model
{

    var $schema = "CREATE TABLE `species_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_table` int(11) NOT NULL,
  `id_row` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `fr` varchar(50) NOT NULL,
  `en` varchar(50) NOT NULL,
  `de` varchar(50) NOT NULL,
  `es` varchar(50) NOT NULL,
  `nl` varchar(50) NOT NULL,
  `it` varchar(50) NOT NULL,
  `ja` varchar(50) NOT NULL,
  `cs` varchar(50) NOT NULL,
  `pl` varchar(50) NOT NULL,
  `zh-CN` varchar(50) NOT NULL,
  `ru` varchar(50) NOT NULL,
  `fi` varchar(50) NOT NULL,
  `pt` varchar(50) NOT NULL,
  `da` varchar(50) NOT NULL,
  `no` varchar(50) NOT NULL,
  `sk` varchar(50) NOT NULL,
  `se` varchar(50) NOT NULL,
  `is` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IdTable` (`id_table`,`id_row`)
) ENGINE=MyISAM AUTO_INCREMENT=9713 DEFAULT CHARSET=utf8";
    var $field = array("id", "id_table", "id_row", "scientific_name", "fr", "en", "de", "es", "nl", "it", "ja", "cs", "pl", "zh-CN", "ru", "fi", "pt", "da", "no", "sk", "se", "is");
    var $validate = array(
        'id_table' => array(
            'reference_to' => array('The constraint to table.id isn\'t respected.', 'table', 'id')
        ),
        'id_row' => array(
            'reference_to' => array('The constraint to row.id isn\'t respected.', 'row', 'id')
        ),
        'scientific_name' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'fr' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'en' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'de' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'es' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'nl' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'it' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'ja' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'cs' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'pl' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'zh-CN' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'ru' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'fi' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'pt' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'da' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'no' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'sk' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'se' => array(
            'not_empty' => array('This field is requiered.')
        ),
        'is' => array(
            'not_empty' => array('This field is requiered.')
        ),
    );

    function get_validate()
    {
        return $this->validate;
    }

}
