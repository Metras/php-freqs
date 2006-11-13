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
  
  send_sql($users, $db);
  send_sql($options, $db);
  send_sql($freq, $db);
  send_sql($tags, $db);
  send_sql($cat, $db);
  send_sql($com_freq, $db);
  send_sql($com_loc, $db);
  send_sql($country, $db);
  send_sql($state, $db);
  send_sql($city, $db);
  //Not sure if there is a more effecient way to do above, but it works ;)

  //If we are still alive, then installation must have been successful
  load_locations($db);
  return(true);

}

function send_sql($sql, $db)
{
  $result=mysql_query($sql,$db);
  if (!$result)
  {
    die('Oops.. something went wrong<br>'.$sql.'<br>'.mysql_error().'<br>');
  }
  return($result);
}

function load_locations($db)
{
  //Lets read in our data to install basic locales.
  global $maindir;
  
  $datadir=$maindir."functions/data/";
  $readdata=scandir($datadir);  //Read directory
  
  //Now strip all non-directory entries
  $count=0;
  foreach($readdata as $dirdata)
  {
    if (!is_dir($dirdata))
    { 
      if (!strpos($dirdata,".",0))
      {
        $countries[$count]=$dirdata;
        $count++;
      }
    }
  }
  //We should be left with an Array containing our Country folders.
  
  foreach($countries as $country)
  {
    //Write country to database (If it doesn't exist)
    $sql="insert into country values('','$country')";
    /*This needs to be updated.. we should select from database, and upon failure add*/ 
    send_sql($sql,$db);
    
    //Now get our country ID from the database
    $country_id=mysql_insert_id();    
    
    $statesdir=$datadir.$country."/";
    $readdata=scandir($statesdir);  //Read directory
    //Now strip all directory entries
    
    $count=0;
    foreach($readdata as $dirdata)
    {
      if (!is_dir($dirdata))
      { 
        $states[$count]=$dirdata;
        $count++;
      }
    }
    //At this point we know all states in a country, for each state read in places
    foreach($states as $state)
    {
      $currentfile=$statesdir.$state;
      
      //Write state to database (If it doesn't exist)
      $sql="insert into state values('','$state',$country_id)";
      /*This needs to be updated.. we should select from database, and upon failure add*/ 
      send_sql($sql,$db);
    
      //Now get our state ID from the database
      $state_id=mysql_insert_id();
      
      $fp=fopen($currentfile,"r");
      while (!feof($fp)) 
      {
        $buffer = trim(fgets($fp, 4096));
        if ($buffer != "")
        {
          $buffer=str_replace("'","\'",$buffer);
          $sql="insert into city values('','$buffer',$state_id)";
          send_sql($sql,$db);
        }
      }
      fclose($fp);
    }
  }
}

function showme($data)
{
  echo "<pre>";
  var_dump($data);
  echo "</pre>";
 }	
?>
