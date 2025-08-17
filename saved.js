
document.addEventListener('click', async (event) => {
    // Check if the clicked element is a save button
    if (event.target.classList.contains('save-btn') || event.target.closest('.save-btn')) {
        const saveBtn = event.target.closest('.save-btn'); // Handle clicks on child elements like <i>
        const postId = saveBtn.getAttribute('data-post-id'); // Get the post ID

        if (!postId) {
            console.error('Post ID is missing.');
            return;
        }

        // Send a POST request to save_post.php to save/unsave the post
        try {
            console.log("Attempting to save/unsave post with ID:", postId);

            const response = await fetch('save_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}`, // Fix the syntax here
            });

            const result = await response.json(); // Parse the JSON response
            console.log("Save post response:", result);

            if (result.success) {
                // Toggle the save icon based on the saved status
                const icon = saveBtn.querySelector('i');
                if (result.saved) {
                    icon.classList.add('bi-save2-fill'); // Saved icon
                    icon.classList.remove('bi-save2'); // Not saved icon
                } else {
                    icon.classList.remove('bi-save2-fill'); // Saved icon
                    icon.classList.add('bi-save2'); // Not saved icon
                }
            } else {
                console.error('Error:', result.error);
            }
        } catch (error) {
            console.error('Error during fetch:', error); // Uncommented this to handle errors properly
        }
    }
});

