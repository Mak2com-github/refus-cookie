
jQuery(document).ready(function() {
    var button = document.getElementById('cn-refuse-cookie');
    const data = {action:"update_data"};
    button.addEventListener('click', function() {
        console.log('Les cookies ont été refusés.');

        fetch('http://localhost/wordpress/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Cache-Control': 'no-cache',
            },
            body: new URLSearchParams(data),
        })
        .then(response => {
            if(response.status === 200 ) {
                console.log('Envoyées');
            }
        });
    });
});
