<!DOCTYPE html>
<html lang="fr">
<!-- ouvrir le site depuit le terminal php -S localhost: -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/element.css">
    <link rel="stylesheet" href="./style/navbar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="script/script.js"></script>
        <title>UniversBet</title>   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>


<body>
<div class="navbar">
        <a href="./accueil.php">
            <div class="navbar-section">
                <img src="images/logo.jpg" alt="Profile Image" class="navbar_logo">
            </div>
        </a>

        <div class="navbar-section  navbar-section-large">
            <a href="league.php" class="mg-left">Championnats</a>
            <a href="amis.php" class="mg-left">Amis</a>
            <a href="Combats.php" class="mg-left">Combats</a>
            <a href="apprendre.php" class="mg-left">Apprendre</a>
    <?php            

        if (isset($_SESSION['utilisateur'])) {
            $email = $_SESSION['utilisateur'];

            $test_admin = "SELECT `test_admin`,Pseudo,Pdp,Credit FROM `utilisateur` WHERE `email` = ?";
            $sth = $dbh->prepare($test_admin);
            $success = $sth->execute(array($email));
            $test_admin = $sth->fetch(PDO::FETCH_ASSOC);

            $pseudo = $test_admin['Pseudo'];
            $avatar = $test_admin['Pdp'];
            $credit =  $test_admin['Credit'];

            echo ' 
                        <a href="deconnexion.php" class="mg-left">Deconnexion</a>
                        </div>  
                        <div class="profile-container">
                            <a href="./profile.php?nom='.$pseudo.'">
                                <div class="image_profil"><img src="images/avatars/'.$avatar.'" alt="Profile Image" class="avatar"></div>
                            </a>
                            <div class="username">'.$pseudo.'</div>
                        </div>

        
        
';


        }
            
else {
    echo "
    </div>
    <div class='navbar-section text-right'>
    
    <a class='' href='inscription.php'>Inscription</a>
    <a class='mg-left' href='index.php'>Connexion</a>
    </div>
    ";
}
            
            
            
            
            ?>

    </div>  

<div class="side_open">
  <label class="burger" for="burger">
    <input  class="line" type="checkbox" id="burger" />
  </label>
    <div class='links'>
<?php 

if (isset($_SESSION['utilisateur'])) {
    echo '
    <a href="./profile.php?nom='.$pseudo.'"><div class="image_profil"><img src="images/avatars/'.$avatar.'" alt="Profile Image" class="avatar"></div></a>
    ';
}
else{echo "
        <a class='bouton_connexion' href='inscription.php'>Inscription</a>
        <a class='bouton_connexion' href='index.php'>Connexion</a>";}?>
    </div>
</div>

<div id="sidebar">
    <!---
            <div class="">
                <a class="" onclick="toggleSubMenu('sportsSubMenu')">Pari ▼</a>
                <ul class="" id="sportsSubMenu">
                    <a href="#" class=" ">Football</a></li>
                    <a href="#" class=" ">Esport</a></li>
                    <a href="#" class=" ">Handball</a></li>
                </ul>
            </div> 
    --->
            <a href="./" class="mg-left">Accueil</a>
            <a href="league.php" class="mg-left">Championnats</a>
            <a href="amis.php" class="mg-left">Amis</a>
            <a href="Combats.php" class="mg-left">Combats</a>
            <a href="apprendre.php" class="mg-left">Apprendre</a>
            
</div>



<script src="script/script.js"></script>
</body>
