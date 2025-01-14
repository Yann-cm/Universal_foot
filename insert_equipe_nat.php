<?php 
            require_once  'fonction_global.php';
            require_once  'config.php';

$pays_deja_fait = ['France','Spain','Portugal','Italy','Brazil','Serbia','Morocco','Uruguay','Turkey','Korea'];


$pays = [];


foreach ($pays as $pay){
id_and_joueur_pays($dbh,$pay);}