<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_pictures_comment extends Model
{

    var $schema = "CREATE TABLE `species_pictures_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_pictures_main` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `date_inserted` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IdUserMain` (`id_user_main`),
  KEY `IdSpeciesPicturesMain` (`id_species_pictures_main`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    var $field = array("id", "id_species_pictures_main", "id_user_main", "comment", "date_inserted");
    var $validate = array(
        'id_species_pictures_main' => array(
            'reference_to' => array('The constraint to species_pictures_main.id isn\'t respected.', 'species_pictures_main', 'id')
        ),
        'id_user_main' => array(
            'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
        ),
        'comment' => array(
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
