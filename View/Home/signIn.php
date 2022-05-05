<?php $this->styles = '
    <link rel="stylesheet" href="css/Home/mobile.css">
    <link rel="stylesheet" href="css/Home/tablet.css" media="screen AND (min-width: 600px)">
    <link rel="stylesheet" href="css/Home/desktop.css" media="screen AND (min-width: 1024px)">
    '; ?>
<?php $this->title = "Connexion"; ?>

<section class="login">
    <form class="login-form" action="">
        <div class="img-form-login">
            <img src="media/icons-utilisateur.png" alt="logo">
        </div>
        <h1>Connexion</h1>
        <label for="username">
            <i class="fa fa-user"></i><input name="username" type="text" placeholder="Nom d'utilisateur">
        </label>
        <label for="password">
            <i class="fa fa-lock"></i><input name="password" type="password" placeholder="Mot de passe">
        </label>
        <div>
            <button class="btn-form" type="submit">Se connecter</button>
        </div>
        <a href="Home/forgotPassword">Mot de passe oublié ?</a>
    </form>
</section>


