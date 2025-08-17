document.addEventListener("DOMContentLoaded", function () {
    const createPostContainer = document.getElementById("create-post-container");
    const createPostBtn = document.getElementById("create-post-btn"); // Button to trigger post creation form
    const exitBtn = document.getElementById("exit");

    // Show the create post container when the "Add Post" button is clicked
    createPostBtn.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent default link behavior
        createPostContainer.style.display = "flex"; // Show the container
    });

    // Hide the create post container when the "Exit" button is clicked
    exitBtn.addEventListener("click", function () {
        createPostContainer.style.display = "none"; // Hide the container
    });
});



// let currentAudio = null; // Declare it once at the top

// document.addEventListener('click', (event) => {
//     if (event.target.classList.contains('play-btn')) {
//         const audioUrl = event.target.getAttribute('data-audio');

//         if (!audioUrl) {
//             console.error('Audio URL is missing.');
//             return;
//         }

//         if (currentAudio && currentAudio !== event.target.audio) {
//             currentAudio.pause();
//             currentAudio.currentTime = 0;
//             currentAudio.playingButton.textContent = '▶';
//         }

//         try {
//             if (!event.target.audio) {
//                 event.target.audio = new Audio(audioUrl);
//                 event.target.audio.play();
//                 currentAudio = event.target.audio;
//                 event.target.textContent = '⏸';
//                 event.target.audio.playingButton = event.target;
//             } else {
//                 if (event.target.audio.paused) {
//                     event.target.audio.play();
//                     event.target.textContent = '⏸';
//                     currentAudio = event.target.audio;
//                 } else {
//                     event.target.audio.pause();
//                     event.target.textContent = '▶';
//                 }
//             }
//         } catch (err) {
//             console.error('Failed to play audio:', err);
//         }
//     }
// });



document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("add-song-form");  // The form element
    const createPostContainer = document.getElementById("create-post-container");  // The container for the create post form
    const responseContainer = document.getElementById("response");  // Where to display success/error messages

    // Handle form submission
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Create a FormData object to hold the form data (including files)
        const formData = new FormData(form);

        // Send AJAX request to create_post.php
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "create_post.php", true);

        // Set up what happens when the request finishes
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                if (response.success) {
                    // Post created successfully
                    responseContainer.innerHTML = `<p style="color: green;">${response.success}</p>`;
                    // Optionally, hide the create post container after successful post
                    createPostContainer.style.display = "none";
                    form.reset();  // Reset the form
                } else {
                    // Error creating post
                    responseContainer.innerHTML = `<p style="color: red;">${response.error}</p>`;
                }
            } else {
                // Server error or non-200 response
                responseContainer.innerHTML = `<p style="color: red;">Error with the server. Please try again later.</p>`;
            }
        };

        // Set up what happens in case of an error
        xhr.onerror = function () {
            responseContainer.innerHTML = `<p style="color: red;">There was an error with the request. Please try again later.</p>`;
        };

        // Send the form data
        xhr.send(formData);
    });

    // Handle the show/hide of the create post container
    const createPostBtn = document.getElementById("create-post-btn");
    const exitBtn = document.getElementById("exit");

    // Show the create post container when "Add Post" button is clicked
    createPostBtn.addEventListener("click", function () {
        createPostContainer.style.display = "flex";
    });

    // Hide the create post container when the "Exit" button is clicked
    exitBtn.addEventListener("click", function () {
        createPostContainer.style.display = "none";
    });
});

