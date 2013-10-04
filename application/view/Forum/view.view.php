<?php



echo "<div class=\"forum\">";


echo '
<div class="pagepost">
	<p class="pagelink"><span class="postlink right"><a class="button btBlueTest overlayW btMedium" href="'.LINK.'forum/post/'.$out['link'].'/">Post new topic</a></span>
	<span class="button btGreyLite btMedium">'.__("Pages").' : </span><strong class="button btBlueTest overlayW btMedium">1</strong>
	</p>
	
</div>';













echo "<table>";

echo '<thead>
		<tr>
			<th scope="col" class="tcl">'.__("Topic").'</th>
			<th scope="col" class="tc2">'.__("Replies").'</th>
			<th scope="col" class="tc3">'.__("Views").'</th>
			<th scope="col" class="tcr">'.__("Last post").'</th>
		</tr>
</thead>';



if (count($out['forum']) == 0)
{
	echo '
	<tbody>
		<tr class="rowodd">
			<td class="tcl">
				<div class="icon"><div class="nosize">1</div></div>
					<div class="tclcon">
						<div>
							<h4>'.__("Forum is empty.").'</h4>
						</div>
					</div>
			</td>
			<td class="tc2"></td>
			<td class="tc3"></td>
			<td class="tcr"></td>
		</tr>
	</tbody>';
}
else
{
	foreach ($out['forum'][$cat['id']] as $forum)
	{
		if ( empty($forum['description']) )
		{
			$forum['description'] = __("Any discution about ").$forum['name'];
		
		}
		
		echo '
		<tbody>
			<tr class="rowodd">
				<td class="tcl">
					<div class="icon"><div class="nosize">1</div></div>
						<div class="tclcon">
							<div>
								<h4><a href="'.LINK.'forum/view/'.$link.'/">'.$forum['name'].'</a></h4>
							</div>
						</div>
				</td>
				<td class="tc2">319</td>
				<td class="tc3">3,244</td>
				<td class="tcr"><a href="viewtopic.php?pid=42881#p42881">2011-10-19 00:01:56</a><br /><span class="byuser">by Franz</span></td>
			</tr>
		</tbody>';
	}
}


echo "</table>";
echo "</div>";





?>