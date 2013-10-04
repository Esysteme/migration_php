<?php

// think to update the one in javascript
function generate_option($number)
{
	$output=array();
	
	for($i=0; $i<= $number; $i++ )
	{
		$var = array();
		
		$var['id'] = $i;
		$var['libelle'] = $i;
		
		
		$output[] = $var;
	}
	return $output;
}



echo '<form action="" method="POST">';



echo '<table class="table" id="variante">
<tbody><tr>
<th class="datagrid-cell" colspan="5">' . __("Species detained") . '</th>
<th class="datagrid-cell" colspan="4">' . __("For sale") . '</th>
<th class="datagrid-cell" colspan="3">' . __("Exchange") . '</th>
<th rowspan="2">' . __("Action") . '</th>
</tr>


<th class="datagrid-cell">' . __("Scientific name") . '</th>
<th>' . __("Subspecies") . '</th>
<th>' . __("Male") . '</th>
<th>' . __("Female") . '</th>
<th>' . __("Unknow") . '</th>

<th>' . __("Male") . '</th>
<th>' . __("Female") . '</th>
<th>' . __("Unknow") . '</th>
<th>' . __("Price per unit") . '</th>
	

<th>' . __("Male") . '</th>
<th>' . __("Female") . '</th>
<th>' . __("Unknow") . '</th>


</tr>';



for ( $i = 1; $i <= $data['nbrow']; $i++ )
{
	$disable = '';
	if ( $data['nbrow'] == 1 )
	{
		$disable = 'disabled="disabled"';
		$disable_delete = 'btGrey';
	}
	else
	{
		$disable_delete = 'btBlueTest';
	}
	
	$id_corrected = $i -1;
	if (empty($data['stock'][$id_corrected]))
	{	
		/*
		$_GET["link__species_sub__user_main"][$i]["male"] = "0";
		$_GET["link__species_sub__user_main"][$i]["female"] = "0";
		$_GET["link__species_sub__user_main"][$i]["unknow"] = "0";
		*/
		
		$data['stock'][$id_corrected]['list_subspecies'] = array();
		$data['stock'][$id_corrected]['id_species_sub'] = "";
		

	}
	else
	{
		
		
		$_GET["link__species_sub__user_main"][$i]["id_species_main"] = $data['stock'][$id_corrected]['id_species_main'];
		$_GET["link__species_sub__user_main"][$i]["id_species_main-auto"] = $data['stock'][$id_corrected]['scientific_name'];
		
		$_GET["link__species_sub__user_main"][$i]["male"] = $data['stock'][$id_corrected]['male'];
		$_GET["link__species_sub__user_main"][$i]["female"] = $data['stock'][$id_corrected]['female'];
		$_GET["link__species_sub__user_main"][$i]["unknow"] = $data['stock'][$id_corrected]['unknow'];
	}
	
	(empty($data['stock'][$id_corrected]['forsale_male']))? $data['stock'][$id_corrected]['forsale_male'] = 0: "";
	(empty($data['stock'][$id_corrected]['forsale_female']))? $data['stock'][$id_corrected]['forsale_female'] = 0: "";
	(empty($data['stock'][$id_corrected]['forsale_unknow']))? $data['stock'][$id_corrected]['forsale_unknow'] = 0: "";

	
	
	echo '<tr id="tr-' . ($i) . '" class="blah">

	<td align="center">';
	echo autocomplete("link__species_sub__user_main", "id_species_main", "textform species",$i);

	echo '</td><td>';
	echo select("link__species_sub__user_main", "id_species_sub", $data['stock'][$id_corrected]['list_subspecies'],$data['stock'][$id_corrected]['id_species_sub'],"textform subspecies",0,$i);

	
	echo '</td><td>';


	echo input("link__species_sub__user_main", "male", "textform input-number male only_integer_positif", $i);
	echo '</td><td>';
	echo input("link__species_sub__user_main", "female", "textform input-number female only_integer_positif", $i);
	echo '</td><td>';
	echo input("link__species_sub__user_main", "unknow", "textform input-number unknow only_integer_positif", $i);
	
	// a vendre
	echo '</td><td>';
	echo select("link__species_sub__user_main__for_sale", "forsale_male", generate_option($_GET["link__species_sub__user_main"][$i]["male"]),"0","textform forsale_male int",0,$i);
	echo '</td><td>';
	echo select("link__species_sub__user_main__for_sale", "forsale_female", generate_option($_GET["link__species_sub__user_main"][$i]["female"]),"0","textform forsale_female int",0,$i);
	echo '</td><td>';
	echo select("link__species_sub__user_main__for_sale", "forsale_unknow",generate_option($_GET["link__species_sub__user_main"][$i]["unknow"]),"0","textform forsale_unknow int",0,$i);

	echo '</td><td>';
	echo input("link__species_sub__user_main__for_sale", "price", "textform price only_integer_positif", $i);
	echo ' €';
	
	//echo select("link__species_sub__user_main", "devise", array('€','$','£'),"0","textform devise int",0,$i);
	//echange
	echo '</td><td>';
	echo select("link__species_sub__user_main__exchange", "exchange_male", generate_option($_GET["link__species_sub__user_main"][$i]["male"]),"0","textform exchange_male int",0,$i);
	echo '</td><td>';
	echo select("link__species_sub__user_main__exchange", "exchange_female", generate_option($_GET["link__species_sub__user_main"][$i]["female"]),"0","textform exchange_female int",0,$i);
	echo '</td><td>';
	echo select("link__species_sub__user_main__exchange", "exchange_unknow", generate_option($_GET["link__species_sub__user_main"][$i]["unknow"]),"0","textform exchange_unknow int",0,$i);
	
	
	
	echo '</td>
	<td>
	<input id="delete-' . ($i) . '" class="delete-line button '.$disable_delete.' overlayW btMedium" type="button" value="Effacer" style="margin:0;" ' . $disable . ' />
	</td>
	</tr>';
}

echo '</tbody></table>';

echo '<br />';
echo '<input type="checkbox" name="all_for_sale" /> <b>'.__('If checked, all your species are for sale.').'</b><br />';
echo '<input type="checkbox" name="all_for_sale" /> <b>'.__('Display quantities of my species to the members.').'</b><br />';	
echo "<br />";
echo '<input id="add" type="button" class="button btBlueTest overlayW btMedium" value="' . __('Add a species') . '" />';
echo ' - ';
echo '<input id="add" type="submit" class="button btBlueTest overlayW btMedium" value="' . __('Save') . '" />';
echo '</form>';