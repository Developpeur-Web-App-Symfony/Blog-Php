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
        <label>
            <i class="fa fa-user"></i><input type="text" placeholder="Nom d'utilisateur">
        </label>
        <label>
            <i class="fa fa-envelope"></i><input type="text" placeholder="Email">
        </label>
        <label>
            <i class="fa fa-lock"></i><input type="password" placeholder="Mot de passe">
        </label>
        <label>
            <i class="fa fa-lock"></i><input type="password" placeholder="Confirmez le mot de passe">
        </label>
        <div>
            <button class="btn-form" type="submit">S'inscrire</button>
        </div>
        <a href="Home/signIn">Je possède déjà un compte</a>
    </form>
</section>


