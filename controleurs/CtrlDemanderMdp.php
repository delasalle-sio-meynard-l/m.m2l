<?php
if(!isset($_POST["btnDemanderMdp"]) == true) {
    $nomUser = "";
    $themeFooter = $themeNormal;
    $message = "";
    $typeMessage = "";
    include_once('vues/VueDemanderMdp.php');
}
else {
    if (empty($_POST["txtDemanderMdp"]) == true) $nomUser = ""; else $nomUser = $_POST["txtDemanderMdp"];
    if($nomUser == '') {
        $message = 'Données incomplètes ou incorrectes !';
        $typeMessage = 'avertissement';
        $themeFooter = $themeProbleme;
    }
    
    else {
        include_once('modele/DAO.class.php');
        $dao = new DAO();
        
        if($dao->getUtilisateur($nomUser) == null){
            $message = "Le nom d'utilisateur est inexistant !";
            $typeMessage = 'avertissement';
            $themeFooter = $themeProbleme;
            include_once('vues/VueDemanderMdp.php');
        }
        
    }
}