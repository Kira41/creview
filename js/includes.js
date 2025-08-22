(function() {
    const script = document.currentScript;
    const basePath = script.getAttribute('data-base-path') || '';

    function loadHTML(id, url, callback) {
        fetch(url)
            .then(response => response.text())
            .then(data => {
                const container = document.getElementById(id);
                if (container) {
                    container.innerHTML = data;
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            })
            .catch(error => console.error('Error loading', url, error));
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadHTML('nav-placeholder', basePath + 'nav.html', function() {
            if (typeof initMobileNav === 'function') {
                initMobileNav();
            }
        });
        loadHTML('footer-placeholder', basePath + 'footer.html');
    });
})();
