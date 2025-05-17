document.getElementById("arche_footer").innerHTML = fetch("arche_footer.html")
    .then(res => res.text())
    .then(data => document.getElementById("arche_footer").innerHTML = data);




document.getElementById("copyright").style.color = 'red';