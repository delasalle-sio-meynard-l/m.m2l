<?php
    // inclusion de la classe Outils
    include_once ('../modele/Outils.class.php');
    // inclusion des paramètres de l'application
    include_once ('../modele/parametres.localhost.php');

    // Récupération des données transmises
    // la fonction $_GET récupère une donnée passée en paramètre dans l'URL par la méthode GET
    if ( empty ($_GET ["nomUtilisateur"]) == true)  $nomUtilisateur = "";  else   $nomUtilisateur = $_GET ["nom"];
    if ( empty ($_GET ["mdpUtilisateur"]) == true)  $mdpUtilisateur = "";  else   $mdpUtilisateur = $_GET ["mdp"];

    // si l'URL ne contient pas les données, on regarde si elles ont été envoyées par la méthode POST
    // la fonction $_POST récupère une donnée envoyées par la méthode POST
    if ( $nomUtilisateur == "" && $mdpUtilisateur == "" )
        {	if ( empty ($_POST ["nomUtilisateur"]) == true)  $nomUtilisateur = "";  else   $nomUtilisateur = $_POST ["nom"];
            if ( empty ($_POST ["mdpUtilisateur"]) == true)  $mdpUtilisateur = "";  else   $mdpUtilisateur = $_POST ["mdp"];
        }
    
        if($nomUtilisateur == "" || $mdpUtilisateur =="") {
            $msg = "Erreur : Données incomplètes";
        }
        else {
            include_once ('../modele/DAO.class.php');
            $dao = new DAO();
            
            if ($dao->getUtilisateur($nomUtilisateur) == null) {
                $msg = "Erreur : nom d'utilisateur inexistant.";
            }
            
            else {
                $mdpDemande = chaine_aleatoire(5);
                
                $sujet = "Changement de votre mot de passe";
                $message = "Votre mot de passe a été modifié. \n\n";
                $message.= "Votre mot de passe est : " .$mdpDemande;
                $dao->modifierMdpUser($nomUtilisateur, $mdpDemande);
                $adrEmail = $dao->getEmailUtilisateur($nomUtilisateur);
                
                $ok = $out->envoyerMail($adrEmail, $sujet, $message, "delasalle.sio.crib@gmail.Com");
                
                if(! $ok) {
                    $msg = "Erreur : Echec lors de l'envoi du mail.";
                }
                
                else {
                    $msg = "Vous allez recevoir un mail avec votre nouveau mot de passe.";
                }
            }
        }