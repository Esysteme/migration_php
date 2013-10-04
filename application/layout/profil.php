<?php



echo "<h3>" . $data['species'][0][$GLOBALS['_LG']->Get()] . " - <i>" . $data['species'][0]["species_"] . "</i></h3>";


echo "<div id=\"whwdfg\">";


//debug($data['img']);


echo "<div id=\"menu_admin_crop\">";

echo "<div class=\"title_box\"><a href=\"\">" . __('Scientific classification of species') . "</a></div>";
echo "<div id=\"select_taxo\">";
echo __("Kingdom") . " : " . select("none", "id_species_kingdom", $data['species_kingdom'], $data['species'][0]['id_species_kingdom']) . "<br />";

echo __("Phylum") . " : " . select("none", "id_species_phylum", $data['species_phylum'], $data['species'][0]['id_species_phylum']) . "<br />";
echo __("Class") . " : " . select("none", "id_species_class", $data['species_class'], $data['species'][0]['id_species_class']) . "<br />";
echo __("Order") . " : " . select("none", "id_species_order", $data['species_order'], $data['species'][0]['id_species_order']) . "<br />";
echo __("Family") . " : " . select("none", "id_species_family", $data['species_family'], $data['species'][0]['id_species_family']) . "<br />";


echo __("Genus") . " : " . select("none", "id_species_genus", $data['species_genus'], $data['species'][0]['id_species_genus']) . "<br />";
echo __("Species") . " : " . select("species_picture_main", "id_species_main", $data['species_main'], $data['species'][0]['id_species_main']) . "<br />";
echo __("Subspecies") . " : " . select("species_picture_main", "id_species_sub", $data['species_sub'], $data['species'][0]['id_species_sub']) . "<br />";

echo "</div>";

echo "<div class=\"title_box\"><a href=\"\">" . __('Validation of picture') . "</a></div>";


echo "<div id=\"select_agrement\">";

echo __("I accept") . " / " . __("I refuse") . " : " . select("species_picture_main", "id_species_picture_info", $data['pic_info'], $data['species']['0']['id_species_picture_info']) . "<br />";



echo "<input name=\"ivalidate\" type=\"submit\" value=\"" . __("I validate") . "\" class=\"button btGreen overlayW btMedium largecolon\">";
echo "<input name=\"idontknow\" type=\"submit\" value=\"" . __("I don't know") . "\" class=\"button btOrange overlayW btMedium largecolon\">";

echo select("species_picture_main", "id_species_picture_info2", $data['pic_info2'], $data['species']['0']['id_species_picture_info2']) . "<br />";
echo "<input name=\"irefuse\" type=\"submit\" value=\"" . __("I Refuse") . "\" class=\"button btRed overlayW btMedium largecolon\">";


echo "</div>";

echo "<div class=\"title_box\"><a href=\"\">" . __('References') . "</a></div>";


echo "<div id=\"references\">";
$_GET["species_author"]["surname"] = $data['species'][0]["author"];


echo "<b>Author :</b> " . $data['img']['author'] . "<br />";


/*
  echo "Select : ".autocomplete("species_picture_main","id_author")."<br />";
  echo "<div class=\"clear\" style=\"text-align:center\">".__("or add a new one:")."</div>";
  echo "<div class=\"clear\">".__("Firstname")." : ".input("species_author","firstname")." </div>";
  echo "<div class=\"clear\">".__("Name")." : ".input("species_author","name")." </div>";
 */

echo "<div style=\"clear:both\" ></div>";
echo "<b>URL found :</b> <a target=\"_BLANK\" href=\"" . $data['species'][0]["url_found"] . "\">http://farm4.static.flickr.com/</a><br />";
echo "<b>Contexte :</b> <a target=\"_BLANK\" href=\"" . $data['species'][0]["url_context"] . "\">http://www.flickr.com/</a><br />";
echo "<b>Original size :</b> " . $data['species'][0]["width"] . "*" . $data['species'][0]["height"] . "<br />";
echo "<b>Date imported :</b> 2009-08-29 18:48:04<br />";




//echo "<b>License :</b> ".select("licence","libelle",$data['licence'] , $data['species']['0']['id_licence'])."<br />";
echo "<b>License :</b> " . $data['img']['license']["text"] . "<br />";



echo "</div>";


echo "</div>";


//select author, count(1) from species_picture_in_wait group by author having count(1) > 1 order by count(1) desc






echo "<div id=\"all_crop\">";

echo "<div id=\"main_crop\" />";

echo "<h3>" . __("Preview and validated photos") . "</h3>";


echo "<span class=\"shadowImage\"><div class=\"passive\"><a href=\"" . LINK . "species/nominal/\">
			<table><tr><td>";
echo "<div id=\"preview\" style=\"overflow: hidden; width: 158px; height: 158px;\" />";
echo "<img src=\"" . IMG . "tmp_pic/" . SIZE_SITE_MAX . "/" . $data['species'][0]["name"] . "\" style=\"width: 158px; height: 158px;\" />";
echo "</div>";
echo "</td></tr></table>
			</a></div></span>";


echo "</div>";



//echo "<div style=\"clear:both\"></div>";
echo "<div id=\"to_crop\">";
echo "<h3>Picture to crop and validate</h3>";


echo "</div>";



echo "</div>";
echo "<div style=\"clear:both\"></div>";
echo "</div>";
echo "</form>";