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
    $nomUtilisateur = '';
    $message = '';
    $typeMessage = '';
    $themeFooter = $themeNormal;
    include_once('vues/VueDemanderMdp.php');
}
else {
    if (empty($_POST["txtDemanderMdp"]) == true) $nomUtilisateur = ""; else $nomUtilisateur = $_POST["txtDemanderMdp"];
    if($nomUtilisateur == '') {
        $message = 'Données incomplètes ou incorrectes !';
        $typeMessage = 'avertissement';
        $themeFooter = $themeProbleme;
        include_once('vues/VueDemanderMdp.php');
    }
    
    else {
        include_once('modele/DAO.class.php');
        $dao = new DAO();
        $out = new Outils();
        
        if($dao->getUtilisateur($nomUtilisateur) == null){
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
            $dao->modifierMdpUser($nomUtilisateur, $mdpDemande);
            $adrEmail = $dao->getEmailUtilisateur($nomUtilisateur);
            
            $ok = $out->envoyerMail($adrEmail, $sujet, $message, "delasalle.sio.crib@gmail.Com");
            
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