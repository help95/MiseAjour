<?php
/*
 * Nom du fichier : reservation.php
 * 
 * 
 * Objectif :Creer le template de la page 4/5
 * Remarques : toutes ces variables indiquent le passager qui n'est pas celui qui reserve 
 * 
 * 			$_SESSION['lstIndicatifTelephone']=$_POST['lstIndicatifTelephone'];
			$_SESSION['txtPortable']=$_POST['txtPortable'];
			$_SESSION['txtNom']=$_POST['txtNom'];
 * **/
	session_start();
	
	require_once('../includes/tpl_base.php');

	if(isset($_POST['chckPassagerDifferent']))
	{
		// si le passager est different de celui qui reserve
		// INFO MARC
		
		
		if($_POST['chckPassagerDifferent']=="oui")
		{
			$_SESSION['lstIndicatifTelephone']=$_POST['lstIndicatifTelephone'];
			$_SESSION['txtPortable']=$_POST['txtPortable'];
			$_SESSION['txtNom']=$_POST['txtNom'];
			
					 //si on vient d'un aller simple la varibale de session txtIndicatif n'est pas creer
					if(!isset($_POST['txtIndicatifPortable'])){
		
						if($_SERVER["SERVER_ADDR"]=="::1" || $_SERVER["SERVER_ADDR"]=="127.0.0.1" || $_SERVER["SERVER_ADDR"]=="192.168.3.2") // si localhost
						{
							$c = mysql_connect('localhost', 'root', '');
							mysql_select_db('a-n');
						}
						else
						{
							$c = mysql_connect('db922.1and1.fr', 'dbo206617947', 'D5ZEtV4h');
							mysql_select_db('db206617947');
						}
		
							mysql_query("SET NAMES 'utf8'");
							mysql_query('SET CHARACTER SET utf8');
						            
							$ma_req="select identifiant_tel from  aeroport_pays where id_pays=".$_POST['lstIndicatifTelephone'];
						    $table_aeroport_pays=mysql_fetch_array(mysql_query($ma_req));
		    
		    				mysql_close($c);
		    				$_POST['txtIndicatifPortable']=$table_aeroport_pays['identifiant_tel'];
					  }
			
			$_SESSION['txtIndicatifPortable']=$_POST['txtIndicatifPortable'];
			$_SESSION['chckPassagerDifferent']="oui";
		}
	}
	else	//sinon 
	{
		$_SESSION['lstIndicatifTelephone']=$_SESSION['client']['ind_port'];
		$_SESSION['txtPortable']=$_SESSION['client']['tel_port'];				
		$_SESSION['txtNom']=$_SESSION['client']['nom'];
		$_SESSION['txtIndicatifPortable'] = ""; // c'est juste pour affichage
		
		$_SESSION['chckPassagerDifferent']="non";
	}
	
	if((isset($_POST['res_3']) || isset($_GET['res_3'])) && isset($_SESSION['trajet']) && !isset($_SESSION['fin_resa']))
	{
		// le fil d'ariane
		$tab_ariane = array(
							array(
								'ARIANE' => $ariane_accueil,
								'LIEN' => 'index.html'
								),
							array(
								'ARIANE' => $ariane_reserver,
								'LIEN' => 'reserver.html'
								),
							array(
								'ARIANE' => $ariane_reservation_2,
								'LIEN' => ''
								)
							);
							
		foreach($tab_ariane as $tab)
		{
			$tpl->setBlock('fil', array(
										'ARIANE' => $tab['ARIANE'],
										'LIEN' => $tab['LIEN']
										)
							);
		}


        // récupération des infos de l'aller
        

        if($_SESSION['trajet']['type_trajet'] == '1')
        {
            $type_reservation = "RAJOUT";

            if(isset($_POST['accept_forfait_mini']) && $_POST['accept_forfait_mini'] == "on")
                $type_reservation = "FORFAIT";


            // on attend pour l'aller
            if(isset($_POST['attendre']) && $_POST['attendre'] == "on")
                $type_reservation = "ATTENDRE";

            if(isset($_POST['rb_navette']))
                $_SESSION['etat_aller']['rb_navette'] = $_POST['rb_navette'];
            else
                $_SESSION['etat_aller']['rb_navette'] = "";

            $_SESSION['etat_retour']['rb_navette'] = "";


            // si on choisi une navette existante

            if($_POST['navette_dispo'] == 1)
            {
                $t = get_navette(intval($_POST['rb_navette']));

                $_SESSION['vehicule_id_aller'] = $t['vehicule'];
                $_SESSION['chauffeur_id_aller'] = $t['chauffeur'];
                $_SESSION['trajet']['heure_depart'] = $t['date'];
            }

            $_SESSION['vehicule_id_retour'] = "";
            $_SESSION['chauffeur_id_retour'] = "";
            $_SESSION['trajet']['heure_retour'] = "";
            $_SESSION['etat_retour']['type_reservation'] = "";

            $_SESSION['id_com_retour'] = 0;


            $_SESSION['etat_aller']['type_reservation'] = $type_reservation;


            if($_SESSION['trajet']['pt_rass_aller'] == 4)
            {
                $_SESSION['etat_aller']['distance'] = intval($_POST['distance']);
                $_SESSION['etat_aller']['decalage'] = intval($_POST['decalage']);
            }

            $_SESSION['etat_retour']['distance'] = 0;
            $_SESSION['etat_retour']['decalage'] = 0;
        }
        else
        {
            $type_reservation = "RAJOUT";

            if(isset($_POST['accept_forfait_mini']) && $_POST['accept_forfait_mini'] == "on")
                $type_reservation = "FORFAIT";


            // on attend pour le retour
            if(isset($_POST['attendre']) && $_POST['attendre'] == "on")
                $type_reservation = "ATTENDRE";

            if(isset($_POST['rb_navette']))
                $_SESSION['etat_retour']['rb_navette'] = $_POST['rb_navette'];
            else
                $_SESSION['etat_retour']['rb_navette'] = "";


            // si on choisi une navette existante

            if($_POST['navette_dispo'])
            {
                $t = get_navette(intval($_POST['rb_navette']));

                $_SESSION['vehicule_id_retour'] = $t['vehicule'];
                $_SESSION['chauffeur_id_retour'] = $t['chauffeur'];
                $_SESSION['trajet']['heure_retour'] = $t['date'];
            }

            if($_SESSION['etat_aller']['rajout'])
            {
                $_SESSION['vehicule_id_aller'] = $_SESSION['etat_aller']['vehicule'];
                $_SESSION['chauffeur_id_aller'] = $_SESSION['etat_aller']['chauffeur'];
                $_SESSION['trajet']['heure_depart'] = $_SESSION['etat_aller']['heure_depart'];
            }


            $_SESSION['etat_retour']['type_reservation'] = $type_reservation;

            if($_SESSION['trajet']['pt_rass_retour'] == 4)
            {
                $_SESSION['etat_retour']['distance'] = intval($_POST['distance']);
                $_SESSION['etat_retour']['decalage'] = intval($_POST['decalage']);
            }
        }


        $type_res_min = "";


		$tab_date_depart = explode('-', $_SESSION['trajet']['date_depart']);
		

		$annee_depart = intval($tab_date_depart[2]);
		$mois_depart = intval($tab_date_depart[1]);
		$jour_depart = intval($tab_date_depart[0]);

		$format_date_aller = "'" . $annee_depart . "-" . $mois_depart . "-" . $jour_depart . " " . $_SESSION['trajet']['heure_depart'] . "'";



		if($_SESSION['trajet']['type_trajet'] == 0)
		{

			$tab_date_retour = explode('-', $_SESSION['trajet']['date_retour']);
			
			$annee_retour = intval($tab_date_retour[2]);
			$mois_retour = intval($tab_date_retour[1]);
			$jour_retour = intval($tab_date_retour[0]);
			
			$format_date_retour = "'" . $annee_retour . "-" . $mois_retour . "-" . $jour_retour . " " . $_SESSION['trajet']['heure_retour'] . "'";
		}
		else
			$format_date_retour = "";
		
		

		
		$nb_pers_aller = ($_SESSION['trajet']['passager_adulte_aller'] + $_SESSION['trajet']['passager_enfant_aller']);
		$nb_pers_retour = ($_SESSION['trajet']['passager_adulte_retour'] + $_SESSION['trajet']['passager_enfant_retour']);
		
			
		// calcul du prix

		$majoration_derniere_minute = 0;
		$majoration_domicile = 0;
		$prix_prise_aller = 0;
		$prix_prise_retour = 0;
        $prix_km_domicile = floatval(get_option("maj_dom_km"));
		$navette_aller = false;
		$navette_retour = false;
		$maj_der_min_72 = false;
		$maj_der_min_24 = false;
		$maj_dom_aller = false;
		$maj_dom_retour = false;
		
		$txt_der_min_72 = intval(get_option("maj_72"));
		$txt_der_min_24 = intval(get_option("maj_24"));

        $txt_prix_prise_dom_gen = intval(get_option("maj_dom")); // cout domicile si adresse pas trouvé ou sur strasbourg

		if($_SESSION['trajet']['pt_rass_aller'] == 4)
		{
			$maj_dom_aller = true;

			if($_SESSION['etat_aller']['distance'] <= 10000)
			{
				$prix_prise_aller = $txt_prix_prise_dom_gen;
				$majoration_domicile += $prix_prise_aller;
			}
			else
			{
				$prix_prise_aller = (floor(($_SESSION['etat_aller']['distance'] / 1000))) * $prix_km_domicile;
				$majoration_domicile += $prix_prise_aller;
			}
		}
		
		if($_SESSION['trajet']['type_trajet'] == 0 && $_SESSION['trajet']['pt_rass_retour'] == 4)
		{
			$maj_dom_retour = true;
			
			if($_SESSION['etat_retour']['distance'] <= 10000)
			{
				$prix_prise_retour = $txt_prix_prise_dom_gen;
				$majoration_domicile += $prix_prise_retour;
			}
			else
			{
				$prix_prise_retour = (floor(($_SESSION['etat_retour']['distance'] / 1000))) * $prix_km_domicile;
				$majoration_domicile += $prix_prise_retour;
			}
		}
			


        $tab_date_depart = explode('-', $_SESSION['trajet']['date_depart']);
        $tab_dbl_pt = explode(":", $_SESSION['trajet']['heure_depart']);

		$diff = mktime(intval($tab_dbl_pt[0]), intval($tab_dbl_pt[1]), 0, $mois_depart, $jour_depart, $annee_depart) - time();

		if($diff <= 3600*24)
		{
			$type_res_min = "24";
			$maj_der_min_24 = true;
			$majoration_derniere_minute = $txt_der_min_24;
		}
		elseif($diff > 3600*24 && $diff <= 3600*72)
		{
			$type_res_min = "72";
			$maj_der_min_72 = true;
			$majoration_derniere_minute = $txt_der_min_72;
		}

		
		
		$prix_total_aller = 0;
        $prix_total_retour = 0;
		$prix_aller = 0;
		$prix_retour = 0;
		$supplement = 0;
		
		$surcout_aller = 0;
		$surcout_retour = 0;
		
		$cout_par_personne_retour = "";
		$nb_personne_forfait_retour = "";
		$cout_par_personne_aller = "";
		$nb_personne_forfait_aller = "";
		
		$tab_surcout = array(1, 2); // id des destinations où la navette à la demande est surtaxée
		$txt_surcout_demande = get_option("maj_surcout_demande");
			
	
		if($_SESSION['etat_aller']['type_reservation'] != "RAJOUT") // nouveau trajet pour l'aller
		{
			$id_lieu_dest = $_SESSION['trajet']['dest'];
			$id_lieu_depart = $_SESSION['trajet']['depart'];
			
			$id_lieu = $id_lieu_dest;
		
			$ret_prix_trajet = query("SELECT prix_forfait, nb_personne FROM aeroport_lieu WHERE id_lieu = '" . $id_lieu_dest . "'");
			
			$sql = "";
			if($id_lieu_dest == 100)
			{
				$sql = "SELECT prix_forfait, nb_personne FROM aeroport_lieu WHERE id_lieu = '" . $id_lieu_depart . "'";
				
				$ret_prix_trajet->closeCursor();
		
				$ret_prix_trajet = query($sql);
				
				$id_lieu = $id_lieu_depart;
			}
			
			$row = $ret_prix_trajet->fetch();
			
			$prix_forfait_aller = $row['prix_forfait'];


			$ret_prix_trajet->closeCursor();


            $nb_personne_forfait_aller = $row['nb_personne'];
			
			
			$cout_par_personne_aller = round(($prix_forfait_aller / $nb_personne_forfait_aller), 2);
			

            if($_SESSION['id_com_aller'] != 0 && $_SESSION['trajet']['dest'] == 100)
                $nb_personne_forfait_aller = 1;
            else
                $nb_personne_forfait_aller = $row['nb_personne'];

        
			if($nb_pers_aller < $nb_personne_forfait_aller)
				$prix_aller = $prix_forfait_aller; // forfait mini
			else
				$prix_aller = $cout_par_personne_aller * $nb_pers_aller;
			
			
			if(!$_SESSION['trajet']['bool_depart_fixe'] && (in_array($_SESSION['trajet']['depart'], $tab_surcout) || in_array($_SESSION['trajet']['dest'], $tab_surcout)))
				$surcout_aller += $txt_surcout_demande;

			$supplement = $majoration_derniere_minute + $prix_prise_aller;
			
			$prix_total_aller = $prix_aller + $supplement + $surcout_aller;

		}
		else // trajet existant : on paye toujours le prix par personne
		{
			$id_lieu_dest = $_SESSION['trajet']['dest'];
			$id_lieu_depart = $_SESSION['trajet']['depart'];
			
			$id_lieu = $id_lieu_dest;
		
			$ret_prix_trajet = query("SELECT prix_forfait, nb_personne FROM aeroport_lieu WHERE id_lieu = '" . $id_lieu_dest . "'");
			
			$sql = "";
			if($id_lieu_dest == 100)
			{
				$sql = "SELECT prix_forfait, nb_personne FROM aeroport_lieu WHERE id_lieu = '" . $id_lieu_depart . "'";
				
				$ret_prix_trajet->closeCursor();
		
				$ret_prix_trajet = query($sql);
				
				$id_lieu = $id_lieu_depart;
			}
			
			$row = $ret_prix_trajet->fetch();
			
			
			$prix_forfait_aller = $row['prix_forfait'];
			
			$ret_prix_trajet->closeCursor();
			

			$nb_personne_forfait_aller = 1;
				
			$cout_par_personne_aller = round(($prix_forfait_aller / $row['nb_personne']), 2);
				
			$prix_aller = $cout_par_personne_aller * $nb_pers_aller;

			$_SESSION['trajet']['bool_depart_fixe'] = true;

			$supplement = $majoration_derniere_minute + $prix_prise_aller;
			
			$prix_total_aller = $prix_aller + $supplement + $surcout_aller;

		}

        if($_SESSION['trajet']['type_trajet'] == "0")
        {
            if($_SESSION['etat_retour']['type_reservation'] != "RAJOUT") // nouveau trajet pour le retour
            {
                $id_lieu_dest = $_SESSION['trajet']['dest'];
                $id_lieu_depart = $_SESSION['trajet']['depart'];

                $id_lieu = $id_lieu_dest;

                $ret_prix_trajet = query("SELECT prix_forfait, nb_personne FROM aeroport_lieu WHERE id_lieu = '" . $id_lieu_dest . "'");

                $sql = "";
                if($id_lieu_dest == 100)
                {
                    $sql = "SELECT prix_forfait, nb_personne FROM aeroport_lieu WHERE id_lieu = '" . $id_lieu_depart . "'";

                    $ret_prix_trajet->closeCursor();

                    $ret_prix_trajet = query($sql);

                    $id_lieu = $id_lieu_depart;
                }

                $row = $ret_prix_trajet->fetch();

                $prix_forfait_retour = $row['prix_forfait'];

                $nb_personne_forfait_retour = $row['nb_personne'];

                $ret_prix_trajet->closeCursor();


                $cout_par_personne_retour = round(($prix_forfait_retour / $nb_personne_forfait_retour), 2);


                if($_SESSION['id_com_retour'] != 0 && $_SESSION['trajet']['dest'] == 100)
                    $nb_personne_forfait_retour = 1;
                else
                    $nb_personne_forfait_retour = $row['nb_personne'];


                if($nb_pers_retour < $nb_personne_forfait_retour)
                    $prix_retour = $prix_forfait_retour; // forfait mini
                else
                    $prix_retour = $cout_par_personne_retour * $nb_pers_retour;


                if(!$_SESSION['trajet']['bool_retour_fixe'] && (in_array($_SESSION['trajet']['depart'], $tab_surcout) || in_array($_SESSION['trajet']['dest'], $tab_surcout)))
                    $surcout_retour += $txt_surcout_demande;

                $supplement = $prix_prise_retour;

                $prix_total_retour = $prix_retour + $supplement + $surcout_retour;

            }
            else // trajet existant : on paye toujours le prix par personne
            {
                $id_lieu_dest = $_SESSION['trajet']['dest'];
                $id_lieu_depart = $_SESSION['trajet']['depart'];

                $id_lieu = $id_lieu_dest;

                $ret_prix_trajet = query("SELECT prix_forfait, nb_personne FROM aeroport_lieu WHERE id_lieu = '" . $id_lieu_dest . "'");

                $sql = "";
                if($id_lieu_dest == 100)
                {
                    $sql = "SELECT prix_forfait, nb_personne FROM aeroport_lieu WHERE id_lieu = '" . $id_lieu_depart . "'";

                    $ret_prix_trajet->closeCursor();

                    $ret_prix_trajet = query($sql);

                    $id_lieu = $id_lieu_depart;
                }

                $row = $ret_prix_trajet->fetch();


                $prix_forfait_retour = $row['prix_forfait'];

                $ret_prix_trajet->closeCursor();


                $nb_personne_forfait_retour = 1;

                $cout_par_personne_retour = round(($prix_forfait_retour / $row['nb_personne']), 2);

                $prix_retour = $cout_par_personne_retour * $nb_pers_retour;

                $_SESSION['trajet']['bool_retour_fixe'] = true;

                $supplement = $prix_prise_retour;

                $prix_total_retour = $prix_retour + $supplement;

            }
        }

        $prix = $prix_total_aller + $prix_total_retour;


	


		// calcul de l'heure réelle de prise à domicile
		if($_SESSION['trajet']['type_trajet'] == 0) // si aller-retour
		{
			if($_SESSION['trajet']['dest'] != 100) // str -> aéroport -> str
			{
				$tps_pt_rass = get_tps_rass(intval($_SESSION['trajet']['pt_rass_aller']));

				$tab_tps_rass = explode(':', $_SESSION['trajet']['heure_depart']);
			
				$tps_rassemblement_aller = mktime($tab_tps_rass[0], $tab_tps_rass[1]) + $tps_pt_rass;
					
				if($_SESSION['trajet']['pt_rass_aller'] == 4)
					$tps_rassemblement_aller -= $_SESSION['etat_aller']['decalage'];

				$tab_tps_rass = explode(':', $_SESSION['trajet']['heure_retour']);
				
				$tps_rassemblement_retour = mktime($tab_tps_rass[0], $tab_tps_rass[1]);
			}
			else // sinon, aéroport -> str -> aéroport
			{
				$tps_pt_rass = get_tps_rass(intval($_SESSION['trajet']['pt_rass_retour']));

				$tab_tps_rass = explode(':', $_SESSION['trajet']['heure_retour']);
			
				$tps_rassemblement_retour = mktime($tab_tps_rass[0], $tab_tps_rass[1]) + $tps_pt_rass;
					
				if($_SESSION['trajet']['pt_rass_retour'] == 4)
					$tps_rassemblement_retour -= $_SESSION['etat_retour']['decalage'];

				$tab_tps_rass = explode(':', $_SESSION['trajet']['heure_depart']);
				
				$tps_rassemblement_aller = mktime($tab_tps_rass[0], $tab_tps_rass[1]);
			}
		}
		else // si aller simple
		{
			$tps_pt_rass = get_tps_rass(intval($_SESSION['trajet']['pt_rass_aller']));

			$tab_tps_rass = explode(':', $_SESSION['trajet']['heure_depart']);
	
			if($_SESSION['trajet']['dest'] != 100) // si on part de strasbourg
			{
				$tps_rassemblement_aller = mktime($tab_tps_rass[0], $tab_tps_rass[1]) + $tps_pt_rass;
				
				if($_SESSION['trajet']['pt_rass_aller'] == 4)
					$tps_rassemblement_aller -= $_SESSION['etat_aller']['decalage'];
			}
			else
				$tps_rassemblement_aller = mktime($tab_tps_rass[0], $tab_tps_rass[1]);
				
			$tps_rassemblement_retour = 0;
		}
		
		
		// on enregistre le tout dans la base pour y avoir accès après le paiement
		

		
		if($_SESSION['res_der_min'])
			$is_der_min = "1";
		else
			$is_der_min = "0";
		
		
		if(isset($_SESSION['id_paypal']))
			write("DELETE FROM aeroport_paypal WHERE id_paypal = '" . $_SESSION['id_paypal'] . "'");

		$_SESSION['id_paypal'] = get_max_id('aeroport_paypal', 'id_paypal') + 1;
		
		$info_vol_aller = $compagnie_vol . " : " . $_SESSION['trajet']['compagnie_depart_vol'] . "\n";
		$info_vol_aller .= $dest_vol . " : " . $_SESSION['trajet']['provenance_depart_vol'] . "\n";
		$info_vol_aller .= $heure_vol . " : " . $_SESSION['trajet']['heure_depart_vol'] . "h" . $_SESSION['trajet']['minute_depart_vol'] . "\n";
		
		$info_vol_retour = $compagnie_vol . " : " . $_SESSION['trajet']['compagnie_retour_vol'] . "\n";
		$info_vol_retour .= $dest_vol . " : " . $_SESSION['trajet']['provenance_retour_vol'] . "\n";
		$info_vol_retour .= $heure_vol . " : " . $_SESSION['trajet']['heure_retour_vol'] . "h" . $_SESSION['trajet']['minute_retour_vol'] . "\n";


		$fixe_paypal_aller = "0";
		$fixe_paypal_retour = "0";
		
		
		if(in_array($_SESSION['trajet']['depart'], $tab_surcout) || in_array($_SESSION['trajet']['dest'], $tab_surcout))
		{
			if($_SESSION['trajet']['bool_depart_fixe'])
				$fixe_paypal_aller = "1";
		}
		else
			$fixe_paypal_aller = "1";


		if(in_array($_SESSION['trajet']['dest'], $tab_surcout) || in_array($_SESSION['trajet']['depart'], $tab_surcout))
		{
			if($_SESSION['trajet']['bool_retour_fixe'])
				$fixe_paypal_retour = "1";
		}
		else
			$fixe_paypal_retour = "1";



        $mnt_a_facturer = 0;
        $reste_a_payer = 0;

        $a_payer_aller = true;
        $a_payer_retour = true;

		// aller simple
		if($_SESSION['trajet']['type_trajet'] != "0")
		{
			if($type_res_min != '24')
			{
				if($_SESSION['etat_aller']['type_reservation'] != "ATTENDRE")
					$mnt_a_facturer = $prix;
				else
					$a_payer_aller = false;
			}
			else
			{
				if($_SESSION['etat_aller']['type_reservation'] == "RAJOUT" && est_valide($_SESSION['etat_aller']['rb_navette']))
					$mnt_a_facturer = $prix;
				else
					$a_payer_aller = false;
			}
		}
		else
		{
            if($type_res_min != '24')
            {
                if($_SESSION['etat_aller']['type_reservation'] != "ATTENDRE" && $_SESSION['etat_retour']['type_reservation'] != "ATTENDRE")
                    $mnt_a_facturer = $prix;
                else
                {
                    if($_SESSION['etat_aller']['type_reservation'] == "ATTENDRE" && $_SESSION['etat_retour']['type_reservation'] == "ATTENDRE")
                    {
                        $mnt_a_facturer = 0;
                        $reste_a_payer = $prix;

                        $a_payer_aller = false;
                        $a_payer_retour = false;
                    }
                    elseif($_SESSION['etat_aller']['type_reservation'] == "ATTENDRE")
                    {
                        $mnt_a_facturer = $prix_total_retour;
                        $reste_a_payer = $prix - $mnt_a_facturer;

                        $a_payer_aller = false;
                    }
                    else
                    {
                        $mnt_a_facturer = $prix_total_aller;
                        $reste_a_payer = $prix - $mnt_a_facturer;

                        $a_payer_retour = false;
                    }
                }
            }
            else
            {
                if($_SESSION['etat_aller']['type_reservation'] == "RAJOUT" && est_valide($_SESSION['etat_aller']['rb_navette']))
                {
                    if($_SESSION['etat_aller']['type_reservation'] != "ATTENDRE" && $_SESSION['etat_retour']['type_reservation'] != "ATTENDRE")
                        $mnt_a_facturer = $prix;
                    else
                    {
                        if($_SESSION['etat_aller']['type_reservation'] == "ATTENDRE" && $_SESSION['etat_retour']['type_reservation'] == "ATTENDRE")
                        {
                            $mnt_a_facturer = 0;
                            $reste_a_payer = $prix;

                            $a_payer_aller = false;
                            $a_payer_retour = false;
                        }
                        elseif($_SESSION['etat_aller']['type_reservation'] == "ATTENDRE")
                        {
                            $mnt_a_facturer = $prix_total_retour;
                            $reste_a_payer = $prix - $mnt_a_facturer;

                            $a_payer_aller = false;
                        }
                        else
                        {
                            $mnt_a_facturer = $prix_total_aller;
                            $reste_a_payer = $prix - $mnt_a_facturer;

                            $a_payer_retour = false;
                        }
                    }
                }
                else
                {
                    $a_payer_aller = false;
                    $a_payer_retour = false;
                }
            }
		}
			
			
		
		$sql = "INSERT INTO
					aeroport_paypal
				VALUES (
						'" . intval($_SESSION['id_paypal']) . "',
						'" . intval($_SESSION['trajet']['type_trajet']) . "',
						'" . intval($_SESSION['trajet']['depart']) . "', 
						'" . intval($_SESSION['trajet']['dest']) . "', 
						'" . intval($_SESSION['trajet']['pt_rass_aller']) . "', 
						'" . addslashes($_SESSION['trajet']['rass_adresse_aller']) . "', 
						'" . addslashes(trim($_SESSION['trajet']['rass_cp_aller'])) . "', 
						'" . addslashes($_SESSION['trajet']['rass_ville_aller']). "', 
						'" . addslashes($info_vol_aller) . "', 
						'" . intval($_SESSION['trajet']['pt_rass_retour']) . "', 
						'" . addslashes($_SESSION['trajet']['rass_adresse_retour']) . "', 
						'" . addslashes(trim($_SESSION['trajet']['rass_cp_retour'])) . "', 
						'" . addslashes($_SESSION['trajet']['rass_ville_retour']). "', 
						'" . addslashes($info_vol_retour) . "', 
						'" . substr($format_date_aller, 1, (strlen($format_date_aller) - 2)). ':00' . "', 
						'" . substr($format_date_retour, 1, (strlen($format_date_retour) - 2)). ':00' . "', 
						'" . date('H:i', $tps_rassemblement_aller) . "',
						'" . date('H:i', $tps_rassemblement_retour) . "',
						'" . intval($_SESSION['trajet']['passager_adulte_aller']) . "', 
						'" . intval($_SESSION['trajet']['passager_enfant_aller']) . "', 
						'" . $_SESSION['trajet']['passager_bebe_aller_g0'] . "|" . $_SESSION['trajet']['passager_bebe_aller_g1'] . "|" . $_SESSION['trajet']['passager_bebe_aller_g2'] . "|" . $_SESSION['trajet']['passager_bebe_aller_g3'] . "',
						'" . addslashes($_SESSION['trajet']['info_compl']) . "', 
						'" . intval($_SESSION['trajet']['passager_adulte_retour']) . "', 
						'" . intval($_SESSION['trajet']['passager_enfant_retour']) . "', 
						'" . $_SESSION['trajet']['passager_bebe_retour_g0'] . "|" . $_SESSION['trajet']['passager_bebe_retour_g1'] . "|" . $_SESSION['trajet']['passager_bebe_retour_g2'] . "|" . $_SESSION['trajet']['passager_bebe_retour_g3'] . "',
						'" . addslashes($_SESSION['client']['civilite']) . "', 
						'" . addslashes($_SESSION['client']['nom']) . "', 
						'" . addslashes($_SESSION['client']['prenom']) . "', 
						'" . addslashes($_SESSION['client']['mail']) . "', 
						'" . addslashes($_SESSION['client']['tel_fixe']) . "',
						'" . addslashes($_SESSION['client']['tel_port']) . "',
						'" . addslashes($_SESSION['client']['adresse']) . "', 
						'" . intval($_SESSION['client']['cp']) . "', 
						'" . addslashes($_SESSION['client']['ville']) . "', 
						'" . intval($_SESSION['client']['pays']) . "', 
						'" . $prix . "',
						'" . intval($_SESSION['chauffeur_id_aller']) . "', 
						'" . intval($_SESSION['chauffeur_id_retour']) . "',
						'" . intval($_SESSION['vehicule_id_aller']) . "', 
						'" . intval($_SESSION['vehicule_id_retour']) . "',
						'" . intval($_SESSION['etat_aller']['rb_navette']) . "',
						'" . intval($_SESSION['etat_retour']['rb_navette']) . "',
						'" . intval($prix_aller) . "',
						'" . intval($prix_retour) . "',
						'" . intval($prix_prise_aller) . "',
						'" . intval($prix_prise_retour) . "',
						'" . $type_res_min . "',
						'" . intval($is_der_min) . "',
						'" . get_ip() . "',
						'" . $fixe_paypal_aller . "',
						'" . $fixe_paypal_retour . "',
						'" . $_SESSION['id_com_aller'] . "',
						'" . $_SESSION['id_com_retour'] . "',
						'1',
						'" . $_SESSION['etat_aller']['type_reservation'] . "',
                        '" . $_SESSION['etat_retour']['type_reservation'] . "',
						'" . $a_payer_aller . "',
                        '" . $a_payer_retour . "',
                        '" . addslashes ($_SESSION['trajet']['provenance_depart_vol']) . "',
                        '" . addslashes ($_SESSION['trajet']['provenance_retour_vol']) . "',
                        '" . $_SESSION['client']['ind_fixe'] . "',
                        '" . $_SESSION['client']['ind_port'] . "',
                        '" . $_SESSION['client']['pro'] . "'
						)";

		write($sql);
		
		/*
			Ajout KEMPF
			Insertion dans la table facture
		*/
		$facture_tva = (double)get_option("tva");
		$facture_horaire_demande_aller = ($fixe_paypal_aller == 0) ? get_option("maj_surcout_demande") : "";
		$facture_maj_dom_aller = (intval($_SESSION['trajet']['pt_rass_aller']) == 4) ? get_option("maj_dom") : "";
		$facture_horaire_demande_retour = ($fixe_paypal_retour == 0) ? get_option("maj_surcout_demande") : "";
		$facture_maj_dom_retour = (intval($_SESSION['trajet']['pt_rass_retour']) == 4) ? get_option("maj_dom") : "";
		$facture_res_der_min = (intval($is_der_min) == 1) ? get_option("maj_".intval($type_res_min)) : "";
		
		$facture_est_simple = ($_SESSION['trajet']['type_trajet'] == 1) ? true : false;
		$facture_date_retour = ($facture_est_simple) ? "" : $jour_retour . "/" . $mois_retour . "/" . $annee_retour;
		$facture_lieu_1_retour = ($facture_est_simple) ? "" : get_lieu(intval($_SESSION['trajet']['dest']));
		$facture_lieu_2_retour = ($facture_est_simple) ? "" : get_lieu(intval($_SESSION['trajet']['depart']));
		
		$id_facture = intval(get_option("id_max_facture"));
		set_option("id_max_facture", ($id_facture + 1));
		
		$sql = "INSERT INTO
					aeroport_facture
				VALUES (
					". $id_facture .",
					'" . addslashes($_SESSION['client']['civilite']) . "', 
					'" . addslashes($_SESSION['client']['nom']) . "', 
					'" . addslashes($_SESSION['client']['prenom']) . "', 
					'" . addslashes($_SESSION['client']['mail']) . "', 
					'" . $prix . "',
					'" . $facture_tva . "',
					'" . $jour_depart . "/" . $mois_depart . "/" . $annee_depart . "',
					'" . intval($prix_aller) . "',
					'" . get_lieu(intval($_SESSION['trajet']['depart'])) . "',
					'" . get_lieu(intval($_SESSION['trajet']['dest'])) . "',
					'" . $facture_horaire_demande_aller . "',
					'" . $facture_maj_dom_aller . "',
					'" . $facture_date_retour . "',
					'" . intval($prix_retour) . "',
					'" . $facture_lieu_1_retour . "',
					'" . $facture_lieu_2_retour . "',
					'" . $facture_horaire_demande_retour . "',
					'" . $facture_maj_dom_retour . "',
					'" . $facture_res_der_min . "',
					'" . date('d/m/Y') . "',
					'" . $_SESSION['lang'] . "',
					'0')";
		
		write($sql);
		
		// Fin ajout pour la facture

		$custom = $_SESSION['id_paypal'] . '|';
		
		if($_SESSION['logger'])
			$custom .= $_SESSION['client']['id_client'] . '|1';
		else
			$custom .= '0|0';
		
		$id_non_finalise = (!empty($_SESSION['id_demande_non_finalisee'])) ? $_SESSION['id_demande_non_finalisee'] : 0;
		
		$custom .= '|' . $_SESSION['lang'] . '|1|0|0|0|0|0|'.$id_facture.'|'.$id_non_finalise;



		$_SESSION['resa']['custom'] = $custom;
        $_SESSION['resa']['prix'] = $mnt_a_facturer;

        
		$lieu_depart = get_lieu($_SESSION['trajet']['depart']);
		$lieu_arrive = get_lieu($_SESSION['trajet']['dest']);
			
			
		$descr_trajet = 'Trajet '.$lieu_depart. ' -> '. $lieu_arrive . ' | Date : ' . $_SESSION['trajet']['date_depart_long'];
		
		if($_SESSION['trajet']['type_trajet'] == 0)
			$descr_trajet .= " et " . $_SESSION['trajet']['date_retour_long'];


/*
 * Determination pour la langue de PAYPAL
 * 
 * INFO MARC
 * */
        $lang_paypal = (strtolower($_SESSION['lang']) == 'fr') ? 'FR' : 'GB';


        global $active_paypal, $active_ca;

        
        if($_SESSION['client']['est_admin'] == "0" && $mnt_a_facturer != 0)
        {
            if($active_paypal)
                $encrypted = form_paypal($mnt_a_facturer, $descr_trajet, $custom, $lang_paypal);
            else
                $encrypted = "0";
        }
        else
            $encrypted = "";


        if($active_ca)
            $encrypted_ca = crypter($_SESSION['resa']['custom'] . "-|-" . $_SESSION['resa']['prix'] . "-|-" . $_SESSION['client']['mail']);
        else
            $encrypted_ca = "";
        

		$tpl->set(array(
						"TITRE_PAGE" => $titre_page_res_recap,
						"TITRE_MON_TRAJET" => $mon_trajet,
						"IS_DER_MIN" => $is_der_min,
						"TXT_DER_MIN" => $txt_der_min,
						"RECAPITULATIF" => $recapitulatif,
						"TITRE_TRAJET" => $titre_trajet,
						"TITRE_CLIENT" => $titre_client,
						"TYPE_TRAJET" => $trajet_type,
						"TRAJET_DEPART" => $trajet_depart,
						"TRAJET_ARRIVE" => $trajet_arrive,
						"DATE_DEPART" => $date,
						"DATE_RETOUR" => $date,
						"HEURE_DEPART" => $heure,
						"HEURE_RETOUR" => $heure,
						"INFO_VOL" => $info_vol,
						"PT_RASSEMBLEMENT" => $pt_rassemblement,
						"PASSAGER_ADULTE" => $passager_adulte,
						"PASSAGER_ENFANT" => htmlspecialchars($passager_enfant, ENT_COMPAT, "UTF-8"),
						"INFO_COMPL" => $info_compl,
						"ALLER" => $aller,
						"RETOUR" => $retour,
						"PROVENANCE_VOL" => $provenance_vol,
						"COMPAGNIE_VOL" => $compagnie_vol,
						"HEURE_VOL" => $heure_vol,
						"DEST_VOL" => $dest_vol,
						"ALT_PAYPAL" => $alt_paypal,
						"TRAJET" => $_SESSION['trajet']['type_trajet'],
						"TXT_TYPE_TRAJET" => ($_SESSION['trajet']['type_trajet'] == 1) ? $trajet_aller_simple : $trajet_aller_retour,
						"TXT_TRAJET_DEPART" => $lieu_depart,
						"TXT_TRAJET_ARRIVE" => $lieu_arrive,
						"TXT_PT_RASS_ALLER" => get_pt_rass($_SESSION['trajet']['pt_rass_aller']),
						"TXT_PT_RASS_RETOUR" => get_pt_rass($_SESSION['trajet']['pt_rass_retour']),
						"TXT_RASS_ADRESSE_ALLER" => $_SESSION['trajet']['rass_adresse_aller'],
						"TXT_RASS_CP_ALLER" => $_SESSION['trajet']['rass_cp_aller'],
						"TXT_RASS_VILLE_ALLER" => $_SESSION['trajet']['rass_ville_aller'],
						"TXT_RASS_ADRESSE_RETOUR" => $_SESSION['trajet']['rass_adresse_retour'],
						"TXT_RASS_CP_RETOUR" => $_SESSION['trajet']['rass_cp_retour'],
						"TXT_RASS_VILLE_RETOUR" => $_SESSION['trajet']['rass_ville_retour'],
						"TXT_DATE_DEPART" => $_SESSION['trajet']['date_depart_long'],
						"TXT_DATE_RETOUR" => $_SESSION['trajet']['date_retour_long'],
						"COMPAGNIE" => $compagnie_vol,
						"DEST_VOL" => $dest_vol,
						"HEURE_VOL" => $heure_vol,
						"COMPAGNIE_INFO_VOL_ALLER" => $_SESSION['trajet']['compagnie_depart_vol'],
						"DEST_INFO_VOL_ALLER" => $_SESSION['trajet']['provenance_depart_vol'],
						"HEURE_INFO_VOL_ALLER" => $_SESSION['trajet']['heure_depart_vol'] . "h" . $_SESSION['trajet']['minute_depart_vol'],
						"COMPAGNIE_INFO_VOL_RETOUR" => $_SESSION['trajet']['compagnie_retour_vol'],
						"DEST_INFO_VOL_RETOUR" => $_SESSION['trajet']['provenance_retour_vol'],
						"HEURE_INFO_VOL_RETOUR" => $_SESSION['trajet']['heure_retour_vol'] . "h" . $_SESSION['trajet']['minute_retour_vol'],
						"TXT_HEURE_DEPART" => str_replace(':', 'h', date('H:i', $tps_rassemblement_aller)),
						"TXT_HEURE_RETOUR" => str_replace(':', 'h', date('H:i', $tps_rassemblement_retour)),
						"TXT_PASSAGER_ADULTE_ALLER" => $_SESSION['trajet']['passager_adulte_aller'],
						"TXT_PASSAGER_ENFANT_ALLER" => $_SESSION['trajet']['passager_enfant_aller'],
						"TXT_PASSAGER_ADULTE_RETOUR" => $_SESSION['trajet']['passager_adulte_retour'],
						"TXT_PASSAGER_ENFANT_RETOUR" => $_SESSION['trajet']['passager_enfant_retour'],
						"TXT_INFO_COMPL" => nl2br(wordwrap($_SESSION['trajet']['info_compl'], 100, '<br />', true)),
						"NOM_CLIENT" => $nom_client,
						"PRENOM_CLIENT" => $prenom_client,
						"TEL_CLIENT" => $tel_client,
						"PORT_CLIENT" => $port_client,
						"ADRESSE_CLIENT" => $adresse_client,
						"CODE_POST_CLIENT" => $code_post_client,
						"VILLE_CLIENT" => $ville_client,
						"PAYS_CLIENT" => $pays_client,
						"CIVILITE" => $civilite,
						"TXT_CIVILITE_CLIENT" => $_SESSION['client']['civilite'],
						"TXT_NOM_CLIENT" => $_SESSION['client']['nom'],
						"TXT_PRENOM_CLIENT" => $_SESSION['client']['prenom'],
						"TXT_MAIL_CLIENT" => $_SESSION['client']['mail'],
						"TXT_TEL_CLIENT" => $_SESSION['client']['tel_fixe'],
						"TXT_PORT_CLIENT" => $_SESSION['client']['tel_port'],
                        "TXT_IND_PORT" => get_indicatif($_SESSION['client']['ind_port']),
                        "TXT_IND_FIXE" => get_indicatif($_SESSION['client']['ind_fixe']),
						"TXT_ADRESSE_CLIENT" => $_SESSION['client']['adresse'],
						"TXT_CODE_POST_CLIENT" => $_SESSION['client']['cp'],
						"TXT_VILLE_CLIENT" => $_SESSION['client']['ville'],
						"TXT_PAYS_CLIENT" => get_pays($_SESSION['client']['pays']),
						"INFO_INCORRECT" => $info_incorrect,
						"BTN_PAYER" => $btn_payer,
						"TARIFS" => $tarif,
						"COUT_TRAJET_BASE" => $cout_trajet_base,
						"COUT_PAR_PERSONNE" => $cout_par_personne_aller,
						"TXT_COUT_TRAJET_ALLER" => $prix_aller,
						"TXT_COUT_TRAJET_RETOUR" => $prix_retour,
						"COUT_PAR_PERSONNE_ALLER" => $cout_par_personne_aller,
						"COUT_PAR_PERSONNE_RETOUR" => $cout_par_personne_retour,
						"NB_PASSAGER" => $nb_passager,
						"DOMICILE_ALLER" => $maj_dom_aller,
						"DOMICILE_RETOUR" => $maj_dom_retour,
						"PRISE_DOMICILE" => $prise_domicile,
						"DEPOSE_DOMICILE" => $depose_domicile,
						"DERNIERE_MINUTE_72" => $maj_der_min_72,
						"DERNIERE_MINUTE_24" => $maj_der_min_24,
						"TXT_DER_MIN_72" => $txt_der_min_72,
						"TXT_DER_MIN_24" => $txt_der_min_24,
						"RES_DER_MIN_72" => $res_der_minute_72,
						"RES_DER_MIN_24" => $res_der_minute_24,
						"PRIX_TOTAL" => $prix_total,
						"TXT_PRIX_TOTAL" => $prix,
						"PERSONNE" => $personne,
						"TXT_FORFAIT_MINI" => $nb_personne_forfait_aller,
						"TXT_FORFAIT_MINI_ALLER" => $nb_personne_forfait_aller,
						"TXT_FORFAIT_MINI_RETOUR" => $nb_personne_forfait_retour,
						"FORFAIT_MINI" => $forfait_mini,
						"TARGET_IMG_PAYPAL" => (strtolower($_SESSION['lang']) == 'fr') ? 'fr_FR/FR' : 'en_US',
                        "ENCRYPTED" => $encrypted,
						"TARIFS_XX_PERSONNE" => $tarif_s . " " . ($nb_pers_aller + $nb_pers_retour) . " " . $personne . "s",
						"TARIFS_XX_PERSONNE_ALLER" => $tarif_s . " " . $nb_pers_aller . " " . $personne . "s",
						"TARIFS_XX_PERSONNE_RETOUR" => $tarif_s . " " . $nb_pers_retour . " " . $personne . "s",
						"TXT_NB_PASSAGER" => $nb_pers_aller + $nb_pers_retour,
						"TXT_NB_PASSAGER_ALLER" => $nb_pers_aller,
						"TXT_NB_PASSAGER_RETOUR" => $nb_pers_retour,
						"TXT_NB_PASSAGER_TOT" => ($nb_pers_aller + $nb_pers_retour),
						"PRIX_PRISE_ALLER" => $prix_prise_aller,
						"PRIX_PRISE_RETOUR" => $prix_prise_retour,
						"SURCOUT_DEMANDE" => $surcout_demande,
						"TXT_SURCOUT_ALLER" => $surcout_aller,
						"TXT_SURCOUT_RETOUR" => $surcout_retour,
						"EST_ADMIN" => $_SESSION['client']['est_admin'],
						"TYPE_RESA" => $type_reservation,
						"JAI_ATTENDU" => $jai_attendu,
						"ID_PAYPAL" => $_SESSION['id_paypal'],
						"TXT_TOTAL_ALLER" => $prix_aller,
						"TXT_TOTAL_RETOUR" => $prix_retour,
						"TXT_SUPPLEMENT_ALLER" => $prix_prise_aller,
						"TXT_SUPPLEMENT_RETOUR" => $prix_prise_retour,
						"BOOL_NAVETTE_DEMANDE" => (in_array($_SESSION['trajet']['depart'], $tab_surcout) || in_array($_SESSION['trajet']['dest'], $tab_surcout)) ? "1" : "0",
						"BOOL_NV_DEMANDE_ALLER" => ($_SESSION['trajet']['bool_depart_fixe']) ? "1" : "0",
						"BOOL_NV_DEMANDE_RETOUR" => ($_SESSION['trajet']['bool_retour_fixe']) ? "1" : "0",
						"TYPE_RES_DER_MIN" => $type_res_min,
						"ON_A_ATTENDU" => ($_SESSION['etat_aller']['type_reservation'] == "ATTENDRE" || (isset($_SESSION['etat_retour']) && $_SESSION['etat_retour']['type_reservation'] == "ATTENDRE") ),
						"JAI_ATTENDU_ALLER" => $jai_attendu_aller,
						"JAI_ATTENDU_RETOUR" => $jai_attendu_retour,
                        "PRIX_A_PAYER" => $prix_a_payer,
						"ATTENDRE_ALLER" => (($_SESSION['etat_aller']['type_reservation'] == "ATTENDRE") ? true : false),
						"ATTENDRE_RETOUR" => (isset($_SESSION['etat_retour']) && ($_SESSION['etat_retour']['type_reservation'] == "ATTENDRE") ? true : false),
						"EMAIL" => $email,
						"CONFIRMATION_RESA" => $confirmation_resa,
						"BTN_CONFIRMATION" => $btn_envoyer,
                        "TXT_MNT_A_PAYER" => $mnt_a_facturer,
                        "A_PAYER_ALLER" => $a_payer_aller,
                        "A_PAYER_RETOUR" => $a_payer_retour,
                        "TARIF_MAJ_DEMANDE" => $txt_surcout_demande,
                        "ACTIVE_PAYPAL" => $active_paypal,
                        "ACTIVE_CA" => $active_ca,
                        "MODE_DE_PAIEMENT" => $mode_de_paiement,
                        "INFO_MODE_PAIEMENT" => $info_mode_paiement,
                        "ENCRYPTED_CA" => $encrypted_ca,
                        "PROFESSIONNEL" => $_SESSION['client']['pro'],
                        "JESUISPRO" => $je_suis_pro,
		
						// KEMPF : Données nécessaires pour la fonction AJAX de demande annulée
						
						"CIVILITE_CLIENT2" => ($_SESSION['client']['civilite']),
						"NOM_CLIENT2" => ($_SESSION['client']['nom']),
						"PRENOM_CLIENT2" => ($_SESSION['client']['prenom']),
						"EMAIL_CLIENT" => $_SESSION['client']['mail'],
						"ID_TRAJET_DEPART" => $_SESSION['trajet']['depart'],
						"ID_TRAJET_DEST" => $_SESSION['trajet']['dest'],
						"TRAJET_EST_SIMPLE" => $_SESSION['trajet']['type_trajet'],
						"PRIX_TRAJET" => ($prix + $supplement_res_der_min2),
		
						/***
						 * Cas rajout d'un passager
						 * 
						 *  * */
						"TXT_AUTRE_PASSAGER" => $_SESSION['chckPassagerDifferent'],
		
						"TITRE_AUTRE_PASSAGER" => $titre_autre_passager,
						"NOM_AUTRE_PASSAGER" => $nom_autre_passager,
						"INDICATIF_TEL_AUTRE_PASSAGER" => $indicatif_tel_autre_passager,
						"TEL_PORT_AUTRE_PASSAGER" => $tel_port_autre_passager,

						
						"TXT_NOM_AUTRE_PASSAGER" =>	$_SESSION['txtNom'],
						/*"TXT_INDICATIF_TEL_AUTRE_PASSAGER" => $_SESSION['lstIndicatifTelephone'],*/
						"TXT_TEL_PORT_AUTRE_PASSAGER" => $_SESSION['txtPortable'],
						"TXT_INDICATIF_TEL_AUTRE_PASSAGER" => $_SESSION['txtIndicatifPortable']
						)
				  );

		//die(
		//print("<PRE>").print_r($_SESSION).print("</PRE>")
		//);
		
		$tpl->parse("aeroport/reservation/index.html");
	}
	else
	{
		header('Location: ../index.html');
		exit();
	}
		
?>