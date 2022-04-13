<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="MatthieuJ">
    <base href="<?= $webRoot ?>">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="css/normalizer.css">
    <link rel="stylesheet" href="css/main.css">
    <?= $styles ?>
</head>
<body>

<header style="border: 2px solid blue">
    <nav>
        <a href="">
            <img src="" alt="">
        </a>
        <p>
            <a href="">facebook</a>
            <a href="">twitter</a>
        </p>
        <button>hamburger</button>
        <ul>
            <li>menu 1</li>
            <li>menu 2</li>
            <li>menu 3</li>
            <li>menu 4</li>
        </ul>
    </nav>
</header>

<section style="border: 2px solid black">
    <img src="" alt="">
    <div>
        <h1>mon site a moi</h1>
        <form action="">
            <input type="email">
            <p>inscription<a href="">sign in</a></p>
        </form>
        <a href="">contact me</a>
    </div>
</section>
<section style="border: 2px solid red">
    <img src="" alt="">
    <img src="" alt="">
    <img src="" alt="">
    <img src="" alt="">
    <img src="" alt="">
</section>
<main style="border: 2px solid blue">
        <!-- CONTENT -->
        <?= $content ?>
</main>

<footer class="main-footer">
    footer
</footer>
</body>
</html>
