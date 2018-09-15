<?php 

  if( isset($_POST["nev"]) && $_POST["nev"]!="") //ha kilett adat küldve
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
$sql = "DROP TABLE Termek"; 
$retval = mysqli_query($kapcsolat, $sql ); 
if(! $retval ) 
{ 
  die('Could not delete table: ' . mysql_error()); 
} 
echo "Table deleted successfully\n";    
    $parancs ="CREATE TABLE Termek (
     id MEDIUMINT NOT NULL AUTO_INCREMENT,
     nev CHAR(30) NOT NULL,
     db INT(30) NOT NULL,
     ar CHAR(30) NOT NULL,
     film_id CHAR(30) NOT NULL,
     PRIMARY KEY (id));"; 
$retval=mysqli_query($kapcsolat, $parancs); 

if(! $retval ) 
{ 
  die('Could not create table: ' . mysql_error()); 
} 
echo "Table created successfully\n"; 
        */
      //adatok felvevése
      $termek_nev=$_POST["nev"];
      $termek_ar=$_POST["ar"];
      $termek_db=$_POST["db"];
      $film_id="nincs";
      //benne van már?
      $van=false;
      $eredmeny = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Termek"); 
          while ( $egy_sor = mysqli_fetch_object( $eredmeny ) ) 
          { 
            if($egy_sor->nev == $termek_nev)
            {
              $van=true;
            }
          } 
      //kiértékelés
      if(!$van)
      {
        //prepared statement az sql injection elkerülése végett
        $stmt = $kapcsolat->prepare("INSERT INTO Termek (nev, db, ar, film_id) 
                      VALUES (?, ?, ?, ?)");
          
          if ($stmt === false) 
          {
             trigger_error($this->mysqli->error, E_USER_ERROR);
             return;
          }
              $stmt->bind_param('siss', $termek_nev, $termek_db, $termek_ar, $film_id );
              $status = $stmt->execute();
              
          if ($status === false) 
          {
              trigger_error($stmt->error, E_USER_ERROR);
          }
          $stmt->close();


        $uzenet= "A termék hozzáadása sikerült.\n";
        $color="green";
      }
      else
      {
        $uzenet= "A termék hozzáadása nem sikerült. A termék már szerepel az adatbázisban.\n";
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
          <li><a href="film_hozzadasa.php">Film hozzáadása</a></li>
          <li class="active"><a href="#">Termék hozzáadása</a></li>
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
    

    <form name="product" method="post" action="<?php print $PHP_SELF;?>"> 
      <center>
        
      <table align="center"> 
      <tr> <td><input type="text" class="form-control" name="nev" placeholder="Név (pl: Popcorn)"></td></tr> 
      <br>
      <tr><td><br><input type="text" class="form-control" name="db" placeholder="Darabszám (pl: 800)"></td></tr>
      <tr><td><br><input type="text" class="form-control" name="ar" placeholder="Ár (pl: 15$)"></td></tr> 
      
      </table>

      <br><br>
        <?php 
        //hiba/sikerességi üzenet
        if($uzenet!="") 
          { 
            echo "<br>"; 
            echo "<b><font color=\"".$color."\">".$uzenet."</font></b>"; 
            echo "<br>";
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