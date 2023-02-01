//je vais récupérer le bouton en l'appelant par sa class
//si on click dessus un message va s'afficher dans la console de l'inspecteur

//je vais récupérer le bouton en l'appelant par sa class
//si on click dessus un message va s'afficher dans la console de l'inspecteur
// jQuery(document).ready(function() {
//     var button = document.querySelector('.cky-prefrence-btn-wrapper .cky-btn-accept');
    
//     if (button) {
//         button.addEventListener('click', function() {
//             console.log('Le bouton Accepté a été clické.');
//         });
//     }  
// });

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


    // var xhr = new XMLHttpRequest();
    // xhr.open("POST", "cookie.php", true);

    // //envoi les informations du header adaptées avec la requête
    // xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // //Appelle une fonction au changement d"état.
    // xhr.onreadystatechange = function() {
    //     if(this.readyState === XMLHttpRequest.DONE && this.status === 200) {
    //     }
    // }
    // xhr.send();

// if (button) {
//         button.addEventListener("click", function() {
//             fetch("wp-content/plugins/RefusCookie/cookie.php", {
//                 method : "POST",
//                 headers: {
//                     "Content-Type": "application/x-www-form-urlencoded"
//                 },
//                 body: JSON.stringify({column: "refus", value:false})
//             })
//             .then(response => response.json())
//             .then(data => console.log(data))
//             .catch(error => console.error(error));
//         })
//     }


// let analyticsChecked = true;

// document.querySelectorAll('.cky-switch input[type="checkbox"]').addEventListener("change", function() {
//     analyticsChecked = this.checked;
// });
// document.getElementById('.cky-prefrence-btn-wrapper .cky-btn cky-btn-preferences').addEventListener("click", function() {
//     if(!analyticsChecked) {
//         fetch("wp-content/plugins/RefusCookie/cookie.php", {
//             method: "POST",
//         headers: {
//         "Content-Type": "application/json"
//             },
//         body: JSON.stringify({ data: "true" })
//         })
//     .then(response => response.json())
//     .then(data => console.log(data))
//     .catch(error => console.error(error));
// }


