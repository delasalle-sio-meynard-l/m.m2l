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

			function afficher_information(msg) {
				$('#texte_message_information').empty();
				$('#texte_message_information').append(msg);
				$.mobile.changePage('#affichage_message_information', {transition: "flip"});
				//alert(msg);
			}

			function afficher_avertissement(msg) {
				$('#texte_message_avertissement').empty();
				$('#texte_message_avertissement').append(msg);
				$.mobile.changePage('#affichage_message_avertissement', {transition: "flip"});
				//alert(msg);
			}

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
					<div data-role="fieldcontain">
						<input type="text" name="txtDemanderMdp" id="txtDemanderMdp" data-mini="true" placeholder = "Entrez votre nom" value ="<?php echo $nom; ?>">
					</div>
					<div data-role="fieldcontain">
						<input type="submit" name="btnDemanderMdp" id="btnDemanderMdp" data-ajax="false" data-mini="true" value="M'envoyer un nouveau mot de passe">
					</div>
				</form>
			</div>
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal;?>">
				<h4>Suivi des réservations de salles<br>Maison des ligues de Lorraine (M2L)</h4>
			</div>
		</div>
		
		<?php include once('vues/dialog_message.php'); ?>
	</body>
</html>
