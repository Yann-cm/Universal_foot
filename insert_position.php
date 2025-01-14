<?php

require_once  'config.php';
require_once  'Api_connect.php';




function insert_position($id_api,$dbh){
	$response = api_info_league($id_api);


	$data = json_decode($response, true);

	if ($data !== null) {
		$league = $data['response'][0]['league']['name'];
		$logo = $data['response'][0]['league']['logo'];
		$pays = $data['response'][0]['league']['country'];
		$drapeaux = $data['response'][0]['league']['flag'];
		$nb_equipes = count($data['response'][0]['league']['standings'][0]);
		$id_api = $data['response'][0]['league']['id'];

		
		echo $league;
		echo "</br>";
		echo $pays;
		echo "</br>";
		echo $nb_equipes;

		$insert_championnat = "INSERT INTO `Championnat` (`Nom`, `Logo`, `Date_debut`, `Date_fin`, `Id_sport`, `Nb_equipes`,`pays`,`drapeaux`,Id_api) 
								SELECT ?, ?, '2024-08-16', '2025-05-18', ?, ?, ?, ?, ? 
								FROM DUAL
								WHERE NOT EXISTS (SELECT 1 FROM Championnat WHERE `Nom` = ?)";
		$insert_championnat = $dbh->prepare($insert_championnat);
		$insert_championnat->execute(array($league, $logo, 1, $nb_equipes,$pays,$drapeaux,$id_api,$league));


		$id_championnat = "SELECT id FROM Championnat WHERE Nom = ?";
		$sth = $dbh->prepare($id_championnat);
		$success = $sth->execute(array($league));
		$id_championnat = $sth->fetch(PDO::FETCH_ASSOC);



		for ($i = 0; $i < $nb_equipes; $i++) {
			
			$rank = $data['response'][0]['league']['standings'][0][$i]['rank'];
			$name = $data['response'][0]['league']['standings'][0][$i]['team']['name'];
			$id_api = $data['response'][0]['league']['standings'][0][$i]['team']['id'];
			$logo = $data['response'][0]['league']['standings'][0][$i]['team']['logo'];
			$but_marquer = $data['response'][0]['league']['standings'][0][$i]['all']['goals']['for'];
			$but_encaiser = $data['response'][0]['league']['standings'][0][$i]['all']['goals']['against'];
			$h_win = $data['response'][0]['league']['standings'][0][$i]['home']['win'];
			$h_lose = $data['response'][0]['league']['standings'][0][$i]['home']['lose'];
			$h_draw = $data['response'][0]['league']['standings'][0][$i]['home']['draw'];
			$a_win = $data['response'][0]['league']['standings'][0][$i]['away']['win'];
			$a_lose = $data['response'][0]['league']['standings'][0][$i]['away']['lose'];
			$a_draw = $data['response'][0]['league']['standings'][0][$i]['away']['draw'];
			#echo "$rank,   $name </br>";

			$insert_equipe = "INSERT INTO Equipes (`Nom`, `Logo`,`Id_api`, `Id_sport`,`last_insert`)
								SELECT ?, ?, ?,?, Now()
								WHERE NOT EXISTS (SELECT 1 FROM Equipes WHERE Nom = ?)";
			$insert_equipe = $dbh->prepare($insert_equipe);
			$insert_equipe->execute(array($name, $logo, $id_api,1, $name));
			
			$id_equipe = "SELECT id FROM Equipes WHERE Nom = ?";
			$sth = $dbh->prepare($id_equipe);
			$success = $sth->execute(array($name));
			$id_equipe = $sth->fetch(PDO::FETCH_ASSOC);

			
			$test_position = "SELECT * FROM `Position` WHERE Id_equipes = ? and Id_championnat = ?";
			$sth = $dbh->prepare($test_position);
			$success = $sth->execute(array($id_equipe['id'], $id_championnat['id']));
			$test_position = $sth->fetch(PDO::FETCH_ASSOC);
			
			if (! $test_position) {
				$insert_position = "INSERT INTO `Position` (Id_championnat, Id_equipes, `Position`,g_marquer,g_encaisser,win_h,win_a,lose_h,lose_a,draw_h,draw_a) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$insert_position = $dbh->prepare($insert_position);
				$insert_position->execute(array($id_championnat['id'], $id_equipe['id'], $rank,$but_marquer,$but_encaiser,$h_win,$a_win,$h_lose,$a_lose,$h_draw,$a_draw));
			} 
			else {
				$update_position = "UPDATE `Position`
										SET `Position` = ?, g_marquer = ?, g_encaisser = ?, win_h = ?, win_a = ?,lose_h = ?, lose_a = ?,draw_h = ?, draw_a = ?
										WHERE Id_championnat = ? and Id_equipes = ?";
				$update_position = $dbh->prepare($update_position);
				$update_position->execute(array($rank,$but_marquer,$but_encaiser,$h_win,$a_win,$h_lose,$a_lose,$h_draw,$a_draw, $id_championnat['id'], $id_equipe['id']));
			}

	}}
}