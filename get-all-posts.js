document.addEventListener("DOMContentLoaded", function () {
    const postsContainer = document.getElementById("all-posts");
    let currentAudio = null;
    let currentButton = null;  // To keep track of the current play button

    function loadPosts() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "get-all-posts.php", true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                try{
                    
                    // console.log("Raw response:", xhr.responseText);

                const posts = JSON.parse(xhr.responseText);
                postsContainer.innerHTML = '';

                posts.forEach(post => {
                    const heartIconClass = post.liked ? 'bi-heart-fill' : 'bi-heart';
                    const saveIconClass = post.saved ? 'bi-bookmark-fill' : 'bi-bookmark';
                    const downloadIconClass = post.downloaded ? 'bi-download' : 'bi-download';

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

                    postsContainer.innerHTML += postHtml;
                });

                addEventListeners();
            }
            catch{
                console.error("Failed to parse JSON response:", error);
            }
            } 
            else {
                console.error("Error fetching posts:", xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error("Request failed");
        };

        xhr.send();
    }

    function addEventListeners() {
        const playBtns = document.querySelectorAll('.play-btn');
        playBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const audioUrl = this.getAttribute('data-audio');
                togglePlay(audioUrl, this);
            });
        });
    }

    // This function is used to play or pause the song
    function togglePlay(audioUrl, button) {
        // If there is already an audio playing and the current song is different
        if (currentAudio && currentAudio.src !== audioUrl) {
            currentAudio.pause();  // Stop the current audio
            currentAudio.currentTime = 0;  // Reset the current audio to the beginning
            currentButton.innerHTML = '&#9654;';  // Reset the previous button to play
        }

        // If the audio is paused or it's a new song, play it
        if (!currentAudio || currentAudio.src !== audioUrl || currentAudio.paused) {
            if (currentAudio && !currentAudio.paused) {
                currentAudio.pause();  // Pause the audio if it's already playing
                currentAudio.currentTime = 0;  // Reset it to the beginning
                currentButton.innerHTML = '&#9654;';  // Reset the previous button to play
            }

            // Create and play the new audio
            currentAudio = new Audio(audioUrl);
            currentAudio.play();  // Play the new audio
            button.innerHTML = '&#10074;&#10074;';  // Change button to pause icon
            currentButton = button;  // Store the current button for pause toggle

            // When the audio ends, change button to play icon
            currentAudio.addEventListener('ended', function () {
                button.innerHTML = '&#9654;';  // Show play icon when audio ends
            });
        } else {
            // If the same song is clicked again, just pause it
            currentAudio.pause();
            button.innerHTML = '&#9654;';  // Change button to play icon
        }
    }

    // Initial load of posts
    loadPosts();
});
