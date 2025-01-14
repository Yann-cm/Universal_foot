<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">

    <link rel="stylesheet" href="./style/formulaire.css">
    <link rel="stylesheet" href="./style/style.css">


    
    <title>Connexion</title>
    
</head>

<body class=>


<?php
        session_start();
        require_once  'config.php';


        function getIP() {
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($ips as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP)) {
                        return $ip;
                    }
                }
            } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                return $_SERVER['HTTP_CLIENT_IP'];
            } else {
                return $_SERVER['REMOTE_ADDR'];
            }
            return '';
        }

        $ip = getIP();
        $query = 'SELECT Id_utilisateur,Email FROM `utilisateur` WHERE Ip = ?';
        $requete = $dbh->prepare($query);
        $requete->execute(array($ip));
        $Id_user = $requete->fetch(PDO::FETCH_ASSOC);

        if (isset($Id_user['Id_utilisateur'])){
            if(isset($_COOKIE[$Id_user['Id_utilisateur']])){
                $_SESSION['utilisateur'] = $Id_user['Email'];
                header('Location: accueil.php');
                die();

            }
        }



        if (isset($_POST['connexion'])) {

            if (empty($_POST['email'])) {
                echo " Le mail n'est pas rempli";
            } else {
                if (empty($_POST['mdp'])) {
                    echo "Le mot de passe n'est pas rempli";
                } else {
                    $email = $_POST['email'];
                    $mdp = $_POST['mdp'];

                    
                    $query = 'SELECT  Id_utilisateur,mdp,email FROM utilisateur WHERE email = ? AND mdp = ?';
                    $requete = $dbh->prepare($query);
                    $requete->execute(array($email, $mdp));
                    $Id_user = $requete->fetch(PDO::FETCH_ASSOC);


                    if ( ! isset($Id_user['Id_utilisateur'])) {
                        echo " L'email ou le mot de passe est incorrect";
                    } else {

                        if (isset($_POST['rester_co'])){
                            $duree_vie = time() + (30 * 24 * 60 * 60);
                            setcookie($Id_user['Id_utilisateur'], $ip,$duree_vie,'/');
                        }
                        
                        $_SESSION['utilisateur'] = $email;


                        $sql = "UPDATE utilisateur SET ip = ? WHERE email = ?";

                        $sth = $dbh->prepare($sql);
                        $is_successful = $sth->execute(array($ip, $email));

                        header('Location: accueil.php');
                        die();
                    }
                }
            }
        }
        ?>


	<div class="container">
		<h1>Connexion</h1>
		<form action="#" method="post">

			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" id="email" name="email" required>
			</div>
			<div class="form-group">
				<label for="mdp">mot de Passe</label>
				<input type="password" id="mdp" name="mdp" required>
			</div>
            <div class="form-group">
                <label class="checkbox-container">Rester connecter
                    <input type="checkbox" name="rester_co">
                    <span class="checkmark"></span>
                </label>
            </div>

			<button type="submit" id="connexion" name="connexion" class="bouton">Conexion</button>
			<p class="sous-texte">Vous n'avez pas encore de compte ? <a href="inscription.php">Créer un compte</a></p>
		</form>
	</div>

    
</body>

</html>

