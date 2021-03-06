<?php
	require_once('./includes/init_functions.php');
	
	$_SESSION["service"] = "royal";
	
	if (!isset($_POST['select_lieu_aller'])){ 
		header("Location: index.php");
		exit;
	}
	
	// Si des valeurs de session existes, on les utilisera pour remplir le formulaire
	
	$nom_client = (isset($_SESSION["nom_client"])) ? $_SESSION["nom_client"] : "";
	$prenom_client = (isset($_SESSION["prenom_client"])) ? $_SESSION["prenom_client"] : "";
	$ville_client = (isset($_SESSION["ville_client"])) ? $_SESSION["ville_client"] : "";
	$tel_fixe_client = (isset($_SESSION["tel_fixe_client"])) ? $_SESSION["tel_fixe_client"] : "";
	$tel_port_client = (isset($_SESSION["tel_port_client"])) ? $_SESSION["tel_port_client"] : "";
	$mail_client = (isset($_SESSION["mail_client"])) ? $_SESSION["mail_client"] : "";
	
	// Récupération des valeurs des trajets
	$_SESSION["trajet"] = array();
	
	/* 
		Calcul du prix
	*/
	
	// Majoration si le client ne réserve que pour le spectacle
	$menu_spectacle = intval($_POST['radio_menu_spectacle']);
	$majoration_spectacle_only = ($menu_spectacle == 1) ? intval(get_value_of_option("maj_only_spectacle")) : 0;
	
	// Le prix est égal au nombre de personnes multiplié par le tarif min
	$nb_min_ar = intval(get_value_of_option("nb_forfait_min_aller_retour"));
	if ($_POST['select_nb_personnes'] < $nb_min_ar){
		$tarif_min = intval(get_value_of_option("tarif_min_aller_retour"));
		$prix = $nb_min_ar * $tarif_min;
		$prix += ($majoration_spectacle_only * $nb_min_ar);
	}else{
		$prix = get_value_of_option("tarif_min_aller_retour") * $_POST['select_nb_personnes'];
		$prix += ($majoration_spectacle_only * $_POST['select_nb_personnes']);
	}
	
	// Majoration si recherche à domicile
	if (($_POST['select_lieu_aller'] == 4 || $_POST['select_lieu_retour'] == 4))
	{
		$prix += get_value_of_option("maj_domicile");
	}
	
	$_SESSION["trajet"]["prix"] = $prix;
	$_SESSION["trajet"]["menu_spectacle"] = $menu_spectacle;
	$_SESSION["trajet"]["lieu_aller"] = $_POST['select_lieu_aller'];
	$_SESSION["trajet"]["lieu_retour"] = $_POST['select_lieu_aller'];
	$_SESSION["trajet"]["nb_personnes"] = $_POST['select_nb_personnes'];
	$_SESSION["trajet"]["jour_long"] = $_POST['jour_long'];
	$_SESSION["trajet"]["commentaire"] = htmlspecialchars($_POST['demande_particuliere']);
	
	// $_SESSION["trajet"][""] = ;
	$_SESSION["trajet"]["adresse_aller"] = ($_POST['select_lieu_aller'] == 4) ? htmlspecialchars($_POST['txt_adresse_compl_aller']."<br />".$_POST['txt_cp_compl_aller']."<br />".$_POST['txt_ville_compl_aller']) : "";
	$_SESSION["trajet"]["adresse_retour"] = ($_POST['select_lieu_aller'] == 4) ? htmlspecialchars($_POST['txt_adresse_compl_aller']."<br />".$_POST['txt_cp_compl_aller']."<br />".$_POST['txt_ville_compl_aller']) : "";
	
	// Date Aller
	$libelle_menu = ($menu_spectacle == 2) ? "" : "spectacle";
	if ($_POST['radio_type_spectacle'] == "midi"){
		$tab_explode = explode(':', get_value_of_option("midi_aller_".$libelle_menu));
		$heure_aller = intval($tab_explode[0]);
		$minute_aller = intval($tab_explode[1]);
	}else{
		$tab_explode = explode(':', get_value_of_option("soir_aller_".$libelle_menu));
		$heure_aller = intval($tab_explode[0]);
		$minute_aller = intval($tab_explode[1]);
	}
	
	// On formate la date
	$d_explode = explode('-',$_POST['jour']);
	$_SESSION["trajet"]["date_aller"] = $d_explode[2]."-".$d_explode[1]."-".$d_explode[0]." ".sprintf('%02d', $heure_aller).":".sprintf('%02d', $minute_aller).":00";
	$_SESSION["trajet"]["heure_aller"] = sprintf('%02d', $heure_aller)."h".sprintf('%02d', $minute_aller);
	
	// Date Retour
	if ($_POST['radio_type_spectacle'] == "midi"){
		$tab_explode = explode(':', get_value_of_option("midi_retour"));
		$heure_retour = intval($tab_explode[0]);
		$minute_retour = intval($tab_explode[1]);
	}else{
		$tab_explode = explode(':', get_value_of_option("soir_retour"));
		$heure_retour = intval($tab_explode[0]);
		$minute_retour = intval($tab_explode[1]);
	}
	
	// On formate la date
	$d_explode = explode('-',$_POST['jour']);
	if ($_POST['radio_type_spectacle'] == "midi"){
		$_SESSION["trajet"]["date_retour"] = $d_explode[2]."-".$d_explode[1]."-".$d_explode[0]." ".sprintf('%02d', $heure_retour).":".sprintf('%02d', $minute_retour).":00";
	}else{
		// Le retour du spectacle du soir est le lendemain
		$_SESSION["trajet"]["date_retour"] = $d_explode[2]."-".$d_explode[1]."-".($d_explode[0]+1)." ".sprintf('%02d', $heure_retour).":".sprintf('%02d', $minute_retour).":00";
		$timestamp = mktime($heure_retour, $minute_retour, 0, intval($d_explode[1]), intval($d_explode[0]), intval($d_explode[2]));
		$timestamp = $timestamp + 24*3600;
		$_SESSION["trajet"]["date_retour"] = date("Y-m-d H:i:00", $timestamp);
	}
	$_SESSION["trajet"]["heure_retour"] = sprintf('%02d', $heure_retour)."h".sprintf('%02d', $minute_retour);
	
	
	// Fonction permettant de gérer l'affichage des petits 
	// panneau en fonction des champs déjà remplis
	function get_display($v, $d = null)
	{
		if (isset($d))
		{
			if (!empty($v) || !empty($d))
			{
				return "style=\"display:none;\"";
			}
			else
			{
				return "";
			}

		}
		else
		{
			if (!empty($v))
			{
				return "style=\"display:none;\"";
			}
			else
			{
				return "";
			}
		}
		
	}
	
