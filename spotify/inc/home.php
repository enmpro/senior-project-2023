<?php
// 
// This is Music Utopia Home Page
// User has not been authenticated and we need to allow him to request access
//
// Code by Hanbi Hanz Choi
//

// Include page header
include 'inc/html/header.php';
$REDIRECT_URI = "https://cantio.live/spotify/callback/index.php";
$CLIENT_ID = "446002b2d4434bff9fec8de86cee969d";
$CLIENT_SECRET = "645c16a2a1bd4a1990df607ccc7e1b17";
?>
<div class="container" id="home_container">
    <h1>Cantio</h1>
    <p><button onclick="userLogInRequest();">Log In User</button></p>
</div>

<script>
    // User log in request on button click
    const userLogInRequest = () => {
        let logInUri = 'https://accounts.spotify.com/authorize' +
            '?client_id=<?php echo $CLIENT_ID; ?>' +
            '&response_type=code' +
            '&redirect_uri=<?php echo $REDIRECT_URI; ?>' +
            '&scope=app-remote-control user-top-read user-read-currently-playing user-read-recently-played streaming app-remote-control user-read-playback-state user-modify-playback-state' +
            '&show_dialog=true';
        // Debug
        console.log(logInUri);
        
        // Open URL to request user log in from Spotify
        window.open(logInUri, '_self');
    }
</script>
<?php
// Include page footer
include 'inc/html/footer.inc.php';