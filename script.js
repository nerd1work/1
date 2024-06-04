document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if (response[username] === password) {
                document.getElementById("message").innerText = "Login successful!";
                window.location.href = "welcome.html?username=" + username;
            } else {
                document.getElementById("message").innerText = "Invalid username or password!";
            }
        }
    };
    xhttp.open("GET", "users.json", true);
    xhttp.send();
});
