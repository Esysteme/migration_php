<?php

foreach($data['photo'] as $line)
{
	echo $line['id'].'<img src="'.IMG.'photos_in_wait/'.$line['name'].'" height="250" width="" />';
}



/*
UPDATE species_picture_in_wait SET id_history_etat = 3 where 225246 <= id and id <= 225827
 * UPDATE species_picture_in_wait SET id_history_etat = 3 where 225983 <= id and id <= 226014
 * UPDATE species_picture_in_wait SET id_history_etat = 3 where 225017 <= id and id <= 225038
 * 
 * 

225246 225827
 225983 226014
 * 226089
 * 225017  225038
 * 
 * 

 */