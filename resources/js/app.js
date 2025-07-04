import './bootstrap';

const spinnerEl =
    `
    <div style="
        width: 1em;
        aspect-ratio: 1;
        margin: 0.155em auto;
        border-radius: 1000px;
        border: dashed 3px var(--bg-color);
        border-bottom-color: transparent;
        animation: spinner 1200ms ease-in-out alternate infinite;
        ">
    </div>

    <style>
        @keyframes spinner {
            100% {rotate: 450deg;}
        }
    </style>
    `;

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

    // Button Spinner
    const submitButtons = document.querySelectorAll('button.submit-btn');

    if (submitButtons) {
        submitButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const label = btn.textContent;
                const width = btn.getBoundingClientRect().width;

                setTimeout(() => {
                    btn.style.width = width + 'px';
                    btn.innerHTML = spinnerEl;
                    btn.disabled = 'true';
                }, 300);

                setTimeout(() => {
                    btn.removeAttribute('style');
                    btn.innerHTML = label;
                    btn.removeAttribute('disabled');
                }, 15000);
            });
        });
    }
});
