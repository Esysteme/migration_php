<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class forum_main extends Model
{

	var $schema = "CREATE TABLE `forum_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_forum_category` int(11) DEFAULT NULL,
  `name` varchar(80) CHARACTER SET utf8 DEFAULT 'New forum',
  `description` text CHARACTER SET utf8,
  `redirect_url` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `moderators` text CHARACTER SET utf8,
  `num_topics` int(11) DEFAULT '0',
  `num_posts` int(11) DEFAULT '0',
  `last_post` int(11) DEFAULT NULL,
  `last_post_id` int(11) DEFAULT NULL,
  `last_poster` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `sort_by` tinyint(1) DEFAULT NULL,
  `disp_position` int(11) DEFAULT '0',
  `id_species_kingdom` int(11) DEFAULT '0',
  `id_species_phylum` int(11) DEFAULT '0',
  `id_species_class` int(11) DEFAULT '0',
  `id_species_order` int(11) DEFAULT '0',
  `id_species_family` int(11) DEFAULT '0',
  `id_species_genus` int(11) DEFAULT '0',
  `id_species_main` int(11) DEFAULT '0',
  `is_species_sub` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_forum_category` (`id_forum_category`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1";
	var $field = array("id", "id_forum_category", "name", "description", "redirect_url", "moderators", "num_topics", "num_posts", "last_post", "last_post_id", "last_poster", "sort_by", "disp_position", "id_species_kingdom", "id_species_phylum", "id_species_class", "id_species_order", "id_species_family", "id_species_genus", "id_species_main", "is_species_sub");
	var $validate = array(
		'id_forum_category' => array(
			'reference_to' => array('The constraint to forum_category.id isn\'t respected.', 'forum_category', 'id')
		),
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'description' => array(
			'not_empty' => array('This field is requiered.')
		),
		'redirect_url' => array(
			'not_empty' => array('This field is requiered.')
		),
		'moderators' => array(
			'not_empty' => array('This field is requiered.')
		),
		'num_topics' => array(
			'numeric' => array('This must be an int.')
		),
		'num_posts' => array(
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
		'sort_by' => array(
			'numeric' => array('This must be an int.')
		),
		'disp_position' => array(
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
