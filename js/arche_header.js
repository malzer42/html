// Load header and footer using JS
document.getElementById("arche_header").innerHTML = fetch("arche_header.html")
    .then(res => res.text())
    .then(data => document.getElementById("arche_header").innerHTML = data);



function updateDateTime() {
    const now = new Date();
    const options = {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    };
    document.getElementById('datetime').textContent = now.toLocaleString('en-US', options);
}

setInterval(updateDateTime, 1000); // update every second
updateDateTime(); // initial call