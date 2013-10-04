<?php



class TestAction
{
   
	
	function doEcho($data){
        return $data;
    }

    function multiply($num){
        if(!is_numeric($num)){
            throw new Exception('Call to multiply with a value that is not a number');
        }
        return $num*8;
    }

    function getTree($id)
	{
        include "../../../../../config.php";
		include "../../../../../class/sql.lib.php";
		
		$out = array();
		
		$arbo = explode(".",$id);
		$NbArbo = count($arbo)-1;
		
		
		switch ($NbArbo)
		{
			case 1:
							
				$sql = "select * from SpeciesKingdom order by ScientificName";
				$res = sql::sql_query($sql);

				
				while ($ob = mysql_fetch_object($res))
				{
					$ob->ScientificName = ucwords(strtolower($ob->ScientificName));
					
					array_push($out, array(
						'id'=>'n.1.'.$ob->Id,
						'text'=>$ob->ScientificName,
						'leaf'=>false
					));
					
				}
			break;
			
			case 2:
			{
				
				$sql = "select * from SpeciesPhylum where IdSpeciesKingdom = '".$arbo[2]."' order by ScientificName";
				$res = sql::sql_query($sql);
				while ($ob = mysql_fetch_object($res))
				{
					array_push($out, array(
						'id'=>$id.".".$ob->Id,
						'text'=>$ob->ScientificName,
						'leaf'=>false
					));
				}
			}
			case 3:
				
				$sql = "select * from SpeciesClass where IdSpeciesPhylum = '".$arbo[3]."' order by ScientificName";
				$res = sql::sql_query($sql);
				
				while ($ob = mysql_fetch_object($res))
				{
					array_push($out, array(
						'id'=>$id.".".$ob->Id,
						'text'=>$ob->ScientificName,
						'leaf'=>false
					));
				}
			break;
			case 4:
				$sql = "select * from SpeciesOrder where IdSpeciesClass = '".$arbo[4]."' and IsValid=1 order by ScientificName";
				$res = sql::sql_query($sql);
			
				while ($ob = mysql_fetch_object($res))
				{
					array_push($out, array(
						'id'=>$id.".".$ob->Id,
						'text'=>$ob->ScientificName,
						'leaf'=>false
					));
				}
			case 5:
			
				$sql = "select * from SpeciesFamily where IdSpeciesOrder = '".$arbo[5]."' and IsValid=1 order by ScientificName";
				$res = sql::sql_query($sql);
				
				while ($ob = mysql_fetch_object($res))
				{
					array_push($out, array(
						'id'=>$id.".".$ob->Id,
						'text'=>$ob->ScientificName,
						'leaf'=>false
					));
				}
			break;
			
			
			case 6:
				$sql = "select * from SpeciesGenus where IdSpeciesFamily = '".$arbo[6]."' and IsValid=1 order by ScientificName";
				$res = sql::sql_query($sql);
				
				while ($ob = mysql_fetch_object($res))
				{
					array_push($out, array(
						'id'=>$id.".".$ob->Id,
						'text'=>$ob->ScientificName,
						'leaf'=>false
					));
				}
			break;
			
			case 7:
			
				$sql = "select * from SpeciesMain where IdSpeciesGenus = '".$arbo[7]."' and IsValid!=0 order by ScientificName";
				$res = sql::sql_query($sql);
				
				while ($ob = mysql_fetch_object($res))
				{
				
					array_push($out, array(
						'id'=>$id.".".$ob->Id,
						'text'=>"".$ob->ScientificName."",
						'leaf'=>true
					));
					
				}
			break;
		}
        return $out;
    }
}


?>