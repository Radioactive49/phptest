<?php 
    //kapcsolodás
     $mysql_host = "localhost"; 
     $mysql_database = "***"; 
     $mysql_user = "***"; 
     $mysql_password = "***";
     $kapcsolat = ($GLOBALS["___mysqli_ston"] = mysqli_connect( $mysql_host,  $mysql_user,  $mysql_password, $mysql_database )); 
      if ( ! $kapcsolat ) 
      { 
        die( "Nem lehet kapcsolódni a MySQL kiszolgálóhoz!" ); 
      } 
      ((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $mysql_database)) or die ( "Nem lehet megnyitni a $mysql_database: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) ); 

      
    
    
      
function kiir()
{
  //kombinált műsor termékekkel kiírása
  $eredmeny = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Film_osszerendeles"); 
  print "<table class=\"table table-dark\">";
  while ( $egy_sor = mysqli_fetch_object( $eredmeny ) ) 
         { 
            $film1=$egy_sor->film1;
            $film2=$egy_sor->film2;
            print "<tr>";
            print "<td><b><center>";
            print $film1;
            print "</center></b></td>"; 
            print "<td><b><center>";
            print $film2;    
            print "</center></b></td>";
            print "</tr>"; 

            print "<tr>";
            $eredmeny2 = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Termek");
            print "<td><center>";
            while ( $egy_sor2 = mysqli_fetch_object( $eredmeny2 ) ) 
            {
                
                if($egy_sor2->film_id == $film1)
                {
                    print $egy_sor2->nev."  ";
                }
               
            }
             print "</center></td>";

            $eredmeny3 = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Termek");
            print "<td><center>";
            while ( $egy_sor3 = mysqli_fetch_object( $eredmeny3 ) ) 
            {
                
                if($egy_sor3->film_id == $film2)
                {
                    print $egy_sor3->nev."  ";
                }
                
            }
            print "</center></td>";
            print "</tr>";

         }
  print "</table>";

}
?> 
<html> 

  <head> 
    
   <link href="bootstrap.css" rel="stylesheet">
   <link href="main.css" rel="stylesheet">
   
  </head> 

  <body bgcolor="#f7f7f7"> 
    
    <div class="nav">
    <nav class="navbar navbar-inverse">
      <div class="container">
        <ul  class="pull-left nav nav-tabs">
          <li><a href="film_hozzadasa.php">Film hozzáadása</a></li>
          <li><a href="termek_hozzadasa.php">Termék hozzáadása</a></li>
          <li><a href="film_termek_hozzarendeles.php">Film-Termék hozzárendelés</a></li>
          <li><a href="kombinalt_musor.php">Kombinált mûsor készítés</a></li>
          <li class="active"><a href="#">Mûsorok</a></li>
        </ul>

        <ul class="pull-right nav nav-tabs">
          <li><a href="bejelentkezes.php">Kijelentkezés</a></li>
        </ul>
      </div>
    </nav>
    </div>
  
    <? kiir();?>

  </body> 
</html>