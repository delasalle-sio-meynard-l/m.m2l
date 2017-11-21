<?php
if ( ! isset ($_POST ["btnConfirmer"]) == true) {
    $NomUser = "";
    $themeFooter = $themeNormal;
    $message = '';
    $typeMessage = '';
    include_once('vues/vueSupprimerUtilisateur.php');
}
else {
    if ($NomUser == '') {
        $message = "Données incomplètes ou incorrectes !";
        $typeMessage = 'avertissement';
        $themeFooter = $themeProbleme;
        //include_once ('vues/VueSupprimerUtilisateur.php');
    }
    else {
        include_once ('modele/DAO.class.php');
        $dao = new DAO();
        
        if($dao->getUtilisateur($nomUser)==null) {
            $message = "Nom d'utilisateur inexistant !";
            $typeMessage = 'avertissement';
            $themeFooter = $themeProbleme;
            //include_once ('vues/VueSupprimerUtilisateur.php');
        }
    }
}
?>