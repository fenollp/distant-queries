<?php
	/********************************************************************************
	 * @author	Pierre Fenoll aka radioxid - Contact at zmindster at gmail dot com
	 * @date	25/02/2009

	 * @proto	array distant_queries( string $pathToWhereToPOST, array $sql_queries, string $login_POST, string $password_POST )

	 * @desc	effectut des mysql_query en POST sur un serveur distant
	 * @samp	print_r( distant_queries('http://www.perdu.com/distant.php', array(entier => "requète SQL")) );

	 * @comm	Nécessite PHP4+
	********************************************************************************/

function distant_queries(
	$sql_queries,   //array envoyé contenant les requètes et commandes...

	/// ASSIGNEZ UNE VALEUR PAR DÉFAUT À CETTE VARIABLE 
	 // ET VOUS N'AUREZ PLUS QU'UN PARAMÈTRE À DONNER À LA FONCTION !!!
	 // ... Elle deviendra facultative. Voicit comment faire:
	 // $pathToWhereToPOST = 'http://localhost/distant/distant.php', et vous voilà débarrassé
	$pathToWhereToPOST,     //URL du script distant

	$login_POST = 'login',  //login donné en POST
	$password_POST = 'password'    //mot de passe donné en POST
)
{
////////////////////////////////  FUNCTIONS  ///////////////////////////////////
// Rétroconpatibilité: PHP4
	if ( !function_exists('http_build_query') )
	{
		function http_build_query( $data, $prefix = '', $sep = '', $key = '' )
		{
			$ret = array();
			foreach ( (array)$data  as  $k => $v )
			{
				if ( is_int($k) && $prefix != null )
					$k = urlencode($prefix . $k);
				if ( !empty($key) || ($key === 0) )
					$k = $key.'['.urlencode($k).']';
				if ( is_array($v) || is_object($v) )
					array_push($ret, http_build_query($v, '', $sep, $k));
				else
					array_push($ret, $k.'='.urlencode($v));
			}
			if ( empty($sep) )
				$sep = ini_get('arg_separator.output');
			return implode($sep, $ret);
		}
	}
////////////////////////////////////////////////////////////////////////////////

//////////////////////////  ENVOI | RÉCEPTION DU POST  /////////////////////////
	$resultats_du_post = file_get_contents(
		$pathToWhereToPOST,
		false,
		stream_context_create(
			$opts = array(
				'http' => array(
					'user_agent' => 'SQL restrics bypasser',	//qui je suis (si cela peut servir: statistiques, ...)
					'method' => 'POST',
					'header' => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query(
						array_merge(
							array(
								'log' => $login_POST,		// POST login
								'pwd' => $password_POST,	// POST password
							),
							$sql_queries,		//queries
							array(
								'foo' => 'bar'	//var de test
							)
						)
					)
				)
			)
		)
	);
////////////////////////////////////////////////////////////////////////////////

////////////////////////  TRAITEMENT DE LA RÉCEPTION  //////////////////////////
	if ( preg_match_all('#(.+)#', $resultats_du_POST, $lignes) )
		// Pallie l'option m des regex PCRE
		$lignes = $lignes[1];
	else
		return false;   //la fonction retourne false quand le script distant ne répond pas
	
	foreach ( $lignes  as  $k => $ligne )
	{
		if ( !empty($ligne) )
		{
			// les différents types de réponse:
			/// le bool(...)
			if ( preg_match('#^bool\((.+)\)$#', $ligne, $bool_ligne) )
				$ligne = ( $bool_ligne[1] === 'true' ) ? true : false ;

			/// le mysql result(...)
			elseif ( preg_match('#^mysql result\((.+)\)$#', $ligne, $mysql_result__ligne) )
				$ligne = unserialize( rawurldecode($mysql_result__ligne[1]) );

			/// le dump(...)
			elseif ( preg_match('#^dump\((.+)\)$#', $ligne, $dump_ligne) )
				$ligne = unserialize( rawurldecode($dump_ligne[1]) );

			else { }	// et les erreurs, affichées telles qu'elles.

			$dial[$k]['question'] = $sql_queries[$k];
			$dial[$k]['reponse']  = $ligne;
		} else { }
	}
////////////////////////////////////////////////////////////////////////////////
	return $dial;
}
?>
