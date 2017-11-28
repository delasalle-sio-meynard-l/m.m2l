<?php
//STBW
if ( ! isset ($_POST ["btnConfirmer"]) == true) {
    // si la confirmation n'a pas été demandée, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
    $NomUtilisateur = "";
    $themeFooter = $themeNormal;
    $message = '';
    $typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
    include_once ('vues/VueSupprimerUtilisateur.php');;
}
else {
    
    // récupération des données postées
    if ( empty ($_POST ["txtNomUtilisateur"]) == true)  $NomUtilisateur = "";  else   $NomUtilisateur = $_POST ["txtNomUtilisateur"];
    
    if ($NomUtilisateur == '') {
        // si les données sont incomplètes, réaffichage de la vue avec un message explicatif
        $message = 'Données incomplètes ou incorrectes !';
        $typeMessage = 'avertissement';
        $themeFooter = $themeProbleme;
        include_once ('vues/VueSupprimerUtilisateur.php');
        echo 'Données incomplètes ou incorrectes !';
    }
    else {
        // connexion du serveur web à la base MySQL
        include_once ('modele/DAO.class.php');
        $dao = new DAO();
        
        
        //Cherche si utilisateur n'existe pas
        if($dao->getUtilisateur($NomUtilisateur)==null)
        {
            //L'utilisateur n'existe pas
            $message = "Nom d'utilisateur inexistant !";
            $typeMessage = 'avertissement';
            $themeFooter = $themeProbleme;
            include_once ('vues/VueSupprimerUtilisateur.php');
            //echo "La réservation n'existe pas !";
        }
        else
        {
            $toutEstBon = true;
            //L'utilisateur existe
            
            
            //Verifie si l'utilisateur a passé des réservations à venir
            if($dao->aPasseDesReservations($NomUtilisateur))
            {
                //Utilisateur a passé des réservations
                $message = "Cet utilisateur a passé des réservations à venir !";
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                $toutEstBon = false;
                include_once ('vues/VueSupprimerUtilisateur.php');
                //echo "La réservation est déjà confirmée !";
            }
            
            if ($toutEstBon == true)
            {
                //Tout est bon
                //On crée un objet Outils
                $out = new Outils();
                
                //On recupere l'adresse email avant la suppression
                    $adrEmail = $dao->getEmailUtilisateur($NomUtilisateur);
                
                //On supprime l'utilisateur
                $ok = $dao->supprimerUtilisateur($NomUtilisateur);
                if ($ok){
                    //On a pu supprimer l'utilisateur
                    
                    //On envoie un mail pour l'utilisateur
                    
                    $message = "Vous avez été supprimé de l'application M2L";
                    
                    $ok = $out->envoyerMail($adrEmail, "MRBS / Don't Reply ", $message, "delasalle.sio.crib@gmail.Com");
                    
                    if ($ok)
                    {
                        //L'envoi de mail a réussi
                        $message = "Suppresion effectuée. <br> Un mail va être envoyé à l'utilisateur";
                        $typeMessage = 'information';
                        $themeFooter = $themeNormal;
                        include_once ('vues/VueSupprimerUtilisateur.php');
                    }
                    else
                    {
                        //L'envoi de mail a echoué
                        $message = "Suppresion effectuée. <br> L'envoi du mail à l'utilisateur a rencontré un problème !";
                        $typeMessage = 'avertissement';
                        $themeFooter = $themeProbleme;
                        include_once ('vues/VueSupprimerUtilisateur.php');
                    }
                }
                else {
                    //On n'a pas pu supprimer l'utilisateur
                    $message = "Problème lors de la suppresion de l'utilisateur !";
                    $typeMessage = 'avertissement';
                    $themeFooter = $themeProbleme;
                    include_once ('vues/VueSupprimerUtilisateur.php');
                }
                
                
            }
            
            unset($out);
        }
        unset($dao);
    }
}

