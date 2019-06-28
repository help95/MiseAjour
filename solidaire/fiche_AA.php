<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel='stylesheet' href='./css/style.css' />
		<title>Alsace Solidaire - Alsace Avenir</title>
	</head>
<body>
	<img src='./img/background.png' alt='bg' id='bgBody' style='height:1200px;' />
	<div id='contenu'>
		<table align='center'>
			<tr>
				<td style='width:800px'>
					<!--<img src='./img/logos/logo-alsace-navette.png' alt='logo' width='70' />
					<img src='./img/titres/titre-alsace-navette.png' />-->
				<?php
					if(isset($_GET['lang']))
					{
				?>
						<img src='./img/logoSolidaire.jpg' width="64" style='margin-left:6px;float:left;cursor:pointer;' border='0' onclick='window.location.href="./index.php?lang=<?php echo $_GET['lang']; ?>"' />
				<?php 
					}
					else
					{
				?>
						<img src='./img/logoSolidaire.jpg' width="64" style='margin-left:6px;float:left;cursor:pointer;' border='0' onclick='window.location.href="./index.php"' />
				<?php
					}
				?>
					<img src='./img/titres/titre-alsace-navette.png' />
					<img src='./img/titres/banniere-animee.gif' width='569' />
				</td>
			</tr>
		</table>
		<div style='width:770px;height:773px;font-size:12pt;background:url(./img/fiche/ficheaa.png) no-repeat;margin-left:auto;margin-right:auto;text-align:left;padding-top:230px;'>
		<div style='margin-left:100px;width:400px;float:left;margin-right:50px;'>
		<?php
			if(isset($_GET['lang']))
			{
				if($_GET['lang']=="fr")
				{
				?>
				<div class='titre'>Nom</div>
				<div class='contenuText'>Alsace-avenir</div>
				<div class='titre'>Date de création</div>
				<div class='contenuText'>Mars 2010</div>
				<div class='titre'>Cible</div>
				<div class='contenuText'>Les citoyens alsaciens</div>
				<div class='titre'>Moyens mis en oeuvre</div>
				<div class='contenuText'>Site internet</div>
				<div class='contenuText'>Flyers</div>
				<div class='contenuText'>Affiches</div>
				<div class='contenuText'>Facebook</div>
				<div class='titre'>Services mis en place</div>
				<div class='contenuText'>Forum</div>
				<div class='divLien'><a href='http://alsace-avenir.eu/forum/' class='lien' style='color:#9F3D8A' target='blank'>www.alsace-avenir.net</a></div>
				<?php
				}
				else if($_GET['lang']=="en")
				{
				?>
				<div class='titre'>Name</div>
				<div class='contenuText'>Alsace-avenir</div>
				<div class='titre'>Creation Date</div>
				<div class='contenuText'>March 2010</div>
				<div class='titre'>Target</div>
				<div class='contenuText'>Alsatian citizens</div>
				<div class='titre'>Ressources deployed</div>
				<div class='contenuText'>Website</div>
				<div class='contenuText'>Flyers</div>
				<div class='contenuText'>Posters</div>
				<div class='contenuText'>Facebook</div>
				<div class='titre'>Services established</div>
				<div class='contenuText'>Forum</div>
				<div class='divLien'><a href='http://alsace-avenir.eu/forum/' class='lien' style='color:#9F3D8A' target='blank'>www.alsace-avenir.net</a></div>
				<?php
				}
				else if($_GET['lang']=="ger")
				{
				?>
				<div class='titre'>Name</div>
				<div class='contenuText'>Alsace-avenir</div>
				<div class='titre'>Creation Date</div>
				<div class='contenuText'>March 2010</div>
				<div class='titre'>Target</div>
				<div class='contenuText'>Alsatian citizens</div>
				<div class='titre'>Ressources deployed</div>
				<div class='contenuText'>Website</div>
				<div class='contenuText'>Flyers</div>
				<div class='contenuText'>Posters</div>
				<div class='contenuText'>Facebook</div>
				<div class='titre'>Services established</div>
				<div class='contenuText'>Forum</div>
				<div class='divLien'><a href='http://alsace-avenir.eu/forum/' class='lien' style='color:#9F3D8A' target='blank'>www.alsace-avenir.net</a></div>
				<?php
				}
			}
			else
			{
		?>
		<div class='titre'>Nom</div>
		<div class='contenuText'>Alsace-avenir</div>
		<div class='titre'>Date de création</div>
		<div class='contenuText'>Mars 2010</div>
		<div class='titre'>Cible</div>
		<div class='contenuText'>Les citoyens alsaciens</div>
		<div class='titre'>Moyens mis en oeuvre</div>
		<div class='contenuText'>Site internet</div>
		<div class='contenuText'>Flyers</div>
		<div class='contenuText'>Affiches</div>
		<div class='contenuText'>Facebook</div>
		<div class='titre'>Services mis en place</div>
		<div class='contenuText'>Forum</div>
		<div class='titre'>Caractéristiques</div>
		<div class='divLien'><a href='http://alsace-avenir.eu/forum/' class='lien' style='color:#9F3D8A' target='blank'>www.alsace-avenir.net</a></div>
		<?php
			}
		?>
		</div>
		<div id='menuLateral'>
			<a href='./fiche_RAJP.php?lang=<?php echo $_GET['lang']; ?>' onmouseover='this.style.color="white"' onmouseout='this.style.color="black"'>RAJ Plus</a>
			<div style='font-weight:bold;border-bottom:0.1px solid #555;clear:both;margin-bottom:10px;height:2px;width:170px'>&nbsp;</div>
			<a href='./fiche_RAJC.php?lang=<?php echo $_GET['lang']; ?>' onmouseover='this.style.color="white"' onmouseout='this.style.color="black"'>RAJ Campus</a>
			<div style='font-weight:bold;border-bottom:0.1px solid #555;clear:both;margin-bottom:10px;height:2px;width:170px'>&nbsp;</div>
			<a href='./fiche_RAJJ.php?lang=<?php echo $_GET['lang']; ?>' onmouseover='this.style.color="white"' onmouseout='this.style.color="black"'>RAJ Junior</a>
			<div style='font-weight:bold;border-bottom:0.1px solid #555;clear:both;margin-bottom:10px;height:2px;width:170px'>&nbsp;</div>
			<a href='./fiche_D2C.php?lang=<?php echo $_GET['lang']; ?>' onmouseover='this.style.color="white"' onmouseout='this.style.color="black"'>Droit2citoyen</a>
			<div style='font-weight:bold;border-bottom:0.1px solid #555;clear:both;margin-bottom:10px;height:2px;width:170px'>&nbsp;</div>
			<a href='./fiche_AA.php?lang=<?php echo $_GET['lang']; ?>' style='color:white'>Alsace-avenir</a>
			<div style='font-weight:bold;border-bottom:0.1px solid #555;clear:both;margin-bottom:10px;height:2px;width:170px'>&nbsp;</div>
			<a href='./fiche_WAG.php?lang=<?php echo $_GET['lang']; ?>' onmouseover='this.style.color="white"' onmouseout='this.style.color="black"'>Web Artist Gallery</a>
		</div>
		</div>
	</div>
<!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://alsace-navette.com/piwik/" : "http://alsace-navette.com/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://alsace-navette.com/piwik/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tag -->
</body>
</html>