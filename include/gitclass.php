<?php 

//this class is an important class lol

class gitclass {

    var $db_host;
    var	$username;
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
	if($_POST['repo_url'])
	{
	  $insert_query = 'insert into '.$this->tablename.'(
                name
                )
                values
                (
                "' . $this->SanitizeForSQL($_POST['repo_url']) . '"

                )';      
	  if(!mysql_query( $insert_query ,$this->connection))
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
                "name VARCHAR( 256 ) NOT NULL ,".
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