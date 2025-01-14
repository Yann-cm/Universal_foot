<!DOCTYPE html>
<html lang="fr">
<!-- ouvrir le site depuit le terminal php -S localhost: -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            if (isset($_GET['Id_league'])){
                $equipes = classement($_GET['Id_league'],$dbh);
               

                echo '
                <div class="info_c"> Classement </div>
                <div class="classement">
                <div class="c">
                  <table>
                  <tr>
                    <th>P</th>
                    <th>Logo</th>
                    <th>Nom</th>
                    <th>Point</th>
                    <th>V</th>
                    <th>D</th>
                    <th>N</th>
                    <th>Nb match</th>
                    <th>But marquer</th>
                    <th>But encaisser</th>
                    <th>GoalD</th>
                    <th>Plus d"infos</th>
                  </tr>';
                  foreach ($equipes as $equipe) {
                    echo '
                    <tr>
                        <td>'.$equipe["Position"].'</td>
                        <td><a href="voir_plus.php?nom='.$equipe["Nom"].'"><img src="'.$equipe["Logo"].'" alt="Logo de '.$equipe["Nom"].'" style="max-width: 50px;"></a></td>
                        <td>'.$equipe["Nom"].'</td>
                        <td>'.$equipe["point"].'</td>
                        <td>'.$equipe["Win"].'</td>
                        <td>'.$equipe["Lose"].'</td>
                        <td>'.$equipe["Draw"].'</td>
                        <td>'.$equipe["nb_match"].'</td>
                        <td>'.$equipe["g_marquer"].'</td>
                        <td>'.$equipe["Draw"].'</td>
                        <td>'.$equipe["g_encaisser"].'</td>
                        <td><a href="voir_plus.php?nom='.$equipe['Nom'].'"class="btn_classement">Clique</a></td>

                    </tr>';}
                  
                  
                    echo '</table>
                  
                
                </div>';
            }


            
else{
    echo '<div class="league">';
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
            </div>';
    } 

 }

?>
</div>
</body>
</html>
            