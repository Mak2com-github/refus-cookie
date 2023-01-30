//je vais récupérer le bouton en l'appelant par sa class
//si on click dessus un message va s'afficher dans la console de l'inspecteur
    console.log('cookie js loaded');

    jQuery(document).ready(function() {
        var button = document.querySelector('.cky-prefrence-btn-wrapper .cky-btn-reject');

        if (button) {
            button.addEventListener('click', function() {
                console.log('Le bouton Refusé a été clické.');
                var variable1 = "consentid";
                var variable3 = "consent";
                var variable3 = "action";
                var variable4 = "necessary";
                var variable5 = "functionnal";
                var variable6 = "analytics";
                var variable7 = "performance";
                var variable8 = "advertisement";

                $.ajax({
                    type: "POST",
                    url: "Controller.php",

                })
            });
        }
    });

//je vais récupérer le bouton en l'appelant par sa class
//si on click dessus un message va s'afficher dans la console de l'inspecteur
    jQuery(document).ready(function() {
        var button = document.querySelector('.cky-prefrence-btn-wrapper .cky-btn-accept');
        
        if (button) {
            button.addEventListener('click', function() {
                console.log('Le bouton Accepté a été clické.');
            });
        }  
    });

//je vérifie si le plugin est installé 
fetch("wp-content/plugins/cookie-law-info")
.then(function(response) {
        if(response.ok) {
            console.log("Le plugin est installé.");
        } else {
            console.log("Le plugin n'est pas installé");
        }
    })
    .catch(function() {
        console.log("An error occured while trying to access the file.");
    });

//je vérifie la présence des valeurs suivantes
var consentid = "consentid";
if (consentid == "consentid") {
    console.log("consentid existe.");
} else {
    console.log("consentid n'existe pas.");
}

var consent = "consent";
if (consent == "consent") {
    console.log("consent existe.");
} else {
    console.log("consent n'existe pas.");
}

var action = "action";
if (action == "action") {x
    console.log("action existe.");
} else {
    console.log("action n'existe pas.");
}

var necessary = "necessary";
if (necessary == "necessary") {
    console.log("necessary existe.");
} else {
    console.log("necessary n'existe pas.");
}

var functionnal = "functionnal";
if (functionnal == "functionnal") {
    console.log("functionnal existe.");
} else {
    console.log("functionnal n'existe pas.");
}

var analytics = "analytics";
if (analytics == "analytics") {
    console.log("analytics existe.");
} else {
    console.log("analytics n'existe pas.");
}

var performance = "performance";
if (performance == "performance") {
    console.log("performance existe.");
} else {
    console.log("performance n'existe pas.");
}

var advertisement = "advertisement";
if (advertisement == "advertisement") {
    console.log("advertisement existe.");
} else {
    console.log("advertisement n'existe pas.");
}

//je vérifis si le Analytics a été accepté ou non
jQuery(document).ready(function() {
    var button = document.querySelector('.cky-switch input[type="checkbox"]');
    
    if (button) {
        button.addEventListener('click', function() {
            console.log('Le paramètre Analytics a été refusé');
        });
    }  

});

