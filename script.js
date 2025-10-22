document.addEventListener('DOMContentLoaded', () => {
    const generateBtn = document.getElementById('generate-btn');
    const storyEl = document.getElementById('story');
    const languageEl = document.getElementById('language');
    const promptEl = document.getElementById('prompt');

    generateBtn.addEventListener('click', () => {
        const language = languageEl.value;
        const prompt = promptEl.value;

        if (!prompt) {
            alert('Please enter a prompt for the story.');
            return;
        }

        storyEl.textContent = 'Generating your story...';

        fetch('generate_story.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ language, prompt })
        })
        .then(response => response.json())
        .then(data => {
            if (data.story) {
                storyEl.textContent = data.story;
            } else {
                storyEl.textContent = 'There was an error generating the story. Please try again.';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            storyEl.textContent = 'There was an error generating the story. Please try again.';
        });
    });
});
