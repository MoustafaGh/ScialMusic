document.addEventListener("DOMContentLoaded", function () {
    const savedPostsContainer = document.getElementById("saved-posts");
    let currentAudio = null; // Currently playing audio
    let currentButton = null; // Currently active button

    // Function to load the saved posts
    function loadSavedPosts() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "get-saved-posts.php", true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const posts = JSON.parse(xhr.responseText);
                savedPostsContainer.innerHTML = ''; // Clear the container

                if (posts.error) {
                    savedPostsContainer.innerHTML = `<p>${posts.error}</p>`;
                    return;
                }

                // Create HTML for each post
                posts.forEach(post => {
                    const postHtml = `
                        <div class="music-post" data-post-id="${post.id}">
                            <img src="./${post.image}" alt="${post.music_title}" class="post-image">
                            <div class="music-details">
                                <div class="music-title">
                                    <h3>${post.username}</h3>
                                </div>
                                <div class="track-list">
                                    <div class="track-item">
                                        <span>${post.artist_name}</span>
                                        <span>${post.music_title}</span>
                                    </div>
                                </div>
                                <div class="controls">
                                    <button class="play-btn" data-audio="./${post.audio_file}">Play</button>
                                </div>
                            </div>
                        </div>
                    `;
                    savedPostsContainer.innerHTML += postHtml;
                });

                // Add event listeners after posts are loaded
                addPlayButtonListeners();
            } else {
                console.error("Error fetching saved posts:", xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error("Request failed");
        };

        xhr.send();
    }

    // Add event listeners to each play button
    function addPlayButtonListeners() {
        const playButtons = document.querySelectorAll(".play-btn");
        playButtons.forEach(button => {
            button.addEventListener("click", function () {
                const audioUrl = this.getAttribute("data-audio");
                togglePlayPause(audioUrl, this);
            });
        });
    }

    // Toggle play and pause when the play button is clicked
    function togglePlayPause(audioUrl, button) {
        // If the clicked button is the same as the current button
        if (currentButton === button) {
            // If audio is already playing, pause it
            if (!currentAudio.paused) {
                currentAudio.pause();
                button.innerHTML = "&#9654;"; // Change to Play icon
            } else {
                // If audio is paused, play it
                currentAudio.play();
                button.innerHTML = "&#10074;&#10074;"; // Change to Pause icon
            }
            return;
        }

        // If a new button is clicked, stop the current audio and reset its button
        if (currentAudio) {
            currentAudio.pause();
            currentButton.innerHTML = "&#9654;"; // Reset to Play icon
        }

        // Create a new audio object and play it
        currentAudio = new Audio(audioUrl);
        currentAudio.play();
        button.innerHTML = "&#10074;&#10074;"; // Change to Pause icon
        currentButton = button;

        // When the audio ends, reset the button to "Play"
        currentAudio.addEventListener('ended', () => {
            button.innerHTML = "&#9654;"; // Change to Play icon
            currentAudio = null;
            currentButton = null;
        });
    }

    loadSavedPosts(); // Load saved posts when page loads
});
