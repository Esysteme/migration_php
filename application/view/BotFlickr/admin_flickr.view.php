<?php


$title = "";

foreach($data['img'] as $img)
{
	if ($title != $img['tag_search'])
	{
		echo '<div class="clear"></div>';
		echo '<h3>'.$img['tag_search'].'</h3>';
		$title = $img['tag_search'];
	}
	
	
	if ($img['to_delete'] == 1)
	{
		$class = 'color-underline';
	}
	else
	{
		$class = '';
	}
	echo '<div class="left '.$class.'" style="width:102px; padding:1px; height: 120px">';
	echo '<img src="'.$img['miniature'].'" height="'.$img['height'].'" width="'.$img['width'].'" />';
	echo '<br />'.$img['photo_id'];
	echo '</div>';
}

echo '<div class="clear"></div>';