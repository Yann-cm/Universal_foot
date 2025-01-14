<?php
require_once  'config.php';
session_start();
$email = $_SESSION['utilisateur'];
$query = 'SELECT Id_utilisateur FROM `utilisateur` WHERE Email = ?';
$requete = $dbh->prepare($query);
$requete->execute(array($email));
$Id_user = $requete->fetch(PDO::FETCH_ASSOC);


setcookie($Id_user['Id_utilisateur'],'',time() - 3600, '/');

session_destroy();
header("Location: accueil.php"); 
exit();
