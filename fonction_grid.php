<?php
        require_once  'config.php';




function parie_cour($dbh,$email){
  $parie_cours = "  SELECT * FROM `Parie` 
  JOIN Matchs ON Parie.Id_match = Matchs.Id
  WHERE Matchs.Date > NOW() and email = ?;";
  $sth = $dbh->prepare($parie_cours);
  $success = $sth->execute(array($email));
  $parie_cours = $sth->fetchAll(PDO::FETCH_ASSOC);
  $nb_parie_cour = $parie_cours != False ? count(($parie_cours)) : 0;


  if ($nb_parie_cour == 0){
      $reponse = "veuillez parier pour cette équipe pour avoir votre historique";
  }
  else if ($nb_parie_cour == 1){
    $id_equipe_A_1 = $parie_cours[0]['Id_equipe_A'];
    $id_equipe_B_1 = $parie_cours[0]['Id_equipe_B'];


    $equipe_A_1 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
    $sth = $dbh->prepare($equipe_A_1);
    $success = $sth->execute(array($id_equipe_A_1));
    $equipe_A_1 = $sth->fetch(PDO::FETCH_ASSOC);

    $equipe_B_1 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
    $sth = $dbh->prepare($equipe_B_1);
    $success = $sth->execute(array($id_equipe_B_1));
    $equipe_B_1 = $sth->fetch(PDO::FETCH_ASSOC);

    $montant_1 = $parie_cours[0]['montant'];
    $date_1 = $parie_cours[0]['Date'];
    $logo_A_1 = $equipe_A_1['Logo'];
    $logo_B_1 = $equipe_B_1['Logo'];
    $reponse = '
      <div class="pcour_logo_1"><img src="'.$logo_A_1.'" alt="Description de l"image" class="img"></div>
      <div class="pcour_logo_11"><img src="'.$logo_B_1.'" alt="Description de l"image" class="img"></div>
      <div class="info_pari_cour_1">
        <div class="date_parie_cour_1"> date : </br>'.$date_1.'</div>
        <div class="credit_pari_cour_1">montant : </br> '.$montant_1.' credit </div>
      </div>
      veuillez parier pour cette équipe pour avoir votre historique';}
  else if ($nb_parie_cour == 2){
    
    #parie 1
    $id_equipe_A_1 = $parie_cours[0]['Id_equipe_A'];
    $id_equipe_B_1 = $parie_cours[0]['Id_equipe_B'];
    $equipe_A_1 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
      $sth = $dbh->prepare($equipe_A_1);
      $success = $sth->execute(array($id_equipe_A_1));
      $equipe_A_1 = $sth->fetch(PDO::FETCH_ASSOC);
    $equipe_B_1 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
      $sth = $dbh->prepare($equipe_B_1);
      $success = $sth->execute(array($id_equipe_B_1));
      $equipe_B_1 = $sth->fetch(PDO::FETCH_ASSOC);
    $montant_1 = $parie_cours[0]['montant'];
    $date_1 = $parie_cours[0]['Date'];
    $logo_A_1 = $equipe_A_1['Logo'];
    $logo_B_1 = $equipe_B_1['Logo'];
    
    #parie 2
    $id_equipe_A_2 = $parie_cours[1]['Id_equipe_A'];
    $id_equipe_B_2 = $parie_cours[1]['Id_equipe_B'];
    $equipe_A_2 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
    $sth = $dbh->prepare($equipe_A_2);
    $success = $sth->execute(array($id_equipe_A_2));
    $equipe_A_2 = $sth->fetch(PDO::FETCH_ASSOC);
    $equipe_B_2 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
    $sth = $dbh->prepare($equipe_B_2);
    $success = $sth->execute(array($id_equipe_B_2));
    $equipe_B_2 = $sth->fetch(PDO::FETCH_ASSOC);
    $montant_2 = $parie_cours[0]['montant'];
    $date_2 = $parie_cours[0]['Date'];
    $logo_A_2 = $equipe_A_2['Logo'];
    $logo_B_2 = $equipe_B_2['Logo'];
    $reponse = '
    <div class="pcour_logo_1"><img src="'.$logo_A_1.'" alt="Description de l"image" class="img"></div>
    <div class="pcour_logo_2"><img src="'.$logo_A_2.'" alt="Description de l"image" class="img"></div>
    <div class="pcour_logo_11"><img src="'.$logo_B_1.'" alt="Description de l"image" class="img"></div>
    <div class="pcour_logo_22"><img src="'.$logo_B_2.'" alt="Description de l"image" class="img"></div>
    
    <div class="info_pari_cour_1">
      <div class="date_parie_cour_1"> date : </br>'.$date_1.'</div>
      <div class="credit_pari_cour_1">montant : </br> '.$montant_1.' credit </div>
    </div>
    <div class="info_parie_cour_2">
      <div class="date_parie_cour_2"> date : </br>'.$date_2.'</div>
      <div class="credit_pari_cour_2">montant : </br> '.$montant_2.' credit </div>
    </div>
  veuillez parier pour cette équipe pour avoir votre historique';}
  else {
      #parie 1
      $id_equipe_A_1 = $parie_cours[0]['Id_equipe_A'];
      $id_equipe_B_1 = $parie_cours[0]['Id_equipe_B'];
      $equipe_A_1 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
        $sth = $dbh->prepare($equipe_A_1);
        $success = $sth->execute(array($id_equipe_A_1));
        $equipe_A_1 = $sth->fetch(PDO::FETCH_ASSOC);
      $equipe_B_1 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
        $sth = $dbh->prepare($equipe_B_1);
        $success = $sth->execute(array($id_equipe_B_1));
        $equipe_B_1 = $sth->fetch(PDO::FETCH_ASSOC);
      $montant_1 = $parie_cours[0]['montant'];
      $date_1 = $parie_cours[0]['Date'];
      $logo_A_1 = $equipe_A_1['Logo'];
      $logo_B_1 = $equipe_B_1['Logo'];
      
      #parie 2
      $id_equipe_A_2 = $parie_cours[1]['Id_equipe_A'];
      $id_equipe_B_2 = $parie_cours[1]['Id_equipe_B'];
      $equipe_A_2 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
      $sth = $dbh->prepare($equipe_A_2);
      $success = $sth->execute(array($id_equipe_A_2));
      $equipe_A_2 = $sth->fetch(PDO::FETCH_ASSOC);
      $equipe_B_2 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
      $sth = $dbh->prepare($equipe_B_2);
      $success = $sth->execute(array($id_equipe_B_2));
      $equipe_B_2 = $sth->fetch(PDO::FETCH_ASSOC);
      $montant_2 = $parie_cours[0]['montant'];
      $date_2 = $parie_cours[0]['Date'];
      $logo_A_2 = $equipe_A_2['Logo'];
      $logo_B_2 = $equipe_B_2['Logo'];

      #parie 3
      $id_equipe_A_3 = $parie_cours[2]['Id_equipe_A'];
      $id_equipe_B_3 = $parie_cours[2]['Id_equipe_B'];
      $equipe_A_3 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
      $sth = $dbh->prepare($equipe_A_3);
      $success = $sth->execute(array($id_equipe_A_3));
      $equipe_A_3 = $sth->fetch(PDO::FETCH_ASSOC);
      $equipe_B_3 = "SELECT Logo FROM `Equipes` WHERE `Id` = ?";
      $sth = $dbh->prepare($equipe_B_3);
      $success = $sth->execute(array($id_equipe_B_3));
      $equipe_B_3 = $sth->fetch(PDO::FETCH_ASSOC);
      $montant_3 = $parie_cours[0]['montant'];
      $date_3 = $parie_cours[0]['Date'];
      $logo_A_3 = $equipe_A_3['Logo'];
      $logo_B_3 = $equipe_B_3['Logo'];

  $reponse = '

            <div class="pcour_logo_1"><img src="'.$logo_A_1.'" alt="Description de l"image" class="img"></div>
            <div class="pcour_logo_2"><img src="'.$logo_A_2.'" alt="Description de l"image" class="img"></div>
            <div class="pcour_logo_3"><img src="'.$logo_B_3.'" alt="Description de l"image" class="img"></div>
            <div class="pcour_logo_11"><img src="'.$logo_B_1.'" alt="Description de l"image" class="img"></div>
            <div class="pcour_logo_22"><img src="'.$logo_B_2.'" alt="Description de l"image" class="img"></div>
            <div class="pcour_logo_33"><img src="'.$logo_B_3.'" alt="Description de l"image" class="img"></div>
            
            <div class="info_pari_cour_1">
              <div class="date_parie_cour_1"> date : </br>'.$date_1.'</div>
              <div class="credit_pari_cour_1">montant : </br> '.$montant_1.' credit </div>
            </div>
            <div class="info_parie_cour_2">
              <div class="date_parie_cour_2"> date : </br>'.$date_2.'</div>
              <div class="credit_pari_cour_2">montant : </br> '.$montant_2.' credit </div>
            </div>
            <div class="ifno_parie_cour_3">
              <div class="date_parie_cour_3"> date : </br>'.$date_3.'</div>
              <div class="credit_pari_cour_3">montant : </br> '.$montant_3.' credit </div>
            </div>
          ';}
      return $reponse;
  }


  function historique_parie($email,$dbh,$id_equipes){
  $reponse = '<div class="historique_parie_logo_1">histoiruqe_logo_1</div>
  <div class="historique_parie_logo_2">histoiruqe_logo_2</div>
  <div class="historique_parie_logo_3">histoiruqe_logo_3</div>
  <div class="historique_parie_logo_11">histoiruqe_logo_11</div>
  <div class="historique_parie_logo_22">histoiruqe_logo_22</div>
  <div class="historique_parie_logo_33">histoiruqe_logo_33</div>
  <div class="historique_parie_info_1">
    <div class="historique_parie_idate_1">histoiruqe_date_1</div>
    <div class="historique_parie_credit_1">histoiruqe_credit_1</div>
  </div>
  <div class="historique_parie_info_2">
    <div class="historique_parie_idate_2">histoiruqe_date_2</div>
    <div class="historique_parie_credit_2">histoiruqe_credit_2</div>
  </div>
  <div class="historique_parie_info_3">
    <div class="historique_parie_idate_3">histoiruqe_date_3</div>
    <div class="historique_parie_credit_3">histoiruqe_credit_3</div>
  </div>';
return $reponse;}