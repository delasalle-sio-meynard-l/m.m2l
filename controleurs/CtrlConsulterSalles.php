<?php

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauUtilisateur'] != 'utilisateur' && $_SESSION['niveauUtilisateur'] != 'administrateur') {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    
    // connexion du serveur web à la base MySQL
    include_once ('modele/DAO.class.php');
    $dao = new DAO();
    
    // récupération des salles à l'aide de la méthode getLesSalles de la classe DAO
    $i = $dao->getLesSalles();
    echo $i;
    
    $lesSalles = array();
    
    $uneSalle = new Salle(1, "Full", 35, "Balek");
    $uneSalle2 = new Salle(2, "Try", 20, "It");
    $lesSalles[] = $uneSalle;
    $lesSalles[] = $uneSalle2;
    
    // mémorisation du nombre de salles
    $NumSalle = sizeof($lesSalles);
    
    // préparation d'un message précédent la liste
    if ($NumSalle >1) {
        $message = $NumSalle . " salles disponibles en réservation : ";
    }
    else {
        $message = $NumSalle . " salle disponible en réservation : ";
    }
    
    // affichage de la vue
    //include_once ('vues/VueConsulterSalles.php');
    
    unset($dao);		// fermeture de la connexion à MySQL
}