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
  
  
  /*  Test for tables, no tables means run installation routine  */

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
