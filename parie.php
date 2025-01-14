                                <div class="boutons_match">
                                <form action="#" method="post">
                                    <button type="submit"  name="parie_1" class="bouton_match">Parier 400 crédits</button>
                                </form>
                                </div>

                                <form action="#" method="post">
                                    <button type="submit"  name="parie_2" class="bouton_match">Parier 400 crédits</button>
                                </form>
<?php
            
            
            if (isset($_POST['parie_1']) or isset($_POST['parie_2'])) {
            if (isset($_SESSION['utilisateur'])) {
                $equipe_parier = isset($_POST['parie_1']) ? $nom_A : $nom_B;
                echo '
                    <div id="popup" class="popup" style="display: block;">
                        <div class="popup__info popup__info__mid">
                            <span class="popup__info__fermer"></span>
                            <p>êtes vous sur de vouloir parier 400 crédit pour '.$equipe_parier.'</p>
                            <form action="#" method="post">
                                <button type="submit"  name="parier"  value="'.$equipe_parier.'"  class="bouton_match">Valider</button>
                            </form>
                            <a href="accueil.php" class="bouton_match">Annuler</a>
                        </div>
                    </div>';
                } 
            else {
                echo '
                    <div id="popup" class="popup" style="display: block;">
                        <div class="popup__info popup__info__mid">
                            <p>Veuillez vous connecter pour pouvoir parier</p>
                            <a href="index.php" class="bouton_match">Se connecter</a>
                        </div>
                    </div>';
            }
        }

        if (isset($_POST['parier'])) {
            $reponse = parie(400,$match_J['Id'],$_SESSION['utilisateur'],$dbh,$_POST['parier']);
            if (isset($reponse)){
                echo '
                    <div id="popup" class="popup" style="display: block;">
                        <div class="popup__info popup__info__mid">
                            <p>'.$reponse.'</p>
                            <a href="accueil.php" class="bouton_match">Fermer</a>
                        </div>
                    </div>';
            }
        }