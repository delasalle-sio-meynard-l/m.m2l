<?php
include_once ('../modele/Outils.class.php');

include_once ('../modele/parametres.localhost.php');


if ( empty ($_GET ["nom"]) == true)  $nom = "";  else   $nom = $_GET ["nom"];
if ( empty ($_GET ["mdp"]) == true)  $mdp = "";  else   $mdp = $_GET ["mdp"];
if ( empty ($_GET ["numreservation"]) == true)  $id = "";  else   $id = $_GET ["numreservation"];

if ( $nom == "" && $mdp == "" )
{	
    if ( empty ($_POST ["nom"]) == true)  $nom = "";  else   $nom = $_POST ["nom"];
    if ( empty ($_POST ["mdp"]) == true)  $mdp = "";  else   $mdp = $_POST ["mdp"];
    if ( empty ($_POST ["numreservation"]) == true)  $id = "";  else   $id = $_POST ["numreservation"];
}

if ( $nom == "" || $mdp == "")
{	
    $msg = "Erreur : données incomplètes.";
}
else
{	
    include_once ('../modele/DAO.class.php');
    $dao = new DAO();
    
    
    if ( $dao->getNiveauUtilisateur($nom, $mdp) == "inconnu" )
        $msg = "Erreur : authentification incorrecte.";
        else
        {
            
            if ($id == "") {
                $msg = "Erreur : numéro de réservation inexistant.";
            }
            else {
                
                $ok = $dao->estLeCreateur($nom, $id);
                
                if ( $ok == false ) {
                    $msg = "Erreur : vous n'êtes pas l'auteur de cette réservation.";
                }
                else {
                    
                    $res = $dao->getReservation($id);
                    
                    $endTime = $res->getEnd_time();
                    
                    $diff = ($endTime - time() );
                    
                    if ($diff < 0){
                        
                        $msg = "Erreur : cette réservation est déjà passée.";
                        
                    }
                    else {
                        
                        
                        $cancel = $dao->annulerReservation($id);
                        
                        
                        $user = $dao->getUtilisateur($nom);
                        $mail = $user->getEmail();
                        
                        $sujet = "Annulation de réservation n° ".$id;
                        $adresseEmetteur = "delasalle.sio.eleves@gmail.com";
                        $message = "La réservation n° ".$id." a bien été annulée ! "."Bonne journée ".$nom." ! ";
                        $ok = Outils::envoyerMail($mail, $sujet, $message, $adresseEmetteur);
                        
                        if ( $ok ) {
                            $msg = "Enregistrement effectué : vous allez recevoir un mail de confirmation.";
                        }
                        else {
                            $msg = "Enregistrement effectué, cependant l'envoi de mail a échoué.";
                        }
                        
                        
                    }
                    
                }
            }
        }
        
        unset($dao);
}

creerFluxXML ($msg);

exit;


function creerFluxXML($msg)
{	
    $doc = new DOMDocument();
    
    
    $doc->version = '1.0';
    
    $doc->encoding = 'UTF-8';
    
    
    $elt_commentaire = $doc->createComment('Service web ConfirmerReservation - BTS SIO - Lycée De La Salle - Rennes');
    
    $doc->appendChild($elt_commentaire);
    
    
    $elt_data = $doc->createElement('data');
    $doc->appendChild($elt_data);
    
    
    $elt_reponse = $doc->createElement('reponse', $msg);
    $elt_data->appendChild($elt_reponse);
    
    
    $doc->formatOutput = true;
    
    
    echo $doc->saveXML();
    return;
}
?>