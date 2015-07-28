<?php
//Definicion a lo que queremos encontrar en Twitter

    $Tema="MovilidadQuito"; //Tema (en Pagina y Hashtag)
	$filtro = $_POST['Hashtag'];//Para encontrar una palabra especifica sobre el tema seleccionado
	//Definicion de las librerias a utilizar
include('TwitterAPIExchange.php');
require 'autoload.php';

use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;

//Con este comando iniciamos sesion en la nube Parse
ParseClient::initialize('zwEbj6LY9GgEXADHaVeusZWRdxYe0Vz0snI6XdqE', '1SrNArG69KAzaRMjc7KDM59g6Pfry4Pwx9aKMnzm', 'EcXNEgGMsCRFAHcg3oQhnzpAF71NBRx1ImEMpd9S');

//Con este comando ingresamos en un vector los datos de la aplicacion de Twitter
$settings = array(
    'oauth_access_token' => "2921608641-mkfKKvSfPe7zsjRTHsZXQAnYJWkaAky5qWT6NW1",
    'oauth_access_token_secret' => "9388TOeqazpRHViCjOcLVxDXP4nqzKAenfDWP1fACc9fJ",
    'consumer_key' => "OynQacY2jmyfuWHvL9kxz5mhB",
    'consumer_secret' => "4jdnWIz8TpKnjU5HKB3915kPtJ4Oban7ZxM8cFjAcjlTQxrbqm"
);
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$variableGET = '?q=%23'.$Tema.'&result_type=recent';  

$requestMethod = 'GET';

$twitterhsh = new TwitterAPIExchange($settings);
$RestTwitterhsh= $twitterhsh->setGetfield($variableGET)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();
					 
$arrayTwitterhsh = json_decode($RestTwitterhsh,TRUE);

foreach ($arrayTwitterhsh['statuses'] as $statuses => $values)
{
	
	$Twitterhsh = new ParseObject("TwitterHshtg");
	
	
	$Twitterhsh->set("FechaPublicacion", $values['created_at']);
	$Twitterhsh->set("Tweet", $values['text']);
    $Twitterhsh->set("ReTweets", $values['retweet_count']);
	
	$Twitterhsh->save();
}

foreach ($arrayTwitterhsh['statuses'] as $statuses => $values)
{
	$Twitterfh1 = new ParseObject("FiltroHshtg");
	
	if(ereg($filtro, $values['text']))
	{
	$Twitterfh1->set("FechaPublicacion", $values['created_at']);
	$Twitterfh1->set("Tweet", $values['text']);
    $Twitterfh1->set("ReTweets", $values['retweet_count']);
	$Twitterfh1->save();
	}	
}

echo 'Conexion con Twitter realizada ';

?>