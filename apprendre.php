<!DOCTYPE html>
<html lang="fr">
<!-- ouvrir le site depuit le terminal php -S localhost: -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="./style/league.css">
    <link rel="stylesheet" href="./style/aprendre.css">
    <link rel="stylesheet" href="./style/cherche_et_trouve.css">
    <script src="script/script.js"></script>
    <title>UniversBet</title>
</head>

<body>
    <?php 
            session_start();
            require_once  'config.php';
            require_once  'fonction_global.php';
            require_once  'navbar.php';
            $doc_fr = json_decode(file_get_contents('Doc_fr/doc_fr.json'), true);

if (isset($_GET['nb_etoiles'])){
    if (isset($_GET['equipes']) ){
        if ($_GET['equipes'] == 'all' and ! isset($_POST['affiche_reponse'])){
            $joueur_hasard = 'SELECT Pays.Nom as Pays,Joueurs.Id, Joueurs.Nom as Nom ,Photo, Equipes.Nom as Equipes,Joueurs.Position as Position, Age , Numero FROM `Joueurs`  
                                JOIN Equipes ON Equipes.Id = Joueurs.id_equipe
                                JOIN Pays ON Pays.Id = Joueurs.Id_pays
                                WHERE Joueurs.star = ?  ORDER BY RAND() LIMIT 1';
            $sth = $dbh->prepare($joueur_hasard);
            $success = $sth->execute(array($_GET['nb_etoiles']));
            $joueur_hasard = $sth->fetchAll(PDO::FETCH_ASSOC);
            $equipes = 'all';

        }

        else if ( ! isset($_POST['affiche_reponse'])){
            
            if (! isset($_GET['niveaux'])){
                echo '
                <div class="league">
                    <div class="info_league">
                        <a href="apprendre.php?nb_etoiles='.$_GET['nb_etoiles'].'&equipes=all&niveaux=1"class="">
                            <div class="league_gauche">
                                <img src="images/all.jpg" alt="Description de l" image" class="league_gauche_img">
                            </div>
                            <div class="league_droite">
                                <p>Connaiseur</p>
                            </div>
                        </a>
                    </div>
                    <div class="info_league">
                        <a href="apprendre.php?nb_etoiles='.$_GET['nb_etoiles'].'&equipes=all&niveaux=2"class="">
                            <div class="league_gauche">
                                <img src="images/all.jpg" alt="Description de l" image" class="league_gauche_img">
                            </div>
                            <div class="league_droite">
                                <p>Maître</p>
                            </div>
                        </a>
                    </div>
                    <div class="info_league">
                        <a href="apprendre.php?nb_etoiles='.$_GET['nb_etoiles'].'&equipes=all&niveaux=3"class="">
                            <div class="league_gauche">
                                <img src="images/all.jpg" alt="Description de l" image" class="league_gauche_img">
                            </div>
                            <div class="league_droite">
                                <p>Incolable</p>
                            </div>
                        </a>
                    </div>
                </div>';
            }

            else {
                $joueur_hasard = 'SELECT Pays.Nom as Pays ,Joueurs.Id, Joueurs.Nom as Nom ,Photo,Position, Equipes.Nom as Equipes, Age , Numero FROM `Joueurs`  
                JOIN Equipes ON Equipes.Id = Joueurs.id_equipe
                JOIN Pays ON Pays.Id = Joueurs.Id_pays
                WHERE Id_equipe = ? Joueurs.star = ? and ORDER BY RAND() LIMIT 1;';
                $sth = $dbh->prepare($joueur_hasard);
                $success = $sth->execute(array($_GET['equipes'],$_GET['niveaux']));
                $joueur_hasard = $sth->fetchAll(PDO::FETCH_ASSOC);
                $niveaux = True;
            }

        }

        else {
            $joueur_hasard = 'SELECT Pays.Nom as Pays, Joueurs.Id ,Joueurs.Nom as Nom ,Photo,Joueurs.Position as Position, Equipes.Nom as Equipes, Age , Numero FROM `Joueurs`  
                JOIN Equipes ON Equipes.Id = Joueurs.id_equipe
                JOIN Pays ON Pays.Id = Joueurs.Id_pays
                WHERE Joueurs.Id = ? ;';
            $sth = $dbh->prepare($joueur_hasard);
            $success = $sth->execute(array($_POST['affiche_reponse']));
            $joueur_hasard = $sth->fetchAll(PDO::FETCH_ASSOC);
        }

        if (isset($joueur_hasard)){
            $liste = [
                ["Pays :", $doc_fr["pays"][$joueur_hasard[0]['Pays']] ?? $joueur_hasard[0]['Pays']],
                ["Poste :", $doc_fr["poste"][$joueur_hasard[0]['Position']]],
                ["Numero :", $joueur_hasard[0]['Numero']],
                ["Age :", $joueur_hasard[0]['Age']],
                ["Equipes :", $joueur_hasard[0]['Equipes']]];
            unset($niveaux);
            
            $id = $joueur_hasard[0]['Id'];
            shuffle($liste);
            $nom = $joueur_hasard[0]['Nom'];
            $photo = $joueur_hasard[0]['Photo'];

            if (isset($_POST['reponse'])) {
                $reponse = (isset($_POST["reponse"])) ?$_POST["reponse"] : "Temps écouler" ;
                if ($reponse == "time"){
                    $resultat = 'rater">Temps écouler';
                }
                else if ($reponse != $nom ){
                    $resultat = 'rater">Mauvaise réponse';
                }
                else{
                    $resultat = 'reussi">Bonne réponse Bien jouer !';
                }

                echo '
                <div class="cherche">
                    <div class="cherche_et_trouve">
                        <div class="top-div">

                            <p class="'.$resultat.'</p>
                        </div>
                        <div class="middle-div">
                            <div class="image-container">
                                <img src="'.$photo.'" alt="Image">
                            </div>
                        </div>
                        <div class="bottom-div">
                            <p class="reponse">'.$nom.'</p>
                            <p class="reponse">'.$doc_fr["poste"][$joueur_hasard[0]['Position']].'</p>
                            <p class="reponse">'.$joueur_hasard[0]['Equipes'].'</p>
                            <p class="reponse">Age : '.$joueur_hasard[0]['Age'].'</p>
                            <p class="reponse">Numero : '.$joueur_hasard[0]['Numero'].'</p>
                            <p class="reponse">Pays : '.(isset($doc_fr["pays"][$joueur_hasard[0]['Pays']]) ? $doc_fr["pays"][$joueur_hasard[0]['Pays']] : $joueur_hasard[0]['Pays']).'</p>
                            
                            
                            <a href="apprendre.php?nb_etoiles='.$_GET['nb_etoiles'].'&equipes=all&niveaux=1"class="a"> Rejouer </a>
                        </div>
                    </div>
                </div>';
            } 
            
            else{
                $requete = "SELECT Nom FROM Joueurs";
                $joueurs = $dbh->query($requete);
                echo '
                    <div class="cherche">
                        <div class="cherche_et_trouve">
                            <form id="cherche_et_trouve" method="post">
                                <input type="hidden" name="reponse" id="reponse">
                                <input type="hidden" name="affiche_reponse" id="id_personne">
                                <button type="button" id="demarage" class="bouton_jeux">Démarrer</button>
                                <div id="chrono-container" class="chrono-container">
                                    <label for="chrono">Chronomètre :</label>
                                    <div id="chrono">20</div>
                                </div>
                                <div id="joueur_info">
                                    <div id="info">
                                    <label for="info">Indice sur le joueur :</label>

                                    </div>
                                </div>
                                    <label for="titre_joueur" id="titre_joueur">Trouvez Le joueur</label>
                                    <input type="text" id="reponse_user" list="datalist_joueurs">
                                    <datalist id="pas_mt">';

                                    foreach($joueurs as $nom){ 
                                        echo  '<option value="'.$nom[0].'"></option>';
                                    }
                                            
                                    echo '  
                                    </datalist>
                                    <button type="button" name="affiche_reponse" id="submitButton" class="bouton_jeux">Envoyer</button>
                            </form>
                        </div>
                    </div>';
            }    

    
    ?>
    <script>
        window.gameInfo = {
            info1: "<?php echo $liste[0][0].' '.$liste[0][1]; ?>",
            info2: "<?php echo $liste[1][0].' '.$liste[1][1]; ?>",
            info3: "<?php echo $liste[2][0].' '.$liste[2][1]; ?>",
            info4: "<?php echo $liste[3][0].' '.$liste[3][1]; ?>",
            info5: "<?php echo $liste[4][0].' '.$liste[4][1]; ?>"
        };
        window.playerId = <?php echo $id; ?>;
    </script>
    <script src="script/script_jeux.js"></script>



    <?php
        }
    }
    else{
            echo '
            <div class="league">
                <div class="info_league">
                    <a href="apprendre.php?nb_etoiles='.$_GET['nb_etoiles'].'&equipes=all"class="">
                        <div class="league_gauche">
                            <img src="images/all.jpg" alt="Description de l" image" class="league_gauche_img">
                        </div>
                        <div class="league_droite">
                            <p>Tout les Joueurs niveaux '.$_GET['nb_etoiles'].' étoiles</p>
                        </div>
                    </a>
                </div>';
            $info_star = 'SELECT * FROM `Equipes` WHERE star = ?';
            $sth = $dbh->prepare($info_star);
            $success = $sth->execute(array($_GET['nb_etoiles']));
            $info_star = $sth->fetchAll(PDO::FETCH_ASSOC);
            $equipes = "all";
            for ($i = 0; $i < count($info_star); $i++) {
                echo'
                <div class="info_league">
                    <a href="apprendre.php?nb_etoiles='.$_GET['nb_etoiles'].'&equipes='.$info_star[$i]['Id'].'"class="">
                        <div class="league_gauche">
                            <img src="'.$info_star[$i]['Logo'].'" alt="Description de l" image" class="league_gauche_img">
                        </div>
                        <div class="league_droite">
                            <p>'.$info_star[$i]['Nom'].'</p>
                        </div>
                    </a>
                </div>';


            }
            echo '</div>';
    }
        
}

