<?php

if ( !empty($data['breeder']) )
{

	echo '<table class="table" style="width:100%">';
	echo '<tr>';
	echo '<th></th><th>' . __("Name") . '</th><th>' . __("Country") . '</th><th>' . __("City") . '</th><th>' . __("Male") . '</th><th>' . __("Female") . '</th><th>' . __("Unknow") . '</th><th>' . __("Total") . '</th>';
	echo '</tr>';


	$i = 0;


	$species = "-1";

	foreach ( $data['breeder'] as $line )
	{
		$i++;


		if ( $species != $line['scientific_name'] )
		{
			echo '<tr class="alternate">';
			echo '<th>' . $i . '</th>
		<td><span class="tree-hit tree-expanded"></span>' . $line['scientific_name'] . '</td>
		<td></td>
		<td></td>
		<td>' . $data['species'][$line['scientific_name']]['male'] . '</td>
		<td>' . $data['species'][$line['scientific_name']]['female'] . '</td>
		<td>' . $data['species'][$line['scientific_name']]['unknow'] . '</td>
		<td>' . $data['species'][$line['scientific_name']]['total'] . '</td>';
			echo '</tr>';

			$species = $line['scientific_name'];

			$i++;
		}


		echo '<tr>';
		echo '<th>' . $i . '</th>
		<td><span class="tree-indent"></span><span class="tree-file"></span><a href="'.LINK.'user/profil/'.$line['id_user_main'].'"><img class="country" src="'.IMG.'country/type2/'.strtolower($line['iso']).'.png" width="16" height="11"> ' . $line['firstname'] . ' ' . $line['name'] . '</a></td>
		<td><img class="country" src="'.IMG.'country/type2/'.strtolower($line['iso']).'.png" width="16" height="11"> ' . $line['country'] . '</td>
		<td>' . $line['city'] . '</td>
		<td>' . $line['male'] . '</td>
		<td>' . $line['female'] . '</td>
		<td>' . $line['unknow'] . '</td>
		<td>' . $line['SUM_COUNTS'] . '</td>';
		echo '</tr>';
	}


	echo '</table>';
}