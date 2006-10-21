<?php
	
	//Index Page
	
  //Load Config settings
  require_once('config.php');
  require_once('functions/database_functions.php');
    
  /*  Need to add a simple config check routine */
  if ($config_db_database == "")
  {
    die('Database Information Incomplete');
  }
  /*  End Config File check */
  
  //DB Check, get our db link
  $db=dbcheck();
  //**Need to update this, instead of dieing in the function, it should return false and be delt with.
  
  
  //Now you can pass $db to any function that requires DB access.  
    
  /*  Test for Installation Complete  */
  $installed=dbinstalled($db);
  if (!$installed)
  {
    doinstall($db);
  }

?>
<html>
	<head>
	 <title>phpFreqs</title>
	 <link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
<body>

Home Page ;)
	
</body>

</html>	
