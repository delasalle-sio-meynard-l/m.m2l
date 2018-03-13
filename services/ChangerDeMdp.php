<?php
    
    include_once ('../modele/Outils.class.php');
    include_once ('../modele/parametres.localhost.php');
    
    if ( empty ($_REQUEST["txtNouveauMdp"]) == true) $nouveauMdp = ""; else $nouveauMdp = $_REQUEST["txtNouveauMdp"];
    if ( empty ($_REQUEST['txtConfirmation']) == true) $confirmationMdp = ""; else $confirmationMdp = $_REQUEST['txtConfirmation'];
    if ($lang != "json") $lang="xml";
    
    if ($nouveauMdp == "" && $confirmationMdp == "") {
        $message = "Erreur : Données incomplètes";
    }