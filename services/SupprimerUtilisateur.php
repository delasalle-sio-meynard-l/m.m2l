<?php
// inclusion de la classe Outils
include_once ('../modele/Outils.class.php');
// inclusion des paramètres de l'application
include_once ('../modele/parametres.localhost.php');

// Récupération des données transmises
// la fonction $_GET récupère une donnée passée en paramètre dans l'URL par la méthode GET
if ( empty ($_GET ["nomAdmin"]) == true)  $nomAdmin = "";  else   $nomAdmin = $_GET ["nomAdmin"];
if ( empty ($_GET ["mdpAdmin"]) == true) $mdpAdmin = "";  else   $mdpAdmin = $_GET ["mdpAdmin"];
if ( empty ($_GET ["name"]) == true)  $name = "";  else   $name = $_GET ["$name"];

// si l'URL ne contient pas les données, on regarde si elles ont été envoyées par la méthode POST
// la fonction $_POST récupère une donnée envoyées par la méthode POST
if ( $nomAdmin == "" && $mdpAdmin == "" && $name == "")
{	
    if ( empty ($_POST ["nomAdmin"]) == true)  $nomAdmin = "";  else   $nomAdmin = $_POST ["nomAdmin"];
    if ( empty ($_POST ["mdpAdmin"]) == true) $mdpAdmin = "";  else   $mdpAdmin = $_POST ["mdpAdmin"];
    if ( empty ($_POST ["name"]) == true)  $name = "";  else   $name = $_POST ["$name"];
}

if ( empty ($_REQUEST["lang"]) == true) $lang = "";  else $lang = strtolower($_REQUEST["lang"]);

if ($lang != "json") $lang = "xml";

// Contrôle de la présence des paramètres
if ( $nomAdmin == "" || $mdpAdmin == "" || $name == "")
{	$msg = "Erreur : données incomplètes.";
}
else
{
    include_once ('../modele/DAO.class.php');
    $dao = new DAO();
    
    if ( $dao->getNiveauUtilisateur($nomAdmin, $mdpAdmin) != "administrateur" ) {
        $msg = "Erreur : authentification incorrecte.";
    }
    else 
    {
        if ($dao->existeUtilisateur($name) == false)
        {
            $msg = "Erreur : nom d'utilisateur inexistant.";
        }
        else
        {
            if ($dao->aPasseDesReservations($name))
            {
                $msg = "Erreur : cet utilisateur a passé des réservations à venir.";
            }
            else 
            {
                $dao->supprimerUtilisateur($name);
                
                $adresseDestinataire = $dao->getEmailUtilisateur($NomUtilisateur);
                
                $sujet = "MRBS / Don't Reply ";
                $message = "Vous avez été supprimé de l'application M2L";
                $ok = Outils::envoyerMail($adresseDestinataire, $sujet, $message, "delasalle.sio.crib@gmail.Com");
                
                if ($ok)
                {
                    $msg = "Suppression effectuée ; un mail va être envoyé à l'utilisateur.";
                }
                else
                {
                    $msg = "Suppression effectuée ; l'envoi du mail à l'utilisateur a rencontré un problème.";
                }
            }
        }
    }
}

if ($lang == "xml")
    creerFluxXML ($msg, $lesSalles);
    else
        creerFluxJSON ($msg, $lesSalles);
        
// fin du programme (pour ne pas enchainer sur la fonction qui suit)
exit;
        
function creerFluxXML($msg, $lesSalles)
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



