<?php	//distant

//include la fonction fix_magic_quotes()
require_once(dirname(__FILE__).'/func.fix_magic_quotes.php');
	fix_magic_quotes();		// magic_quotes = OFF


if (
	$_POST['log'] == 'login' && $_POST['pwd'] == 'password'
	//|| $_SERVER['HTTP_USER_AGENT'] == 'SQL restrics bypasser'     //surtout ne pas le demander UNIQUEMENT
	//les login, password (et user agent) envoy�s lors du POST sont les m�mes ici
)		///	CHANGEZ LES !
{
	//header('Content-type: text/plain; charset=ISO-8859-1');
	//header('Content-type: text/plain; charset=utf-8');
/////////////////////////////////  FONCTIONS  //////////////////////////////////
	function dump_table( $table )
	{
		// on �labore une structure
		$structure  = PHP_EOL . '-- --------------------------------------------------------' . PHP_EOL;
		$structure .= '--' . PHP_EOL;
		$structure .= "-- Structure de la table '$table'" . PHP_EOL;
		$structure .= '--' . PHP_EOL . PHP_EOL;

		// et on dump le CREATE TABLE
		$pre_structure = mysql_fetch_array( mysql_query("SHOW CREATE TABLE $table") );
		$structure .= $pre_structure[1].';' . PHP_EOL . PHP_EOL;

		// on va ensuite chercher le contenu de la table
		$all_entry = mysql_query("SELECT * FROM $table") or die(mysql_error());
		$nb_entry  = mysql_num_rows($all_entry);

		if ( $nb_entry >= 1 )	//si la table n'est pas vide
		{
			// on pr�sente ce contenu
			$contenu  = '--' . PHP_EOL;
			$contenu .= "-- Contenu de la table '$table'" . PHP_EOL;
			$contenu .= '--' . PHP_EOL . PHP_EOL;
			$contenu .= "INSERT INTO $table VALUES" . PHP_EOL;	//INSERT INTO

			$nb_fields = mysql_num_rows( mysql_query("SHOW COLUMNS FROM $table") );   //on compte les champs

			while ( $one_entry = mysql_fetch_array($all_entry) )	//pour chaque ligne
			{ 
				$contenu .= '(';	//Chaque champ sur la m�me ligne

				for ( $i = 0 ; $i < $nb_fields ; $i++ )
					$contenu .= "'".addslashes($one_entry[$i])."', ";	//s�par�s par une virgule

				$contenu  = substr($contenu, 0, -2);	//remplac�e � la fin par la parenth�se fermante
				$contenu .= '),' . PHP_EOL;	//retour � la ligne et on recommence
			}

			$contenu = substr($contenu, 0, -2);	//� la fin de l'INSERT...
			$contenu .= ';' . PHP_EOL;	//... on place un point-virgule
		}

		// renvoie la structure et le contenu de la table
		return $structure . $contenu;
	}
////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////  VARIABLES  /////////////////////////////////

		$sql_host  = 'sql.free.fr';	// SQL host
		$sql_login = 'radioxid';	// SQL login
		$sql_pass  = 'masteryo';	// SQL password
		$sql_db    = $_POST[0];	// SQL database

////////////////////////////////////////////////////////////////////////////////

/////////////////////////////  SQL QUERIES DE BASE  ////////////////////////////
	//mysql_connect
	$mysql_connect = mysql_connect(
		$sql_host,
		$sql_login,
		$sql_pass
	) or die(mysql_error());

	//mysql_select_db
	$mysql_select_db = mysql_select_db(
		$sql_db
	) or die(mysql_error());
	var_dump($mysql_select_db);	//Le var_dump cr�e le return
////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////  MYSQL  ////////////////////////////////////
	foreach ( $_POST  as  $i => $query )
	{
		// on traite les $key = entier, sauf la $key = 0 (sql_db)
		if ( is_int($i) && $i !== 0 )
		{
		///////////////////////////////////  QUERIES  //////////////////////////////////
			// LA requ�te mysql
			$mysql_query = mysql_query($query) or mysql_error();    //PAS DE DIE()

			/// NORMAL QUERY
			if ( is_bool($mysql_query) )	//OK pour une resource 'normale'
				var_dump($mysql_query) . PHP_EOL;

			/// SELECT
			elseif ( get_resource_type($mysql_query) == 'mysql result' )
			//sinon on envoie du texte | http://www.php.net/manual/fr/resource.php
			{
				//array-ise le mysql result, le formate maison et le print en JSON
				while ( $data = mysql_fetch_assoc($mysql_query) )
				{
					foreach ( $data  as  $key => $fetched_data )
					{
						$array[$key][] = $fetched_data;
					}
				}
				//rawurlencode �vite les parenth�ses inopin�es
				print 'mysql result('. rawurlencode( serialize($array) ) .')' . PHP_EOL;
			} else { }
		////////////////////////////////////////////////////////////////////////////////
		}
		else	//quand commandes sp�ciales
		{
			if ( $i == 'dump' )	//cr�e un dump mysql
			{
			////////////////////////////////////  DUMP  ////////////////////////////////////
				foreach ( $query  as  $database => $tables )	// premi�re boucle des bases de donn�es � sauvegarder
				{
					if ( $database != $sql_db )	//si la BDD est diff�rente de la courante
					{
						$sql_db = $database;
						mysql_select_db($sql_db);	// on s�lectionne la bonne base de donn�e
					}

					// infos relatives � la database
					$structure_bdd  = '--' . PHP_EOL;
					$structure_bdd .= "-- SAUVEGARDE DE LA BASE DE DONNEE '$sql_db'" . PHP_EOL;
					$structure_bdd .= '--' . PHP_EOL;
					$structure_bdd .= '-- G�n�r� le : '.date('d F Y').' � '.date('H:i:s') . PHP_EOL;
					$structure_bdd .= '-- Timestamp : '.time() . PHP_EOL;
					$structure_bdd .= '--' . PHP_EOL . PHP_EOL;

					if ( empty($tables) )	//si on ne pr�cise pas les tables � dumper, on dump la base !
					{
						$dumps[$sql_db] = $structure_bdd;	//initialise avec les infos de la BDD

						// trouve toutes les tables de la database
						$all_table = mysql_query("SHOW TABLES") or die(mysql_error());

						while ( $une_table = mysql_fetch_array($all_table) )	//pour chaque table
							$dumps[$sql_db] .= dump_table($une_table[0]);	//on met toutes les tables en m�me temps
					}
					else	//quand $tables n'est pas vide
						foreach ( $tables as $table )	//on met une table par ligne du tableau
							$dumps[$sql_db][$table] = $structure_bdd . dump_table($table);
				}

				//et l'outpout en rawurlencode (pour �viter d'envoyer des parenth�ses)
				print 'dump('. rawurlencode( serialize($dumps) ) .')' . PHP_EOL;
			////////////////////////////////////////////////////////////////////////////////
			}
			elseif ( $i == '' )	//VOTRE partie
			{
			//////////////////////////////////  YOUR OWN  //////////////////////////////////
				// Mettez ce que vous y voulez !
			////////////////////////////////////////////////////////////////////////////////
			} else { }
		}
	}
////////////////////////////////////////////////////////////////////////////////
}
?>
