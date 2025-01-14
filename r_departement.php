<?php
require_once  'config.php';
// Chemin vers le fichier JSON
$cheminFichier = 'C:\Users\yannc\OneDrive\Documents\Gitlab\Gitlab BTS\projet_bts1_universbet\departements-region (1).json';

// Lire le contenu du fichier JSON
$contenuFichier = file_get_contents($cheminFichier);

// Décoder le contenu JSON en une structure de données PHP
$data = json_decode($contenuFichier, true);


if ($data === null) {
    // La conversion a échoué
    echo 'Erreur lors de la lecture du fichier JSON.';
} else {
    // La conversion a réussi, vous pouvez maintenant utiliser les données
	for ($i = 0; $i < count($data); $i++) {
        $region = $data[$i]['region_name'];
        $departement = $data[$i]['dep_name'];
        $id = $data[$i]['num_dep'];

        $insert_region = "INSERT INTO Region (`Nom`)
        SELECT ?
        WHERE NOT EXISTS (SELECT 1 FROM Region WHERE Nom = ?)";
        $insert_region = $dbh->prepare($insert_region);
        $insert_region->execute(array($region,$region));
        
        
		$id_region = "SELECT id FROM Region WHERE Nom = ?";
		$sth = $dbh->prepare($id_region);
		$success = $sth->execute(array($region));
		$id_region = $sth->fetch(PDO::FETCH_ASSOC);

        echo  $id_region['id'];

        $insert_departement = "INSERT INTO Departement (`Id`, `Id_region`, `Nom`)
                                SELECT ?, ?, ?
                                WHERE NOT EXISTS (SELECT 1 FROM Departement WHERE Nom = ?)";
        $insert_departement = $dbh->prepare($insert_departement);
        $insert_departement->execute(array(strval($id), $id_region['id'], $departement, $departement));

        
        echo " $region   ->     $departement       ->  $id    </br> </br>";




    }
}



