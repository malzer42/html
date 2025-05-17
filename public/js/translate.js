// Initialize i18next
i18next
    .use(i18nextHttpBackend)
    .init({
        lng: 'en', // default language
        fallbackLng: 'en',
        debug: true,
        backend: {
            loadPath: '/locales/{{lng}}/translation.json'
        }
    }, function(err, t) {
        updateContent();
    });

// Update content based on translations
function updateContent() {
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        el.innerHTML = i18next.t(key);
    });
}

// Switch language on select
document.getElementById('langSwitcher').addEventListener('change', function() {
    const newLang = this.value;
    i18next.changeLanguage(newLang, () => updateContent());
});
