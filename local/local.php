<?php	//local

//include la fonction distant_queries()
require_once(dirname(__FILE__).'/func.distant_queries.php');


//set some vars
	$pathToWhereToPOST = 'http://radioxid.free.fr/HIDDEN/distant.php';	// script DISTANT
	$log = 'login';	// POST login
	$pwd = 'password';	// POST password

//exemple de query:
	$question = array(
		0 => 'radioxid',	//database
		1 => "SELECT * FROM jeux_video",	//mysql_query
		2 => "INSERT INTO jeux_video VALUES ('', 'PacMan', 'sega', '15', '1')",	//mysql_query
		 // ...	---> mysql_query

		'dump' => array('radioxid' => array('jeux_video'))    //mysql dump
		 /*
			'database' => array('table1', 'table2'),	//sort un array des dumps des tables choisies
			'autre_database'	//sort un dump de la base
		 */
	);

	$reponse = distant_queries($question, $pathToWhereToPOST, false, $log, $pwd);
	/// Vous pouvez ignorer les derniers paramètres passés à la fonction distant_queries() 
	 // si vous les avez stipulés par défaut dans func.distant_queries.php

	//var_dump($reponse);   //debug
	print_r ($reponse);

/********************************************************************************
	La table jeux_video ici => ./../distant/jeux_video.sql
		ou là => http://radioxid.free.fr/HIDDEN/SdZ/jeux_video.sql

Retourne:
Array	// $reponse
(
	[0] => Array	//key zero :: select db
		(
			[question] => test	//database
			[reponse] => 1	//true au mysql_select_db
		)

	[1] => Array	//key 1
		(
			[question] => SELECT * FROM jeux_video	//query
			[reponse] => Array	//retour formate maison
				(
					[nom] => Array
						(
							[0] => Super Mario Bros
							[1] => Sonic
							[2] => Zelda : ocarina of time
							[3] => Mario Kart 64
							[4] => Super Smash Bros Melee
							...
						)
				)
		)
)
********************************************************************************/
?>

