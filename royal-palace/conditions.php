<?php
	require_once('./includes/init_functions.php');
?>
<html>
	<head>
		   <meta name="viewport" content="width=device-width" />
		<title><?php echo $lang_titre_conditions.' :: '.$lang_titre_main; ?></title>
		
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
		<meta name="Language" content="fr" />		
	<link rel="stylesheet" type="text/css" href="styles/base.css" media="all" />
		<link rel="stylesheet" type="text/css" href="styles/style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="styles/calendrier.css" media="screen" />
		<!-- Phone -->
		<link href="phone.css" rel="stylesheet" type="text/css" media="only screen and (max-width:640px)">
		<!-- Tablet -->
		<link href="tablet.css" rel="stylesheet" type="text/css" media="only screen and (min-width:641px) and (max-width:960px)">
	</head> 
	<body> 

		<div id="global">
			<!-- On ins�re le header + le menu -->
			<?php require_once('./includes/include_entete_menu.php'); ?>
			
			<!-- Le contenu -->
			<div id="contenu">
				<!-- Titre de la page -->
				<h1><?php echo $lang_titre_conditions; ?></h1>
				
				<?php
					// Contenu de la page
					echo $lang_texte_conditions.get_value_of_option("maj_domicile").$lang_texte_conditions_1;
				?>
			</div>
			
			<!-- Le pied de page -->
			<?php require_once('./includes/include_pied_de_page.php'); ?>
		</div>
		
	</body> 
</html>