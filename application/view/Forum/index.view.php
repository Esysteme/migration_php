<?php

echo "Topics: Active | Unanswered<br />";




foreach ($out['category'] as $cat)
{
	echo "<div class=\"forum\">";
	echo "<table>";

	echo '<thead>
			<tr>
				<th scope="col" class="tcl">'.$cat['name'].'</th>
				<th scope="col" class="tc2">'.__("Topics").'</th>
				<th scope="col" class="tc3">'.__("Posts").'</th>
				<th scope="col" class="tcr">'.__("Last post").'</th>
			</tr>
	</thead>';

	
	foreach ($out['forum'][$cat['id']] as $forum)
	{
		
		if ($cat['id'] == 2)
		{
			$link = "0-".$out['tree_id']."-".$forum['id'];
		}
		else
		{
			$link = $forum['id']."-".$out['tree_id'];
		}
		
		
		
		if ( empty($forum['description']) )
		{
			$forum['description'] = __("Any discution about")." ".$forum['name'];
		
		}
		
		echo '
		<tbody>
			<tr class="rowodd">
				<td class="tcl">
					<div class="icon"><div class="nosize">1</div></div>
						<div class="tclcon">
							<div>
								<h4><a href="'.LINK.'forum/view/'.$link.'/">'.$forum['name'].'</a></h4>
								<div class="forumdesc">'.$forum['description'].'</div>
							</div>
						</div>
				</td>
				<td class="tc2">319</td>
				<td class="tc3">3,244</td>
				<td class="tcr"><a href="viewtopic.php?pid=42881#p42881">2011-10-19 00:01:56</a><br /><span class="byuser">by Franz</span></td>
			</tr>
		</tbody>';
	}
	
	echo "</table>";
	echo "</div>";
}



echo '
<div class="block" id="brdstats">
	<h2><span>Informations sur les forums</span></h2>
	<div class="box">
		<div class="inbox">
			<dl class="conr">
				<dt><strong>'.__("Board statistics").'</strong></dt>
				<dd><span>'.__("Total number of registered users").'&nbsp;: <strong>5 015</strong></span></dd>
				<dd><span>'.__("Total number of topics").'&nbsp;: <strong>8 631</strong></span></dd>
				<dd><span>'.__("Total number of posts").'&nbsp;: <strong>78 461</strong></span></dd>
			</dl>
			<dl class="conl">
				<dt><strong>Informations sur l\'utilisateur</strong></dt>
				<dd><span>'.__("Newest registered user").'&nbsp;: RebeccaAngela101</span></dd>
				<dd><span>'.__("Registered users online").'&nbsp;: <strong>0</strong></span></dd>
				<dd><span>'.__("Registered users today").'&nbsp;: <strong>23</strong></span></dd>
				<dd><span>'.__("Guests online").'&nbsp;: <strong>12</strong></span></dd>
			</dl>
			<div class="clearer"></div>
			<dl class="clearb" id="onlinetodaylist">
				<dt><strong>En ligne aujourd\'hui&nbsp;:  </strong></dt>				
				<dd>RebeccaAngela101,</dd> 
				<dd><span style="color:#e32d2d">fanf73</span>,</dd> 
				<dd><span style="color:#32902c">PascL</span>,</dd> 
				<dd>trotirider,</dd> 
				<dd><span style="color:#e32d2d">Mpok</span>,</dd> 
				<dd>SR-71,</dd> 
				<dd>washing02,</dd> 
				<dd><span style="color:#32902c">adaur</span>,</dd> 
				<dd>Anthrax,</dd> 
				<dd>Hutch,</dd> 
				<dd>Wan,</dd> 
				<dd>justinlouis11,</dd> 
				<dd>thib3113,</dd> 
				<dd>Le bibliothécaire,</dd> 
				<dd>frankmann,</dd> 
				<dd><span style="color:#e32d2d">Otomatic</span>,</dd> 
				<dd>BassPression,</dd> 
				<dd>Leferhargeash,</dd> 
				<dd>Cr@sh_,</dd> 
				<dd><span style="color:#32902c">Defaz</span>,</dd> 
				<dd>Boxsmoroapart,</dd> 
				<dd>x3dt,</dd> 
				<dd>bibi</dd>
			</dl>
		</div>
	</div>
</div>';


	
	

?>