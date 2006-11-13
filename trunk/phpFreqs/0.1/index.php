<?php
	
	//Index Page
	//Right off the bat, lets determine where we live
	
	$maindir=$_SERVER["SCRIPT_FILENAME"];
  $lastslash=strrpos($maindir,"/");
  $maindir=substr($maindir,0,$lastslash+1);
  unset($lastslash);
	
  //Load Config settings
  require_once('config.php');
  require_once('functions/functions.php');
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
  unset($installed);
  //Installation Check completed, start page as normal.

?>
<html>
	<head>
	 <title>phpFreqs</title>
	 <!-- This has to be updated to choose theme's CSS -->
	 <link rel="stylesheet" type="text/css" href="themes/default/style.css">
	</head>
	
<body>
<!--Start with our main logo
Really anything can be put into this <DIV> just keep it inside the tags. -->

<div class="logo"></div>
<div class="sidebar"><?php dosidebar();?></div>
<div class="content">
<?php
//Load specific module here.
//If no module specified, we'll load main page.

  if (!isset($_GET['module']))
  {
    include $maindir."/modules/main.php";
  }
  else
  {
    $modname=$maindir."modules/".$_GET['module'].".php";
    //Test if module exists, otherwise load main
    if (file_exists($modname))
    {
      include $modname;
    }
    else
    {
      include $maindir."/modules/main.php";
    }
  }

?>
</div>	
</body>

</html>	
