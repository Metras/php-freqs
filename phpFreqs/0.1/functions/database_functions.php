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
  $sql='select version from options;';
  $check=@mysql_query($sql,$db);
  
  if (!$check)
  {
    if (mysql_errno() == "1146")
    {
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
  $users='CREATE TABLE users (id int, username varchar(15), first varchar(25), email varchar(255), password varchar(16), active BOOL, verified BOOL, verifycode varchar(75), location int, lat float, lon float, gmt int signed)';
  $options='CREATE TABLE options (version int, install_date int, gmt int signed, path varchar(255), name varchar(255), tagline varchar(255))';
  $freq='CREATE TABLE freq (id int, freq int, category int, lat float, lon float, verified BOOL, protocol int, location int)';
  $tags='CREATE TABLE tags (user int, freq int, tags text)';
  $cat='CREATE TABLE category(id int, decription varchar(50), abbrev varchar(10))';
  $com_freq='CREATE TABLE comments_freq (user int, freq int, date int, comment text)';
  $com_loc='CREATE TABLE comments_loc (user int, location int, date int, comment text)';
  $country='CREATE TABLE country (id int, name varchar(75))';
  $state='CREATE TABLE state (id int, name varchar(75), country int)';
  $city='CREATE TABLE city (id int, name varchar(75), state int)';
  
  install_create_tables($users, $db);
  install_create_tables($options, $db);
  install_create_tables($freq, $db);
  install_create_tables($tags, $db);
  install_create_tables($cat, $db);
  install_create_tables($com_freq, $db);
  install_create_tables($com_loc, $db);
  install_create_tables($country, $db);
  install_create_tables($state, $db);
  install_create_tables($city, $db);
  //Not sure if there is a more effecient way to do above, but it works ;)

  //If we are still alive, then installation must have been successful
  return(true);

}

function install_create_tables($sql, $db)
{
  $result=mysql_query($sql,$db);
  if (!$result)
  {
    die('Oops.. something went wrong '.mysql_error().'<br>');
  }
}
	
?>
