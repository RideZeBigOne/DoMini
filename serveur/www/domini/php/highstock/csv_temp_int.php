<?php // content="text/plain; charset=utf-8"
	include("../../infos/config.inc.php"); // on inclu le fichier de config
	$filename = "../../csv/temp_int.csv";
	
	@mysql_connect($host,$login,$passe) or die("Impossible de se connecter � la base de donn�es");
	@mysql_select_db("$bdd") or die("Impossible de se connecter � la base de donn�es");

	// on compte le nombre de ligne pour les donn�es des relev�s int�rieurs
	// on pr�pare la requ�te
	// on cr�e une table temporaire avec tous les champs n�cessaires pour extraire les tableaux pour JPGRAPH
	$SQL="SELECT DATE_FORMAT(date_time, '%d-%m-%Y') AS DATE, DATE_FORMAT(date_time, '%H:%i:%s') AS Heure,ana1, ana2, ana3, ana4 
	FROM analog2
	WHERE date_time <=  NOW( ) AND date_time >= SUBDATE(NOW(),360) 
	ORDER BY date_time"; 

	// on execute la requete
	$RESULT = @mysql_query($SQL);
	$n=0;

	// fetch a row and write the column names out to the file
	$row = mysql_fetch_assoc($RESULT);
	$line = "";
	$comma = "";
	$fp = fopen($filename, "w");

	foreach($row as $name => $value) {
		$line .= $comma .  str_replace('"', '""', $name) ;
		$comma = ";";
	}
	$line .= "\n";
	fputs($fp, $line);

	// remove the result pointer back to the start
	mysql_data_seek($RESULT, 0);

	// and loop through the actual data
	while($row = mysql_fetch_assoc($RESULT)) {
	   
		$line = "";
		$comma = "";
		foreach($row as $value) {
			$line .= $comma . str_replace('"', '""', $value);
			$comma = ";";
		}
		$line .= "\n";
		fputs($fp, $line);
	}

	fclose($fp);
		 
		 
		 
	echo"CSV temp�rature int�rieure export�.<br>";

	// // on execute la requete
	$RESULT = @mysql_query($SQL);
	//on quite la session mysql
	mysql_free_result($RESULT) ;
	mysql_close();

?>