<?php


echo "<div class=\"title_box\"><a href=\"\">".__("The last registered")."</a></div>";
echo "<table class=\"alternance\" width=\"100%\">";


$i=1;



foreach($data as $tab)
{
	$i++;
	if ($i %2 ==0)
	{
		echo "<tr class=\"couleur2\">";
	}
	else
	{
		echo "<tr class=\"couleur1\">";
	}
	echo "<td><img class=\"country\" src=\"".IMG."/country/type1/".$tab['iso'].".gif\" width=\"18\" height=\"12\" /></td>";
	echo "<td><a href=\"".LINK."user/profil/".$tab['id']."\">".$tab['firstname']." ".$tab['name']."</a></td>";
	echo "<td>".substr($tab['date_created'],0,-3)."</td>";
	echo "<tr>";
}

echo "</table>";





?>