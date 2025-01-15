<?php
        require_once  'config.php';
		require_once  'insert_match.php';
		require_once  'insert_position.php';
		require_once  'Api_connect.php';


function parie($montant,$id_match,$email,$dbh,$equipe){
	$query = 'SELECT `Id`, `date` FROM Parie WHERE email = ?';
	$requete = $dbh->prepare($query);
	$requete->execute(array($email));
	$test_co = $requete->rowCount();

	$test_credit = "SELECT Credit FROM `utilisateur` WHERE Email = ?";
	$sth = $dbh->prepare($test_credit);
	$success = $sth->execute(array($email));
	$test_credit = $sth->fetch(PDO::FETCH_ASSOC);

	if($test_credit < 400){
			return "Ereur vous n'avez pas assez de crédit , 400 crédit sont nécessaire";
	}
	
	else if($test_co == 0){
		$insertparie = $dbh->prepare("INSERT INTO `Parie` (`Id`, `date`, `email`, `Id_match`, `montant`,`equipe`) VALUES (NULL, NOW(), ?, ?, ?, ?);");
		$insertparie->execute(array($email, $id_match,$montant,$equipe));
		$insertparie = $dbh->prepare("UPDATE utilisateur SET Credit = Credit - ? WHERE Email = ?;");
		$insertparie->execute(array($montant,$email));
		return "Votre vote à été valider x)";
	}
	else {
		return "Vous avez deja parier...";
	}
}
function nb_match($id_equipe,$dbh){
	$nb_matchs = "SELECT  (win_h+win_a) as nb_win, 
						(lose_h+lose_a) as nb_lose, 
						(draw_h+draw_a) as nb_draw, 
						((win_h + win_a) + (lose_h + lose_a) + (draw_h + draw_a)) as nb_match 
						FROM `Position` WHERE Id_equipes = ?";
	$sth = $dbh->prepare($nb_matchs);
	$success = $sth->execute(array($id_equipe));
	$nb_matchs = $sth->fetch(PDO::FETCH_ASSOC);
	return $nb_matchs;
}
function classement($id_championnat,$dbh){
	$classement = "	SELECT Position.Position,Nom, Logo,(Position.win_h+Position.win_a) AS Win, (Position.lose_h+Position.lose_a) AS Lose, (Position.draw_h+Position.draw_a) AS Draw, g_marquer - g_encaisser as goaldif,(win_h+win_a)*3+draw_h+draw_a as `point` ,((win_h + win_a) + (lose_h + lose_a) + (draw_h + draw_a)) as nb_match , g_marquer , g_encaisser
					FROM `Equipes` 
					JOIN Position ON Position.Id_equipes = Equipes.Id 
					WHERE Position.Id_championnat = ?
					ORDER BY Position.Position";
    $sth = $dbh->prepare($classement);
    $success = $sth->execute(array($id_championnat));
    $classement = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $classement;
}
function info_team($id_api,$dbh){

	$response = api_info_team($id_api);


	$data = json_decode($response, true);

	$nom = $data['response'][0]['venue']['name'];
	$ville = $data['response'][0]['venue']['city'];
	$nb_place = $data['response'][0]['venue']['capacity'];
	$date = $data['response'][0]['team']['founded'];
	$photo = $data['response'][0]['venue']['image'];
	$surface = $data['response'][0]['venue']['surface'];

	$insertparie = $dbh->prepare("INSERT INTO `Stades` (`Nom`, `Ville`, `Nb_place`, `date_creation`,`photo`,`Surface`) VALUES (?, ?, ?, ?, ?,?);");
	$insertparie->execute(array($nom, $ville,$nb_place,$date,$photo,$surface));

	
    $id_stade = " SELECT `Id` FROM `Stades` WHERE `Nom` = ? and `Ville` = ?";
	$sth = $dbh->prepare($id_stade);
	$success = $sth->execute(array($nom,$ville));
	$id_stade = $sth->fetch(PDO::FETCH_ASSOC);

	$insert_id_stade = $dbh->prepare("UPDATE Equipes SET Id_stade = ? WHERE `Id_api` = ?;");
	$insert_id_stade->execute(array($id_stade['Id'], $id_api));
	
	return [$nom,$ville,$nb_place,$date,$photo];
}
function joueur($id_api,$dbh){
	$response = api_info_joueur_team($id_api);
	
	$data = json_decode($response, true);

		
    $id_equipe = "	SELECT `Id` FROM `Equipes` WHERE Id_api = ?";
	$sth = $dbh->prepare($id_equipe);
	$success = $sth->execute(array($id_api));
	$id_equipe = $sth->fetch(PDO::FETCH_ASSOC);
	
	$deletejoueur = $dbh->prepare("DELETE FROM Joueurs
									WHERE Id_equipe = ?;");
	$deletejoueur->execute(array($id_equipe['Id']));

	$change_date = $dbh->prepare("UPDATE Equipes SET last_insert = NOW() WHERE `Id` = ?;");
	$change_date->execute(array($id_equipe['Id']));
	  
	foreach ($data['response'][0]['players'] as $player) {
		  $api = $player['id'];
		  $nom= $player['name'];
		  $age =$player['age'];
		  $numero = $player['number'] !== null ? $player['number'] : 0;
		  $position = $player['position'];
		  $photo = $player['photo'];
		  $insertjoueur = $dbh->prepare("INSERT INTO `Joueurs` (`Id`, `Nom`, `Age`, `Numero`, `Position`,`Photo`,`Id_api`,`Id_equipe`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?);");
		  $insertjoueur->execute(array($nom, $age,$numero,$position,$photo,$api,$id_equipe['Id']));
	}
	return 3;
}	
function insert_info_championnat($dbh){
		$id_champ = 
		"SELECT Id_api 
		FROM Championnat 
		WHERE DATEDIFF(NOW(), Last_insert) > 1;";
		
		$sth = $dbh->prepare($id_champ);
		$success = $sth->execute(array());
		$id_champ = $sth->fetchAll(PDO::FETCH_ASSOC);

		for ($i = 0; $i < count($id_champ); $i++) {
			insert_position($id_champ[$i]['Id_api'],$dbh);
			insert_match($id_champ[$i]['Id_api'],$dbh);
			}
		return True;
}
function amis($dbh,$pseudo){
	$amis = "SELECT DISTINCT Amis.ID_1 as ID_Ami FROM Amis
				JOIN utilisateur as user_2 ON Amis.ID_2 = user_2.Id_utilisateur
				WHERE user_2.Pseudo = ? and Amis.Reponse is NOT NULL
			UNION 
			SELECT DISTINCT Amis.ID_2 From Amis
				JOIN utilisateur as user_1 ON Amis.ID_1 = user_1.Id_utilisateur
				WHERE user_1.pseudo = ? and Amis.Reponse is NOT NULL;";
	$sth = $dbh->prepare($amis);
	$success = $sth->execute(array($pseudo,$pseudo));
	$amis = $sth->fetchAll(PDO::FETCH_ASSOC);

	return $amis;
}
function requete_envoyer($dbh,$pseudo){
	$requete_envoyer = "SELECT user_2.Pseudo, user_2.Pdp ,user_2.Id_utilisateur
	FROM Amis
	JOIN utilisateur as user_1 ON user_1.Id_utilisateur = Amis.ID_1
	JOIN utilisateur as user_2 ON user_2.Id_utilisateur = Amis.ID_2
	WHERE user_1.Pseudo = ? and Amis.Reponse is NULL;";
	$sth = $dbh->prepare($requete_envoyer);
	$success = $sth->execute(array($pseudo));
	$requete_envoyer = $sth->fetchAll(PDO::FETCH_ASSOC);

	return $requete_envoyer;
}
function requete_recu($dbh,$pseudo){
	$requete_recu = "SELECT user_2.Pseudo, user_2.Pdp ,user_2.Id_utilisateur
		FROM Amis
		JOIN utilisateur as user_1 ON user_1.Id_utilisateur = Amis.ID_2
		JOIN utilisateur as user_2 ON user_2.Id_utilisateur = Amis.ID_1
		WHERE user_1.Pseudo = ? and Amis.Reponse is NULL;";
	$sth = $dbh->prepare($requete_recu);
	$success = $sth->execute(array($pseudo));
	$requete_recu = $sth->fetchAll(PDO::FETCH_ASSOC);

	return $requete_recu;
}
function demande_amis($dbh,$pseudo_1,$pseudo_2){
	$insertparie = $dbh->prepare("INSERT INTO `Amis` (`ID_1`, `ID_2`) 
									SELECT user_1.Id_utilisateur, user_2.Id_utilisateur
									FROM utilisateur AS user_1, utilisateur AS user_2
									WHERE user_1.Pseudo = ? AND user_2.Pseudo = ?;");
	$insertparie->execute(array($pseudo_1,$pseudo_2));

}
function acept_amis($dbh,$pseudo_1,$pseudo_2){
	$accept = "UPDATE Amis 
				JOIN utilisateur AS user_1 ON user_1.Id_utilisateur = Amis.ID_1
				JOIN utilisateur AS user_2 ON user_2.Id_utilisateur = Amis.ID_2
				SET Amis.Reponse = NOW()
				WHERE user_1.Pseudo = ?  AND user_2.Pseudo = ?;";
	$sth = $dbh->prepare($accept);
	$is_successful = $sth->execute(array($pseudo_1,$pseudo_2));
	
	return True;
}
function refuse_amis($dbh,$pseudo_1,$pseudo_2){
	$refuse = "DELETE Amis FROM  Amis 
					JOIN utilisateur AS user_1 ON user_1.Id_utilisateur = Amis.ID_1
					JOIN utilisateur AS user_2 ON user_2.Id_utilisateur = Amis.ID_2
					WHERE user_1.Pseudo = ?  AND user_2.Pseudo = ?;";
	$sth = $dbh->prepare($refuse);
	$is_successful = $sth->execute(array($pseudo_1,$pseudo_2));
	
	return True;
}
function recherche_user($dbh,$pseudo){
	$user = "SELECT * FROM `utilisateur` 
				WHERE Pseudo LIKE ?;";
	$sth = $dbh->prepare($user);
	$success = $sth->execute(array("%$pseudo%"));
	$user = $sth->fetchAll(PDO::FETCH_ASSOC);

	return $user;


}
function highlight_match($dbh){

		$reset = "UPDATE Matchs 
		SET Matchs.highlight = 0;";
		$sth = $dbh->prepare($reset);
		$is_successful = $sth->execute(array());

		$match_high = "UPDATE Matchs 
		JOIN Equipes AS Equipe_A ON Equipe_A.Id = Matchs.Id_equipe_A 
		JOIN Equipes AS Equipe_B ON Equipe_B.Id = Matchs.Id_equipe_B 
		JOIN Position AS Position_A ON Equipe_A.Id = Position_A.Id_equipes 
		JOIN Position AS Position_B ON Equipe_B.Id = Position_B.Id_equipes 
		SET Matchs.highlight = 1 
		WHERE Position_A.Position < 5 and Position_B.Position < 5 and Matchs.date > NOW() and Matchs.date < DATE_ADD(NOW(), INTERVAL 14 DAY);";

		$sth = $dbh->prepare($match_high);
		$is_successful = $sth->execute(array());




}
function voir_profil($dbh,$pseudo){

	$profil = "SELECT * FROM `utilisateur` 
						where Pseudo = ? or Id_utilisateur = ?";
	$sth = $dbh->prepare($profil);
	$success = $sth->execute(array($pseudo,$pseudo));
	$profil = $sth->fetch(PDO::FETCH_ASSOC);

	return $profil;
}
function test_ami($dbh,$pseudo_1,$pseudo_2){
	$query = 'SELECT user_2.Pseudo
				FROM Amis 
				JOIN utilisateur as user_1 ON user_1.Id_utilisateur = Amis.ID_1
				JOIN utilisateur as user_2 ON user_2.Id_utilisateur = Amis.ID_2
				WHERE user_1.Pseudo = ? and user_2.Pseudo = ? and Amis.Reponse is NULL';
	$requete = $dbh->prepare($query);
	$requete->execute(array($pseudo_1,$pseudo_2));
	$test_ami = $requete->rowCount();

	if ($test_ami > 0){
		return 'Votre demande à été envoyer à '.$pseudo_2;
	}

	else{
		$query = 'SELECT user_2.Pseudo
		FROM Amis 
		JOIN utilisateur as user_1 ON user_1.Id_utilisateur = Amis.ID_1
		JOIN utilisateur as user_2 ON user_2.Id_utilisateur = Amis.ID_2
		WHERE (user_1.Pseudo = ? and user_2.Pseudo = ?) or 
				(user_1.Pseudo = ? and user_2.Pseudo = ?) 
				and Amis.Reponse is not NULL';
		$requete = $dbh->prepare($query);
		$requete->execute(array($pseudo_1,$pseudo_2,$pseudo_2,$pseudo_1));
		$test_2 = $requete->rowCount();

		if ($test_2 > 0 ){
			return " ";
		}

		else {
			$query = 'SELECT user_2.Pseudo
						FROM Amis 
						JOIN utilisateur as user_1 ON user_1.Id_utilisateur = Amis.ID_1
						JOIN utilisateur as user_2 ON user_2.Id_utilisateur = Amis.ID_2
						WHERE user_1.Pseudo = ? and user_2.Pseudo = ? and Amis.Reponse is NULL';
			$requete = $dbh->prepare($query);
			$requete->execute(array($pseudo_2,$pseudo_1));
			$test_3 = $requete->rowCount();

			if($test_3 > 0){
				return '
				<form method="POST">
                	<button type="submit" id="accept_user" name="accept_user" class="voir-plus add_user" value="'.$pseudo_2.'">Accepter</button>
            	</form>
           		<form method="POST">
                	<button type="submit" id="refuse_user" name="refuse_user" class="voir-plus refuse_user" value="'.$pseudo_2.'">Refuser</button>
            	</form>';
			}

			else {
				return '
				<form method="POST">
					<button type="submit" id="add_user" name="add_user" class="voir-plus add_user" value="'.$pseudo_2.'">Ajouter</button>
				</form>';
			}

		}
		
	}


}
function id_and_joueur_pays($dbh,$pays){

	$response = api_info_team_nat($pays);

	$data = json_decode($response, true);

	$stade = $data['response'][0]['venue']['name'];
	$nb_place = $data['response'][0]['venue']['capacity'];
	$ville = $data['response'][0]['venue']['city'];
	$date = $data['response'][0]['team']['founded'];
	$photo = $data['response'][0]['venue']['image'];
	$surface = $data['response'][0]['venue']['surface'];
	$logo = $data['response'][0]['team']['logo'];
	$api = $data['response'][0]['team']['id'];
	$nom = $data['response'][0]['team']['country'];

	$insertstades = $dbh->prepare("INSERT INTO `Stades` (`Nom`, `Ville`, `Nb_place`, `date_creation`,`photo`,`Surface`) VALUES (?, ?, ?, ?, ?,?);");
	$insertstades->execute(array($stade, $ville,$nb_place,$date,$photo,$surface));

	
    $id_stade = " SELECT `Id` FROM `Stades` WHERE `Nom` = ? and `Ville` = ?";
	$sth = $dbh->prepare($id_stade);
	$success = $sth->execute(array($stade,$ville));
	$id_stade = $sth->fetch(PDO::FETCH_ASSOC);
	
	$insertpays = $dbh->prepare("UPDATE Pays SET Id_stade = ? , Founded = ?, Logo = ?, Id_api = ? WHERE Nom = ?;");
	$insertpays->execute(array($id_stade['Id'],$date,$logo,$api,$nom));



    $id_pays = " SELECT `Id`,`Id_api` FROM `Pays` WHERE `Nom` = ?";
	$sth = $dbh->prepare($id_pays);
	$success = $sth->execute(array($nom));
	$id_pays = $sth->fetch(PDO::FETCH_ASSOC);

	$response = api_joueur_team_nat($id_pays['Id_api']);
	$data = json_decode($response, true);
	
	foreach ($data['response'][0]['players'] as $player) {
		
		$id = $player['id'];
		$insertpays = $dbh->prepare("UPDATE Joueurs SET Id_pays = ?  WHERE Id_api = ?;");
		$insertpays->execute(array($id_pays['Id'],$id));

	}

}