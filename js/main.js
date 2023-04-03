function addCustomEvent($url, $element) {
    var btn = document.querySelector($element)
    var datas = {action: 'rc_update_data',}
    if (btn) {
        btn.addEventListener('click', function() {
            fetch($url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache',
                },
                body: new URLSearchParams(datas),
            })
                .then(response => response.json())
                .then(response => {
                    console.log(response)
                    if(!response.success) {
                        console.log(response)
                    }
                });
        });
    }
}

jQuery(document).ready(function() {
    var url = php_datas.admin_ajax
    var ips = php_datas.registered_ips
    var visitor = php_datas.visitor_ip
    var targets = php_datas.registered_targets
    var found = false

    for (const value of Object.values(ips)) {
        if (value.ip === visitor) {
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
            addCustomEvent(url, target)
        }
    }
});
