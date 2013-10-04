<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_picture_in_wait extends Model
{

	// Nous donnons donc � Gliale la structure d'un enregistrement
	var $schema = "CREATE TABLE `UserMain` (
 `Id` int(11) NOT NULL auto_increment,
 `IsValid` int(11) NOT NULL,
 `Login` varchar(50) NOT NULL,
 `Email` varchar(50) NOT NULL,
 `Password` varchar(20) NOT NULL,
 `Name` varchar(40) NOT NULL,
 `Firstname` varchar(40) NOT NULL,
 `IP` varchar(15) NOT NULL,
 `CountryIP` char(2) NOT NULL,
 `Points` int(11) NOT NULL,
 `LastLogin` datetime NOT NULL,
 `LastConnected` datetime NOT NULL,
 `LastJoined` datetime NOT NULL,
 `KeyAuth` char(32) NOT NULL,
 PRIMARY KEY  (`Id`),
 UNIQUE KEY `email` (`Email`),
 UNIQUE KEY `login` (`Login`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8";
	// R�gles de validation des donn�es

	var $field = array();
	var $validate = array(
		'md5' => array(
			'is_unique' => array('This photo already exist in the database.'),
			'not_empty' => array("The md5 of picture must be set.")
		),
		'url_md5' => array(
		),
		'url_found' => array(
			'not_empty' => array("Le nom doit �tre renseign�.")
		),
		'id_species_picture_info' => array(
			'reference_to' => array("Please select what king of picture it is", "species_picture_info", "id")
		)
	);

	function get_validate()
	{
		return $this->validate;
	}

}

?>