<?php $this->styles = '
    <link rel="stylesheet" href="css/Home/mobile.css">
    <link rel="stylesheet" href="css/Home/tablet.css" media="screen AND (min-width: 600px)">
    <link rel="stylesheet" href="css/Home/desktop.css" media="screen AND (min-width: 1024px)">
    '; ?>
<?php $this->title = "Inscription"; ?>

<section class="login">
    <form class="login-form" action="">
        <div class="img-form-login">
            <img src="media/icons-utilisateur.png" alt="logo">
        </div>
        <h1>Inscription</h1>
        <div>
            <i class="fa fa-user"> Nom d'utilisateur</i><input type="text">
        </div>
        <div>
            <i class="fa fa-envelope"> Email</i><input type="text">
        </div>
        <div>
            <i class="fa fa-lock"> Mot de passe</i><input type="password">
        </div>
        <div>
            <i class="fa fa-lock"> Entrez le mot de passe À nouveau</i><input type="password">
        </div>
        <div>
            <button class="btn-form-contact" type="submit">S'inscrire</button>
        </div>
        <a href="Home/signIn">Je possède déjà un compte</a>
    </form>
</section>


