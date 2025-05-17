const text = document.getElementById('movingText');
let position = 0;

function moveText() {
    position += 2; // Speed: increase for faster
    text.style.left = position + 'px';

    if (position > window.innerWidth) {
        position = -text.offsetWidth; // Reset to start from left again
    }

    requestAnimationFrame(moveText); // Smooth animation
}

moveText();
