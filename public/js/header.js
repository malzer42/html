// Load header and footer using JS
document.getElementById("header").innerHTML = fetch("header.html")
    .then(res => res.text())
    .then(data => document.getElementById("header").innerHTML = data);
