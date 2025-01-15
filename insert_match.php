<?php
require_once  'config.php';
require_once  'Api_connect.php';


function insert_match($id_api,$dbh){

	$response = api_match_league($id_api);

	echo $response;
	#ALTER TABLE ma_table AUTO_INCREMENT = 1;


	$data = json_decode($response, true);

	if ($data !== null ) {
		for ($i = 0; $i <= count($data['response'])-1; $i++) {


			$league = $data['response'][$i]['league']['name'];
			$dateOrigine = $data['response'][$i]['fixture']['date'];
			
			$heure_test = substr($dateOrigine, 11, 12);
			$heure = intval($heure_test) + 1;
			$date = substr($dateOrigine, 0, 10);
			$minutes = substr($dateOrigine, 13, 3); 
			$date = $date . ' ' .$heure. $minutes . ':00';

			$pays = $data['response'][$i]['league']['country'];
			$name_A = $data['response'][$i]['teams']['home']['name'];
			$logo_A = $data['response'][$i]['teams']['home']['logo'];
			$But_A = ($data['response'][$i]['goals']['home'] !== null) ? $data['response'][$i]['goals']['home'] : 0;
			$winner_A = $data['response'][$i]['teams']['home']['winner'];
			$winner_B = $data['response'][$i]['teams']['away']['winner'];
			$name_B = $data['response'][$i]['teams']['away']['name'];
			$logo_B = $data['response'][$i]['teams']['away']['logo'];
			$But_B = ($data['response'][$i]['goals']['away'] !== null) ? $data['response'][$i]['goals']['away'] : 0;
			$statue = $data['response'][$i]['fixture']['status']['long'];

			$id_api = $data['response'][$i]['fixture']['id'];
			$stade = $data['response'][$i]['fixture']['venue']['name'];
			$stade_ville = $data['response'][$i]['fixture']['venue']['city'];

			#echo $name_A."    VS    " .$name_B."    Le :".$date."     lieux : ".$stade;
			#echo "</br>";


			$id_sport = 1 ;
			
			$id_championnat = "SELECT `Id` FROM Championnat WHERE Nom = ?";
			$sth = $dbh->prepare($id_championnat);
			$success = $sth->execute(array($league));
			$id_championnat = $sth->fetch(PDO::FETCH_ASSOC);

			$id_equipe_A = "SELECT `Id` FROM Equipes WHERE Nom = ? and Id_sport = ?";
			$sth = $dbh->prepare($id_equipe_A);
			$success = $sth->execute(array($name_A,$id_sport));
			$id_equipe_A = $sth->fetch(PDO::FETCH_ASSOC);

			$id_equipe_B = "SELECT `Id` FROM Equipes WHERE Nom = ? and Id_sport = ?";
			$sth = $dbh->prepare($id_equipe_B);
			$success = $sth->execute(array($name_B,$id_sport));
			$id_equipe_B = $sth->fetch(PDO::FETCH_ASSOC);


			$id_Statue = "SELECT `Id` FROM Statue WHERE Nom = ?";
			$sth = $dbh->prepare($id_Statue);
			$success = $sth->execute(array($statue));
			$id_Statue = $sth->fetch(PDO::FETCH_ASSOC);



			if (! isset($id_Statue['Id'])){
				$insert_statue = "INSERT INTO `statue` (`Nom`) VALUES (?);";
				$insert_statue = $dbh->prepare($insert_statue);
				$insert_statue->execute(array($statue));
			}

			$id_Statue = "SELECT `Id` FROM Statue WHERE Nom = ?";
			$sth = $dbh->prepare($id_Statue);
			$success = $sth->execute(array($statue));
			$id_Statue = $sth->fetch(PDO::FETCH_ASSOC);

			$test_insert = "SELECT api FROM `Matchs` WHERE Id_equipe_A = ? AND Id_equipe_B = ? AND Date = ?";
			$sth = $dbh->prepare($test_insert);
			$success = $sth->execute(array($id_equipe_A['Id'],$id_equipe_B['Id'],$date));
			$test_insert = $sth->fetch(PDO::FETCH_ASSOC);



			$uplast_insert = "UPDATE Championnat 
							SET Last_insert = NOW() ";
			$uplast_insert = $dbh->prepare($uplast_insert);
			$uplast_insert->execute(array());




			if (isset($test_insert['api'])){

				$update_match = "UPDATE `Matchs`
										SET `But_A` = ?, But_B = ?, Id_statue = ?
										WHERE Id_equipe_A = ? and Id_equipe_B = ? and Date = ?";
				$update_match = $dbh->prepare($update_match);
				$update_match->execute(array($But_A,$But_B,$id_Statue['Id'],$id_equipe_A['Id'],$id_equipe_B['Id'],$date));


			}
			else {
			$insert_equipe = "INSERT INTO `Matchs` (`Id_equipe_A`, `Id_equipe_B`, `Id_championat`, `Date`, `But_A`, `But_B`, `Id_Sport`, `Id_statue`, `api`)
									SELECT ?, ?, ?, ?, ?, ?, ?, ?, ?
									WHERE NOT EXISTS (SELECT 1 FROM Matchs WHERE `Date` = ? AND `Id_equipe_A` = ? AND `Id_equipe_B` = ?)";
								
			$insert_equipe = $dbh->prepare($insert_equipe);
			$insert_equipe->execute(array($id_equipe_A['Id'],
											$id_equipe_B['Id'],
											$id_championnat['Id'],
											$date,
											$But_A,
											$But_B,
											$id_sport,
											$id_Statue['Id'],
											$id_api,
											$date,
											$id_equipe_A['Id'],
											$id_equipe_B['Id']));}

			

		}}else {
		echo "Erreur de décodage JSON.";
	}

}
