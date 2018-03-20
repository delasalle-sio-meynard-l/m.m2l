<?php
// inclusion de la classe Outils
include_once ('../modele/Outils.class.php');
// inclusion des paramètres de l'application
include_once ('../modele/parametres.localhost.php');

// Récupération des données transmises
// la fonction $_GET récupère une donnée passée en paramètre $nom l'URL par la méthode GET
if ( empty ($_GET ["nom"]) == true)  $nom = "";  else   $nom = $_GET ["nom"];
if ( empty ($_GET ["mdp"]) == true) $mdp = "";  else   $mdp = $_GET ["mdp"];
if ( empty ($_GET ["numreservation"]) == true)  $numReservation = "";  else   $numReservation = $_GET ["numreservation"];

// si l'URL ne contient pas les données, on regarde si elles ont été envoyées par la méthode POST
// la fonction $_POST récupère une donnée envoyées par la méthode POST
if ( $nom == "" && $mdp == "" && $numReservation == "")
{
    if ( empty ($_POST ["nom"]) == true)  $nom = "";  else   $nom = $_POST ["nom"];
    if ( empty ($_POST ["mdp"]) == true) $mdp = "";  else   $mdp = $_POST ["mdp"];
    if ( empty ($_POST ["numreservation"]) == true)  $numReservation = "";  else   $numReservation = $_POST ["numreservation"];
}

if ( empty ($_REQUEST["lang"]) == true) $lang = "";  else $lang = strtolower($_REQUEST["lang"]);

if ($lang != "json") $lang = "xml";

// Contrôle de la présence des paramètres
if ( $nom == "" || $mdp == "" || $numReservation == "")
{	$msg = "Erreur : données incomplètes.";
}
else
{
    include_once ('../modele/DAO.class.php');
    $dao = new DAO();
    
    if ( $dao->getNiveauUtilisateur($nom, $mdp) == "inconnu" ) {
        $msg = "Erreur : authentification incorrecte.";
    }
    else
    {
        if ($dao->existeReservation($numReservation) == false)
        {
            $msg = "Erreur : numéro de réservation inexistant.";
        }
        else
        {
            if ($dao->estLeCreateur($nom, $numReservation) == false)
            {
                $msg = "Erreur : vous n'êtes pas l'auteur de cette réservation.";
            }
            else
            {
                $laReservation = $dao->getReservation($numReservation);
                
                if($laReservation->getStatus() == 0)
                {
                    $msg = "Erreur : cette réservation est déjà confirmée.";
                }
                else
                {
                    if($laReservation->getEnd_time() < time())
                    {
                        $msg = "Erreur : cette réservation est déjà passée.";
                    }
                    else
                    {
                        $adresseDestinataire = $dao->getEmailUtilisateur($nom);
                        
                        $dao->confirmerReservation($numReservation);
                        
                        
                        $sujet = "MRBS / Confirmation de réservation ";
                        $message = "Vous avez confirmé la réservation n°".$numReservation.".";
                        $ok = Outils::envoyerMail($adresseDestinataire, $sujet, $message, "delasalle.sio.crib@gmail.Com");
                        
                        if ($ok)
                        {
                            $msg = "Enregistrement effectué ; vous allez recevoir un mail de confirmation.";
                        }
                        else
                        {
                            $msg = "Enregistrement effectué ; l&#39;envoi du mail de confirmation a rencontré un problème.";
                        }
                    }
                }
            }
        }
    }
}

if ($lang == "xml")
    creerFluxXML ($msg);
    else
        creerFluxJSON ($msg);
        
        // fin du programme (pour ne pas enchainer sur la fonction qui suit)
        exit;
        
        function creerFluxXML($msg)
        {	// crée une instance de DOMdocument (DOM : Document Object Model)
            $doc = new DOMDocument();
            
            // specifie la version et le type d'encodage
            $doc->version = '1.0';
            $doc->encoding = 'UTF-8';
            
            // crée un commentaire et l'encode en ISO
            $elt_commentaire = $doc->createComment('Service web SupprimerUtilisateur - BTS SIO - Lycée De La Salle - Rennes');
            // place ce commentaire à la racine du document XML
            $doc->appendChild($elt_commentaire);
            
            // crée l'élément 'data' à la racine du document XML
            $elt_data = $doc->createElement('data');
            $doc->appendChild($elt_data);
            
            // place l'élément 'reponse' dans l'élément 'data'
            $elt_reponse = $doc->createElement('reponse', $msg);
            $elt_data->appendChild($elt_reponse);
            
            // place l'élément 'donnees' dans l'élément 'data'
            $elt_donnees = $doc->createElement('donnees');
            $elt_data->appendChild($elt_donnees);
            
            
            
            // Mise en forme finale
            $doc->formatOutput = true;
            
            // renvoie le contenu XML
            echo $doc->saveXML();
            return;
        }
        
        // création du flux JSON en sortie
        function creerFluxJSON($msg)
        {
            
            
            // construction de l'élément "data"
            $elt_data = ["reponse" => $msg, "donnees"];
            
            // construction de la racine
            $elt_racine = ["data" => $elt_data];
            
            // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
            echo json_encode($elt_racine, JSON_PRETTY_PRINT);
            return;
        }
        
        
        