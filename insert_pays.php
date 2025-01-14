<?php
require_once  'config.php';
require_once  'Api_connect.php'


$response = api_pays();

#ALTER TABLE ma_table AUTO_INCREMENT = 1;

echo "sa marcheeeeeeeeeee ";
$data = json_decode( $response, true);

if ($data !== null ) {
    for ($i = 0; $i <= 244; $i++) {

		$pays = $data['response'][$i]['name'];
		$code = $data['response'][$i]['code'];
        $flag = $data['response'][$i]['flag'];

        $insert_pays = "INSERT INTO `Pays` (`Nom`, `Code`, `Flag`, `Id_api`)
        SELECT ?, ?, ?, ?
        WHERE NOT EXISTS (SELECT 1 FROM Pays WHERE `Nom` = ?)";
    
        $insert_pays = $dbh->prepare($insert_pays);
        $insert_pays->execute(array(
                        $pays,
                        $code,
                        $flag,
						$i,
                        $pays));

    }}