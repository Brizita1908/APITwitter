<?php
//Definicion a lo que queremos encontrar en Twitter

    $Tema="MovilidadQuito"; //Tema (en Pagina y Hashtag)
	$filtro = $_POST['Pagina'];//Para encontrar una palabra especifica sobre el tema seleccionado
	
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



$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$variableGET = '?screen_name='.$Tema;  
 
$requestMethod = 'GET';

$twitter = new TwitterAPIExchange($settings);
$RestTwitter= $twitter->setGetfield($variableGET)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();
					 
$arrayTwitter = json_decode($RestTwitter);

for ( $i = 0 ; $i < 20 ; $i ++) 
{
	$Twitter = new ParseObject("TwitterPg");
	
	$Twitter->set("FechaPublicacion", $arrayTwitter[$i]->created_at);
	$Twitter->set("Lugar", $arrayTwitter[$i]->user->location);
	$Twitter->set("Usuario", $arrayTwitter[$i]->in_reply_to_screen_name);
	$Twitter->set("Tweet", $arrayTwitter[$i]->text);
    $Twitter->set("ReTweets", $arrayTwitter[$i]->retweet_count);
    $Twitter->set("PaginaTwitter", $arrayTwitter[$i]->user->name);
	
	$Twitter->save();
}

for ( $i = 0 ; $i < 20 ; $i ++) 
{
	$Twitterf = new ParseObject("FiltroPg");
	
	if(ereg($filtro, $arrayTwitter[$i]->text))
	{
	$Twitterf->set("FechaPublicacion", $arrayTwitter[$i]->created_at);
	$Twitterf->set("Lugar", $arrayTwitter[$i]->user->location);
	$Twitterf->set("Usuario", $arrayTwitter[$i]->in_reply_to_screen_name);
	$Twitterf->set("Tweet", $arrayTwitter[$i]->text);
    $Twitterf->set("ReTweets", $arrayTwitter[$i]->retweet_count);
    $Twitterf->set("PaginaTwitter", $arrayTwitter[$i]->user->name);
	
	$Twitterf->save();
	}
}

echo 'Conexion con Twitter realizada ';
	
?>