<?php

//namespace Application;

use \Glial\Synapse\Singleton;
use \Glial\Acl\Acl;
use \Glial\Synapse\Controller;

class Administration extends Controller
{

    public $module_group = "Administration";

    function index()
    {

        $this->layout_name = "admin";
        $this->title = __("Administration");
        $this->ariane = "> " . $this->title;
        $dir = APP_DIR . DS . "controller";
        // Add your class dir to include path


        if (is_dir($dir)) {

            $acl = new Acl($GLOBALS['_SITE']['id_group']);

            $path = $dir . "/*.controller.php";
            $list_class = glob($path);

            //$method_class_controller = get_class_methods("\Glial\Synapse\Controller");

            foreach ($list_class as $file) {
                if (strstr($file, '.controller.php')) {

                    $full_name = pathinfo($file);
                    list($className, ) = explode(".", $full_name['filename']);


                    if ($className != __CLASS__) {
                        require($file);
                    }

                    $class = new ReflectionClass($className);
                    $tab_methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);


                    $methods = array();
                    foreach ($tab_methods as $method) {
                        if ($method->class === $className) {

                            if (strstr($method->name, 'admin')) {
                                $methods[] = $method->name;
                            }
                        }
                    }

                    //$tab3 = array_diff($methods, $method_class_controller);

                    foreach ($methods as $name) {

                        if ($acl->isAllowed($className, $name)) {



                            if (property_exists($className, "module_group")) {

                                $admin = new $className("", "", "");

                                $tmp = $admin->$name();
                                $this->data['link'][$admin->module_group][$tmp['name']] = $admin->$name();
                                $this->data['link'][$admin->module_group][$tmp['name']]['url'] = $className . "/" . $name . "/";
                            }
                        }
                    }

                    // echo "memory : " . (memory_get_usage() / 1024 / 1024) . " M  fichier : $file : type : " . filetype($file) . "\n<br />";
                }
            }
        }


