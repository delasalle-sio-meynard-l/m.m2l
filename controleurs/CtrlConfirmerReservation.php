<?php
if ( ! isset ($_POST ["btnConfirmer"]) == true) {
    // si la confirmation n'a pas été demandée, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
    $NumReservation = "";
    $themeFooter = $themeNormal;
    $message = '';
    $typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
    include_once ('vues/VueConfirmerReservation.php');
}
else {
    // récupération des données postées
    if ( empty ($_POST ["txtNumReservation"]) == true)  $NumReservation = "";  else   $NumReservation = $_POST ["txtNumReservation"];
    
    if ($NumReservation == '') {
        // si les données sont incomplètes, réaffichage de la vue avec un message explicatif
        $message = 'Données incomplètes ou incorrectes !';
        $typeMessage = 'avertissement';
        $themeFooter = $themeProbleme;
        //include_once ('vues/VueConfirmerReservation.php');
    }
    else {
        // connexion du serveur web à la base MySQL
        include_once ('modele/DAO.class.php');
        $dao = new DAO();
        
        //Cherche la reservation $NumReservation
        if($dao->getReservation($NumReservation)==null)
        {
            //La reservation n'existe pas
            $message = "La réservation n'existe pas !";
            $typeMessage = 'avertissement';
            $themeFooter = $themeProbleme;
            //include_once ('vues/VueConfirmerReservation.php');
        }
        else 
        {
            //La reservation existe
            $laReservation= $dao->getReservation($NumReservation);
            //Verifie si utilisateur est l'auteur de cette reservation  
            if($dao->estLeCreateur($nom, $NumReservation) == false)
            {
                //Ce n'est pas l'auteur de  cette reservation
                $message = "Vous n'êtes pas l'auteur de cette réservation !";
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                //include_once ('vues/VueConfirmerReservation.php');
            }
            
            //Verifie si reservation deja confirmée
            if($laReservation->getStatus() == 0)
            {
                //Dejà confirmée
                $message = "La réservation est déjà confirmée !";
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                include_once ('vues/VueConfirmerReservation.php');
            }
            
            if($laReservation->getEnd_time() < time())
            {
                //Reservation passée
                $message = "La réservation est passée !";
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                //include_once ('vues/VueConfirmerReservation.php');
            }
            
            //Tout est bon
            //include_once ('vues/VueConfirmerReservation.php');
        }
        
    }
}

