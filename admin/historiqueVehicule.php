<?php
	include("verifAuth.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="style.css" rel="stylesheet" type="text/css" /> 
</head>
<body>
<?php 
function execution($req)
{	
	if($_SERVER["SERVER_ADDR"]=="192.168.3.2") // si localhost
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
	
	$res = mysql_query($req,$c);
	mysql_close($c);
	return $res;
}
?>

<div style="width:100%;text-align:center" id='vehicule'>
	<br />
        <select size="1" id="lstVehicule" style="width:200px;" onchange='afficheInfoVehicule()'>
        	<option></option>
	        <?php 
	            $req = "select * from aeroport_vehicule where id_vehicule!=6";
	            $res = execution($req);
	            $nb = mysql_num_rows($res);
	
	             while ($r = @mysql_fetch_assoc($res)){ 
	                 $id_vehicule = $r['id_vehicule'];
	                 $libelle = $r['libelle'];		
	            
	        ?>
	        <option value="<?php echo $id_vehicule; ?>"><?php echo strtolower($libelle); ?></option>
	        <?php 
	             }
	        ?>
        </select>
        <br /><br /><br />
        <div id='graphes' style='text-align:center;width:900px;margin-left:auto;margin-right:auto'>
        </div>
</div>

<script type='text/javascript'>
var graphe = document.getElementById('graphes');
var lst = document.getElementById('lstVehicule');

function afficheInfoVehicule()
{
	var param = "action=statVehicule&id="+lst.options[lst.selectedIndex].value;
	ajax('./php/traitementAjax.php',param,'statVehicule');
}

/** FONCTION AJAX **/
function ajax(url,param,choix)
{
	var httpRequest = false;
	
	if (window.XMLHttpRequest) {
		httpRequest = new XMLHttpRequest();
		if (httpRequest.overrideMimeType) {
			httpRequest.overrideMimeType('text/xml');
		}
	}
	else if (window.ActiveXObject) { // IE
		try {
			httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e) {
			try {
				httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}
		}
	}
	
	if (!httpRequest) {
		alert('Abandon :( Impossible de creer une instance XMLHTTP');
		return false;
	}
	httpRequest.onreadystatechange = function() { traiter(httpRequest,choix); };
	httpRequest.open("POST",url,true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=iso-8859-15');
	httpRequest.send(param);
}

function traiter(httpRequest,choix)
{
	
	if (httpRequest.readyState == 4)
	{
		if (httpRequest.status == 200) 
		{
			graphe.innerHTML = httpRequest.responseText;
		}
		else
		{
		}
	}
	else
	{

	}
}
/** FIN FONCTION AJAX **/
</script>

</body>
</html>