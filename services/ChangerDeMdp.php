<?php
    

    include_once ('../modele/Outils.class.php');
    include_once ('../modele/parametres.localhost.php');
    
    if ( empty ($_GET["txtNouveauMdp"]) == true) $nouveauMdp = ""; else $nouveauMdp = $_GET["txtNouveauMdp"];
    if ( empty ($_GET["txtConfirmation"]) == true) $confirmationMdp = ""; else $confirmationMdp = $_GET["txtConfirmation"];
    
    if ($nouveauMdp == "" && $confirmationMdp == "")
    {
        if (empty($_POST["txtNouveauMdp"]) == true) $nouveauMdp = ""; else $nouveauMdp = $_POST["txtNouveauMdp"];
        if (empty($_POST["txtConfirmation"]) == true) $confirmationMdp = ""; else $confirmationMdp = $_POST["txtConfirmation"];
    }
    
    if ($nouveauMdp == "" && $confirmationMdp == "") {
        $message = "Erreur : Données incomplètes";
    }