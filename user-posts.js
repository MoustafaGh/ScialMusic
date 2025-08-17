document.addEventListener("DOMContentLoaded", function () {
    const userPostsContainer = document.getElementById("user-posts");
    let currentAudio = null; // To hold the current audio playing
    let currentButton = null; // To track the button controlling the current audio

    function loadUserPosts() {
        // Get the username from the URL parameters
        const username = new URLSearchParams(window.location.search).get("username");

        if (!username) {
            console.error("Profile username not provided.");
            userPostsContainer.innerHTML = "<p>Profile username not provided.</p>";
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("GET", `user-posts.php?username=${username}`, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const posts = JSON.parse(xhr.responseText);

                // Clear the container before adding posts
                userPostsContainer.innerHTML = "";

                if (posts.error) {
                    userPostsContainer.innerHTML = `<p>${posts.error}</p>`;
                    return;
                }

                posts.forEach(post => {
                    const heartIconClass = post.isLiked ? "bi-heart-fill" : "bi-heart";
                    const saveIconClass = post.isSaved ? "bi-bookmark-fill" : "bi-bookmark";
                    const downloadIconClass = "bi-download";

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
                                    <div>
                                        <button>&#171;</button>
                                        <button class="play-btn" data-audio="./${post.audio_file}">&#9654;</button>
                                        <button>&#187;</button>
                                    </div>
                                    <div>
                                        <button class="like-btn" data-post-id="${post.id}">
                                            <i class="bi ${heartIconClass}"></i>
                                        </button>
                                        <button class="save-btn" data-post-id="${post.id}">
                                            <i class="bi ${saveIconClass}"></i>
                                        </button>
                                        <button class="download-btn" data-post-id="${post.id}">
                                            <i class="bi ${downloadIconClass}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    userPostsContainer.innerHTML += postHtml;
                });

                // Add event listeners for dynamically loaded play buttons
                addPlayButtonListeners();
            } else {
                console.error("Error fetching user posts:", xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error("Request failed");
        };

        xhr.send();
    }

    function addPlayButtonListeners() {
        const playButtons = document.querySelectorAll(".play-btn");
        playButtons.forEach(button => {
            button.addEventListener("click", function () {
                const audioUrl = this.getAttribute("data-audio");
                togglePlayPause(audioUrl, this);
            });
        });
    }

    function togglePlayPause(audioUrl, button) {
        // If the clicked button is already controlling the current audio
        if (currentAudio && currentButton === button) {
            // If audio is playing, pause it
            if (currentAudio.paused) {
                currentAudio.play();
                button.innerHTML = "&#10074;&#10074;"; // Change to pause icon
            } else {
                currentAudio.pause();
                button.innerHTML = "&#9654;"; // Change to play icon
            }
            return;
        }

        // If another audio is playing, stop it and reset its button
        if (currentAudio) {
            currentAudio.pause();
            currentButton.innerHTML = "&#9654;"; // Reset to play icon
        }

        // Play the new audio and update the button
        currentAudio = new Audio(audioUrl);
        currentAudio.play();
        button.innerHTML = "&#10074;&#10074;"; // Change to pause icon
        currentButton = button;

        // When the audio ends, reset the button to "Play"
        currentAudio.addEventListener('ended', () => {
            button.innerHTML = "&#9654;"; // Change to play icon
            currentAudio = null;
            currentButton = null;
        });
    }

    // Load user posts when the page loads
    loadUserPosts();
});
