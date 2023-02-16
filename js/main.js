function addCustomEvent($url, $action, $element) {
    var btn = document.querySelector($element)
    if (btn) {
        console.log(btn)
        btn.addEventListener('click', function() {
            console.log('Les cookies ont été refusés.');
            fetch($url + '/wp-admin/admin-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache',
                },
                body: new URLSearchParams($action),
            })
                .then(response => {
                    if(response.status === 200 ) {
                        console.log('Envoyées');
                    } else {
                        console.log("Ajax Error : " + response)
                    }
                });
        });
    }
}

jQuery(document).ready(function() {
    var action = {action:"rc_update_data"};
    var url = php_datas.home_url
    var ips = php_datas.registered_ips
    var visitor = php_datas.visitor_ip
    var targets = php_datas.registered_targets
    var found = false

    for (const value of Object.values(ips)) {
        if (value.ip === visitor) {
            console.log("IP is in the JSON value")
            found = true
            break // stop the loop
        }
    }
    if (!found) {
        for (const value of Object.values(targets)) {
            if (value.type === "id") {
                var target = "#" + value.element
            } else {
                var target = "." + value.element
            }
            addCustomEvent(url, action, target)
        }
    }
});
