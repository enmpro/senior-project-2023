<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Music App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add custom styles here, if needed */
        body {
            padding-top: 56px; /* Adjust for fixed navbar height */
        }
        .homepage-section {
            padding: 60px 0;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">Your Music App</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#profile">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#community">Community</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#spotify">Spotify</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#events">Events</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-3">
    <div class="row g-5 row-cols-1 row-cols-md-2 ">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">User Profile</h5>
                    <p class="card-text">View and manage your user profile information.</p>
                    <a href="#" class="btn btn-primary">Go to Profile</a>
                </div>
            </div>
            
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Community Area</h5>
                    <p class="card-text">Connect with other music lovers in our community.

                    </p>
                    <a href="#" class="btn btn-primary">Go to Profile</a>
                </div>
            </div>
            
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Spotify Area</h5>
                    <p class="card-text">Connect your Spotify account for a personalized experience.</p>
                    <a href="#" class="btn btn-primary">Go to Profile</a>
                </div>
            </div>
            
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Events Area</h5>
                    <p class="card-text">Explore and join upcoming music events in your area.</p>
                    <a href="#" class="btn btn-primary">Go to Profile</a>
                </div>
            </div>
            
        </div>
    
    </div>
</div>

<!-- Footer -->
<footer class="bg-light text-center py-4 fixed-bottom">
    <p>&copy; 2023 Your Music App. All rights reserved.</p>
</footer>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
