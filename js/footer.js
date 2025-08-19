document.addEventListener('DOMContentLoaded', () => {
    const placeholder = document.getElementById('footer');
    if (!placeholder) return;
    const footerPath = window.location.pathname.includes('/pages/') ? '../footer.html' : 'footer.html';
    fetch(footerPath)
        .then(response => response.text())
        .then(html => {
            placeholder.innerHTML = html;
            const footerEl = placeholder.querySelector('footer');
            if (!footerEl) return;
            if (window.location.pathname.includes('/pages/')) {
                footerEl.querySelectorAll('a[href^="pages/"]').forEach(link => {
                    link.setAttribute('href', link.getAttribute('href').replace('pages/', ''));
                });
            }
            placeholder.replaceWith(footerEl);
        })
        .catch(err => console.error('Footer load error:', err));
});
