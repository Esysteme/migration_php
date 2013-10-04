<?php



$author = -1;
$tag_search = -1;

function headerform()
{
	echo '</ul>';
	echo '<div class="clear"></div>';
	echo '<input class="button btBlueTest overlayW btMedium confirm" type="submit" value="' . __("Update") . '">';
	echo '</form></div>';
}

if ( count($data['to_sort']) > 0 )
{

	foreach ($data['to_sort'] as $tab )
	{
		if ( $author != $tab['id_species_author'] )
		{
			if ( $author !== -1 )
			{
				headerform();
			}
			echo '<div><form class="pending" name="" action="" method="post">';
			echo '<h3><a href="' . LINK . 'author/image/' . $tab['id'] . '">' . $tab['surname'] . '</a> <span class="right"><ol><li id="validall">Valid all</li><li id="invalidall">Invalid all</li></ol></span></h3>';
			$author = $tab['id_species_author'];
			$new = 1;

			echo '<ul>';
		}

		/*
		  if ( $tag_search != $tab['tag_search'] || $new == 1 )
		  {
		  echo '<h3 style="color:red">' . $tab['tag_search'] . '</h3>';
		  $tag_search = $tab['tag_search'];
		  } */



		if ( $tab['gg2'] > 1 )
		{
			$class = "img_valid";
		}
		else
		{
			$class = "img_dunno";
		}

		if ( $tab['gg2'] !== $tab['gg'] )
		{
			$class = "img_duble";
		}

		echo '<li class="selector ' . $class . '">';

		echo '<img title="' . __('Tag') . " :\n" . $tab['tag_search'] . "\n------------------------\n" . __('Species') . " :\n" . $tab['species'] . '" src="' . str_replace("_s", "_q", $tab['miniature']) . '" height="150" width="150"  /> ';
		echo '<input type="hidden" name="link__species_picture_id__species_picture_search[' . $tab['id_link'] . ']" value="1" />';
		echo '<span><img style="display:block;" src="' . str_replace("_s", "_z", $tab['miniature']) . '"></span>';
		echo '</li>';

		$new++;
	}
	headerform();
}