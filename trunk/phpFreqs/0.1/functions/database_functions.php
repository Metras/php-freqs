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
	
	
	$test_Db=mysql_select_db($config_db_database,$db_link);
  if (!test_db)
  {
    die('Could not select DB<br><br>'.mysql_error());
  }
	
	return($db_link);
} 	
?>
