<?php
session_start();
require '../vendor/autoload.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();

$accessToken = $_SESSION['accessToken'];

// Fetch the saved access token from somewhere. A session for example.
$api->setAccessToken($accessToken);


$releases = $api->getNewReleases([
    'country' => 'us',
]);

foreach ($releases->albums->items as $album) {
    echo '<a href="' . $album->external_urls->spotify . '">' . $album->name . '</a> <br>';
    echo $album->images[0]->url;
}

?>