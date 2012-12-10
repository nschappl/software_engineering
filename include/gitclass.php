<?php 
//this class is an important class lol
class gitclass {

    var $db_host;
    var $username;
    var $pwd;
    var $database;
    var $tablename;
    var $connection;
    
    function InitDB($host,$uname,$pwd,$database,$tablename)
    {
        $this->db_host  = $host;
        $this->username = $uname;
        $this->pwd  = $pwd;
        $this->database  = $database;
        $this->tablename = $tablename;
        
    }
    
    function login($username) {
    }
    
    function addRepo()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }
        
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
    if($_POST['repo_archive_url'] && $_POST['title'] && $_POST['summary'] && $_POST['client'])
    {
      $insert_query = 'insert into '.$this->tablename.'(
                url, 
                proj_title,
                exec_summ,
                client,
                repo_name,
                repo_login
                )
                values
                (
                "' . $_POST['repo_archive_url'] . '",
                "' . $this->SanitizeForSQL($_POST['title']) . '",
                "' . $this->SanitizeForSQL($_POST['summary']) . '",
                "' . $this->SanitizeForSQL($_POST['client']) . '",
                "' . $this->SanitizeForSQL($_POST['repo_name']) . '",
                "' . $this->SanitizeForSQL($_POST['repo_login']) . '"

                )';


            if(!mysql_query( $insert_query ,$this->connection))
            {
                $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
                return false;
            }
        
        }
        
    }

    function removeRepo()
    {
        if(!isset($_POST['remove_sub']))
        {
            return false;
        }

        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }

        if($_POST['repo_name'])
        {
            $remove_query = 'DELETE FROM '.$this->tablename.' WHERE repo_name="'.$_POST['repo_name'].'"';
           
            if(!mysql_query( $remove_query, $this->connection))
            {
                $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
                return false;

            }
        }
    }
    
    function getRepos() 
    {
    
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
    
    $select_query = 'select * from '.$this->tablename;
    
    $result = mysql_query($select_query, $this->connection);
    $data = array();
    while($row = mysql_fetch_assoc($result))
    {
      $data[] = $row;
    }

    return $data;
    
    }

    function cloneRepos()
    {

       include("./include/membersite_config.php");
             
       if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        //Make sure directories are created
        if(!is_dir('/var/server_files/tracked_projects'))
        {
            mkdir('/var/server_files/tracked_projects', 0777);
        }
 
        $tracked_repos = $this->getRepos();
        
        if(is_array($tracked_repos))
        {
            foreach ($tracked_repos as $repo)
            {
                $file_name ="".$repo['repo_name']."".time().".tar.gz";
           
                $token = $fgmembersite->getusertoken();

                $url = explode("{",$repo['url']);

                mkdir('/var/server_files/tracked_projects/'.$repo['repo_name'], 0777);
                system('curl -H "Authorization: token '.$token.'" -L -o /var/server_files/tracked_projects/'.$file_name.' '.$url[0].'tarball');
                system('tar -xzf /var/server_files/tracked_projects/'.$file_name.' -C /var/server_files/tracked_projects/'.$repo['repo_name'].''); 
            }
        }


    }

    function cleanRepos()
    {
        system('rm -r /var/server_files/tracked_projects');
    }
    
    
    function SanitizeForSQL($str)
    {
        if( function_exists( "mysql_real_escape_string" ) )
        {
              $ret_str = mysql_real_escape_string( $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }
    
    
    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }  
    
    function DBLogin()
    {
        
        $this->connection = mysql_connect($this->db_host,$this->username,$this->pwd);
        
        if(!$this->connection)
        {   
            $this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
            return false;
        }
        if(!mysql_select_db($this->database, $this->connection))
        {
            $this->HandleDBError('Failed to select database: '.$this->database.' Please make sure that the database name provided is correct');
            return false;
        }
        if(!mysql_query("SET NAMES 'UTF8'",$this->connection))
        {
            $this->HandleDBError('Error setting utf8 encoding');
            return false;
        }
        
        $this->Ensuretable();
        
        return true;
    }   
    
    
    function Ensuretable()
    {
        $result = mysql_query("SHOW COLUMNS FROM $this->tablename");   
        if(!$result || mysql_num_rows($result) <= 0)
        {
            return $this->CreateTable();
        }
        return true;
    }
    
    function CreateTable()
    {
        $qry = "Create Table $this->tablename (".
               "id_repo INT NOT NULL AUTO_INCREMENT ,".
               "url VARCHAR( 256 ) NOT NULL ,".
               "proj_title VARCHAR ( 256 ) NOT NULL, ".
               "exec_summ text NOT NULL, ".
               "client VARCHAR( 256 ) NOT NULL,".
               "repo_name VARCHAR ( 256 ) NOT NULL,".
               "repo_login VARCHAR ( 256 ) NOT NULL,".
               "UNIQUE ( url ), ".
               "PRIMARY KEY ( id_repo )".
               ")";
                
                
        if(!mysql_query($qry,$this->connection))
        {
            $this->HandleDBError("Error creating the table \nquery was\n $qry");
            return false;
        }
        return true;
    }
    
    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }
    
    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n mysqlerror:".mysql_error());
    }
    

}

?>