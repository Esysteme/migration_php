<?php





$id_node = "node-".uniqid();


$url = explode("/", $_GET['path']);

if ( empty($url[4]) || strstr($url[4], ">") )
{
	$url[5] = 'empty';
}



if ( count($data['pending']) > 0 )
{
	echo "<ul id=\"\" class=\"menu_tab onglet\" style=\"padding-left: 3px;\">";

	$i = 0;

	foreach ( $data['pending'] as $tab )
	{
		if ( ($url[5] === 'empty' && $i === 0) || ($url[5] === $tab['name']) )
		{
			echo '<li id="' . $tab['name'] . '" class="selected">';
            $url[5] = -1;
		}
		else
		{
			echo '<li id="' . $tab['name'] . '">';
		}

		echo '<a href="' . LINK . "species/nominal/" . $data['param'][0] . "/pending/sort/" . $tab['name'] . '/" data-target="'.$id_node.'" data-link="species-sort" style="padding-left:24px"><img src="' . IMG . '16/' . $tab['pic16'] . '" width="16" height="16" />' . $tab['name'] . ' (' . $tab['cpt'] . ')</a>';
		echo '</li>';
	}
echo '<li class="right">';
		echo '<a href="' . LINK . '"species/nominal/" data-target="'.$id_node.'" data-link="species-sort" style="padding-left:24px">Crop</a>';
		echo '</li>';
	echo "</ul>";
}



echo '<div id="'.$id_node.'">';
\glial\synapse\FactoryController::addNode("species", "sort", $data['param']);
echo '</div>';




