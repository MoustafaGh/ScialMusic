document.addEventListener('click', async (event) => {
    // Check if the clicked element is a like button
    if (event.target.classList.contains('like-btn') || event.target.closest('.like-btn')) {
        const likeBtn = event.target.closest('.like-btn'); // Handle clicks on child elements like <i>
        const postId = likeBtn.getAttribute('data-post-id'); // Get the post ID

        if (!postId) {
            console.error('Post ID is missing.');
            return;
        }

        // Send a POST request to likes.php to add/remove like
        try {
            const response = await fetch('likes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}`,
            });

            const result = await response.json(); // Parse the JSON response

            if (result.success) {
                // Toggle the heart icon based on the like status
                const icon = likeBtn.querySelector('i');
                if (result.liked) {
                    icon.classList.remove('bi-heart'); // Empty heart
                    icon.classList.add('bi-heart-fill'); // Filled heart
                } else {
                    icon.classList.remove('bi-heart-fill'); // Filled heart
                    icon.classList.add('bi-heart'); // Empty heart
                }
            } else {
                console.error('Error:', result.error);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
});
