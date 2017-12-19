<?php
    

    include_once ('../modele/Outils.class.php');
    include_once ('../modele/parametres.localhost.php');
    
    if ( empty ($_GET["nouveauMdp"]) == true) $nouveauMdp = ""; else $nouveauMdp = $_GET["nom"];
    if ( empty ($_GET["confirmationMdp"]) == true) $confirmationMdp = ""; else $confirmationMdp = $_GET["confirmationMdp"];
    
    if ($nouveauMdp == "" && $confirmationMdp == "")
    {
        if (empty($_POST["nouveauMdp"]) == true) $nouveauMdp = ""; else $nouveauMdp = $_POST["nouveauMdp"];
        if (empty($_POST["confirmationMdp"]) == true) $confirmationMdp = ""; else $confirmationMdp = $_POST["confirmationMdp"];
    }
    
    if ($nouveauMdp == "" && $confirmationMdp == "") {
        $message = "Erreur : Données incomplètes";
    }