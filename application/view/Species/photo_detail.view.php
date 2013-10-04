<?php


$id_node = "node-".uniqid();

$var = $data['photo'][0];

$species_name = str_replace(" ", "_", $var['nominal']);
$path = "Eukaryota/{$var['kingdom']}/{$var['phylum']}/{$var['class']}/{$var['order']}/{$var['family']}/{$var['genus']}/" . $species_name;
$picture_name = $var['id_photo'] . "-" . $species_name . ".jpg";

echo '<h3 class="item">' . $var['info_photo'];
if ( $GLOBALS['_SITE']['IdUser'] != -1 )
{
	echo "<span> [<a href=\"" . LINK . "photo/admin_crop/id_species_picture_main:" . $var['id_photo'] . "\" rel=\"nofollow\">" . __("Edit") . "</a>]</span>";
}
echo '</h3>';


if ( $var['width'] > 890 )
{
	$var['height'] = ceil($var['height'] / $var['width'] * 890);
	$var['width'] = 890;
}

echo '<a href="' . $var['url_context'] . '" target="_BLANK"><img src="' . FARM1 . 'crop/890x/' . $path . '/' . $picture_name . '" width="' . $var['width'] . '" height="' . $var['height'] . '" /></a><br />';


echo '<h3>' . __("Details") . '</h3>';


echo __("Author") . ' : <a href="' . LINK . 'author/image/' . $var['id_species_author'] . '/">' . $var['surname'] . '</a><br />';
echo "Id : <b>" . $var['id_photo'] . "</b><br />";
echo __("Found on") . " : <a href=\"" . $var['url_context'] . "\" target=\"_BLANK\" rel=\"nofollow\">" . $var['url_context'] . "</a> <br />";




echo '<div id="'.$id_node.'">';
\glial\synapse\FactoryController::addNode("comment",  "image", array($var['id_photo']));
echo '</div>';








