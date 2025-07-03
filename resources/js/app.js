import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    // Post Sorting
    const select = document.getElementById('sort-by');
    const container = document.querySelector('.posts-container'); // Update this if the container has a different class

    if (select && container) {
        select.addEventListener('change', () => {
            const key = select.value;
            const posts = Array.from(container.querySelectorAll('.post-card'));
            console.log(key);

            posts.sort((a, b) => {
                let aVal = a.dataset[key];
                let bVal = b.dataset[key];

                if (key === 'created') {
                    aVal = new Date(aVal);
                    bVal = new Date(bVal);
                } else {
                    aVal = parseInt(aVal);
                    bVal = parseInt(bVal);
                }

                return bVal - aVal; // always descending
            });

            posts.forEach(post => container.appendChild(post));
        });
    }
})
