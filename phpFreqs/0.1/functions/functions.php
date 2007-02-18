<?php


function dosidebar()
{
  //First, Show Links.
  
  /*Follow below for this basic idea for consistency.  Title/HR*/
  echo "Navigation<br>";
  echo '<hr width="50%" align="left">';
  /*Follow above for this basic idea for consistency.  Title/HR*/
  echo "<ul>";
  //Always include a home Link 
  echo '<li><a href="index.php">Home</a><br>';
  //Pull a list of modules and display.
  $list=glob($maindir."modules/*.php");
  natcasesort($list);
  foreach ($list as $makeit)
  {
    $link=str_replace("modules/","",$makeit);
    $link=str_replace(".php","",$link);
    if ($link != "main")
    {
      echo "<li><a href=\"index.php?module=$link\">$link</a><br>";
    }
  }
  echo '</ul>';
  
  /*Follow below for this basic idea for consistency.  Title/HR*/
  echo "Searches<br>";
  echo '<hr width="50%" align="left">';
  /*Follow above for this basic idea for consistency.  Title/HR*/
  echo "<form>";
  echo "<input type=\"text\" name=\"city\" value=\"City\" size=\"12\">";
  echo "<input type=\"submit\" value=\"Search\">";
  echo "</form>";
  echo "<form>";
  echo "<input type=\"text\" name=\"freq\" value=\"Freq(147.6275)\" size=\"12\">";
  echo "<input type=\"submit\" value=\"Search\">";
  echo "</form>";
  
}

?>
