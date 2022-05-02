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
        <div>
            <i class="fa fa-user"> Nom d'utilisateur</i><input type="text">
        </div>
        <div>
            <i class="fa fa-lock"> Mot de passe</i><input type="password">
        </div>
        <div>
            <button class="btn-form-contact" type="submit">Se connecter</button>
        </div>
        <a href="Home/forgotPassword">Mot de passe oublié ?</a>
    </form>
</section>


