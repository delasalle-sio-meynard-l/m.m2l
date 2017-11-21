<?php

if(!isset($_POST["txtNouveauMdp"]) && !isset($_POST["txtConfirmation"])) {
    $nouveauMdp = '';
    $confirmationMdp = '';
    $afficherMdp = 'off';
    $message = '';
    $typeMessage = '';
    include_once('vues/VueChangerDeMdp.php');
}
else {
    if(empty($_POST["txtNouveauMdp"]) == true) $nouveauMdp = "";
        else $nouveauMdp = $_POST["txtNouveauMdp"];
    if(empty($_POST["txtConfirmation"]) == true) $confirmationMdp = "";
        else $confirmationMdp = $_POST["txtConfirmation"];
    if(empty($_POST["caseAfficherMdp"]) == true) $afficherMdp = 'off';
        else $afficherMdp = $_POST["caseAfficherMdp"];
        
        //$EXPRESSION = "#^(.*[0-9].*[a-z].*[A-Z].*|.*[0-9].*[A-Z].*[a-z].*|.*[a-z].*[A-Z].*[0-9].*|.*[a-z].*[0-].*[A-Z].*|.*[A-Z].*[0-9].*[a-z].*|.*[A-Z].*[a-z].*[0-9].*)$#";
        $EXPRESSION = "^[a-zA-Z0-9]+";
        if(strlen($nouveauMdp) <5) {
            $message = 'Données incomplètes';
            $typeMessage = 'avertissement';
            include_once('vues/VueChangerDeMdp.php');
        }
        else {
            if($nouveauMdp != $confirmationMdp) {
                $message = 'Le nouveau mot de passe et<br>sa confirmation sont différents';
                $typeMessage = 'avertissement';
                //include_once('vues/VueChangerDeMdp.php');
            }
            else {
                include_once ('modele/DAO.class.php');
                $dao = new DAO();
                $dao->modifierMdpUser($nom, $nouveauMdp);
                
                $sujet = "Modification de votre mot de passe";
                $message = "Votre mot de passe a été modifié. \n\n";
                $message.= "Votre mot de passe est : " .$nouveauMdp;
                //$adresseEmetteur = "delasalle.sio.eleves@gmail.com";
                //$adresseDestinataire = "delasalle.sio.meynard.l@gmail.com";
                
                $adrEmail = $dao->getEmailUtilisateur($nom);
                
                $ok = mail($adrEmail, $sujet, $message, "From: delasalle.sio.crib@gmail.com");
                
                if($ok) {
                    $message = "Enregistrement effectué.<br>Vous allez recevoir un mail de confirmation.";
                    $typeMessage = 'information';
                    $themeFooter = $themeNormal;
                    include_once('vues/VueChangerDeMdp.php');
                }
                
                else {
                    $message = "Enregistrement effectué.<br>L'envoi du mail de confirmation a rencontré un problème.";
                    $typeMessage = 'avertissement';
                    $themeFooter = $themeProbleme;
                    include_once ('vues/VueChangerDeMdp.php');
                }
                
                
            }
        }
}