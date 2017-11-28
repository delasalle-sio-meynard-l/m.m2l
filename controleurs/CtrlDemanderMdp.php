<?php

//Fonctions 

    function chaine_aleatoire($nb_car, $chaine = 'azertyuiopqsdfghjklmwxcvbn123456789')
    {
        $nb_lettres = strlen($chaine) - 1;
        $generation = '';
        for($i=0; $i < $nb_car; $i++)
            {
                $pos = mt_rand(0, $nb_lettres);
                $car = $chaine[$pos];
                $generation .= $car;
            }
        return $generation;
    }
//

if(!isset($_POST["btnDemanderMdp"]) == true) {
    $nomUser = '';
    $message = '';
    $typeMessage = '';
    $themeFooter = $themeNormal;
    include_once('vues/VueDemanderMdp.php');
}
else {
    if (empty($_POST["txtDemanderMdp"]) == true) $nomUser = ""; else $nomUser = $_POST["txtDemanderMdp"];
    if($nomUser == '') {
        $message = 'Données incomplètes ou incorrectes !';
        $typeMessage = 'avertissement';
        $themeFooter = $themeProbleme;
    }
    
    else {
        include_once('modele/DAO.class.php');
        $dao = new DAO();
        
        if($dao->getUtilisateur($nomUser) == null){
            $message = "Le nom d'utilisateur est inexistant !";
            $typeMessage = 'avertissement';
            $themeFooter = $themeProbleme;
            include_once('vues/VueDemanderMdp.php');
        }
        else {
            $mdpDemande = chaine_aleatoire(5);
            
            $sujet = "Changement de votre mot de passe";
            $message = "Votre mot de passe a été modifié. \n\n";
            $message.= "Votre mot de passe est : " .$mdpDemande;
            $dao->modifierMdpUser($nomUser, $mdpDemande);
            $adrEmail = $dao->getEmailUtilisateur($nomUser);
            
            $ok = Envoyermail($adrEmail, $sujet, $message, "From : delasalle.sio.crib@gmail.com");
            
            if($ok) {
                $message = "Vous allez recevoir un mail<br>avec votre nouveau mot de passe.";
                $typeMessage = 'information';
                $themeFooter = $themeNormal;
                include_once('vues/VueDemanderMdp.php');
            }
            
            else {
                $message = "Echec lors de l'envoi du mail!";
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                include_once('vues/VueDemanderMdp.php');
            }
        }
    }
}