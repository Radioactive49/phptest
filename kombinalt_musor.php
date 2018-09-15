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

      
      if(isset($_POST["sel1"]) && isset($_POST["sel2"])) //ha kilett adat küldve
      {
        $film1 = $_POST["sel1"];
        $film2 = $_POST["sel2"];
        //van már ilyen kombinácio?
        $van=false;
        $eredmeny = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Film_osszerendeles"); 
          while ( $egy_sor = mysqli_fetch_object( $eredmeny ) ) 
          { 
            if($egy_sor->film1 == $film1 && $egy_sor->film2 == $film2)
            {
              $van=true;
            }
          } 
      //kiértékelés
      if(!$van)
      {
        //prepared statement használata az sql injection elkerülése végett
        $stmt = $kapcsolat->prepare("INSERT INTO Film_osszerendeles (film1, film2) 
                      VALUES (?, ?)");
          
          if ($stmt === false) 
          {
             trigger_error($this->mysqli->error, E_USER_ERROR);
             return;
          }
              $stmt->bind_param('ss', $film1, $film2 );
              $status = $stmt->execute();
              
          if ($status === false) 
          {
              trigger_error($stmt->error, E_USER_ERROR);
          }
          $stmt->close();

        $uzenet= "A kombinált mûsor elkészítése sikerült.\n";
        $color="green";
      }
      else
      {
        $uzenet= "A kombinált mûsor elkészítése nem sikerült. Ez a mûsor már szerepel az adatbázisban.\n";
        $color="red";
      }





        
      }
/*
$sql = "DROP TABLE Film_osszerendeles"; 
$retval = mysqli_query($kapcsolat, $sql ); 
if(! $retval ) 
{ 
  die('Could not delete table: ' . mysql_error()); 
} 
print "Table deleted successfully\n";  

  $parancs ="CREATE TABLE Film_osszerendeles (
     film1 CHAR(30) NOT NULL,
     film2 CHAR(30) NOT NULL
     );"; 
$retval=mysqli_query($kapcsolat, $parancs); 

if(! $retval ) 
{ 
  die('Could not create table: ' . mysql_error()); 
} 
print "Table created successfully\n";     
    
*/      
function kombmusor_rajzolas()
{
  print "<div class=\"container\">
         <div class=\"form-group\" name=\"lista1\">
         <select class=\"form-control\" name=\"sel1\">";
  $eredmeny = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Film"); 
  while ( $egy_sor = mysqli_fetch_object( $eredmeny ) ) 
         { 
            print "<option>".$egy_sor->nev."</option>";       
         }
  print "</select>
         </div>";
  print "<div class=\"form-group\" name=\"lista2\">
         <select class=\"form-control\" name=\"sel2\">";
  $eredmeny = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Film"); 
  while ( $egy_sor = mysqli_fetch_object( $eredmeny ) ) 
         { 
            print "<option>".$egy_sor->nev."</option>";       
         }
  print "</select>
         </div>
         </div>";

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
          <li class="active"><a href="#">Kombinált mûsor készítés</a></li>
          <li><a href="musor.php">Műsorok</a></li>
        </ul>

        <ul class="pull-right nav nav-tabs">
          <li><a href="bejelentkezes.php">Kijelentkezés</a></li>
        </ul>
      </div>
    </nav>
    </div>
    



    <form name="film-termek" method="post" action="<?php print $PHP_SELF;?>">
         <center>
          <? kombmusor_rajzolas();?>

         <br><br>
            
         <?php 
         //hiba/sikerességi üzenet
            if($uzenet!="") 
              { 
                print "<br>"; 
                print "<b><font color=\"".$color."\">".$uzenet."</font></b>"; 
                print "<br>";
                $uzenet=""; 
              } 
          ?> 


        <div style="formfield"> 
          <input class="btn btn-default" type="submit" name="submit" value="Mentés" /> 
        </div> 
       
        </center> 

    </form> 

  </body> 
</html>