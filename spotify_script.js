
document.addEventListener("DOMContentLoaded", function() {
    fetchUserData();
});

function authenticateWithSpotify() {
    window.location.href = 'spotify_auth.php';
}

function fetchUserData() {
    fetch('spotify_userdata.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('spotifyData').textContent = JSON.stringify(data, null, 2);
        });
}

document.addEventListener("DOMContentLoaded", function() {
    fetchUserData();
});