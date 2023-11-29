<?php
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    '565023feab57447c808621efa57f1512',
    'df726856a4f1434b8fd64adef18649e9',
    'https://cantio.live/spotify/callback.php'
);

$api = new SpotifyWebAPI\SpotifyWebAPI();

if (isset($_GET['code'])) {
    $session->requestAccessToken($_GET['code']);
    $api->setAccessToken($session->getAccessToken());

    print_r($api->me());
} else {
    $options = [
        'scope' => [
            'user-read-email',
        ],
    ];

    header('Location: ' . $session->getAuthorizeUrl($options));
    die();
}

?>