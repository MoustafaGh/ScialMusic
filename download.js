document.addEventListener('click', async (event) => {
    if (event.target.classList.contains('download-btn') || event.target.closest('.download-btn')) {
        const downloadBtn = event.target.closest('.download-btn');
        const postId = downloadBtn.getAttribute('data-post-id');

        if (!postId) {
            console.error('Post ID is missing.');
            return;
        }

        try {
            console.log("Attempting to download post with ID:", postId);

            // Send a request to save download information and get the audio file
            const response = await fetch('downloads.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}`, 
            });

            // Parse the response as JSON
            const result = await response.json();
            console.log("Download response:", result);

            if (result.success) {
                // Trigger the audio file download
                const link = document.createElement('a');
                link.href = result.audio_url; // URL to the audio file
                link.download = result.audio_filename; // Suggested filename for the download
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                console.log('File downloaded successfully.');
            } else {
                if (result.error === 'User not logged in.') {
                    alert('You must be logged in to download this file.');
                } else {
                    console.error('Error:', result.error);
                }
            }
        } catch (error) {
            console.error('Error during fetch:', error);
        }
    }
});