else{
    $query = 'SELECT * FROM Joueurs WHERE star = 1 ORDER BY RAND() LIMIT 1;';
    $requete = $dbh->prepare($query);
    $requete->execute(array());
    $resultats_1 = $requete->fetch(PDO::FETCH_ASSOC);
    
    $query = 'SELECT * FROM Joueurs WHERE star = 2 ORDER BY RAND() LIMIT 1;';
    $requete = $dbh->prepare($query);
    $requete->execute(array());
    $resultats_2 = $requete->fetch(PDO::FETCH_ASSOC);
    
    $query = 'SELECT * FROM Joueurs WHERE star = 3 ORDER BY RAND() LIMIT 1;';
    $requete = $dbh->prepare($query);
    $requete->execute(array());
    $resultats_3 = $requete->fetch(PDO::FETCH_ASSOC);
    

    echo '  <div class="league">
                <div class="info_league">
                    <a href="apprendre.php?nb_etoiles=1"class="">
                        <div class="league_gauche">
                            <img src="'.$resultats_1['Photo'].'" alt="Description de l" image" class="league_gauche_img">
                        </div>
                        <div class="league_droite">
                            <p>
                                Niveaux : 1 
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                < /svg>
                            </p>
                        </div>
                    </a>
                </div>
                <div class="info_league">
                    <a href="apprendre.php?nb_etoiles=2"class="">
                        <div class="league_gauche">
                            <img src="'.$resultats_2['Photo'].'" alt="Description de l" image" class="league_gauche_img">
                        </div>
                        <div class="league_droite">
                            <p>
                                Niveaux : 2
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                </svg>
                            </p>
                        </div>
                    </a>
                </div>
                <div class="info_league">
                    <a href="apprendre.php?nb_etoiles=3"class="">
                        <div class="league_gauche">
                            <img src="'.$resultats_3['Photo'].'" alt="Description de l" image" class="league_gauche_img">
                        </div>
                        <div class="league_droite">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                </svg>
                            </p>
                        </div>
                    </a>
                </div>
            </div>';
    

}

?>




</body>
</html>
            