// Play or pause the music based on its current state
function togglePlayPause(button, audioFile) {
    // Check if an audio is already playing
    if (window.currentAudio && window.currentAudio.src === audioFile) {
        // If the same audio is clicked, toggle play/pause
        if (window.currentAudio.paused) {
            window.currentAudio.play();
            button.innerHTML = "&#10074;&#10074;";  // Change to pause icon
        } else {
            window.currentAudio.pause();
            button.innerHTML = "&#9654;";  // Change to play icon
        }
    } else {
        // If it's a new audio, stop the previous one and play the new one
        if (window.currentAudio) {
            window.currentAudio.pause();
            window.currentButton.innerHTML = "&#9654;"; // Reset previous button
        }

        // Create new audio and play it
        window.currentAudio = new Audio(audioFile);
        window.currentAudio.play();

        // Update button and store the current button
        window.currentButton = button;
        button.innerHTML = "&#10074;&#10074;";  // Change to pause icon
    }
}
