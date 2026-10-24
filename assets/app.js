// Small interactions used across StudentWDF pages.
document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.querySelector('[data-menu-toggle]');
    const navLinks = document.querySelector('[data-nav-links]');

    if (menuButton && navLinks) {
        menuButton.addEventListener('click', function () {
            navLinks.classList.toggle('open');
        });
    }

    const themeButton = document.querySelector('[data-theme-toggle]');
    const savedTheme = localStorage.getItem('studentwdf-theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }
    if (themeButton) {
        themeButton.addEventListener('click', function () {
            document.body.classList.toggle('dark-theme');
            const theme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
            localStorage.setItem('studentwdf-theme', theme);
        });
    }

    document.querySelectorAll('[data-dismiss]').forEach(function (button) {
        button.addEventListener('click', function () {
            const banner = button.closest('.notice-bar');
            if (banner) {
                banner.remove();
            }
        });
    });

    document.querySelectorAll('[data-faq-question]').forEach(function (button) {
        button.addEventListener('click', function () {
            const answer = button.nextElementSibling;
            if (answer) {
                answer.hidden = !answer.hidden;
            }
        });
    });

    const modalOpen = document.querySelector('[data-open-modal]');
    const modal = document.querySelector('[data-modal]');
    const modalClose = document.querySelector('[data-close-modal]');
    if (modalOpen && modal) {
        modalOpen.addEventListener('click', function () {
            modal.hidden = false;
        });
    }
    if (modalClose && modal) {
        modalClose.addEventListener('click', function () {
            modal.hidden = true;
        });
    }

    const sliderButton = document.querySelector('[data-next-tip]');
    const sliderText = document.querySelector('[data-tip-text]');
    if (sliderButton && sliderText) {
        const tips = [
            'Keep your profile details up to date.',
            'Check the Events page for campus activities.',
            'Use the Data Lab to practise search and sorting.'
        ];
        let tipIndex = 0;
        sliderButton.addEventListener('click', function () {
            tipIndex = (tipIndex + 1) % tips.length;
            sliderText.textContent = tips[tipIndex];
        });
    }
});
