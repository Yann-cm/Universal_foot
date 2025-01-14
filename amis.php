<!DOCTYPE html>
<html lang="fr">
<!-- ouvrir le site depuit le terminal php -S localhost: -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="./style/amis.css">
    <link rel="stylesheet" href="./style/popup.css">
    <script src="script/script.js"></script>

    <script src="script/script.js"></script>
    <title>UniversBet</title>
</head>

<body>
    <?php 
            session_start();
            require_once  'config.php';
            require_once  'fonction_global.php';
            require_once  'navbar.php';
            $popup = False;
            $_SESSION['add_user'] = Null;
            if (isset($_SESSION['utilisateur'])){
                
                $pseudo_user = "SELECT pseudo FROM `utilisateur` WHERE Email = ?";
                $sth = $dbh->prepare($pseudo_user);
                $success = $sth->execute(array($_SESSION['utilisateur']));
                $pseudo_user = $sth->fetch(PDO::FETCH_ASSOC);


            $pseudo = (isset($_GET['pseudo'])) ? $_GET['pseudo'] : $pseudo_user['pseudo'];

            $amis = amis($dbh,$pseudo);
        echo '
        <div class="page_amis">
            <div class="partie">
                <h2>Vos amis</h2>';

                for ($i = 0; $i < count($amis); $i++) {
                $profil_ami = voir_profil($dbh,$amis[$i]['ID_Ami']);

                echo '
                <div class="profil-container">
                    <div class="photo-profil">
                        <img src="images/avatars/'.$profil_ami['Pdp'].'" alt="Photo de profil">
                    </div>
                    <div class="info">
                        <div class="pseudo">'.$profil_ami['Pseudo'].'</div>
                        <div class="clan">Nom du clan</div>
                        <a href="profil.php?pseudo='.$amis[$i]['ID_Ami'].'" class="voir-plus">Plus d"info</a>
                    </div>
                </div>';}
            
            
            
    echo '  </div>
            <div class="partie">
                <h2>Chercher un joueur</h2>
                <form  method="GET">
                    <input type="text" name="recherche" placeholder="Rechercher...">
                    <button type="submit">Ajouter en Amis</button>
                </form>
            ';
        if (isset($_GET['recherche'])){
            
            $recherche_amis = recherche_user($dbh,$_GET['recherche']);
            echo '<h2>Joueur trouver :</h2> </br>';
            
            if (isset($recherche_amis[0])){
                for ($i = 0; $i < count($recherche_amis); $i++) {
                    $profil_ami = voir_profil($dbh,$recherche_amis[$i]['Id_utilisateur']);
                    $reponse = test_ami($dbh,$pseudo,$profil_ami['Pseudo']);
                    
                    if ($profil_ami['Pseudo'] == $pseudo){}
                    else {
                    echo '
                    <div class="profil-container">
                        <div class="photo-profil">
                            <img src="images/avatars/'.$profil_ami['Pdp'].'" alt="Photo de profil">
                        </div>
                        <div class="info">
                            <div class="pseudo">'.$profil_ami['Pseudo'].'</div>
                            <div class="clan">Nom du clan</div>
                            '.$reponse.'
                        </div>
                    </div>';
                }}
            }
            else {
                echo '<h2>Aucun Joueur Trouver ...</h2>';
            }
        }
        echo '</div>
        <div class="partie">
            <h2>Vos Demandes</h2>
            <div class="partie requete_envoyer">
                <h2>Envoyer</h2>';

        $requete_envoyer = requete_envoyer($dbh,$pseudo);

        if (isset($requete_envoyer[0])){
            for ($i = 0; $i < count($requete_envoyer); $i++) {
                $profil_ami = voir_profil($dbh,$requete_envoyer[$i]['Id_utilisateur']);
                echo '
                <div class="profil-container">
                    <div class="photo-profil">
                        <img src="images/avatars/'.$profil_ami['Pdp'].'" alt="Photo de profil">
                    </div>
                    <div class="info">
                        <div class="pseudo">'.$profil_ami['Pseudo'].'</div>
                        <div class="clan">Nom du clan</div>
                    </div>
                </div>';
            }
        }
        else {
            echo '<h2>Aucune requete Recus</h2>';
        }
        
        
        
echo '</div><div class="partie requete_recu"><h2>Reçus</h2>';
                
$requete_recu = requete_recu($dbh,$pseudo);

if (isset($requete_recu[0])){
    for ($i = 0; $i < count($requete_recu); $i++) {
        $profil_ami = voir_profil($dbh,$requete_recu[$i]['Id_utilisateur']);

        echo '
        <div class="profil-container">
            <div class="photo-profil">
                <img src="images/avatars/'.$profil_ami['Pdp'].'" alt="Photo de profil">
            </div>
            <div class="info">
                <div class="pseudo">'.$profil_ami['Pseudo'].'</div>
                <div class="clan">Nom du clan</div>
            </div>
            <form method="POST">
                <button type="submit" id="accept_user" name="accept_user" class="voir-plus add_user" value="'.$profil_ami['Pseudo'].'">Accepter</button>
            </form>
            <form method="POST">
                <button type="submit" id="refuse_user" name="refuse_user" class="voir-plus refuse_user" value="'.$profil_ami['Pseudo'].'">Refuser</button>
            </form>
        </div>';
    }
}
else {
    echo '<h2>Aucune requete envoyer</h2>';
}

echo '</div></div></div>';
     


if (isset($_POST['add_user'])){

    $_SESSION['add_pseudo'] = $_POST['add_user'];
    echo '
    <div id="popup" class="popup" style="display: block;">
        <div class="popup__info popup__info__mid">
        <p>Voulez vous vraiment Envoyer une demande d"amis '.$_POST['add_user'].') </p>
        <form method="POST" id="reload_form">
            <button type="submit" id="confirmation" name="confirmation" class="voir-plus add_user" value="'.$_POST['add_user'].'">Valider</button>
        </form>
        <form method="POST" id="reload_form">
            <button type="submit" id="fermer" name="fermer" class="voir-plus add_user">Fermer</button>
        </form>
        </div>
    </div>';
}
if (isset($_POST['confirmation']) and isset($_SESSION['add_pseudo']) and $_SESSION['add_pseudo'] != $_POST['add_user']){
    $_SESSION['add_pseudo'] = $_POST['add_user'];
    demande_amis($dbh,$pseudo,$_POST['confirmation']);
    unset($_POST['confirmation']);
    unset($_POST['add_user']);
}



if (isset($_POST['accept_user'])){
    acept_amis($dbh,$_POST['accept_user'],$pseudo);
    echo "bravoooo";
}
if (isset($_POST['refuse_user'])){
    refuse_amis($dbh,$_POST['refuse_user'],$pseudo);
}





}
else { echo "veuiller vous connecter pour voir vos amis ou les amis des autres.";}
?>
</div>
</body>
</html>
            