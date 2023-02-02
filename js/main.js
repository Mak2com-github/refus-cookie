//je vais récupérer le bouton en l'appelant par sa class
//si on click dessus un message va s'afficher dans la console de l'inspecteur

//je vais récupérer le bouton en l'appelant par sa class
//si on click dessus un message va s'afficher dans la console de l'inspecteur
//je vérifie si le plugin est installé 
fetch("wp-content/plugins/cookie-notice")
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

//je vérifie si le bouton "non"" a été clické
jQuery(document).ready(function() {
var button = document.querySelector('#cn-refuse-cookie');

    button.addEventListener('click', function() {
        console.log('Les cookies ont été refusés.');

        // fetch( {
        //     url : "wp-content/plugins/RefusCookie/cookie.php",
        //     method : "POST",
        //     dataType : "json",
        //     headers: {
        //         "Content-Type": "application/json"
        //     },
        //     body: JSON.stringify({column: "refus", value:false})
        // })
        // console.log("test")
        // .then(response => response.text())
        // console.log("test2")
        // .then(data => console.log(data === "true"))
        // console.log("test3")
        // .catch(error => console.error(error));
        // console.log("test4")

        ajax({
            type: "POST",
            url :"wp-content/plugins/refus-cookie/cookie.php",
            data : { refus : true },
            success: function(data) {
                console.log("Données envoyées");
            }
        });
    });
});




