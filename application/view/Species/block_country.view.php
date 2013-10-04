<?php




echo '<div style="padding:4px">';


echo '<ul class="tree">';


$last_continent = "";

foreach ($data['country'] as $line)
{
	
	
	if ($line["name"] != $last_continent)
	{
		if ($last_continent !== "")
		{
			echo "</ul></li>\n";
		}
		echo '<li><span>' . $line["name"] .'</span>'."\n";
		echo '<ul>'."\n";
	}
	

	
	
	echo '<li><span><img class="country" src="' . IMG . '/country/type2/' . strtolower($line['iso'])
	. '.png" width="16" height="11"> <a href="' . LINK . 'species/country/' . strtolower($line['iso']) . '/' . $data['family'] . '/" style="color:#000">';
	
	
	
	
	if ($data['iso'] == strtolower($line['iso']))
	{
		
		echo "<b>".$line['libelle']	. ' ('	. $line['cpt'] . ')</b></a></span></li>'."\n";
	}
	else
	{
		echo $line['libelle']	. ' ('	. $line['cpt'] . ')</a></span></li>'."\n";
	}


	
	
	
	$last_continent = $line["name"];
}
echo '</ul>';

echo '</div>';