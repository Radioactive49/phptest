<?php 

  if( isset($_POST["film"]) && $_POST["film"]!="") //ha kilett adat küldve
  { 
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

      

/*
$sql = "DROP TABLE Film"; 
$retval = mysqli_query($kapcsolat, $sql ); 
if(! $retval ) 
{ 
  die('Could not delete table: ' . mysql_error()); 
} 
print "Table deleted successfully\n";     
  $parancs ="CREATE TABLE Film (
     id MEDIUMINT NOT NULL AUTO_INCREMENT,
     nev CHAR(30) NOT NULL,
     PRIMARY KEY (id));"; 
$retval=mysqli_query($kapcsolat, $parancs); 

if(! $retval ) 
{ 
  die('Could not create table: ' . mysql_error()); 
} 
print "Table created successfully\n";         
    */
      //film hozzáadás
      $film_nev=$_POST["film"];
      $van=false;
      //benne van e már
      $eredmeny = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Film"); 
          while ( $egy_sor = mysqli_fetch_object( $eredmeny ) ) 
          { 
            if($egy_sor->nev == $film_nev)
            {
              $van=true;
            }
          } 
      //kiértékelés
      if(!$van)
      { 
        //prepared statement az sql injection elkerülése végett
         $stmt = $kapcsolat->prepare("INSERT INTO Film (nev) VALUES (?)");
          
          if ($stmt === false) 
          {
             trigger_error($this->mysqli->error, E_USER_ERROR);
             return;
          }
              $stmt->bind_param('s', $film_nev);
              $status = $stmt->execute();
              
          if ($status === false) 
          {
              trigger_error($stmt->error, E_USER_ERROR);
          }
          $stmt->close();

        $uzenet= "A film hozzáadása sikerült.\n";
        $color="green";
      }
      else
      {
        $uzenet= "A film hozzáadása nem sikerült. A film már szerepel az adatbázisban.\n";
        $color="red";
      }
      

      
         
  } 
?> 
<html> 

  <head> 
    <link rel="stylesheet" href="bootstrap.css"> 
    <link rel="stylesheet" href="main.css"> 
  </head> 

  <body bgcolor="#f7f7f7"> 
    
    <div class="nav">
    <nav class="navbar navbar-inverse">
      <div class="container">
        <ul  class="pull-left nav nav-tabs">
          <li class="active"><a href="#">Film hozzáadása</a></li>
          <li><a href="termek_hozzadasa.php">Termék hozzáadása</a></li>
          <li><a href="film_termek_hozzarendeles.php">Film-Termék hozzárendelés</a></li>
          <li><a href="kombinalt_musor.php">Kombinált mûsor készítés</a></li>
          <li><a href="musor.php">Mûsorok</a></li>
        </ul>

        <ul class="pull-right nav nav-tabs">
          <li><a href="bejelentkezes.php">Kijelentkezés</a></li>
        </ul>
      </div>
    </nav>
    </div>
    

    <form name="login" method="post" action="<?php print $PHP_SELF;?>">
       <center>
        
        <div class="col-xs-4"> 

        </div>
        <div class="col-xs-4">
          <input type="text" name="film" class="form-control" placeholder="Film címe">
        </div>
        <div class="col-xs-4">
  
        </div>

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
          <input class="btn btn-default" type="submit" value="Hozzáadás" /> 
        </div> 
     
       </center> 

    </form> 

  </body> 
</html>