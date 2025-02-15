function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    document.getElementById('currentTime').innerText = timeString;
}

setInterval(updateClock, 1000);
updateClock();
