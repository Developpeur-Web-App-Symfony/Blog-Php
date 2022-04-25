<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="MatthieuJ">
    <base href="<?= $webRoot ?>">
    <title><?= $title ?></title>
    <script src="https://kit.fontawesome.com/38412cb349.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/normalizer.css">
    <link rel="stylesheet" href="css/gabarit_mobile.css">
    <?= $styles ?>
</head>
<body>

<header>
    <nav>
        <a href="index.php">
            <span>JM Website</span>
        </a>
        <div class="follow-nav-logo">
            <a href="https://www.facebook.com/pages/category/Product-Service/JM-Websites-370052016940987/"><i class="fa-brands fa-facebook"></i></a>
            <a href=""><i class="fa-brands fa-instagram-square"></i></a>
        </div>
        <div class="nav-menu" role="navigation" aria-label="navi">
            <button id="btn-nav-menu"><i class="fa-solid fa-bars" id="barIcon"></i>
                <i class="fa fa-times" id="closeIcon"></i>
            </button>
            <ul id="nav-menu-list">
                <li><a href="Home">Accueil</a></li>
                <li><a href="">Blog Post</a></li>
                <li><a href="Home/contact">Contact</a></li>
                <li><a href="">Inscription/Connexion</a></li>
            </ul>
        </div>
    </nav>
</header>

<main>
        <!-- CONTENT -->
        <?= $content ?>
</main>

<footer class="main-footer">
    <a href="">Administration</a>
</footer>
<script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
<script src="js/headerNav.js"></script>
<?= $script ?>
</body>
</html>

