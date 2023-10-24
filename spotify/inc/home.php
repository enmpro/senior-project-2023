<?php
// 
// This is Music Utopia Home Page
// User has not been authenticated and we need to allow him to request access
//
// Code by Hanbi Hanz Choi
//

// Include page header
include 'inc/html/header.php';
?>
<div class="container" id="home_container">
    <h1>Cantio</h1>
    <p><button onclick="userLogInRequest();">Log In User</button></p>
</div>

<script>
    // User log in request on button click
    const userLogInRequest = () => {
        let logInUri = 'https://accounts.spotify.com/authorize' +
            '?client_id=<?php echo $__app_client_id; ?>' +
            '&response_type=code' +
            '&redirect_uri=<?php echo $__redirect_uri; ?>' +
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