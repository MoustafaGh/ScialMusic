document.addEventListener("DOMContentLoaded", function () {
    const postsContainer = document.getElementById("posts-container");
    let currentAudio = null;

    function loadPosts() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "get_posts.php", true);

        xhr.onload = function () {
            if (xhr.status === 200) {
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
            } else {
                console.error("Error fetching posts:", xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error("Request failed");
        };

        xhr.send();
    }

    function addEventListeners() {
        const likeBtns = document.querySelectorAll('.like-btn');
        likeBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const postId = this.getAttribute('data-post-id');
                toggleLike(postId);
            });
        });

        const saveBtns = document.querySelectorAll('.save-btn');
        saveBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const postId = this.getAttribute('data-post-id');
                toggleSave(postId);
            });
        });

        const downloadBtns = document.querySelectorAll('.download-btn');
        downloadBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const postId = this.getAttribute('data-post-id');
                toggleDownload(postId);
            });
        });

        const playBtns = document.querySelectorAll('.play-btn');
        playBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const audioUrl = this.getAttribute('data-audio');
                togglePlay(audioUrl, this);
            });
        });
    }

    function toggleLike(postId) {
        console.log("Toggling like for post:", postId);
    }

    function toggleSave(postId) {
        console.log("Toggling save for post:", postId);
    }

    function toggleDownload(postId) {
        console.log("Toggling download for post:", postId);
    }

    function togglePlay(audioUrl, button) {
        if (currentAudio && !currentAudio.paused) {
            currentAudio.pause();
            button.innerHTML = '&#9654;';
        } else {
            if (currentAudio) {
                currentAudio.pause();
            }

            currentAudio = new Audio(audioUrl);
            currentAudio.play();

            button.innerHTML = '&#10074;&#10074;';
            
            currentAudio.addEventListener('ended', function () {
                button.innerHTML = '&#9654;';
            });
        }
    }

    loadPosts();
});
