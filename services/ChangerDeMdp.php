<?php
    
    include_once ('../modele/Outils.class.php');
    include_once ('../modele/parametres.localhost.php');
    
    if ( empty ($_REQUEST['nom']) == true) $nom = ""; else $nom = $_REQUEST["nom"];
    if ( empty ($_REQUEST["mdp"]) == true) $mdp = ""; else $mdp = $_REQUEST["mdp"];
    if ( empty ($_REQUEST["txtNouveauMdp"]) == true) $nouveauMdp = ""; else $nouveauMdp = $_REQUEST["txtNouveauMdp"];
    if ( empty ($_REQUEST['txtConfirmation']) == true) $confirmationMdp = ""; else $confirmationMdp = $_REQUEST['txtConfirmation'];
    if ($lang != "json") $lang="xml";
    
    $dao = new DAO();
    
    if ($nouveauMdp == "" || $confirmationMdp == "" || $mdp = "" || $nom = "") {
        $message = "Erreur : Données incomplètes";
    }
    else
    {
        if ($dao->getNiveauUtilisateur($nom, $mdp) == "inconnu")
            $msg = "Erreur : authentification incorrecte.";
        
            else {
                if ($nouveauMdp != $confirmationMdp)
                    $msg = "Erreur : le nouveau mot de passe et sa confirmation sont différents";
                else 
                    $msg = "Enregistrement effectué; vous allez recevoir un mail de confirmation";
            }
    }
    
    unset($dao);
    
    if($lang == "xml")
        creerFluxSML ($message)