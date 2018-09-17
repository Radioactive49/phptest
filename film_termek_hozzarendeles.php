<?php 

   //kapcsolodás
     $mysql_host = "localhost"; 
     $mysql_database = "cgphufzk_cgp"; 
     $mysql_user = "cgphufzk_matyi"; 
     $mysql_password = "Joskadb254";
     $kapcsolat = ($GLOBALS["___mysqli_ston"] = mysqli_connect( $mysql_host,  $mysql_user,  $mysql_password, $mysql_database )); 
      if ( ! $kapcsolat ) 
      { 
        die( "Nem lehet kapcsolódni a MySQL kiszolgálóhoz!" ); 
      } 
      ((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $mysql_database)) or die ( "Nem lehet megnyitni a $mysql_database: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) ); 

 
      
    if(isset($_POST['submit'])) //ha kilett adat küldve
   {
          //filmek és termékek kigyüjtése a filmek és termékek sorba
          $eredmeny_f = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Film"); 
          $filmek = array( );
          while ( $egy_sor = mysqli_fetch_object( $eredmeny_f ) ) 
          { 
            array_push($filmek, $egy_sor->nev);
          } 
          $eredmeny_t = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Termek"); 
          $termekek = array( );
          array_push($termekek, " ");
          while ( $egy_sor = mysqli_fetch_object( $eredmeny_t ) ) 
          { 
             $values = array( );
             
             array_push($values, $egy_sor->id);
             array_push($values, $egy_sor->nev);
             array_push($termekek, $values);
          } 
          //filmek és termékek darabszáma
          $f_db=$eredmeny_f->num_rows;
          $t_db=$eredmeny_t->num_rows;
          //a táblázatban a film_id felülirása a radiobutton form alapján
          for($i=0; $i<$t_db+1; $i++)
          {
            //prepared statement az sql injection elkerülése végett
              $stmt = $kapcsolat->prepare("UPDATE Termek SET film_id=? WHERE nev=?");
              /* BK: always check whether the prepare() succeeded */
              if ($stmt === false) {
                trigger_error($this->mysqli->error, E_USER_ERROR);
                return;
              }
              $f_id = $_POST[$termekek[$i][0]];
              $stmt->bind_param('ss', $f_id, $nev);
              $nev = $termekek[$i][1];
              //print $_POST[$termekek[$i]]."  ".$nev."<br>";
              $status = $stmt->execute();
              
              if ($status === false) {
                trigger_error($stmt->error, E_USER_ERROR);
              }
              $stmt->close();
              $uzenet = "Film-Termék hozzárendelés sikeres.";
              $color = "green";
          }
          
    
    
   }
        
function tablazat_rajzolas() {
          //rádiobuttonos táblázat kirajzolása
          //filmek és termékek tömb előállítása
          $eredmeny_f = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Film"); 

          $filmek = array( );
          while ( $egy_sor = mysqli_fetch_object( $eredmeny_f ) ) 
          { 
            array_push($filmek, $egy_sor->nev);
          } 
          $eredmeny_t = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM Termek"); 
          
          $termekek = array( ); //$termekek[i][id=0, nev=1]
          array_push($termekek, " ");
          while ( $egy_sor = mysqli_fetch_object( $eredmeny_t ) ) 
          { 
             $values = array( );
             
             array_push($values, $egy_sor->id);
             array_push($values, $egy_sor->nev);
             array_push($termekek, $values);
          } 
          $f_db=$eredmeny_f->num_rows;
          $t_db=$eredmeny_t->num_rows;
          //táblázat kirajzolás
          print "<br><br><br>";
          
          print "<table class=\"table table-dark\">";
          for($i=0; $i<$t_db+1; $i++)
          {

            print "<tr>";
            
            print "<th scope=\"row\">";
            print $termekek[$i][1];
            print "</th>";
            
            for($j=0; $j<$f_db; $j++)
            {
              
              if($i==0)
              {
                print "<th scope=\"col\"><center>";
                print $filmek[$j];
                 print "</center></th>";
              }
              else
              {
                print "<td><center>";
                print "<input type=\"radio\" name=\"".$termekek[$i][0]."\" value=\"".$filmek[$j]."\">";
                print "</center></td>";
              }
                
                
               
            }
            
            print "</tr>";
          }
          print "</table>" ;     
    
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
          <li><a href="termek_hozzadasa.php">Termék hozzáadása</a></li>
          <li class ="active"><a href="#">Film-Termék hozzárendelés</a></li>
          <li><a href="kombinalt_musor.php">Kombinált műsor készítés</a></li>
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
        <? tablazat_rajzolas();?>

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