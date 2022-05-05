$(document).ready(function () {
    let button = document.getElementById('btn-nav-menu');
    let nav = document.getElementById('nav-menu-list');
    let iconBar = document.getElementById('barIcon');
    let iconClose = document.getElementById("closeIcon");
    iconClose.style.display= "none";

    button.onclick = function(){
        if(nav.style.display==="block"){
            nav.style.display="none";
            iconBar.style.display="block";
            iconClose.style.display="none";
            button.style.backgroundColor="rgb(239, 239, 239)";
        }else{
            nav.style.display="block";
            iconBar.style.display="none";
            iconClose.style.display="block";
            button.style.backgroundColor="#4f4d5f";
        }
    };
});