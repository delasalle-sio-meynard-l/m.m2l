<?php
     
    
    include_once ('../modele/Outils.class.php');
    
    include_once ('../modele/parametres.localhost.php');

    
    if ( empty ($_REQUEST["nom"]) == true)  $nom = "";  else   $nom = $_REQUEST["nom"];
    if ( empty ($_REQUEST["lang"]) == true) $lang = "";  else $lang = strtolower($_REQUEST["lang"]);
    
    if ($lang != "json") $lang = "xml";
    
    if($nom == "") {
        $message = "Erreur : Données incomplètes";
    }
    else {
        include_once ('../modele/DAO.class.php');
        $dao = new DAO();
        $unUtilisateur = $dao->getUtilisateur($nom);
        
        if ($unUtilisateur == null) {
            $message = "Erreur : nom d'utilisateur inexistant.";
        }
        
        else {
            $adrMail = $unUtilisateur->getEmail();
            $password = Outils::creerMdp();
            $dao->modifierMdpUser($nom, $password);
            $level = $dao->getNiveauUtilisateur($nom, $password);
            
            $sujet = "Votre nouveau mot de passe";
            $contenuMail = "Voici les nouvelles données vous concernant :\n\n";
            $contenuMail .="Votre mot de passe : " . $password . " (nous vous conseillons de le changer)\n";
            $contenuMail .="Votre niveau d'accès : " . $level . "\n";
            
            $ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
            if(! $ok) {
                $message = "Erreur : Echec lors de l'envoi du mail.";
            }
            
            else {
                $message = "Vous allez recevoir un mail avec votre nouveau mot de passe.";
            }
        }
    }
        
    
    unset($dao);
    
    if ($lang == "xml")
        creerFluxXML ($message);
    else
        creerFluxJSON ($message);
        
        
        exit;
        
        
        function creerFluxXML($message)
        {	
            $doc = new DOMDocument();
            
            
            $doc->version = '1.0';
            $doc->encoding = 'ISO-8859-1';
            
            
            $elt_commentaire = $doc->createComment('Service web DemanderMdp - BTS SIO - Lycée De La Salle - Rennes');
            
            $doc->appendChild($elt_commentaire);
            
            
            $elt_data = $doc->createElement('data');
            $doc->appendChild($elt_data);
            
            
            $elt_reponse = $doc->createElement('reponse', $message);
            $elt_data->appendChild($elt_reponse);
            
            
            $doc->formatOutput = true;
            
            
            echo $doc->saveXML();
            return;
        }
        
        function creerFluxJSON($message)
        {
            
                
        }
        ?>
        
        
        