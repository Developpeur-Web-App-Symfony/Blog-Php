<?php $this->styles = '
    <link rel="stylesheet" href="css/Home/mobile.css">
    <link rel="stylesheet" href="css/Home/tablet.css" media="screen AND (min-width: 600px)">
    <link rel="stylesheet" href="css/Home/desktop.css" media="screen AND (min-width: 1024px)">
    '; ?>
<?php $this->script = ''; ?>
<?php $this->title = "Contactez-nous"; ?>

<section class="banner">
    <h1>Contact</h1>
    <p>Nous sommes présent pour répondre à toutes vos questions et pouvons intervenir en cas de besoins spécifiques, si vous le souhaitez, n’hésitez plus, notre équipe est là pour vous</p>
</section>
<section class="info-contact">
    <form class="contact-form" action="">
        <label for="name">
            <i class="fa fa-user"></i>
            <input type="text" placeholder="Nom" name="name">
        </label>
        <label for="email">
            <i class="fa fa-envelope"></i>
            <input type="email" placeholder="Email" name="email">
        </label>
        <label for="phone">
            <i class="fa fa-phone"></i>
            <input type="tel" placeholder="Téléphone" name="phone">
        </label>
        <label class="textarea-contact" for="message">
            <textarea name="message" placeholder="Message"></textarea>
        </label>
        <div>
            <button class="btn-form" type="submit">Envoyer le message</button>
        </div>
    </form>
    <div>
        <p>312 rue Octave Legrand</p>
        <p>62110 Hénin Beaumont</p>
        <p>Haut de France, France</p>
    </div>
</section>



