document.addEventListener('alpine:init', () => {
    Alpine.data('generateQR', () => ({
        init() {
            console.log('Alpine initialized');
        },
    }));
});
