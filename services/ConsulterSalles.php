<?php
// inclusion de la classe Outils
include_once ('../modele/Outils.class.php');
// inclusion des paramètres de l'application
include_once ('../modele/parametres.localhost.php');

// Récupération des données transmises
// la fonction $_GET récupère une donnée passée en paramètre dans l'URL par la méthode GET
if ( empty ($_GET ["nom"]) == true)  $nom = "";  else   $nom = $_GET ["nom"];
if ( empty ($_GET ["mdp"]) == true)  $mdp = "";  else   $mdp = $_GET ["mdp"];
if ( empty ($_REQUEST["lang"]) == true) $lang = "";  else $lang = strtolower($_REQUEST["lang"]);

if ($lang != "json") $lang = "xml";

// si l'URL ne contient pas les données, on regarde si elles ont été envoyées par la méthode POST
// la fonction $_POST récupère une donnée envoyées par la méthode POST
if ( $nom == "" && $mdp == "" )
{	if ( empty ($_POST ["nom"]) == true)  $nom = "";  else   $nom = $_POST ["nom"];
if ( empty ($_POST ["mdp"]) == true)  $mdp = "";  else   $mdp = $_POST ["mdp"];
}

$NumSalle = 0;
$lesSalles = array();

// Contrôle de la présence des paramètres
if ( $nom == "" || $mdp == "" )
{	$msg = "Erreur : données incomplètes.";
}
else
{	// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
    include_once ('../modele/DAO.class.php');
    $dao = new DAO();
    
    if ( $dao->getNiveauUtilisateur($nom, $mdp) == "inconnu" )
        $msg = "Erreur : authentification incorrecte.";
    else
    {
            
        // récupération des réservations à venir créées par l'utilisateur
        $lesSalles = $dao->getLesSalles();
        $NumSalle = sizeof($lesSalles);
            
        // préparation d'un message précédent la liste
        if ($NumSalle >1) {
            $msg = $NumSalle . " salles disponibles en réservation : ";
        }
        else {
            $msg = $NumSalle . " salle disponible en réservation : ";
        }
    }
    // ferme la connexion à MySQL
    unset($dao);
}
// création du flux en sortie
if ($lang == "xml")
    creerFluxXML ($msg, $lesSalles);
else
    creerFluxJSON ($msg, $lesSalles);
        
// fin du programme (pour ne pas enchainer sur la fonction qui suit)
exit;





// création du flux XML en sortie
function creerFluxXML($msg, $lesSalles)
{	// crée une instance de DOMdocument (DOM : Document Object Model)
    $doc = new DOMDocument();
    
    // specifie la version et le type d'encodage
    $doc->version = '1.0';
    $doc->encoding = 'UTF-8';
    
    // crée un commentaire et l'encode en ISO
    $elt_commentaire = $doc->createComment('Service web ConsulterSalles - BTS SIO - Lycée De La Salle - Rennes');
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
    
    // traitement des réservations
    if (sizeof($lesSalles) > 0) {
        foreach ($lesSalles as $uneSalle)
        {
            // crée un élément vide 'reservation'
            $elt_salle = $doc->createElement('salle');
            // place l'élément 'reservation' dans l'élément 'donnees'
            $elt_donnees->appendChild($elt_salle);
            
            // crée les éléments enfants de l'élément 'reservation'
            $elt_id                = $doc->createElement('id', $uneSalle->getId());
            $elt_salle->appendChild($elt_id);
            $elt_roomName          = $doc->createElement('room_name', $uneSalle->getRoom_name());
            $elt_salle->appendChild($elt_roomName);
            $elt_capacity          = $doc->createElement('capacity', $uneSalle->getCapacity());
            $elt_salle->appendChild($elt_capacity);
            $elt_area_name         = $doc->createElement('area_name', $uneSalle->getAreaName());
            $elt_salle->appendChild($elt_area_name);
            $elt_area_admin_email  = $doc->createElement('area_admin_email', "chemin.lorette@lorraine-sport.net");
            $elt_salle->appendChild($elt_area_admin_email);

        }
    }
    
    // Mise en forme finale
    $doc->formatOutput = true;
    
    // renvoie le contenu XML
    echo $doc->saveXML();
    return;
}

// création du flux JSON en sortie
function creerFluxJSON($msg, $lesSalles)
{
    /* Exemple de code JSON
     {
     "data":{
     "reponse":"Vous avez effectu\u00e9 2 r\u00e9servation(s).",
     "donnees":{
     "reservation":[
     {
     "id":"1",
     "room_name":"Multimédia",
     "capacity": "25",
     "area_name": "Informatique - multimédia"
     },
     {
     "id":"1",
     "room_name":"Multimédia",
     "capacity": "25",
     "area_name": "Informatique - multimédia"
     }
     ]
     }
     }
     }
     */
    
    // construction d'un tableau contenant les réservations
    $lesLignesDuTableau = array();
    if (sizeof($lesSalles) > 0) {
        foreach ($lesSalles as $uneSalle)
        {	// crée une ligne dans le tableau
            $uneLigne = array();
            $uneLigne["id"] = $uneSalle->getId();
            $uneLigne["room_name"] = $uneSalle->getRoom_name();
            $uneLigne["capacity"] = $uneSalle->getCapacity();
            $uneLigne["area_name"] = $uneSalle->getAreaName();
                    
                    $lesLignesDuTableau[] = $uneLigne;
        }
    }
    // construction de l'élément "reservation"
    $elt_salle = ["salle" => $lesLignesDuTableau];
    
    // construction de l'élément "data"
    $elt_data = ["reponse" => $msg, "donnees" => $elt_salle];
    
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    echo json_encode($elt_racine, JSON_PRETTY_PRINT);
    return;
}
