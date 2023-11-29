<?php

require '../vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    '565023feab57447c808621efa57f1512',
    'df726856a4f1434b8fd64adef18649e9',
    'https://cantio.live/spotify/callbk.php'
);

$state = $session->generateState();
$options = [
    'scope' => [
        'playlist-read-private',
        'user-read-private',
    ],
    'state' => $state,
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();


?>