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
				<a href="index.php?action=Menu" data-transition="<?php echo $transition;?>">Retour menu</a>
			</div>
			<div data-role="content">
				<h4 style="text-align : center; margin-top: 0px; margin-bottom: 0px;">Demander un nouveau mot de passe</h4>
				
				<form name="form1" action="index.php?action=DemanderMdp" data-ajax="false" method="post" data-transition ="<?php echo $transition; ?>">
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="txtDemanderMdp">Pas de label trop pauvre</label>
						<input type="text" name="txtDemanderMdp" id="txtDemanderMdp" data-mini="true" placeholder = "Entrez votre nom" value ="<?php echo $nomUtilisateur; ?>">
					</div>
					<div data-role="fieldcontain" style="margin-top: 0px; margin-bottom: 0px;">
						<p style="margin-top: 0px; margin-bottom: 0px;">
						<input type="submit" name="btnDemanderMdp" id="btnDemanderMdp" data-ajax="false" data-mini="true" value="M'envoyer un nouveau mot de passe">
						</p>
					</div>
				</form>
			</div>
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal;?>">
				<h4>Suivi des réservations de salles<br>Maison des ligues de Lorraine (M2L)</h4>
			</div>
		</div>
		
		<?php include_once('vues/dialog_message.php'); ?>
	</body>
</html>
