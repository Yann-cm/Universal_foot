<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./style/formulaire.css">
    <link rel="stylesheet" href="./style/style.css">

    <title>Inscription</title>
</head>
<body class="">
    <?php
        require_once  'config.php';
        error_reporting(0);
        
        if(isset($_POST['forminscription'])) {
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $email = htmlspecialchars($_POST['email']);
            $mdp = $_POST['mdp'];
            $mdp_conf = $_POST['mdp_conf'];
            $departement = $_POST['departement'];


            if(!empty($_POST['pseudo']) AND !empty($_POST['email']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp_conf'])) {
                $prenomlength = strlen($prenom);
    
                if($prenomlength <= 255) {
        
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

                        $reqemail = $dbh->prepare("SELECT * FROM utilisateur WHERE email = ?");
                        $reqemail->execute(array($email));
                        $emailexist = $reqemail->rowCount();



                        $reqpseudo = $dbh->prepare("SELECT * FROM utilisateur WHERE pseudo = ?");
                        $reqpseudo->execute(array($pseudo));
                        $pseudoExist = $reqpseudo->rowCount();


                        if ($pseudoExist > 0) {
                            $erreur = "<p class=''>Pseudo dejà utilise !</p>";
                        } 
                        elseif ($emailexist == 0) {
                            if ($mdp == $mdp_conf) {
                                
                                $id_departement = "SELECT `Id`,Id_region FROM `Departement` WHERE `nom` = ?";
                                $sth = $dbh->prepare($id_departement);
                                $success = $sth->execute(array($departement));
                                $id_departement = $sth->fetch(PDO::FETCH_ASSOC);

                                $insertmbr = $dbh->prepare("INSERT INTO utilisateur(email, mdp,pseudo, date_creation, credit,Id_departement,Id_region) VALUES(?, ?, ?, NOW(), 400,?,?)");
                                $insertmbr->execute(array($email,$mdp, $pseudo,$id_departement['Id'],$id_departement['Id_region']));
                                header("Location: index.php");
                                exit();
                            } 
                            else {
                                $erreur = "<label class=''>Vos mots de passes ne correspondent pas !</label>";
                            }
                        } 
                        else {
                            $erreur = "<label class='smaller-mg-left nes-text is-error'>Adresse email dejà utilisee !</label>";
                        }
                    } 
                    else {
                        $erreur = "<label class='smaller-mg-left nes-text is-error'>Votre adresse email n'est pas valide !</label>";
                    }
                } 
                else {
                    $erreur = "<label class='smaller-mg-left nes-text is-error'>Votre prenom ne doit pas depasser 255 caracteres !</label>";
                }
            } 
            else {
                $erreur = "<label class='smaller-mg-left nes-text is-error'>Tous les champs doivent être completes !</label>";
            }


        }
        ?>

	<div class="container">
		<h1>Créer un Compte</h1>
		<form action="" method="post">
			<div class="form-group">
				<label for="text">Pseudo</label>
				<input type="text" id="pseudo" name="pseudo" required>
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" id="email" name="email" required>
			</div>
			<div class="form-group">
                <?php 
                        $requete = "SELECT * FROM Departement";
                        $resultats = $dbh->query($requete);
                        
                        if ($resultats) {
                        
                        echo '<label for="departement">Sélectionnez un departement :</label>';
                        echo '<select id="departement" name="departement" required>
                                <option value="" disabled selected>Choissisez votre département ?</option>';
                        
                        foreach ($resultats as $row) {
                            echo '<option value="' . $row['Id'] . '">' . $row['nom'] . '</option>';
                        }
                        echo '</select>';}
                ?>
                    
            </div>
			<div class="form-group">
				<label for="mdp">Mot de Passe</label>
				<input type="password" id="mdp" name="mdp" required>
			</div>
			<div class="form-group">
				<label for="mdp_conf">Confirmation Mot de Passe</label>
				<input type="password" id="mdp_conf" name="mdp_conf" required>
			</div>
			<button type="submit" id="forminscription" name="forminscription" class = "bouton">Créer un compte </button>
			<p class="sous-texte">Vous avez deja un compte ?? <a href="index.php">Connection</a></p>
		</form>
	</div>

    </div>

</body>
</html>
