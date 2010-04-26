<?php
	/********************************************************************************
	 * @author	Pierre Fenoll aka radioxid - Contact at zmindster at gmail dot com
	 * @date	27/07/2009

	 * @proto	array distant_queries( string $pathToWhereToPOST, array $sql_queries, string $login_POST, string $password_POST )

	 * @desc	effectut des mysql_query en POST sur un serveur distant avec cURL
	 * @samp	print_r( distant_queries('http://www.perdu.com/distant.php', array(entier => "requète SQL")) );

	 * @comm	Nécessite PHP4+ & cURL extension
	********************************************************************************/

function distant_queries(
	$sql_queries,   //array envoyé contenant les requètes et commandes...

	/// ASSIGNEZ UNE VALEUR PAR DÉFAUT À CETTE VARIABLE 
	 // ET VOUS N'AUREZ PLUS QU'UN PARAMÈTRE À DONNER À LA FONCTION !!!
	 // ... Elle deviendra facultative. Voicit comment faire:
	 // $pathToWhereToPOST = 'http://localhost/distant/distant.php', et vous voilà débarrassé
	$pathToWhereToPOST,     //URL du script distant
			 
	$with_ssl = true,	//avec ou sans HTTP Secure Socket Layer (SSL)
	$login_POST = 'login',  //login donné en POST
	$password_POST = 'password'    //mot de passe donné en POST
)
{
////////////////////////////////  FUNCTIONS  ///////////////////////////////////
/// PHP<5 paliative function
	if ( !function_exists('curl_setopt_array') )
	{
		function curl_setopt_array( &$ch, $curl_options )
		{
			foreach ($curl_options as $option => $value)
				if ( !curl_setopt($ch, $option, $value) )
					return false;
			return true;
		}
	}

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

////////////////////////////////  POST QUERY  //////////////////////////////////
	$POST_query = http_build_query(
		array_merge(
			array(
				'log' => $login_POST,		// POST login
				'pwd' => $password_POST,	// POST password
			),
			$sql_queries,		//queries
			array(
				'foo' => 'bar'	//mettez ce que bon vous semble
			)
		)
	);
////////////////////////////////////////////////////////////////////////////////

////////////////////////  ENVOI | RÉCEPTION DU POST SSL  ///////////////////////
	$options = array(
		CURLOPT_RETURNTRANSFER => true,	//demande de retourner un contenu
		CURLOPT_FOLLOWLOCATION => true, //suit les redirections

		/// DEBUG
		//CURLOPT_VERBOSE        => true,

		//qui je suis (si cela peut servir: statistiques, ...)
		CURLOPT_USERAGENT      => 'SQL restrics bypasser',

		/// POST
		CURLOPT_POST           => true,	//on fait bien un POST
		CURLOPT_POSTFIELDS     => $POST_query,	//avec ces variables
	);

	if ( $with_ssl )	/// HTTPS | HTTP Secure Socket Layer
	{
		//pour faire du SSL, il suffit d'ajouter un 's' à 'http'
		preg_match('#^[^:/]+(:/+.+)$#', $pathToWhereToPOST, $preg_path);
		$pathToWhereToPOST = 'https' . $preg_path[1];

		$options_ssl = array(
			CURLOPT_SSL_VERIFYHOST => 2,	//défaut
			CURLOPT_SSL_VERIFYPEER => false,	//ne verifie pas le certificat
			//CURLOPT_PORT => 443,    //port HTTPS
			// Vous pouvez utiliser CURLAUTH_BASIC, CURLAUTH_DIGEST, CURLAUTH_GSSNEGOTIATE, 
			 // CURLAUTH_NTLM, CURLAUTH_ANY, et CURLAUTH_ANYSAFE.
			 // CURLAUTH_ANY regroupe CURLAUTH_BASIC | CURLAUTH_DIGEST | CURLAUTH_GSSNEGOTIATE | CURLAUTH_NTLM
			 // CURLAUTH_ANYSAFE regroupe CURLAUTH_DIGEST | CURLAUTH_GSSNEGOTIATE | CURLAUTH_NTLM
			//CURLOPT_HTTPAUTH => CURLAUTH_ANY | CURLAUTH_ANYSAFE,    //ici on englobe tout
		);

		//ajout des options SSL aux casuales
		$options = array_merge($options, $options_ssl);
	}

	$options[CURLOPT_URL] = $pathToWhereToPOST;	//URL HTTP normal où l'on POST
		
	$ch = curl_init();    //démarre curl
	curl_setopt_array($ch, $options);     //set les options
	$resultats_du_POST = curl_exec($ch);  //récupère le résultat

	/// DEBUG
	/*//$err['s']     = $resultats_du_POST;      //source
	$err['no']    = curl_errno($ch);	//code d'erreur cURL
	$err['msg']   = curl_error($ch);	//message d'erreur cURL
	$err['more']  = curl_getinfo($ch);    //more
	print_r($err);*/

	curl_close($ch);      //arrète curl
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

