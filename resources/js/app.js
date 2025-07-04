import './bootstrap';

const btnSpinner =
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
        @keyframes spinner {100% {rotate: 450deg;}}
    </style>
    `;

const pageSpinner =
    `
    <div style="
        margin: 30vh auto 0; width: fit-content; text-align: center;
        animation: show-in 2000ms linear;">
        <p>Loading...</p>
        <div class="page-spinner">
            <div class="line top"></div>
            <div class="line right"></div>
            <div class="line bottom"></div>
            <div class="line left"></div>
        </div>
    </div>

    <style>
        .page-spinner {
            --_page-spinner-width: 2em;
            position: relative;
            width: var(--_page-spinner-width);
            aspect-ratio: 1/1;
            margin-inline: auto;
        }

        .page-spinner .line {
            position: absolute;
            background-color: var(--text-color);
            border-radius: 100px;
            animation: page-spinner-move 1800ms linear infinite;
        }

        .page-spinner .top {
            width: 100%;
            height: calc(var(--_page-spinner-width) / 10);
            transform-origin: 95%;
        }

        .page-spinner .right {
            right: 0;
            height: 100%;
            width: calc(var(--_page-spinner-width) / 10);
            transform-origin: 50% 95%;
        }

        .page-spinner .bottom {
            bottom: 0;
            width: 100%;
            height: calc(var(--_page-spinner-width) / 10);
            transform-origin: 5%;
        }

        .page-spinner .left {
            left: 0;
            height: 100%;
            width: calc(var(--_page-spinner-width) / 10);
            transform-origin: 50% 5%;
        }

        @keyframes page-spinner-move {
            100% {
                rotate: -90deg;
            }
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
                    btn.innerHTML = btnSpinner;
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

    // Page Spinner
    const links = document.querySelectorAll('a');

    if (links) {
        links.forEach(link => {
            link.addEventListener('click', () => {
                const main = document.querySelector('.padding-box');
                main.innerHTML = pageSpinner;
            })
        })
    }

});
