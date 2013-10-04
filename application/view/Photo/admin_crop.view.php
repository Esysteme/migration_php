<?php

// http://192.168.1.48/backup/species/en/photo/admin_crop/id_photo:226191/

echo "<h3>" . $data['species'][0][$GLOBALS['_LG']->Get()] . " - <i>" . $data['species'][0]["species_"] . "</i></h3>";


echo "<div id=\"ggg\">";


//debug($data['img']);

echo "<div id=\"menu_admin_crop\">";

if (!empty($_GET['id_species_picture_main']))
{
	echo '<form action="' . LINK . 'photo/admin_crop/id_species_picture_main:' . $_GET['id_species_picture_main'] . '/" method="post">';
}
else
{
	if (!empty($_GET['id_photo']))
	{
		echo '<form action="' . LINK . 'photo/admin_crop/id_photo:' . $_GET['id_photo'] . '/" method="post">';
	}
	else
	{
		if (!empty($_GET['id_species_main']))
		{
			echo '<form action="' . LINK . 'photo/admin_crop/id_species_main:' . $_GET['id_species_main'] . '/" method="post">';
		}
		else
		{
			echo '<form action="" method="post">';
		}
	}
}

echo hidden("species_picture_main", "crop_height", 250);
echo hidden("species_picture_main", "crop_width", 250);
echo hidden("species_picture_main", "crop_x1", 0);
echo hidden("species_picture_main", "crop_y1", 0);
echo hidden("species_picture_main", "crop_x2", 250);
echo hidden("species_picture_main", "crop_y2", 250);
echo hidden("species_picture_main", "id", $data['species'][0]["id_photo"]);

echo '<input type="hidden" name="img" value="' . base64_encode(serialize($data['img'])) . '">';


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


echo "<b> Id :</b> " . $data['species'][0]['id_photo'] . "<br />";
echo "<b> photo Id :</b> " . $data['species'][0]['photo_id'] . "<br />";
echo "<b>" . __("Author") . " :</b> " . $data['img']['author'] . "<br />";


/*
  echo "Select : ".autocomplete("species_picture_main","id_author")."<br />";
  echo "<div class=\"clear\" style=\"text-align:center\">".__("or add a new one:")."</div>";
  echo "<div class=\"clear\">".__("Firstname")." : ".input("species_author","firstname")." </div>";
  echo "<div class=\"clear\">".__("Name")." : ".input("species_author","name")." </div>";
 */

echo "<div style=\"clear:both\" ></div>";
echo "<b>" . __("Link") . " :</b> <a target=\"_BLANK\" href=\"" . $data['species'][0]["url_found"] . "\">http://farm4.static.flickr.com/</a><br />";
echo "<b>" . __("Context") . " :</b> <a target=\"_BLANK\" href=\"" . $data['species'][0]["url_context"] . "\">http://www.flickr.com/</a><br />";
echo "<b>" . __("Original size") . " :</b> " . $data['species'][0]["width"] . "*" . $data['species'][0]["height"] . "<br />";
echo "<b>" . __("Date imported") . " :</b> " . $data['species'][0]["date_created"] . "<br />";




//echo "<b>License :</b> ".select("licence","libelle",$data['licence'] , $data['species']['0']['id_licence'])."<br />";
echo "<b>" . __("License") . " :</b> " . $data['img']['license']["text"] . "<br />";



echo "</div>";

echo "<div class=\"title_box\"><a href=\"\">" . __('Historical') . "</a></div>";
echo "<div class=\"block-historical\">";


foreach ($data['history'] as $val)
{
	echo '<img src="' . IMG . 'country/type1/' . mb_strtolower($val['iso'], 'utf-8') . '.gif" title="' . __('Made the') . ' ' . $val['date'] . '" width="18" height="12" alt="" /> ';
	echo '<a title="' . __('Made the') . ' ' . $val['date'] . '" alt="' . __('Made the') . ' ' . $val['date'] . '" href="' . LINK . 'user/profil/' . $val['id'] . '">' . $val['firstname'] . ' ' . $val['name'] . '</a>';

	echo " - " . __($val['title']);

	echo "<br />";
//
}


echo "</div>";

