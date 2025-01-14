<!DOCTYPE html>
<html lang="fr">
<!-- ouvrir le site depuit le terminal php -S localhost: -->
<!--LIEN ->  http://localhost/universal/ -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/match_du_jour.css">
    <link rel="stylesheet" href="./style/element.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="./style/popup.css">
    <link rel="stylesheet" href="./style/league.css">

    <script src="script/script.js"></script>
    <title>UniversBet</title>
</head>

<body>
    <?php 
            session_start();
            require_once  'config.php';
            require_once  'fonction_global.php';
            require_once  'navbar.php';
            


            insert_info_championnat($dbh);
            highlight_match($dbh);


            
            
            $match_J = "SELECT `Id`,`Date`,Id_equipe_A, Id_equipe_B, Id_championat FROM `Matchs` WHERE `Matchs`.`Date` > NOW() and `Matchs`.`highlight` = 1 ORDER BY RAND();";
            $sth = $dbh->prepare($match_J);
            $success = $sth->execute();
            $match_J = $sth->fetch(PDO::FETCH_ASSOC);

            $championat_J = "SELECT Nom, Logo , Id_sport,drapeaux FROM `Championnat` WHERE  Id = ?;";
            $sth = $dbh->prepare($championat_J);
            $success = $sth->execute(array($match_J['Id_championat']));
            $championat_J = $sth->fetch(PDO::FETCH_ASSOC);

            
            $equipe_A = "SELECT Nom,Logo FROM `Equipes` WHERE Id = ? and Id_sport = ?;";
            $sth = $dbh->prepare($equipe_A);
            $success = $sth->execute(array($match_J['Id_equipe_A'],$championat_J['Id_sport']));
            $equipe_A = $sth->fetch(PDO::FETCH_ASSOC);

            $equipe_B = "SELECT Nom,Logo FROM `Equipes` WHERE Id = ? and Id_sport = ?;";
            $sth = $dbh->prepare($equipe_B);
            $success = $sth->execute(array($match_J['Id_equipe_B'],$championat_J['Id_sport']));
            $equipe_B = $sth->fetch(PDO::FETCH_ASSOC);
            
            $logo_league = $championat_J['Logo'];
            $league = $championat_J['Nom'];
            $drapeaux_league = $championat_J['drapeaux'];
            $date_match_J = date("Y-m-d\TH:i:s", strtotime($match_J['Date']));
            $nom_A = $equipe_A['Nom'];
            $logo_A = $equipe_A['Logo'];
            $nom_B = $equipe_B['Nom'];
            $logo_B = $equipe_B['Logo'];

            $equipe_pred = "SELECT `Id`,api,Id_Sport FROM `Matchs` WHERE highlight = 1 ;";
            $sth = $dbh->prepare($equipe_pred);
            $success = $sth->execute(array());
            $equipe_pred = $sth->fetchAll(PDO::FETCH_ASSOC);
            $nb_equipes =  count($equipe_pred);
            echo'</div></div>';

			for ($i = 0; $i < $nb_equipes; $i++) {

                $reqmatch = $dbh->prepare("SELECT * FROM Predictions WHERE Id_matchs = ?");
                $reqmatch->execute(array($equipe_pred[$i]['Id']));
                $predexist = $reqmatch->rowCount();
                
                if ($predexist == 0) {
                    $prediction = api_prediction($equipe_pred[$i]['api']);
                    $data = json_decode($prediction, true);

                    $victoire = $data['response'][0]['predictions']['percent']['home'];
                    $defaite = $data['response'][0]['predictions']['percent']['away'];

                    $insert_predictions = "INSERT INTO `Predictions` (`Id_matchs`,`Victoire`,`Defaite`) VALUES (?,?,?);";
                    $insert_predictions = $dbh->prepare($insert_predictions);
                    $insert_predictions->execute(array($equipe_pred[$i]['Id'], $victoire, $defaite));
                }
                else {

                    $prediction = "SELECT Victoire,Defaite FROM Predictions WHERE Id_matchs = ?";
                    $sth = $dbh->prepare($prediction);
                    $success = $sth->execute(array($match_J['Id']));
                    $prediction = $sth->fetch(PDO::FETCH_ASSOC);
        
                }
            }
        echo '
        <div class="block">
            <div class="background_match mid">
                <img src="'.$logo_A.'" alt="Description de l"image" class="background_image" style="left: 0;">
                <img src="'.$logo_B.'" alt="Description de l"image" class="background_image" style="right: 0;">
                    
                <div class="infos_match">
                    <div class="block infos_equipes">
                        <div class=" info_equipes block">
                            <p class="Nom mid">'.$nom_A.'</p>
                            <p class="camp">Domicile</p>
                        </div> 
                        <div class=" info_equipes block">
                            <img src="'.$logo_A.'" alt="Description de l"image" class="team-logo">
                        </div>
                        <div class=" info_equipes">
                            <div class="team_bouton">                          
                                <a href="voir_plus.php?nom='.$nom_A.'"class="mid bouton_match">Voir Plus</a>
                            </div>
                        </div>
                    </div>
                </div>

                    
                <div class="infos_match">
                    <div class="block infos_equipes">
                        <div class=" info_equipes block ">
                            <img src="'.$logo_league.'" alt="Description de l"image" class="league_logo">
                        </div> 
                        
                        <div class=" info_equipes block ">
                            <div class="countdown-box mid">
                                <h1 id="countdown"></h1>
                            </div>
                        </div>
                        <div class=" info_equipe">
                            <div class="rip">
                                <div class="left block">
                                </div>
                                <div class="middle">
                                    <div class="pie-chart-container mid">
                                        <canvas id="myPieChart" class""></canvas>
                                    </div>
                                </div>
                                <div class="right">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
                            
                <div class="infos_match">
                    <div class="block infos_equipes">
                        <div class=" info_equipes block">
                            <p class="Nom mid">'.$nom_B.'</p>
                            <p class="camp">Exterieur</p>
                        </div> 
                        <div class=" info_equipes block">
                            <img src="'.$logo_B.'" alt="Description de l"image" class="team-logo">
                        </div>
                        <div class=" info_equipes">
                            <div class="team_bouton">                          
                                <a href="voir_plus.php?nom='.$nom_B.'" class="mid bouton_match">Voir Plus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var targetDate = new Date("'.$date_match_J.'");
            var victoire = "'.intval(substr($prediction['Victoire'],0,2)).'";
            defaite = "'.intval(substr($prediction['Defaite'],0,2)).'";
            nul = "'. 100-intval(substr($prediction['Victoire'],0,2))-intval(substr($prediction['Defaite'],0,2)).'";
        </script>
        <div class="league">';


        $info_champ = "SELECT * 
		            FROM Championnat ;";
		$sth = $dbh->prepare($info_champ);
		$success = $sth->execute(array());
		$info_champ = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        
        

for ($i = 0; $i < count($info_champ); $i++) {


echo'


<div class="info_league">
    <a href="league.php?Id_league='.$info_champ[$i]['Id'].'"class="">
            <div class="league_gauche">
                <img src="'.$info_champ[$i]['Logo'].'" alt="Description de l" image" class="league_gauche_img">
            </div>
            <div class="league_droite">
                <p>'.$info_champ[$i]['Nom'].'</p>
            </div>
    </a>
</div>

';


} ?>
</div>






</body>
</html>


