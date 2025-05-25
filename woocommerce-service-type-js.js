window.addEventListener('load', function () {
    console.log('🟢 JS yüklendi ve tetiklendi');
});

window.addEventListener('load', function () {
    const select = document.querySelector('#_service_type');
    const fields = document.querySelectorAll('.show-if-villa');

    if (!select || !fields.length) return;

    function toggleFields() {
        const isVilla = select.value === 'villa';
        fields.forEach(el => {
            el.style.display = isVilla ? 'block' : 'none';
        });
    }

    select.addEventListener('change', toggleFields);
    toggleFields();
});