if (!empty($data['img']['tag']))
{
	echo "<div class=\"title_box\"><a href=\"\">" . __('Tags') . "</a></div>";
	echo "<div class=\"block-tag\">";

	foreach ($data['img']['tag'] as $tag)
	{
		echo '<a class="tag left" href="">' . $tag . '</a> ';
	}

	echo "<div style=\"clear:both\"></div>";

	echo "</div>";
}
echo "<div class=\"title_box\"><a href=\"\">" . __('Map') . "</a></div>";
echo "<div class=\"\">";


echo '
<div>

<div>

<div class="block-info">
<input type="checkbox" name="gps[add]" checked="checked" />
Add geolocalisation tag
<input id="location" class="largecolon textform" type="text" name="location" tabindex="3" value="kenya" />
<input class="button btBlueTest overlayW btMedium largecolon" id="searchLoc" type="button" value="Search">
</div>	

<div id="form-map" class="css-map"></div>

<input type="text" name="lat" id="lat" value="" />
<input type="text" name="lng" id="lng" value="" />

</div>
</div>
';



echo "</div>";


echo "<div class=\"title_box\"><a href=\"\">" . __('Informations') . "</a></div>";
echo "<div class=\"block-info\">";


if (!empty($data['img']["title"]))
	echo "<b>" . __("Title") . " :</b> " . $data['img']["title"] . "<br />";
if (!empty($data['img']["author"]))
	echo "<b>" . __("Author") . " :</b> " . $data['img']["author"] . "<br />";
if (!empty($data['img']["date-taken"]))
	echo "<b>" . __("Date of shooting") . " :</b> " . $data['img']["date-taken"] . "<br />";
if (!empty($data['img']["camera"]))
	echo "<b>" . __("Camera") . " :</b> " . $data['img']["camera"] . "<br />";
if (!empty($data['img']["legend"]))
	echo "<b>" . __("Legend") . " :</b> " . $data['img']["legend"] . "<br />";


echo "</div>";


echo "</form>";
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


/*
  echo "<span class=\"shadowImage\">
  <div class=\"passive\">
  <a href=\"".LINK."species/nominal/\">
  <table>";
  $url = IMG."main/nopictureavailable.png";
  echo "
  <tr><td class=\"img\" style=\"background: url(".$url.")\">
  <p class=\"text-ombre\">fdwdf</p></td>
  </tr></table>
  </a>
  </div></span>";
  echo "<span class=\"shadowImage\">
  <div class=\"passive\">
  <a href=\"".LINK."species/nominal/\">
  <table>";
  $url = IMG."main/nopictureavailable.png";
  echo "
  <tr><td class=\"img\" style=\"background: url(".$url.")\">
  <p class=\"text-ombre\">fdwdf</p></td>
  </tr></table>
  </a>
  </div></span>";
  echo "<span class=\"shadowImage\">
  <div class=\"passive\">
  <a href=\"".LINK."species/nominal/\">
  <table>";
  $url = IMG."main/nopictureavailable.png";
  echo "
  <tr><td class=\"img\" style=\"background: url(".$url.")\">
  <p class=\"text-ombre\">fdwdf</p></td>
  </tr></table>
  </a>
  </div></span>";

  echo "<span class=\"shadowImage\">
  <div class=\"passive\">
  <a href=\"".LINK."species/nominal/\">
  <table>";
  $url = IMG."main/nopictureavailable.png";
  echo "
  <tr><td class=\"img\" style=\"background: url(".$url.")\">
  <p class=\"text-ombre\">fdwdf</p></td>
  </tr></table>
  </a>
  </div></span>";
 */

echo "</div>";

//echo "<div style=\"clear:both\"></div>";
echo "<div id=\"to_crop\">";
echo "<h3>Picture to crop and validate</h3>";


$error = error_msg("species_picture_main", "crop");
if (!empty($error))
{
	echo $error . "<br /><br />";
}

echo "<img id=\"photo\" src=\"" . IMG . "tmp_pic/" . SIZE_SITE_MAX . "/" . $data['species'][0]["name"] . "\" />";
echo "</div>";



echo "<h3>" . __("Comments") . "</h3>";

include APP_DIR . '/element/post.php';
echo "</div>";
echo "<div style=\"clear:both\"></div>";


echo "</div>";
