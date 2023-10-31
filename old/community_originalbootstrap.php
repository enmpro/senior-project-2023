<?php
require_once 'login.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: main.php');
    exit;
}

$username = $_SESSION['user_id'];


?>


<!DOCTYPE html>
<html data-bs-theme="light" style="font-size: 14px;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Home - Brand</title>
    <script>
        (function() {

            // JavaScript snippet handling Dark/Light mode switching

            const getStoredTheme = () => localStorage.getItem('theme');
            const setStoredTheme = theme => localStorage.setItem('theme', theme);
            const forcedTheme = document.documentElement.getAttribute('data-bss-forced-theme');

            const getPreferredTheme = () => {

                if (forcedTheme) return forcedTheme;

                const storedTheme = getStoredTheme();
                if (storedTheme) {
                    return storedTheme;
                }

                const pageTheme = document.documentElement.getAttribute('data-bs-theme');

                if (pageTheme) {
                    return pageTheme;
                }

                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            const setTheme = theme => {
                if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-bs-theme', theme);
                }
            }

            setTheme(getPreferredTheme());

            const showActiveTheme = (theme, focus = false) => {
                const themeSwitchers = [].slice.call(document.querySelectorAll('.theme-switcher'));

                if (!themeSwitchers.length) return;

                document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                    element.classList.remove('active');
                    element.setAttribute('aria-pressed', 'false');
                });

                for (const themeSwitcher of themeSwitchers) {

                    const btnToActivate = themeSwitcher.querySelector('[data-bs-theme-value="' + theme + '"]');

                    if (btnToActivate) {
                        btnToActivate.classList.add('active');
                        btnToActivate.setAttribute('aria-pressed', 'true');
                    }
                }
            }

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                const storedTheme = getStoredTheme();
                if (storedTheme !== 'light' && storedTheme !== 'dark') {
                    setTheme(getPreferredTheme());
                }
            });

            window.addEventListener('DOMContentLoaded', () => {
                showActiveTheme(getPreferredTheme());

                document.querySelectorAll('[data-bs-theme-value]')
                    .forEach(toggle => {
                        toggle.addEventListener('click', (e) => {
                            e.preventDefault();
                            const theme = toggle.getAttribute('data-bs-theme-value');
                            setStoredTheme(theme);
                            setTheme(theme);
                            showActiveTheme(theme);
                        })
                    })
            });
        })();
    </script>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=ABeeZee&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/bs-theme-overrides.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/Banner-Heading-Image-images.css">
    <link rel="stylesheet" href="assets/css/Gamanet_Pagination_bs5.css">
    <link rel="stylesheet" href="assets/css/Hero-Features-icons.css">
    <link rel="stylesheet" href="assets/css/pikaday.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top portfolio-navbar gradient navbar-dark" style="background: #1f262d;font-family: Roboto, sans-serif;font-size: 14px;border-style: none;box-shadow: 0px 0px;">
        <div class="container"><a class="navbar-brand logo" href="#" style="font-size: 30px;color: #a7cb3d;font-weight: bold;">CANTIO</a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navbarNav"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="homepage.php" style="color: #a7cb3d;opacity: 0.40;">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" style="color: #a7cb3d;opacity: 0.40;">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" style="color: rgb(167,203,61);opacity: 0.40;">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="community.php" style="color: #a7cb3d;opacity: 0.40;">Community</a></li>
                </ul>
            </div>
            <div class="theme-switcher dropdown"><a class="dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" href="#" style="color: rgba(255,255,255,0.75);"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-sun-fill mb-1">
                        <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path>
                    </svg></a>
                <div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center" href="#" data-bs-theme-value="light"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-sun-fill opacity-50 me-2">
                            <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path>
                        </svg>Light</a><a class="dropdown-item d-flex align-items-center" href="#" data-bs-theme-value="dark"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-moon-stars-fill opacity-50 me-2">
                            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"></path>
                            <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"></path>
                        </svg>Dark</a><a class="dropdown-item d-flex align-items-center" href="#" data-bs-theme-value="auto"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-circle-half opacity-50 me-2">
                            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
                        </svg>Auto</a></div>
            </div>
            <div>
                <form method="post" action="user_logout.php">
                    <button type="submit" name="logout">Log Out</button>
                </form>
            </div>
        </div>
    </nav>
    <main class="page lanidng-page">
        <section class="portfolio-block block-intro" style="font-family: Lato, sans-serif;">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card" style="border-style: none;"><img class="card-img-top w-100 d-block" src="assets/img/testPic.png" style="border-style: hidden;">
                            <div class="card-body" style="border-width: 1px;border-style: none;border-right-style: solid;border-right-color: rgba(167,203,61,0.1);border-bottom-style: ridge;border-bottom-color: rgba(167,203,61,0.6);border-left-style: solid;border-left-color: rgba(167,203,61,0.1);">
                                <h4 class="card-title" style="font-weight: bold;font-family: Roboto, sans-serif;">Emily</h4>
                                <p class="card-text">Nullam id dolor id nibh ultricies vehicula ut id elit. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p><button class="btn btn-primary border rounded-0" data-bss-disabled-mobile="true" data-bss-hover-animate="jello" type="button" style="opacity: 0.80;background: rgb(167,203,61);border-style: none;color: rgb(4,4,4);">Like</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card" style="opacity: 1;border: 1px none #373737 ;border-bottom-style: none;"><img class="card-img-top w-100 d-block" style="font-family: Roboto, sans-serif;border-style: none;" src="assets/img/testPic.png" width="417" height="100">
                            <div class="card-body" style="border-style: none;border-bottom: 1px dashed rgba(167,203,61,0.5) ;">
                                <h4 class="card-title" style="font-family: Roboto, sans-serif;border-style: none;font-weight: bold;">Emily</h4>
                                <p class="card-text" style="font-family: Lato, sans-serif;border-style: none;">Nullam id dolor id nibh ultricies vehicula ut id elit.&nbsp;</p><button class="btn btn-primary border rounded-0" data-bss-hover-animate="jello" type="button" style="border-style: none;background: rgb(167,203,61);color: rgb(0,0,0);opacity: 0.80;">Load</button>
                            </div>
                        </div>
                        <div class="card" style="opacity: 0.80;margin-top: 15px;backdrop-filter: opacity(1);border: 1px none #ea0b0b ;border-top-style: none;border-right-style: none;border-bottom: 1px none #a7cb3d ;border-left-style: none;"><img class="card-img-top w-100 d-block" style="font-family: Roboto, sans-serif;border-style: none;" src="assets/img/testPic.png" width="417" height="100">
                            <div class="card-body" style="border-style: none;border-bottom: 1px dashed rgba(167,203,61,0.5) ;">
                                <h4 class="card-title" style="font-family: Roboto, sans-serif;border-style: none;font-weight: bold;">Emily</h4>
                                <p class="card-text" style="font-family: Lato, sans-serif;border-style: none;">Nullam id dolor id nibh ultricies vehicula ut id elit. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p><button class="btn btn-primary border rounded-0" data-bss-hover-animate="jello" type="button" style="border-style: none;background: rgb(167,203,61);color: rgb(0,0,0);opacity: 0.80;">Load</button>
                            </div>
                        </div>
                        <div class="card" style="opacity: 0.80;margin-top: 15px;backdrop-filter: opacity(1);border: 1px none #ea0b0b ;border-top-style: none;border-right-style: none;border-bottom: 1px none #a7cb3d ;border-left-style: none;"><img class="card-img-top w-100 d-block" style="font-family: Roboto, sans-serif;border-style: none;" src="assets/img/testPic.png" width="417" height="100">
                            <div class="card-body" style="border-style: none;border-bottom: 1px dashed rgba(167,203,61,0.5) ;">
                                <h4 class="card-title" style="font-family: Roboto, sans-serif;border-style: none;font-weight: bold;">Emily</h4>
                                <p class="card-text" style="font-family: Lato, sans-serif;border-style: none;">Nullam id dolor id nibh ultricies vehicula ut id elit. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p><button class="btn btn-primary border rounded-0" data-bss-hover-animate="jello" type="button" style="border-style: none;background: rgb(167,203,61);color: rgb(0,0,0);opacity: 0.80;">Load</button>
                            </div>
                        </div>
                        <nav style="margin-top: 15px;margin-left: 103px;">
                            <ul class="pagination">
                                <li class="page-item" data-bss-hover-animate="rubberBand"><a class="page-link" aria-label="Previous" href="#"><span aria-hidden="true">«</span></a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item" data-bss-hover-animate="rubberBand"><a class="page-link" aria-label="Next" href="#"><span aria-hidden="true">»</span></a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <ul class="nav nav-tabs" role="tablist" style="opacity: 1;border-style: none;">
                                <li class="nav-item" role="presentation"><a class="nav-link active" role="tab" data-bs-toggle="tab" href="#tab-1">Top 10 Songs</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-bs-toggle="tab" href="#tab-2">Attending Events</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" role="tab" data-bs-toggle="tab" href="#tab-3">Matches</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" role="tabpanel" id="tab-1">
                                    <p style="font-family: Roboto, sans-serif;">Emily's top 10 songs</p>
                                    <ul class="list-group text-start" style="border-style: none;">
                                        <li class="list-group-item" style="border-style: none;">
                                            <picture><img width="100" height="100" style="padding-right: 0px;margin-right: 16px;"></picture><span>Ice Spice</span>
                                        </li>
                                        <li class="list-group-item" style="border-style: none;">
                                            <picture><img width="100" height="100" style="margin-right: 16px;"></picture><span>Ice Spice</span>
                                        </li>
                                        <li class="list-group-item" style="border-style: none;">
                                            <picture><img width="100" height="100" style="margin-right: 16px;"></picture><span>Ice Spice</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane" role="tabpanel" id="tab-2">
                                    <p>Content for tab 2.</p>
                                </div>
                                <div class="tab-pane" role="tabpanel" id="tab-3">
                                    <p>Content for tab 3.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="page-footer py-3 border-top" style="font-family: Roboto, sans-serif;border-style: none;">
        <div class="container my-4">
            <div class="links"><a href="#" style="opacity: 0.50;">Home</a><a href="#" style="opacity: 0.50;">About</a></div>
            <div class="social-icons"><a class="me-3" href="#" style="opacity: 0.50;"><i class="icon ion-social-facebook"></i></a><a class="me-3" href="#" style="opacity: 0.50;"><i class="icon ion-social-instagram-outline"></i></a><a class="me-3" href="#" style="opacity: 0.50;"><i class="icon ion-social-twitter"></i></a></div>
        </div>
    </footer>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/pikaday.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>