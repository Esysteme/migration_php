<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


//debug($data['species']);


$last_genus = "";
$last_species = "";
$last_subspecies = "";

echo '<form method="post" action="">';

echo '<ul class="tree">';
foreach ($data['species'] as $value)
{
	!empty($value['valid_genus']) ? $checked = 'checked="checked"' : $checked = '';
	!empty($value['valid_species']) ? $checked2 = 'checked="checked"' : $checked2 = '';
	!empty($value['valid_species']) ? $checked3 = 'checked="checked"' : $checked3 = '';
	
	
	empty($value['valid_genus']) ? $input = '<input type="text" name="ddd" />' : $input ='';
	empty($value['valid_species']) ? $input2 = '<input type="text" name="ddd" />' : $input2 ='';
	empty($value['valid_subspecies']) ? $input3 = '<input type="text" name="ddd" />' : $input3 ='';

	if ($value["genus"] != $last_genus)
	{
		if ($last_genus !== "")
		{
			echo "</ul></li></ul>";
		}
		echo '<li><span><input type="checkbox" name="species_genus[id]['.$value["id_genus"].']" ' . $checked . ' /> ' . $value["genus"] .'</span>'. $input;
		echo '<ul>';
	}

	if ($value["species"] != $last_species)
	{
		if ($last_species !== "" && $value["genus"] == $last_genus)
		{
			echo "</ul></li>\n";
		}

		echo '<li><span><input type="checkbox" name="" ' . $checked2 . ' /> (' .$value["id_genus"].') ' . $value["species"].'</span>'.$input2;
	//	echo '<li><input type="checkbox" name="" ' . $checked2 . ' /> ' . $value["species"].$input2;
		echo '<ul>';
	}
	echo '<li><span><input type="checkbox" name="" ' . $checked3 . ' /> ' . $value["subspecies"].'</span>' .$input3. "</li>\n";

	$last_genus = $value["genus"];
	$last_species = $value["species"];
	$last_subspecies = $value["subspecies"];
}
echo "</ul>";


echo '</form>';