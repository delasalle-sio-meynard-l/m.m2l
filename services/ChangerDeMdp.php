<?php
    
    include_once ('../modele/Outils.class.php');
    include_once ('../modele/parametres.localhost.php');
    
    if ( empty ($_REQUEST['nom']) == true) $nom = ""; else $nom = $_REQUEST["nom"];
    if ( empty ($_REQUEST["ancienMdp"]) == true) $ancienMdp = ""; else $ancienMdp = $_REQUEST["ancienMdp"];
    if ( empty ($_REQUEST["nouveauMdp"]) == true) $nouveauMdp = ""; else $nouveauMdp = $_REQUEST["nouveauMdp"];
    if ( empty ($_REQUEST['confirmationMdp']) == true) $confirmationMdp = ""; else $confirmationMdp = $_REQUEST['confirmationMdp'];
    if ( empty ($_REQUEST['lang']) == true) $lang = ""; else $lang = $_REQUEST["lang"];
    
    if ($lang != "json") $lang="xml";
    
    include_once('../modele/DAO.class.php');
    
    $dao = new DAO();
    
    if ($nouveauMdp == "" || $confirmationMdp == "" || $ancienMdp = "" || $nom = "") {
        $message = "Erreur : Données incomplètes";
    }
    else
    {
        if ($dao->getNiveauUtilisateur($nom, $ancienMdp) == "inconnu")
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
        creerFluxXML ($message);
    else 
        creerFluxJSON($message);
    exit;
    
    function creerFluxXML($message) {
        
        $doc = new DOMDocument();
        
        $doc->version = '1.0';
        $doc->encoding = 'ISO-8859-1';
        
        $elt_commentaire = $doc->createComment('Service web ChangerDeMdp - BTS SIO - Lycée De La Salle - Rennes');
        $doc->appendChild($elt_commentaire);
        
        $elt_data = $doc->createElement('data');
        $doc->appendChild($elt_data);
        
        $elt_reponse = $doc->createElement('reponse', $message);
        $elt_data->appendChild($elt_reponse);
        
        $doc->formatOutput = true;
        
        echo $doc->saveXML();
        return;
    }