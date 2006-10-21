<?php
	
	/*
	
  
  	any/all code in this file should be wrapped inside of a function
	
    Make sure all Functions are well documented.
  
  */
	
	
	
function dbcheck()
{  

/*
  usage:
  $db_link = dbcheck();
  
  This function will read in global variables pulled from the config file.
  
  It returns a resource link to the database, so that the connection to the database can be reused in other functions.
*/

  global $config_db_host, $config_db_port, $config_db_username, $config_db_password, $config_db_database;

  //Do a DB Check
	$db_link=@mysql_connect($config_db_host.":".$config_db_port,$config_db_username,$config_db_password);
	if (!$db_link)
	{
	 //Trap error connecting to DB
	 die('Could not connect: ' . mysql_error());
	}
	$test_db=mysql_select_db($config_db_database,$db_link);
	
  if (!$test_db)
  {
    if (mysql_errno() == "1049")
    {
      //Database not found, try creating.
      $sql="create database $config_db_database;";
      $makeit=mysql_query($sql,$db_link);
      if (!$makeit)
      {
        die('No Database Found, Could not Create one.<br>');
      }
      else
      {
        $test_db=mysql_select_db($config_db_database,$db_link);
        if (!test_db)
        {
          die('Fatal Error: Database Selection after Creation.<br>');
        }
      }
    }
    else
    {
      echo 'Could not select DB<br><br>'.mysql_error().'<br>'.mysql_errno();
    }  
  }
  return($db_link);
} 

function dbinstalled($db)
{
  //Verify if system is installed
  $sql='select version from installed;';
  $check=@mysql_query($sql,$db);
  
  if (!$check)
  {
    if (mysql_errno() == "1146")
    {
      echo "Table Doesn't Exist!<br>";
      return(false);
    }
    else
    {
      die('Fatal Error: Install Version unverifiable<br>'.mysql_errno());
    }  
  }
  else
  {
    return(true);
  }
}

function doinstall($db)
{
  //Create tables in DB
  
}

	
?>
