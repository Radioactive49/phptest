<?php 
    if( isset($_POST["name"]) && isset($_POST["pw"])) //ha kilett k�ldve a form
    { 
      //kapcsolod�s az adatb�zishoz
        $mysql_host = "localhost"; 
        $mysql_database = "***"; 
        $mysql_user = "***"; 
        $mysql_password = "***";
        $kapcsolat = ($GLOBALS["___mysqli_ston"] = mysqli_connect( $mysql_host,  $mysql_user,  $mysql_password, $mysql_database )); 
        if ( ! $kapcsolat ) 
        { 
          die( "Nem lehet kapcsol�dni a MySQL kiszolg�l�hoz!" ); 
        } 
        ((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $mysql_database)) or die ( "Nem lehet megnyitni a $mysql_database: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) ); 


          require_once('hash.php'); //hash.php a jelszo miatt
          $nev=$_POST["name"]; 
          $pw=$_POST["pw"]; 

          //adat kigy�jt�se bejelentkez�shez
          $eredmeny = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM regisztracio "); 
          while ( $egy_sor = mysqli_fetch_object( $eredmeny ) ) 
          { 
            if($egy_sor->nev==$nev) 
              { 
                $pwh=$egy_sor->jelszo; 
                break; 
              } 
          } 
          $result = validate_password($pw, $pwh); //pwh=hash(pw)?
          //kiertekeles
          if($result) 
            { 
              $uzenet= "Welcome ".$nev." you have logged in succesfully."; 
              $_SESSION["usernev"]=$nev; 
              header("Location: film_hozzadasa.php"); 
              exit; 
            } 
            else 
            { 
              $uzenet= "The username or the password is incorrect."; 
             
            } 
      } 
?> 
<html> 

  <head> 
    <link rel="stylesheet" href="bootstrap.css"> 
    <link rel="stylesheet" href="main.css"> 
  </head> 

  <body bgcolor="#f7f7f7"> 

    <form name="login" method="post" action="<?php print $PHP_SELF;?>"> 
    <div class="tabla"> 
    <table align="center"> 
      <tr> <td><input type="text" class="form-control" name="name" placeholder="Felhaszn�l� n�v"></td></tr> 
      <br>
      <tr><td><br><input type="password" class="form-control" name="pw" placeholder="Jelsz�"></td></tr> 
    </table> 
      
      <center> 
        <?php 
        //hiba/sikeress�gi �zenet
        if($uzenet!="") 
          { 
            echo "<br>"; 
            echo "<b><font color=\"".$color."\">".$uzenet."</font></b>"; 
            $uzenet=""; 
          } 
        ?> 

      <div style="formfield"> 
        <br>
        <input class="btn btn-default" type="submit" value="Bejelentkez�s" /> 
      </div> 
      </center>

    </div> 
    
    </form> 

  </body> 
</html>