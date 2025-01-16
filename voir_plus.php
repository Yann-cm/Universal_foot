<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
        <link rel="stylesheet" href="./style/voir_plus.css">
        <link rel="stylesheet" href="./style/effectif.css">   
    <title>Connexion</title>
    
</head>

<body>

<?php 
session_start();
require_once  'config.php';
require_once  'fonction_global.php';
require_once  'fonction_grid.php';
require_once  'navbar.php';



if(isset($_GET['nom'])) {
    // Récupérer la valeur du paramètre 'nom'

    $email = isset($_SESSION['utilisateur']) ? $_SESSION['utilisateur']: null;


    $nom = $_GET['nom'];
	
    $equipe = "SELECT * FROM `Equipes` WHERE Nom = ?";
	  $sth = $dbh->prepare($equipe);
	  $success = $sth->execute(array($nom));
	  $equipe = $sth->fetch(PDO::FETCH_ASSOC);
    
    $id_equipe = $equipe['Id'];
    $id_equipe_api = $equipe['Id_api'];

    $position = " SELECT * FROM `Position` WHERE Id_equipes = ?";
	$sth = $dbh->prepare($position);
	$success = $sth->execute(array($id_equipe));
	$position = $sth->fetch(PDO::FETCH_ASSOC);
    
    $nb_match = nb_match($id_equipe,$dbh);
    
    $logo = $equipe['Logo'];
    $nb_lose = $nb_match['nb_lose'];
    $nb_win = $nb_match['nb_win'];
    $nb_draw =$nb_match['nb_draw'];
    $nb_match =$nb_match['nb_match'];

    $b_marquer= $position['g_marquer'];
    $b_encaisser = $position['g_encaisser'];
    $id_championnat = $position['Id_championnat'];
    $nb_win_h = $position['win_h'];
    $nb_win_a= $position['win_a'];
    $nb_lose_h= $position['lose_h'];
    $nb_lose_a= $position['lose_a'];
    $nb_draw_h= $position['draw_h'];
    $nb_draw_a= $position['draw_a'];
    $position= $position['Position'];
    

    
  $championat = " SELECT * FROM `Championnat` WHERE `Id` = ?";
	$sth = $dbh->prepare($championat);
	$success = $sth->execute(array($id_championnat));
	$championat = $sth->fetch(PDO::FETCH_ASSOC);

    $nom_championat = $championat['Nom'];
    $logo_championat = $championat['Logo'];
    $drapeaux = $championat['drapeaux'];

  $test_stades = " SELECT Id_stade FROM `Equipes` WHERE `Id` = ?";
	$sth = $dbh->prepare($test_stades);
	$success = $sth->execute(array($id_equipe));
	$test_stades = $sth->fetch(PDO::FETCH_ASSOC);


    if ( ! isset($test_stades['Id_stade'])){
        $info_stade = info_team($id_equipe_api,$dbh);
    }
    else{
        $stade = " SELECT * FROM `Stades` WHERE `Id` = ?";
        $sth = $dbh->prepare($stade);
        $success = $sth->execute(array($test_stades['Id_stade']));
        $stade = $sth->fetch(PDO::FETCH_ASSOC);
        $info_stade = [$stade['Nom'],$stade['Ville'],$stade['Nb_place'],$stade['date_creation'],$stade['photo']];
    }

    $test_joueurs = "SELECT Joueurs.Id 
                      FROM `Joueurs` 
                      JOIN Equipes ON Equipes.Id = Joueurs.Id_equipe 
                      WHERE Equipes.Id = ? 
                      AND Equipes.last_insert > DATE_SUB(NOW(), INTERVAL 21 DAY);
                      ";
    $sth = $dbh->prepare($test_joueurs);
    $success = $sth->execute(array($id_equipe));
    $test_joueurs = $sth->fetch(PDO::FETCH_ASSOC);

    if ( ! isset($test_joueurs['Id'])){
      $info_joueur = joueur(intval($id_equipe_api),$dbh);
    }

    $info_effectif = "SELECT * FROM `Joueurs` WHERE Id_equipe = ?;";
    $sth = $dbh->prepare($info_effectif);
    $success = $sth->execute(array($id_equipe));
    $info_effectif = $sth->fetch(PDO::FETCH_ASSOC);
    

    $equipes = classement($id_championnat,$dbh);

    $match_prochain = "SELECT Matchs.Date, Equipes_A.Logo AS Logo_A, Equipes_A.Nom AS Nom_A, Equipes_B.Logo AS Logo_B, Equipes_B.Nom AS Nom_B
              FROM Matchs
              JOIN Equipes AS Equipes_A ON Matchs.Id_equipe_A = Equipes_A.Id
              JOIN Equipes AS Equipes_B ON Matchs.Id_equipe_B = Equipes_B.Id
              WHERE (Id_equipe_A = ? OR Id_equipe_B = ?) AND Matchs.date > NOW()
              ORDER BY Matchs.Date DESC
              LIMIT 3;";
    $sth = $dbh->prepare($match_prochain);
    $success = $sth->execute(array($id_equipe,$id_equipe));
    $match_prochain = $sth->fetchall();


    $match_passe = "SELECT Matchs.Date, Equipes_A.Logo AS Logo_A, Equipes_A.Nom AS Nom_A, Equipes_B.Logo AS Logo_B, Equipes_B.Nom AS Nom_B, But_A, But_B
                    FROM Matchs
                    JOIN Equipes AS Equipes_A ON Matchs.Id_equipe_A = Equipes_A.Id
                    JOIN Equipes AS Equipes_B ON Matchs.Id_equipe_B = Equipes_B.Id
                    WHERE (Id_equipe_A = ? OR Id_equipe_B = ?) AND Matchs.date < NOW()
                    ORDER BY Matchs.Date DESC
                    LIMIT 5;";
    $sth = $dbh->prepare($match_passe);
    $success = $sth->execute(array($id_equipe,$id_equipe));
    $match_passe = $sth->fetchall();

      $nom_cours_1 = ($match_prochain[0]['Nom_A'] === $nom) ? $match_prochain[0]['Nom_B'] : $match_prochain[0]['Nom_A'];
      $nom_cours_2 = ($match_prochain[1]['Nom_A'] === $nom) ? $match_prochain[1]['Nom_B'] : $match_prochain[1]['Nom_A'];
      $nom_cours_3 = ($match_prochain[2]['Nom_A'] === $nom) ? $match_prochain[2]['Nom_B'] : $match_prochain[2]['Nom_A'];


    echo '
    <div class="container">
    <div class="i1">
    <div class="state">
      <div class="lose_h div_info">'.$nb_lose_h.'</div>
      <div class="lose_a div_info">'.$nb_lose_a.'</div>
      <div class="draw_h div_info">'.$nb_draw_h.'</div>
      <div class="draw_a div_info">'.$nb_draw_a.'</div>
      <div class="win_h div_info">'.$nb_win_h.'</div>
      <div class="win_a div_info">'.$nb_win_a.'</div>
      <div class="win div_info">victoire : '.$nb_win.'</div>
      <div class="lose div_info">defaite : '.$nb_lose.'</div>
      <div class="draw div_info">match nul : '.$nb_draw.'</div>
      <div class="saison-2023-2024 div_info">Saison 2024-2025</div>
      <div class="domicile div_info">A domicile</div>
      <div class="exterieur div_info">A l"exterieur</div>
    </div>
    <div class="stade">
      <div class="drapeau-pays"><img src="'.$drapeaux.'" alt="Description de l"image" class="img "></div>
      <div class="ville div_info">'.$info_stade[1].'</div>
      <div class="nb_place div_info">'.$info_stade[2].'</div>
      <div class="stade-photo"><img src="'.$info_stade[4].'" alt="Description de l"image" class="img"></div>
    </div>
    <div class="stade-name div_info">'.$info_stade[0].'</div>
    <div class="logo"><div class="div_info"><img src="'.$logo.'" alt="Description de l"image" class="img"></div></div>
    <div class="Nom div_info">'.$nom.'</div>
  </div>
  <div class="M_c">
    <div class="Match_cours">      
      <div class="test">
        <div class="item item1 info_match">  
            <div class="partie div_info"><div class="div_info"><img src="'.$match_prochain[0]['Logo_A'].'" alt="Description de l"image" class="img_match"></div></div>
            <div class="middle">
              <div class="info_match div_info">'.$nom_championat.'</div>
              <div class="info_match_milieu div_info">'.$match_prochain[0]['Date'].'</div>
              <div class="info_match div_info"><a href="voir_plus.php?nom='.$nom_cours_1.'">Voir l"equipe adverse</a></div>
            </div>
            <div class="partie div_info"><div class="div_info"><img src="'.$match_prochain[0]['Logo_B'].'" alt="Description de l"image" class="img_match"></div></div>
          </div>
          
          <div class="item item2 info_match">  
            <div class="partie div_info"><div class="div_info"><img src="'.$match_prochain[1]['Logo_A'].'" alt="Description de l"image" class="img_match"></div></div>
            <div class="middle">
              <div class="info_match div_info">'.$nom_championat.'</div>
              <div class="info_match_milieu div_info">'.$match_prochain[1]['Date'].'</div>
              <div class="info_match div_info"><a href="voir_plus.php?nom='.$nom_cours_2.'">Voir l"equipe adverse</a></div>
            </div>
            <div class="partie div_info"><div class="div_info"><img src="'.$match_prochain[1]['Logo_B'].'" alt="Description de l"image" class="img_match"></div></div>
          </div>
          
          <div class="item item3 info_match">  
            <div class="partie div_info"><div class="div_info"><img src="'.$match_prochain[2]['Logo_A'].'" alt="Description de l"image" class="img_match"></div></div>
            <div class="middle">
              <div class="info_match div_info">'.$nom_championat.'</div>
              <div class="info_match_milieu div_info">'.$match_prochain[2]['Date'].'</div>
              <div class="info_match div_info"><a href="voir_plus.php?nom='.$nom_cours_3.'">Voir l"equipe adverse</a></div>
            </div>
            <div class="partie div_info"><div class="div_info"><img src="'.$match_prochain[2]['Logo_B'].'" alt="Description de l"image" class="img_match"></div></div>
          </div>
      </div>
    </div>
    <div class="m_info div_info">Prochain Match :</div>
  </div>
  <div class="classement">
    <div class="info_c div_info"> Classement de la '.$nom_championat.'</div>
    <div class="c">
      <table>
      <tr>
        <th>Position</th>
        <th>Logo</th>
        <th>Nom</th>
        <th>Points</th>
        <th>Victoires</th>
        <th>Défaites</th>
        <th>Nuls</th>
        <th>Goaldiff</th>
      </tr>';
      foreach ($equipes as $equipe) {
        echo '
        <tr>
            <td>'.$equipe["Position"].'</td>
            <td><img src="'.$equipe["Logo"].'" alt="Logo de '.$equipe["Nom"].'" style="max-width: 50px;"></td>
            <td>'.$equipe["Nom"].'</td>
            <td>'.$equipe["point"].'</td>
            <td>'.$equipe["Win"].'</td>
            <td>'.$equipe["Lose"].'</td>
            <td>'.$equipe["Draw"].'</td>
            <td>'.$equipe["goaldif"].'</td>
        </tr>';}
      echo '</table>
      
    
    </div>
  </div>
  <div class="m_h">
    <div class="m_histo div_info">Dernier Match Jouer :</div>
          
          <div class="test">
            <div class="item item1 info_match">  
              <div class="partie div_info"><div class="div_info"><img src="'.$match_passe[0]['Logo_A'].'" alt="Description de l"image" class="img_match"></div></div>
              <div class="middle">
                <div class="info_match div_info">'.$nom_championat.'</div>
                <div class="info_match_milieu div_info">'.$match_passe[0]['Date'].'</div>
                <div class="info_match div_info">'.$match_passe[0]['But_A'] . ' : ' . $match_passe[0]['But_B'] .'</div>
              </div>
              <div class="partie div_info"><div class="div_info"><img src="'.$match_passe[0]['Logo_B'].'" alt="Description de l"image" class="img_match"></div></div>
            </div>
            
            <div class="item item2 info_match">  
              <div class="partie div_info"><div class="div_info"><img src="'.$match_passe[1]['Logo_A'].'" alt="Description de l"image" class="img_match"></div></div>
              <div class="middle">
                <div class="info_match div_info">'.$nom_championat.'</div>
                <div class="info_match_milieu div_info">'.$match_passe[1]['Date'].'</div>
                <div class="info_match div_info">'.$match_passe[1]['But_A'] . ' : ' . $match_passe[1]['But_B'] .'</div>
              </div>
              <div class="partie div_info"><div class="div_info"><img src="'.$match_passe[1]['Logo_B'].'" alt="Description de l"image" class="img_match"></div></div>
            </div>
            
            <div class="item ite m3 info_match">  
              <div class="partie div_info"><div class="div_info"><img src="'.$match_passe[2]['Logo_A'].'" alt="Description de l"image" class="img_match"></div></div>
              <div class="middle">
                <div class="info_match div_info">'.$nom_championat.'</div>
                <div class="info_match_milieu div_info">'.$match_passe[2]['Date'].'</div>
                <div class="info_match div_info">'.$match_passe[2]['But_A'] . ' : ' . $match_passe[2]['But_B'] .'</div>
              </div>
              <div class="partie div_info"><div class="div_info"><img src="'.$match_passe[2]['Logo_B'].'" alt="Description de l"image" class="img_match"></div></div>
            </div>
            
            <div class="item item2 info_match">  
              <div class="partie div_info"><div class="div_info"><img src="'.$match_passe[3]['Logo_A'].'" alt="Description de l"image" class="img_match"></div></div>
              <div class="middle">
                <div class="info_match div_info">'.$nom_championat.'</div>
                <div class="info_match_milieu div_info">'.$match_passe[3]['Date'].'</div>
                <div class="info_match div_info">'.$match_passe[3]['But_A'] . ' : ' . $match_passe[3]['But_B'] .'</div>
              </div>
              <div class="partie div_info"><div class="div_info"><img src="'.$match_passe[3]['Logo_B'].'" alt="Description de l"image" class="img_match"></div></div>
            </div>

            <div class="item item1 info_match">  
              <div class="partie div_info"><div class="div_info"><img src="'.$match_passe[4]['Logo_A'].'" alt="Description de l"image" class="img_match"></div></div>
              <div class="middle">
                <div class="info_match div_info">'.$nom_championat.'</div>
                <div class="info_match_milieu div_info">'.$match_passe[4]['Date'].'</div>
                <div class="info_match div_info">'.$match_passe[4]['But_A'] . ' : ' . $match_passe[4]['But_B'] .'</div>
              </div>
              <div class="partie div_info"><div class="div_info"><img src="'.$match_passe[4]['Logo_B'].'" alt="Description de l"image" class="img_match"></div></div>
            </div>
          </div>
  </div>
</div>';

echo '


  </div>
  </div>

    <div class="effectif">';
  
  
  
    $equipe_pred = "SELECT * FROM `Joueurs` WHERE Id_equipe = ? ;";
    $sth = $dbh->prepare($equipe_pred);
    $success = $sth->execute(array($id_equipe));
    $equipe_pred = $sth->fetchAll(PDO::FETCH_ASSOC);
    $nb_joueur =  count($equipe_pred);



    echo '<div style="width: 100%; text-align: center; margin-bottom: 10px;">
          <span style="font-family: Arial, sans-serif; font-size: 20px; font-weight: bold;">gardien :</span>
        </div>';
    for ($i = 0; $i < $nb_joueur; $i++) {
      if ($equipe_pred[$i]['Position'] == 'Goalkeeper' ){
        echo '
        <div class="info_joueur">
          <div class="image">
            <img src="'.$equipe_pred[$i]['Photo'].'" alt="Description de l" image" class="image" style="left: 0;">
          </div>
          <div class="text">
            <p>'.$equipe_pred[$i]['Nom'].'</p>
            <p>Numéro : '.$equipe_pred[$i]['Numero'].'</p>
            <p>'.$equipe_pred[$i]['Age'].' ans</p>
            <p>'.$equipe_pred[$i]['Id_pays'].'</p>';

            if ($equipe_pred[$i]['star'] == 0){
                echo '
              <form action="" method="post">
                <label for="nbEtoiles">Nombre d"étoiles :</label>
                <select name="nbEtoiles" id="nbEtoiles">
                    <option value="1">1 étoile(s)</option>
                    <option value="2">2 étoile(s)</option>
                    <option value="3">3 étoile(s)</option>
                </select>
                <input name="joueur" type="submit" value="'.$equipe_pred[$i]['Nom'].'">
              </form>';
            }

            if ($equipe_pred[$i]['Id_pays'] == Null and $equipe_pred[$i]['star'] == 1){
              echo '
                <form action="" method="post">
                  <label for="pays_choisi">Pays : </label>
                  <select name="pays_choisi" id="pays_choisi">
                    <option value="" disabled selected>Choissisez La nationalité du joueur</option>;';
                      $requete = "SELECT * FROM pays";
                      $resultats = $dbh->query($requete);
                      if ($resultats) {
                          foreach ($resultats as $row) {
                              echo '<option value="' . $row['Id'] . '">' . $row['Nom'] . '</option>';
                          }
                      }
                  echo '</select>
                  <input name="joueur_2" type="submit" value="'.$equipe_pred[$i]['Nom'].'">
                </form>';
            }
          
          echo '
          </div>
        </div>';}
      }
    echo '<div style="width: 100%; text-align: center; margin-bottom: 10px;">
          <span style="font-family: Arial, sans-serif; font-size: 20px; font-weight: bold;">defensseur :</span>
        </div>';
    for ($i = 0; $i < $nb_joueur; $i++) {
      if ($equipe_pred[$i]['Position'] == 'Defender' ){
      echo '
      <div class="info_joueur">
        <div class="image">
          <img src="'.$equipe_pred[$i]['Photo'].'" alt="Description de l" image" class="image" style="left: 0;">
        </div>
        <div class="text">
          <p>'.$equipe_pred[$i]['Nom'].'</p>
          <p>Numéro : '.$equipe_pred[$i]['Numero'].'</p>
          <p>'.$equipe_pred[$i]['Age'].' ans</p>';

        if ($equipe_pred[$i]['star'] == 0){
            echo '
              <form action="" method="post">
                <label for="nbEtoiles">Nombre d"étoiles :</label>
                <select name="nbEtoiles" id="nbEtoiles">
                    <option value="1">1 étoile(s)</option>
                    <option value="2">2 étoile(s)</option>
                    <option value="3">3 étoile(s)</option>
                </select>
                <input name="joueur" type="submit" value="'.$equipe_pred[$i]['Nom'].'">
              </form>';
        }
         
        if ($equipe_pred[$i]['Id_pays'] == Null and $equipe_pred[$i]['star'] == 1){
          echo '
            <form action="" method="post">
              <label for="pays_choisi">Pays : </label>
              <select name="pays_choisi" id="pays_choisi">
                <option value="" disabled selected>Choissisez La nationalité du joueur</option>;';
                  $requete = "SELECT * FROM pays";
                  $resultats = $dbh->query($requete);
                  if ($resultats) {
                      foreach ($resultats as $row) {
                          echo '<option value="' . $row['Id'] . '">' . $row['Nom'] . '</option>';
                      }
                  }
              echo '</select>
              <input name="joueur_2" type="submit" value="'.$equipe_pred[$i]['Nom'].'">
            </form>';
        }
        
        echo '
        </div>
      </div>';}
    }
    echo '<div style="width: 100%; text-align: center; margin-bottom: 10px;">
          <span style="font-family: Arial, sans-serif; font-size: 20px; font-weight: bold;">milieu de terain :</span>
        </div>';
    for ($i = 0; $i < $nb_joueur; $i++) {
      if ($equipe_pred[$i]['Position'] == 'Midfielder' ){
      echo '
      <div class="info_joueur">
        <div class="image">
          <img src="'.$equipe_pred[$i]['Photo'].'" alt="Description de l" image" class="image" style="left: 0;">
        </div>
        <div class="text">
          <p>'.$equipe_pred[$i]['Nom'].'</p>
          <p>Numéro : '.$equipe_pred[$i]['Numero'].'</p>
          <p>'.$equipe_pred[$i]['Age'].' ans</p>';

        if ($equipe_pred[$i]['star'] == 0){
          echo '
            <form action="" method="post">
              <label for="nbEtoiles">Nombre d"étoiles :</label>
              <select name="nbEtoiles" id="nbEtoiles">
                  <option value="1">1 étoile(s)</option>
                  <option value="2">2 étoile(s)</option>
                  <option value="3">3 étoile(s)</option>
              </select>
              <input name="joueur" type="submit" value="'.$equipe_pred[$i]['Nom'].'">
            </form>';
        }
        if ($equipe_pred[$i]['Id_pays'] == Null and $equipe_pred[$i]['star'] == 1){
          echo '
            <form action="" method="post">
              <label for="pays_choisi">Pays : </label>
              <select name="pays_choisi" id="pays_choisi">
                <option value="" disabled selected>Choissisez La nationalité du joueur</option>;';
                  $requete = "SELECT * FROM pays";
                  $resultats = $dbh->query($requete);
                  if ($resultats) {
                      foreach ($resultats as $row) {
                          echo '<option value="' . $row['Id'] . '">' . $row['Nom'] . '</option>';
                      }
                  }
              echo '</select>
              <input name="joueur_2" type="submit" value="'.$equipe_pred[$i]['Nom'].'">
            </form>';
        }
        
        echo '
        </div>
      </div>';}
    }
    echo '<div style="width: 100%; text-align: center; margin-bottom: 10px;">
          <span style="font-family: Arial, sans-serif; font-size: 20px; font-weight: bold;">atackant :</span>
        </div>';
    for ($i = 0; $i < $nb_joueur; $i++) {
      if ($equipe_pred[$i]['Position'] == 'Attacker' ){
      echo '
      <div class="info_joueur">
        <div class="image">
          <img src="'.$equipe_pred[$i]['Photo'].'" alt="Description de l" image" class="image" style="left: 0;">
        </div>
        <div class="text">
          <p>'.$equipe_pred[$i]['Nom'].'</p>
          <p>Numéro : '.$equipe_pred[$i]['Numero'].'</p>
          <p>'.$equipe_pred[$i]['Age'].' ans</p>';

        if ($equipe_pred[$i]['star'] == 0){
          echo '
            <form action="" method="post">
              <label for="nbEtoiles">Nombre d"étoiles :</label>
              <select name="nbEtoiles" id="nbEtoiles">
                  <option value="1">1 étoile(s)</option>
                  <option value="2">2 étoile(s)</option>
                  <option value="3">3 étoile(s)</option>
              </select>
              <input name="joueur" type="submit" value="'.$equipe_pred[$i]['Nom'].'">
            </form>';
        }
        if ($equipe_pred[$i]['Id_pays'] == Null and $equipe_pred[$i]['star'] == 1){
          echo '
            <form action="" method="post">
              <label for="pays_choisi">Pays : </label>
              <select name="pays_choisi" id="pays_choisi">
                <option value="" disabled selected>Choissisez La nationalité du joueur</option>;';
                  $requete = "SELECT * FROM pays";
                  $resultats = $dbh->query($requete);
                  if ($resultats) {
                      foreach ($resultats as $row) {
                          echo '<option value="' . $row['Id'] . '">' . $row['Nom'] . '</option>';
                      }
                  }
              echo '</select>
              <input name="joueur_2" type="submit" value="'.$equipe_pred[$i]['Nom'].'">
            </form>';
        }
        
        echo '
        </div>
      </div>';}
    }
  echo "</div>";

  if (isset($_POST['joueur'])){
    $update_star = $dbh->prepare("UPDATE `Joueurs` SET `star` = ? WHERE `Joueurs`.`Nom` = ?;");
    $update_star->execute(array($_POST['nbEtoiles'],$_POST['joueur']));
  }
  if (isset($_POST['joueur_2'])){
    $update_pays = $dbh->prepare("UPDATE `Joueurs` SET `Id_pays` = ? WHERE `Joueurs`.`Nom` = ?;");
    $update_pays->execute(array($_POST['pays_choisi'],$_POST['joueur_2']));
  }

}



  

else {
    // Si le paramètre 'nom' n'est pas défini dans l'URL
    echo "Le paramètre 'nom' n'est pas défini dans l'URL.";
}

?>
</div>
</div>

</body>

</html>

