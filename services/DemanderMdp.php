<?php

function chaine_aleatoire($nb_car, $chaine = 'azertyuiopqsdfghjklmwxcvbn123456789')
{
    $nb_lettres = strlen($chaine) - 1;
    $generation = '';
    for($i=0; $i < $nb_car; $i++)
    {
        $pos = mt_rand(0, $nb_lettres);
        $car = $chaine[$pos];
        $generation .= $car;
    }
    return $generation;
}
    // inclusion de la classe Outils
    include_once ('../modele/Outils.class.php');
    // inclusion des paramètres de l'application
    include_once ('../modele/parametres.localhost.php');

    // Récupération des données transmises
    // la fonction $_GET récupère une donnée passée en paramètre dans l'URL par la méthode GET
    if ( empty ($_GET ["nom"]) == true)  $nom = "";  else   $nom = $_GET ["nom"];
    
    // si l'URL ne contient pas les données, on regarde si elles ont été envoyées par la méthode POST
    // la fonction $_POST récupère une donnée envoyées par la méthode POST
    if ( $nom == "")
    {	if ( empty ($_POST ["nom"]) == true)  $nom = "";  else   $nom = $_POST ["nom"];
    }
    
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
        
        // création du flux XML en sortie
        creerFluxXML ($message);
        
        // fin du programme (pour ne pas enchainer sur la fonction qui suit)
        exit;
        
        
        // création du flux XML en sortie
        function creerFluxXML($message)
        {	// crée une instance de DOMdocument (DOM : Document Object Model)
            $doc = new DOMDocument();
            
            // specifie la version et le type d'encodage
            $doc->version = '1.0';
            $doc->encoding = 'ISO-8859-1';
            
            // crée un commentaire et l'encode en ISO
            $elt_commentaire = $doc->createComment('Service web DemanderMdp - BTS SIO - Lycée De La Salle - Rennes');
            // place ce commentaire à la racine du document XML
            $doc->appendChild($elt_commentaire);
            
            // crée l'élément 'data' à la racine du document XML
            $elt_data = $doc->createElement('data');
            $doc->appendChild($elt_data);
            
            // place l'élément 'reponse' juste après l'élément 'data'
            $elt_reponse = $doc->createElement('reponse', $message);
            $elt_data->appendChild($elt_reponse);
            
            // Mise en forme finale
            $doc->formatOutput = true;
            
            // renvoie le contenu XML
            echo $doc->saveXML();
            return;
        }
        ?>
        
        
        