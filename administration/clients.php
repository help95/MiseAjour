<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>

<title>infos client</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style.css" rel="stylesheet" type="text/css" > 

</head>

<body>

<div id="container">

	<div id="centre">
	
<?

// ouverture de la connexion et choix de la BD 
   
$connexion = mysql_connect('db922.1and1.fr', 'dbo206617947', 'D5ZEtV4h');
//$db = mysql_connect('localhost', 'root', '');
mysql_select_db('db206617947', $connexion);
//mysql_select_db('navette',$db);
$nom=$_GET["ID"];
$sql="SELECT  distinct nom,prenom,adresse,ville,mail,telephone,portable,cp FROM client where nom='".$nom."'";

$mysql_result3 = mysql_query($sql);
//prendre chaque rang�e

if ($ligne3 = mysql_fetch_array($mysql_result3)) 
{
    echo "<table>\n";
    echo "<thead><th>nom</th><th>prenom</th><th>adresse</th><th>cp</th><th>ville</th><th>mail</th><th>telephone</th><th>portable</th></thead>\n";
	do 
	{
        printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",  $ligne3["nom"],  $ligne3["prenom"] , $ligne3["adresse"] ,$ligne3["cp"],$ligne3["ville"], $ligne3["mail"], $ligne3["telephone"], $ligne3["portable"]);
	} 
    while ($ligne3 = mysql_fetch_array($mysql_result3));
    echo "</table>\n";
} 

mysql_close();

?>
</div>
	
</div>


	

</body>

</html>
