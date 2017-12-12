<?php
// inclusion de la classe Outils
include_once ('../modele/Outils.class.php');
// inclusion des paramètres de l'application
include_once ('../modele/parametres.localhost.php');

// Récupération des données transmises
// la fonction $_GET récupère une donnée passée en paramètre dans l'URL par la méthode GET
if ( empty ($_GET ["nom"]) == true)  $nom = "";  else   $nom = $_GET ["nom"];
if ( empty ($_GET ["mdp"]) == true)  $mdp = "";  else   $mdp = $_GET ["mdp"];

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
                $message = $NumSalle . " salles disponibles en réservation : ";
            }
            else {
                $message = $NumSalle . " salle disponible en réservation : ";
            }
        // ferme la connexion à MySQL
        unset($dao);
}
// création du flux XML en sortie
creerFluxXML ($msg, $lesSalles);

// fin du programme (pour ne pas enchainer sur la fonction qui suit)
exit;





// création du flux XML en sortie
function creerFluxXML($msg, $lesReservations)
{	// crée une instance de DOMdocument (DOM : Document Object Model)
    $doc = new DOMDocument();
    
    // specifie la version et le type d'encodage
    $doc->version = '1.0';
    $doc->encoding = 'ISO-8859-1';
    
    // crée un commentaire et l'encode en ISO
    $elt_commentaire = $doc->createComment('Service web ConsulterReservations - BTS SIO - Lycée De La Salle - Rennes');
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
    if (sizeof($lesReservations) > 0) {
        foreach ($lesReservations as $uneReservation)
        {
            // crée un élément vide 'reservation'
            $elt_reservation = $doc->createElement('reservation');
            // place l'élément 'reservation' dans l'élément 'donnees'
            $elt_donnees->appendChild($elt_reservation);
            
            // crée les éléments enfants de l'élément 'reservation'
            $elt_id         = $doc->createElement('id', $uneReservation->getId());
            $elt_reservation->appendChild($elt_id);
            $elt_timestamp  = $doc->createElement('timestamp', $uneReservation->getTimestamp());
            $elt_reservation->appendChild($elt_timestamp);
            $elt_start_time = $doc->createElement('start_time', date('Y-m-d H:i:s', $uneReservation->getStart_time()));
            $elt_reservation->appendChild($elt_start_time);
            $elt_end_time   = $doc->createElement('end_time', date('Y-m-d H:i:s', $uneReservation->getEnd_time()));
            $elt_reservation->appendChild($elt_end_time);
            $elt_room_name  = $doc->createElement('room_name', $uneReservation->getRoom_name());
            $elt_reservation->appendChild($elt_room_name);
            $elt_status     = $doc->createElement('status', $uneReservation->getStatus());
            $elt_reservation->appendChild($elt_status);
            
            // le digicode n'est renseigné que pour les réservations confirmées
            if ( $uneReservation->getStatus() == "0")		// réservation confirmée
                $elt_digicode = $doc->createElement('digicode', utf8_encode($uneReservation->getDigicode()));
                else										// réservation provisoire
                    $elt_digicode = $doc->createElement('digicode', "");
                    $elt_reservation->appendChild($elt_digicode);
        }
    }
    
    // Mise en forme finale
    $doc->formatOutput = true;
    
    // renvoie le contenu XML
    echo $doc->saveXML();
    return;
}
