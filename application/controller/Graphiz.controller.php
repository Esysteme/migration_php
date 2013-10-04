<?php

use \glial\synapse\Singleton;
use \glial\synapse\Controller;

class Graphiz extends Controller
{

    function index()
    {
        $this->view = false;
        $this->layout_name = false;

        

        $sql = "SELECT TABLE_NAME, REFERENCED_TABLE_NAME
FROM information_schema.referential_constraints
WHERE constraint_schema =  'species' order by REFERENCED_TABLE_NAME desc, TABLE_NAME";

        $res = $this->db['mysql_write']->sql_query($sql);


        $fp = fopen("test.dot", "w");

        if ( $fp ) {
            fwrite($fp, "graph test {\n");
            fwrite($fp, "sep=\"+150\"\n");
            fwrite($fp, "overlap=scalexy\n");
            fwrite($fp, "splines=true");
            //fwrite($fp, "nodesep=1.6");

            $entity = array();
            while ( $ob = $this->db['mysql_write']->sql_fetch_object($res) ) {
                fwrite($fp, $ob->TABLE_NAME . " -- " . $ob->REFERENCED_TABLE_NAME . "\n");
            }

            fwrite($fp, "}");

            $ret = shell_exec("dot -Tpng test.dot -o image/test.png");
            echo $ret;

            echo '<img src="' . IMG . '/test.png" />';
        }
    }

	function test()
{
	$this->layout_name = false;
	$this->view = false;

	echo "gg\n";
}

}
