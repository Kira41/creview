document.addEventListener('DOMContentLoaded', () => {
    const placeholder = document.getElementById('header');
    if (!placeholder) return;
    const headerPath = window.location.pathname.includes('/pages/') ? '../header.html' : 'header.html';
    fetch(headerPath)
        .then(response => response.text())
        .then(html => {
            placeholder.innerHTML = html;
            const headerEl = placeholder.querySelector('header');
            if (!headerEl) return;
            if (window.location.pathname.includes('/pages/')) {
                headerEl.querySelectorAll('a').forEach(link => {
                    const href = link.getAttribute('href');
                    if (href.startsWith('pages/')) {
                        link.setAttribute('href', href.replace('pages/', ''));
                    } else if (!href.startsWith('http') && !href.startsWith('#')) {
                        link.setAttribute('href', '../' + href);
                    }
                });
            }
            placeholder.replaceWith(headerEl);
            if (typeof initMobileNav === 'function') {
                initMobileNav();
            }
        })
        .catch(err => console.error('Header load error:', err));
});
