<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class forum_topic extends Model
{

	var $schema = "CREATE TABLE `forum_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_forum_main` int(11) DEFAULT NULL,
  `id_user_main` int(11) DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `posted` datetime DEFAULT NULL,
  `first_post_id` int(11) DEFAULT NULL,
  `last_post` int(11) DEFAULT NULL,
  `last_post_id` int(11) DEFAULT NULL,
  `last_poster` varchar(200) CHARACTER SET utf8mb4 DEFAULT NULL,
  `num_views` mediumint(8) DEFAULT NULL,
  `num_replies` mediumint(8) DEFAULT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `sticky` tinyint(1) DEFAULT NULL,
  `moved_to` int(10) DEFAULT NULL,
  `id_species_kingdom` int(11) DEFAULT '0',
  `id_species_phylum` int(11) DEFAULT '0',
  `id_species_class` int(11) DEFAULT '0',
  `id_species_order` int(11) DEFAULT '0',
  `id_species_family` int(11) DEFAULT '0',
  `id_species_genus` int(11) DEFAULT '0',
  `id_species_main` int(11) DEFAULT '0',
  `is_species_sub` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1";
	var $field = array("id", "id_forum_main", "id_user_main", "subject", "posted", "first_post_id", "last_post", "last_post_id", "last_poster", "num_views", "num_replies", "closed", "sticky", "moved_to", "id_species_kingdom", "id_species_phylum", "id_species_class", "id_species_order", "id_species_family", "id_species_genus", "id_species_main", "is_species_sub");
	var $validate = array(
		'id_forum_main' => array(
			'reference_to' => array('The constraint to forum_main.id isn\'t respected.', 'forum_main', 'id')
		),
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'subject' => array(
			'not_empty' => array('This field is requiered.')
		),
		'posted' => array(
			'time' => array('This must be a time.')
		),
		'first_post_id' => array(
			'numeric' => array('This must be an int.')
		),
		'last_post' => array(
			'numeric' => array('This must be an int.')
		),
		'last_post_id' => array(
			'numeric' => array('This must be an int.')
		),
		'last_poster' => array(
			'not_empty' => array('This field is requiered.')
		),
		'num_views' => array(
			'numeric' => array('This must be an int.')
		),
		'num_replies' => array(
			'numeric' => array('This must be an int.')
		),
		'closed' => array(
			'numeric' => array('This must be an int.')
		),
		'sticky' => array(
			'numeric' => array('This must be an int.')
		),
		'moved_to' => array(
			'numeric' => array('This must be an int.')
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
		'is_species_sub' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