        $this->set("data", $this->data);
    }

    function admin_table()
    {

        if (IS_CLI) {

            $this->view = false;
            $this->layout_name = false;
        }


        $module = array();
        $module['picture'] = "administration/tables.png";
        $module['name'] = __("Tables");
        $module['description'] = __("Make the dictionary of field");

        if (from() !== "administration.controller.php") {

            if (true) { //ENVIRONEMENT
                $dir = TMP . "database/";

                if (is_dir($dir)) {
                    $dh = opendir($dir);
                    if ($dh) {
                        while (($file = readdir($dh)) !== false) {

                            if (substr($file, 0, 1) === ".") {
                                continue;
                            }

                            unlink($dir . $file);
                        }
                    }
                }

                $sql = "SHOW TABLES";
                $res = $this->db['mysql_write']->sql_query($sql);
                while ($table = $this->db['mysql_write']->sql_fetch_array($res)) {
                    echo $table[0] . "\n";


                    $fp = fopen(TMP . "/database/" . $table[0] . ".table.txt", "w");
                    $sql = "DESCRIBE `" . $table[0] . "`";
                    $res2 = $this->db['mysql_write']->sql_query($sql);
                    while ($ob = $this->db['mysql_write']->sql_fetch_object($res2)) {
                        $data['field'][] = $ob->Field;
                    }

                    $data = serialize($data);
                    fwrite($fp, $data);
                    fclose($fp);
                    unset($data);
                }
            }
        }

        return $module;
    }

    function admin_init()
    {
        $module = array();
        $module['picture'] = "administration/gear_32.png";
        $module['name'] = __("Access Control List");
        $module['description'] = __("Update the right of users and groups");


        if (IS_CLI) {

            $this->view = false;
            $this->layout_name = false;
        }



        if (from() !== "administration.controller.php")
            $this->init();
        //echo from();
        return $module;
    }

    private function init()
    {



        if (true) { //ENVIRONEMENT
            $dir = APP_DIR . DS . "controller" . DS;
            $sql = "TRUNCATE TABLE acl_controller";
            $this->db['mysql_write']->sql_query($sql);
            $sql = "TRUNCATE TABLE acl_action";
            $this->db['mysql_write']->sql_query($sql);
            $sql = "TRUNCATE TABLE acl_action_group";
            $this->db['mysql_write']->sql_query($sql);

            if (is_dir($dir)) {
                $dh = opendir($dir);
                if ($dh) {
                    while (($file = readdir($dh)) !== false) {
                        if (strstr($file, '.controller.php')) {

                            if (filetype($dir . $file) != "file" || substr($file, 0, 1) === ".") {
                                continue;
                            }

                            $class_name = explode(".", $file);
                            $name = $class_name[0];

                            if (!class_exists($name)) {
                                echo $name . "<br />";
                                require($dir . $file);
                            }

                            $tab3 = get_class_methods($name);
                            $tab2 = get_class_methods("Controller");

                            //debug($tab2);
                            //$tab3 = array_diff($tab, $tab2);

                            $acl_controller = array();
                            $acl_controller['acl_controller']['name'] = $name;


                            $acl_action['acl_action']['id_acl_controller'] = $this->db['mysql_write']->sql_save($acl_controller);

                            if (!$acl_action['acl_action']['id_acl_controller']) {
                                echo $file . " : already exist " . $acl_action['acl_action']['id_acl_controller'] . "<br />";
                            }

                            unset($acl_controller['acl_controller']);
                            foreach ($tab3 as $name) {
                                $acl_action['acl_action']['name'] = $name;

                                if (!$this->db['mysql_write']->sql_save($acl_action)) {
                                    echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $name . " : already exist<br />";
                                }
                            }

                            /*                             * ****** */  //echo "fichier : $file : type : " . filetype($dir . $file) . "\n<br />";
                        }
                    }

                    closedir($dh);
                }
            }

            /*
              Visitor
              Member
              Administrator
              Super administrator
             */
            $sql = "TRUNCATE TABLE acl_action_group";
            $this->db['mysql_write']->sql_query($sql);
            // &#2157;etre dans un fichier de config ?
            $this->add_acl("Super administrator", "*");
            $this->add_acl("Member", "user/block_last_registered");
            $this->add_acl("Member", "user/block_last_online");
            $this->add_acl("Member", "forum/");
            $this->add_acl("Member", "user/index");
            $this->add_acl("Member", "home/");
            $this->add_acl("Member", "species/");
            $this->add_acl("Member", "home/");
            $this->add_acl("Member", "who_we_are/");
            $this->add_acl("Member", "media/");
            $this->add_acl("Member", "download/");
            $this->add_acl("Member", "search/");
            $this->add_acl("Member", "partner/");
            $this->add_acl("Member", "contact_us/");
            $this->add_acl("Member", "faq/");
            $this->add_acl("Member", "user/is_logged");
            $this->add_acl("Member", "user/login");
            $this->add_acl("Member", "user/city");
            $this->add_acl("Member", "user/author");
            $this->add_acl("Member", "user/block_newsletter");
            $this->add_acl("Member", "user/block_last_registered");
            $this->add_acl("Member", "user/profil");
            $this->add_acl("Member", "user/mailbox");
            $this->add_acl("Member", "administration/index");
            $this->add_acl("Member", "photo/admin_crop");
            $this->add_acl("Member", "photo/get_options");
            $this->add_acl("Member", "photo/index");
            $this->add_acl("Member", "translation/admin_translation");
            $this->add_acl("Member", "user/user_main");
            $this->add_acl("Member", "author/");
            $this->add_acl("Visitor", "author/");
            $this->add_acl("Visitor", "species/");
            $this->add_acl("Visitor", "home/");
            $this->add_acl("Visitor", "who_we_are/");
            $this->add_acl("Visitor", "media/");
            $this->add_acl("Visitor", "download/");
            $this->add_acl("Visitor", "search/");
            $this->add_acl("Visitor", "partner/");
            $this->add_acl("Visitor", "contact_us/");
            $this->add_acl("Visitor", "faq/");
            $this->add_acl("Visitor", "user/register");
            $this->add_acl("Visitor", "user/lost_password");
            $this->add_acl("Visitor", "user/is_logged");
            $this->add_acl("Visitor", "user/login");
            $this->add_acl("Visitor", "user/city");
            $this->add_acl("Visitor", "user/block_newsletter");
            $this->add_acl("Visitor", "user/confirmation");
            $this->add_acl("Visitor", "user/password_recover");
            $sql = "SELECT id_group, b.name as id_action, c.name as id_controller FROM acl_action_group a
		INNER JOIN acl_action b ON a.id_acl_action = b.id
		INNER JOIN acl_controller c ON c.id = b.id_acl_controller";
            $res = $this->db['mysql_write']->sql_query($sql);

            $data = array();

            while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {
                $data[$ob->id_group][$ob->id_controller][$ob->id_action] = 1;
            }

            $dir = TMP . "acl" . DS . "acl.txt";
            file_put_contents($dir, serialize($data));
        } else {
            set_flash("error", __("Error"), __("Init unavailable in mode production, turn ENVIRONEMENT to true in configuration/environement.php"));
            header("location: " . LINK . "home/index/");
            die();
        }

        //return $module;
    }

    private function add_acl($group, $tree)
    {
        $tree_id = explode("/", $tree);
        /*
          debug($tree);
          debug(count($tree));
          debug($tree_id);
          debug(count($tree_id));
         */   //test if not exist

        if (count($tree_id) == 1) {
            $sql = "select count(1) as cpt from `group` where name = '" . $group . "'";
            $res = $this->db['mysql_write']->sql_query($sql);
            $ob = $this->db['mysql_write']->sql_fetch_object($res);

            if ($ob->cpt != 1) {
                die("Group unknow !");
            }
        } elseif (count($tree_id) == 2) {

            $tree_id['0'] = \glial\utility\Inflector::camelize($tree_id['0']);

            if ($tree_id['1'] === "") {
                $sql = "select count(1) as cpt from `acl_controller` where name = '" . $tree_id['0'] . "'";
                $res = $this->db['mysql_write']->sql_query($sql);
                $ob = $this->db['mysql_write']->sql_fetch_object($res);

                if ($ob->cpt < 1) {
                    die("Controller unknow !");
                }
            } else {
                $sql = "select count(1) as cpt from `acl_action` where name = '" . $tree_id['1'] . "'";
                $res = $this->db['mysql_write']->sql_query($sql);
                $ob = $this->db['mysql_write']->sql_fetch_object($res);

                if ($ob->cpt < 1) {
                    echo "group : " . $group . "<br />";
                    die("Acion unknow (" . $tree_id['1'] . ") !");
                }
            }
        }


        if (count($tree_id) == 1) {
            $sql = "REPLACE INTO acl_action_group (id_acl_action, id_group) SELECT b.id as acl_action, c.id FROM acl_controller a
		INNER JOIN acl_action b ON a.id = b.id_acl_controller
		LEFT JOIN `group` c on 1 = 1
		WHERE c.name = '" . $group . "'";
        } else
        if (count($tree_id) == 2) {

            if ($tree_id['1'] === "") {
                $sql = "REPLACE INTO acl_action_group (id_acl_action, id_group) SELECT b.id as acl_action, c.id FROM acl_controller a
			INNER JOIN acl_action b ON a.id = b.id_acl_controller
			LEFT JOIN `group` c on 1 = 1
			WHERE c.name = '" . $group . "' AND a.name = '" . $tree_id['0'] . "'";
            } else {
                $sql = "REPLACE INTO acl_action_group (id_acl_action, id_group) SELECT b.id as acl_action, c.id FROM acl_controller a
			INNER JOIN acl_action b ON a.id = b.id_acl_controller
			LEFT JOIN `group` c on 1 = 1
			WHERE c.name = '" . $group . "' AND a.name = '" . $tree_id['0'] . "' AND b.name = '" . $tree_id['1'] . "'";
            }
        } else {
            die("Must be XX/YY with last '/'  XX/YY/");
        }

        $this->db['mysql_write']->sql_query($sql);
    }

    function generate_model()
    {

        //php index.php administration generate_model

        $this->layout_name = false;

        $sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA ='species' and TABLE_TYPE = 'BASE TABLE'";
        $res = $this->db['mysql_write']->sql_query($sql);

        while ($ob2 = $this->db['mysql_write']->sql_fetch_object($res)) {

            $table = $ob2->TABLE_NAME;

            $file = APP_DIR . "/model/" . $table . ".php";

            if (!file_exists($file)) {
                $fp = fopen($file, "w");

                echo "FILE : " . $file . "\n";

                $text = "<?php\n\nnamespace Application\Model;
use \Glial\Synapse\Model;

class " . $table . " extends Model\n{\nvar \$schema = \"";

                $sql = "SHOW CREATE TABLE `" . $table . "`";
                $res2 = $this->db['mysql_write']->sql_query($sql);

                $array = $this->db['mysql_write']->sql_fetch_array($res2);

                $sql = "DESCRIBE `" . $table . "`";
                $res3 = $this->db['mysql_write']->sql_query($sql);

                $i = 0;

                unset($data);
                unset($field);

                while ($ob = $this->db['mysql_write']->sql_fetch_object($res3)) {
                    $field[] = "\"" . $ob->Field . "\"";

                    $data[$table][$i]['field'] = $ob->Field;
                    $data[$table][$i]['type'] = $ob->Type;
                    $i++;
                }

                $text .= $array[1];
                $text .= "\";\n\nvar \$field = array(" . implode(",", $field) . ");\n\nvar \$validate = array(\n";

                foreach ($data[$table] as $field) {
                    if ($field['field'] == "id") {
                        continue;
                    }
                    if (mb_substr($field['field'], 0, 2) === "id") {
                        $text .= "\t'" . $field['field'] . "' => array(\n\t\t'reference_to' => array('The constraint to " . mb_substr($field['field'], 3) . ".id isn\'t respected.','" . mb_substr($field['field'], 3) . "', 'id')\n\t),\n";
                    } elseif (mb_substr($field['field'], 0, 2) === "ip") {
                        $text .= "\t'" . $field['field'] . "' => array(\n\t\t'ip' => array('your IP is not valid')\n\t),\n";
                    } elseif ($field['field'] === "email") {
                        $text .= "\t'" . $field['field'] . "' => array(\n\t\t'email' => array('your email is not valid')\n\t),\n";
                    } else {

                        if (mb_strstr($field['type'], "int")) {
                            $text .= "\t'" . $field['field'] . "' => array(\n\t\t'numeric' => array('This must be an int.')\n\t),\n";
                        } elseif (mb_strstr($field['type'], "time")) {
                            $text .= "\t'" . $field['field'] . "' => array(\n\t\t'time' => array('This must be a time.')\n\t),\n";
                        } elseif (mb_strstr($field['type'], "date")) {
                            $text .= "\t'" . $field['field'] . "' => array(\n\t\t'date' => array('This must be a date.')\n\t),\n";
                        } elseif (mb_strstr($field['type'], "datetime")) {
                            $text .= "\t'" . $field['field'] . "' => array(\n\t\t'not_empty' => array('This must be a date time.')\n\t),\n";
                        } elseif (mb_strstr($field['type'], "float")) {
                            $text .= "\t'" . $field['field'] . "' => array(\n\t\t'decimal' => array('This must be a float.')\n\t),\n";
                        } else {
                            $text .= "\t'" . $field['field'] . "' => array(\n\t\t'not_empty' => array('This field is requiered.')\n\t),\n";
                        }
                    }
                }

                $text .= ");\n\nfunction get_validate()\n{\nreturn \$this->validate;\n}\n}\n";

                fwrite($fp, $text);
                fclose($fp);

                unset($data);
            }
        }
    }

    function save_database()
    {
        $this->layout_name = false;
        $this->view = false;

        $path = "/home/www/arkadin/data/database/arkadin/";


        $sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA ='arkadin' and TABLE_TYPE = 'BASE TABLE'";
        $res = $this->db['mysql_write']->sql_query($sql);
        while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {
            shell_exec("mkdir -p " . $path . "structure/table/" . $ob->TABLE_NAME);


            // create structure table
            $sql2 = "SHOW CREATE TABLE `" . $ob->TABLE_NAME . "`;";
            $res2 = $this->db['mysql_write']->sql_query($sql2);

            while ($ob2 = $this->db['mysql_write']->sql_fetch_array($res2, MYSQL_NUM)) {
                echo 'create table : ' . $ob->TABLE_NAME . "\n";

                file_put_contents($path . "structure/table/" . $ob->TABLE_NAME . "/table.sql", $ob2[1] . ";");
            }

            // create index table

            $sql3 = "SHOW INDEXES FROM `" . $ob->TABLE_NAME . "`";
            $res3 = $this->db['mysql_write']->sql_query($sql3);


            while ($ob3 = $this->db['mysql_write']->sql_fetch_object($res3)) {
                echo 'create indexes : ' . $ob->TABLE_NAME . "\n";


                if ($ob3->Key_name == "PRIMARY") {
                    $index[] = "ALTER TABLE `" . $ob->TABLE_NAME . "` ADD PRIMARY KEY (  `" . $ob3->Column_name . "` );";
                } else {
                    if ($ob3->Non_unique == "1") {
                        $index[] = "CREATE UNIQUE INDEX `" . $ob3->Key_name . "`  ON `" . $ob->TABLE_NAME . "` (  `" . $ob3->Column_name . "` );";
                    } else {
                        $index[] = "CREATE INDEX `" . $ob3->Key_name . "` ON `" . $ob->TABLE_NAME . "` (  `" . $ob3->Column_name . "` );";
                    }
                }
            }

            file_put_contents($path . "structure/table/" . $ob->TABLE_NAME . "/index.sql", implode("\n", $index));
        }

        $sql33 = "SELECT * FROM INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA ='arkadin' and TABLE_TYPE = 'VIEW'";
        $res33 = $this->db['mysql_write']->sql_query($sql33);
        while ($ob33 = $this->db['mysql_write']->sql_fetch_object($res33)) {
            shell_exec("mkdir -p " . $path . "structure/view/" . $ob33->TABLE_NAME);
        }
    }

    function insert_backup_table()
    {
        $this->view = false;
        $this->layout_name = false;

        include_once(LIBRARY . "Glial/sgbd/mysql/backup.php");
        include_once (LIB . "wlHtmlDom.php");

        $_SQL = singleton::getInstance(SQL_DRIVER);

        $data = glial\sgbd\mysql\backup::insert();

        //debug($data);
    }

}

