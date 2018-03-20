<?php

if ( empty ($_REQUEST["nom"]) == true)  $nom = "";  else   $nom = $_REQUEST["nom"];
if ( empty ($_REQUEST["ancienMdp"]) == true)  $ancienMdp = "";  else   $ancienMdp = $_REQUEST["ancienMdp"];
if ( empty ($_REQUEST["nouveauMdp"]) == true)  $nouveauMdp = "";  else   $nouveauMdp = $_REQUEST["nouveauMdp"];
if ( empty ($_REQUEST["confirmationMdp"]) == true) $confirmationMdp = "";  else   $confirmationMdp = $_REQUEST["confirmationMdp"];
if ( empty ($_REQUEST["lang"]) == true) $lang = "";  else $lang = strtolower($_REQUEST["lang"]);

if ($lang != "json") $lang = "xml";

if ( $nom == "" || $ancienMdp == "" || $nouveauMdp == "" || $confirmationMdp == "")
{	$msg = "Erreur : données incomplètes.";
}
else
{	
	include_once ('../modele/DAO.class.php');
	$dao = new DAO();
	
	if ( $dao->getNiveauUtilisateur($nom, $ancienMdp) == "inconnu" ) {
	    $msg = "Erreur : authentification incorrecte.";
	}
	else
	 {	
	    if ( $nouveauMdp != $confirmationMdp) {
	        $msg = "Erreur : le nouveau mot de passe et sa confirmation sont différents.";
	    }
	    else {
	        
	        $change = $dao->modifierMdpUser($nom, $nouveauMdp);
	        
	        
	        $user = $dao->getUtilisateur($nom);
	        $mail = $user->getEmail();
	        
	        
	        $sujet = "Changement de mot de passe dans le système de réservation de M2L";
	        $contenuMail = "Votre nouveau mot de passe a bien été modifié par : ".$nouveauMdp."\n\n";
	        
	        $ok = Outils::envoyerMail($mail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
	        if ( ! $ok ) {
	            
	            $msg = "Enregistrement effectué ; l'envoi du mail de confirmation a rencontré un problème.";
	        }
	        else {
	            
	            $msg = "Enregistrement effectué ; vous allez recevoir un mail de confirmation.";
	        }   
	        
	    }
	 }
	    
	
	unset($dao);
}

if ($lang == "xml")
    creerFluxXML ($msg);
    else
        creerFluxJSON ($msg);
        

exit;
 

function creerFluxXML($msg)
{	
    $doc = new DOMDocument();
    
    
    $doc->version = '1.0';
    
    $doc->encoding = 'UTF-8';
    
    
    $elt_commentaire = $doc->createComment('Service web ChangerDeMdp - BTS SIO - Lycée De La Salle - Rennes');
    
    $doc->appendChild($elt_commentaire);
    
    $elt_data = $doc->createElement('data');
    $doc->appendChild($elt_data);
    
    $elt_reponse = $doc->createElement('reponse', $msg);
    $elt_data->appendChild($elt_reponse);
    
    
    $doc->formatOutput = true;
    
    
    echo $doc->saveXML();
    return;
}
function creerFluxJSON($msg)
{
    
    $elt_data = ["reponse" => $msg];
    
    $elt_racine = ["data" => $elt_data];
    
    
    echo json_encode($elt_racine, JSON_PRETTY_PRINT);
    return;
    
}
?>