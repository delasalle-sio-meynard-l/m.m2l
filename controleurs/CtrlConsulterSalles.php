<?php

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauUtilisateur'] != 'utilisateur' && $_SESSION['niveauUtilisateur'] != 'administrateur') {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    echo "Test";
    
    // connexion du serveur web à la base MySQL
    include_once ('modele/DAO.class.php');
    $dao = new DAO();
    
    // récupération des salles à l'aide de la méthode getLesSalles de la classe DAO
    $lesSalles = $dao->getLesSalles();
    
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
    include_once ('vues/VueConsulterSalles.php');
    
    unset($dao);		// fermeture de la connexion à MySQL
}