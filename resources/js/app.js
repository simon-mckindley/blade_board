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
    <div class="spinner-overlay" 
        style="
        position: fixed; 
        inset: 0;
        display: grid;
        place-content: center;
        z-index: 2000;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(2px);">
    <div style="animation: show-in 2000ms linear;">
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

const template = document.createElement('template');
template.innerHTML = pageSpinner.trim();
const spinnerNode = template.content.firstElementChild;


// Romve the spinner overlay when the page is shown (after browser navigation)
window.addEventListener('pageshow', () => {
    const overlay = document.querySelector('.spinner-overlay');
    if (overlay) overlay.remove();
});


document.addEventListener('DOMContentLoaded', function () {
    // Small Screen Navigation functions
    const smallNav = document.querySelector('.small-scrn-nav');
    const smallNavImg = smallNav.querySelector('img');
    const numRows = smallNav.childElementCount;
    // Set the number of grid rows dynamically based on the number of children
    smallNav.style.setProperty('--_num-rows', numRows);

    document.addEventListener('click', function (event) {
        const target = event.target;
        // If the clicked element is NOT the small nav or its children, remove the clicked class
        if (target.closest('.small-scrn-nav') !== smallNav) {
            smallNavImg.classList.remove('clicked');
        }
    });

    // Drawer Actions
    const drawer = document.querySelector('.drawer');

    if (drawer) {
        drawer.querySelector('.drawer-tab').addEventListener('click', function () {
            drawer.classList.toggle('closed');
        });

        // Open drawer on large screens
        if (window.innerWidth >= 1024) {
            drawer.classList.remove('closed');
        }

        // Set date labels
        drawer.querySelectorAll('input[type="date"]').forEach(input => {
            const update = () => {
                input.parentElement.classList.toggle('has-input', input.value !== '');
            };

            input.addEventListener('input', update);
            update(); // run once on page load
        });

        // Sort-by change handler
        document.getElementById('sort-by')?.addEventListener('change', function () {
            document.getElementById('filters-form').submit();
            this.setAttribute('disabled', 'true');
            const width = this.getBoundingClientRect().width;
            this.style.width = width + 'px';
            this.innerHTML = `<option>Loading...</option>`;
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
                const body = document.querySelector('body');
                body.appendChild(spinnerNode);
            })
        })
    }

});
