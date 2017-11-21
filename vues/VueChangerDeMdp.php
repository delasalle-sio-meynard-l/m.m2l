<!DOCTYPE html>
<html>
	<head>
		<?php include_once('vues/head.php'); ?>
		
		<script>
			// version jQuery activée
			
			// associe une fonction à l'événement pageinit
			$(document).bind('pageinit', function() {
				
				<?php if ($typeMessage != '') { ?>
					// affiche la boîte de dialogue 'affichage_message'
					$.mobile.changePage('#affichage_message', {transition: "<?php echo $transition; ?>"});
				<?php } ?>
			} );

		</script>
	</head>
	
	<body>
		<div data-role="page">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4>M2L-GRR</h4>
				<a href="index.php?action=Menu" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			<div  data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Changer mon mot de passe</h4>
				
				<form name="form1" action="index.php?action=ChangerDeMdp" data-ajax="false" method="post" data-transition="<?php echo $transition; ?>">
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="txtNouveauMdp">Nouveau mot de passe :</label>
						<input type="<?php if($afficherMdp == 'off') echo 'password'; else echo 'text' ;?>" name="txtNouveauMdp" id="txtNouveauMdp" data-mini="true" placeholder="Mon nouveau mot de passe" required pattern="^.{8,}$" value = "<?php  echo $nouveauMdp; ?>">
					</div>
					
					<div data-role="fieldcontain">
						<label for="txtConfirmation">Confirmation nouveau mot de passe:</label>
						<input type="<?php if($afficherMdp =='off') echo 'password'; else echo 'text';?>" name="txtConfirmation" id="txtConfirmation" placeholder="Confirmation de mon nouveau mot de passe" required pattern = "^.{8,}$" value="<?php echo $confirmationMdp; ?>">
					</div>
					
					<div data-role="fieldcontain" data-type="horizontal" class="ui-hide-label">
						<label for="caseAfficherMdp">Afficher le mot de passe en clair</label>
						<input type="checkbox" name="caseAfficherMdp" id="caseAfficherMdp" data-mini="true" <?php if($afficherMdp == 'on') echo 'checked'; ?> >
					</div>
					
					<div data-role="fieldcontain">
						<input type="submit" name="btnChangerDeMdp" id="btnChangerDeMdp" value="Envoyer les données">
					</div>
				</form>
			</div>
			<div>
				<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal;?>">
					<h4>Suivi des réservations de salles<br>Maison des ligues de Lorraine (M2L)</h4>
				</div>
			</div>
		</div>
		
		<?php include_once ('vues/dialog_message.php'); ?>
	</body>
</html>