?>
<html>
	<head>
		<title><?php echo $lang_titre_accueil.' :: '.$lang_titre_main; ?></title>
		
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
		<meta name="Language" content="fr" />		
		
		<link rel="stylesheet" type="text/css" href="styles/base.css" media="all" />
		<link rel="stylesheet" type="text/css" href="styles/style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="styles/calendrier.css" media="screen" />
		<script type="text/javascript" src="scripts/calendrier.js"></script>
		<script type="text/javascript" src="scripts/verification_formulaire.js"></script>
		<!-- Gestion de l'affichage des données supplémentaires liées au lieu domicile ou non -->
		<script type="text/javascript">
			
			function show_identifiant_tel(){
				var select_pays = document.getElementById("select_pays");
				var value_select_pays = select_pays.options[select_pays.selectedIndex].value;
				var array = value_select_pays.split(":")
				var ident = array[1];
				
				document.getElementById('identifiant_fixe').innerHTML = '(+'+ident+')';
				document.getElementById('identifiant_port').innerHTML = '(+'+ident+')';
			}
			
			function verif_email(){
				var txt_verif_email = document.getElementById("txt_verif_email").value;
				var txt_email = document.getElementById("txt_email").value;
				var span_verif_mail = document.getElementById("error_verif_mail");
				
				if(txt_verif_email != txt_email){
					span_verif_mail.style.display = '';
				}else{
					span_verif_mail.style.display = 'none';
				}
			}
			
			function verif_telephone(){
				var txt_num_telephone_fixe = document.getElementById("txt_num_telephone_fixe").value;
				var txt_num_telephone_port = document.getElementById("txt_num_telephone_port").value;
				var span_fixe = document.getElementById("error_tel_fixe");
				var span_port = document.getElementById("error_tel_port");
				
				if(txt_num_telephone_fixe == "" && txt_num_telephone_port == "")
				{
					span_fixe.style.display = '';
					span_port.style.display = '';
				}
				else if((txt_num_telephone_fixe != "" && isNaN(txt_num_telephone_fixe.substr(0,1))) || (txt_num_telephone_port != "" && isNaN(txt_num_telephone_port.substr(0,1))))
				{
					span_fixe.style.display = '';
					span_port.style.display = '';
				}
				else
				{
					span_fixe.style.display = 'none';
					span_port.style.display = 'none';
				}
			}
			
			function verif_champ_vide(id){
				var champ = document.getElementById(id).value;
				var span = document.getElementById("error_"+id);
				
				if(champ == ""){
					span.style.display = '';
				}else{
					span.style.display = 'none';
				}
			}
			
			function verif_mail_exp()
			{
				var mail = document.getElementById('txt_email').value;
				var span = document.getElementById("error_txt_email");
				var reg = new RegExp('^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$', 'i');

				if(!reg.test(mail)){
					span.style.display = '';
				}else{
					span.style.display = 'none';
				}
			}
			
		</script>
		
	</head> 
	<body> 
	
		<div id="global">
			<!-- On insère le header + le menu -->
			<?php require_once('./includes/include_entete_menu.php'); ?>
			
			<!-- Le contenu -->
			<div id="contenu">
				<!-- Titre de la page -->
				<h1><?php echo $lang_titre_accueil; ?></h1>
				
				<?php
					// Contenu de la page
					echo '
					'.$lang_effectuez_reservation.'
					<form name="form_reserv" method="post" action="validation.php" id="form_reservation">
						<fieldset class="spaced_fieldset">
							<legend>'.$lang_vos_informations.'</legend>
							<table class="table_reserv">
							
								<tr>
									<th><label for="txt_nom">'.$lang_nom.' *</label></th>
									<td><input class="dotted_input" type="text" value="'.$nom_client.'" id="txt_nom" name="txt_nom" onchange="verif_champ_vide(\'txt_nom\')" onkeyup="verif_champ_vide(\'txt_nom\')" /><span id="error_txt_nom" '.get_display($nom_client).'><img src="http://alsace-navette.com/aeroport/images/error.png" /></span></td>
								</tr>
								
								<tr>
									<th><label for="txt_prenom">'.$lang_prenom.' *</label></th>
									<td><input class="dotted_input" type="text" value="'.$prenom_client.'" id="txt_prenom" name="txt_prenom" onchange="verif_champ_vide(\'txt_prenom\')" onkeyup="verif_champ_vide(\'txt_prenom\')" /><span id="error_txt_prenom" '.get_display($prenom_client).'><img src="http://alsace-navette.com/aeroport/images/error.png" /></span></td>
								</tr>
								
								<tr>
									<th><label for="txt_ville">'.$lang_ville.'</label></th>
									<td><input class="dotted_input" type="text" value="'.$ville_client.'" id="txt_ville" name="txt_ville" /></td>
								</tr>
								
								<tr>
									<th><label for="combo_pays">'.$lang_pays.' *</label></th>
									<td>
										<select name="select_pays" id="select_pays" onchange="show_identifiant_tel()"';
											
											$q = $bdd->query('	SELECT *
																FROM aeroport_pays
																ORDER BY nom_pays
															');
															
											while ($r = $q->fetch()){
												// On sélectionne la France par défaut
												$bonus = ($r['id_pays'] == 66) ? "selected" : "";
												
												echo '<option value="'.$r['id_pays'].':'.$r['identifiant_tel'].'" '.$bonus.'>'.$r['nom_pays'].'</option>';
											}
											
											echo '
										</select>
									</td>
								</tr>
								
								<tr>
									<th><label for="txt_num_telephone_fixe">'.$lang_num_telephone_fixe.' **</label></th>
									<td><span id="identifiant_fixe">(+33)</span><input class="dotted_input" type="text" value="'.$tel_fixe_client.'" id="txt_num_telephone_fixe" name="txt_num_telephone_fixe" onchange="verif_telephone()" onkeyup="verif_telephone()" /><span id="error_tel_fixe" '.get_display($tel_fixe_client, $tel_port_client).'><img src="http://alsace-navette.com/aeroport/images/error.png" /></span></td>
								</tr>
								
								<tr>
									<th><label for="txt_num_telephone_port">'.$lang_num_telephone_port.' **</label></th>
									<td><span id="identifiant_port">(+33)</span><input class="dotted_input" type="text" value="'.$tel_port_client.'" id="txt_num_telephone_port" name="txt_num_telephone_port" onchange="verif_telephone()" onkeyup="verif_telephone()" /><span id="error_tel_port" '.get_display($tel_port_client, $tel_fixe_client).'><img src="http://alsace-navette.com/aeroport/images/error.png" /></span></td>
								</tr>
								
								<tr>
									<th><label for="txt_email">'.$lang_email.' *</label></th>
									<td><input class="dotted_input" type="text" value="'.$mail_client.'" id="txt_email" name="txt_email" onchange="verif_mail_exp()" onkeyup="verif_mail_exp()" /><span id="error_txt_email" '.get_display($mail_client).'><img src="http://alsace-navette.com/aeroport/images/error.png" /></span></td>
								</tr>
								<tr>
									<th><label for="txt_verif_email">'.$lang_verif_email.' *</label></th>
									<td><input class="dotted_input" type="text" value="'.$mail_client.'" id="txt_verif_email" name="txt_verif_email" onchange="verif_email()" onkeyup="verif_email()" /><span id="error_verif_mail" style="display:none"><img src="http://alsace-navette.com/aeroport/images/error.png" /></span></td>
								</tr>
								
							</table>
						</fieldset>
						
						<fieldset class="spaced_fieldset">
							<legend>'.$lang_validation.'</legend>
							
							<table class="table_reserv">
								<tr>
									<td colspan="2" style="text-align:center;"><input type="checkbox" id="accept_cgv" name="accept_cgv"/>'.$lang_lu_accepte_cgv.'</td>
								</tr>
								<tr>
									<td colspan="2" style="text-align:center;"><input type="button" id="bt_valider" name="bt_valider" value="'.$lang_valider.'" onclick="verif_formulaire_client();"/></td>
								</tr>
							</table>
							
						</fieldset>
						* : '.$lang_champ_obligatoire.'<br />
						** : '.$lang_champ_semi_obligatoire.'<br />
					</form>';
				?>
			</div>
			
			<!-- Le pied de page -->
			<?php require_once('./includes/include_pied_de_page.php'); ?>
		</div>
		
	</body> 
</html>