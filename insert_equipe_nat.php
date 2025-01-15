<?php 
            require_once  'fonction_global.php';
            require_once  'config.php';

$pays_a_faire = ['Uruguay'];];
$pays_deja_fait = ['France','Spain','Turkey','Korea','Portugal','Italy','Morocco','Brazil','Serbia'];

$pays = [];


foreach ($pays as $pay){
id_and_joueur_pays($dbh,$pay);
}