<?php $this->styles = '
    <link rel="stylesheet" href="css/Home/mobile.css">
    <link rel="stylesheet" href="css/Home/tablet.css" media="screen AND (min-width: 600px)">
    <link rel="stylesheet" href="css/Home/desktop.css" media="screen AND (min-width: 1024px)">
    '; ?>
<?php $this->script = '
    <script src="js/slider.js"></script>
    '; ?>
<?php $this->title = "Mon Blog"; ?>

<section class="img-form-contact">
    <img src="media/background_home.jpeg" alt="ttt">
    <div>
        <h1>Solutions Web personnalisées à votre image</h1>
        <form class="subscribe-form" action="">
            <input type="email" placeholder="Tapez votre email...">
            <p>Vous ne possedez pas de compte?<a href="#"> Créer en un</a></p>
        </form>
        <a class="contact-me-link" href=""><span>Contactez-nous </span><i class="fa fa-arrow-right"></i></a>
    </div>
</section>
<section class="slider">
    <img class="slide" src="media/slide1.jpg" alt="slide">
    <img class="slide" src="media/slide2.jpg" alt="slide">
    <img class="slide" src="media/slide3.jpg" alt="slide">
    <img class="slide" src="media/slide4.jpg" alt="slide">
    <img class="slide" src="media/slide5.jpg" alt="slide">
</section>
<section>
    <article>
        <a href="">
            <img src="media/article1.jpg" alt="image article">
        </a>
        <div>
            <h1>Titre de l'article</h1>
            <div class="infos-article-top">
                <span><i class="fa fa-calendar"></i> date</span>
                <span><i class="fa fa-clock-o"></i>
 heure</span>
            </div>
            <span>par <b>Auteur</b></span>
            <p class="exerpt-article">Lorem ipsum..........</p>
            <span class="category-title">#Categorie</span>
            <div class="article-bottom">
                <a href="">Lire plus <i class="fa fa-arrow-right"></i></a>
                <div>
                    <a href="https://www.facebook.com/pages/category/Product-Service/JM-Websites-370052016940987/"><i class="fa-brands fa-facebook"></i></a>
                    <a href=""><i class="fa-brands fa-instagram-square"></i></a>
                </div>
            </div>
        </div>
    </article>
</section>


