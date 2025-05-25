document.addEventListener('DOMContentLoaded', function () {
    // Sadece filtre formu varsa çalış
    const filterForm = document.querySelector('.custom-booking-filter form');
    if (!filterForm) return;

    const startInput = document.querySelector("#filter_start_date");
    const endInput = document.querySelector("#filter_end_date");

    if (startInput && endInput) {
        const disabled = window.disabledDates || [];

        const endPicker = flatpickr(endInput, {
            altInput: true,
            altFormat: "d.m.Y",
            dateFormat: "Y-m-d",
            locale: "tr",
            minDate: "today"
        });

        const startPicker = flatpickr(startInput, {
            altInput: true,
            altFormat: "d.m.Y",
            dateFormat: "Y-m-d",
            locale: "tr",
            minDate: "today",
            disable: disabled,
            onChange: function (selectedDates) {
                if (selectedDates.length > 0) {
                    const start = selectedDates[0];
                    const tomorrow = new Date(start);
                    tomorrow.setDate(tomorrow.getDate() + 1);

                    endPicker.set('minDate', tomorrow);
                    endPicker.setDate(tomorrow);
                    endPicker.altInput?.removeAttribute('disabled');
                } else {
                    endPicker.clear();
                    endInput.value = '';
                    endPicker.altInput?.setAttribute('disabled', 'disabled');
                }
            }
        });

        if (startInput.value) {
            endPicker.altInput?.removeAttribute('disabled');
        } else {
            endPicker.altInput?.setAttribute('disabled', 'disabled');
        }
    }

    function initDropdownFilter(containerId, inputName) {
        const dropdown = document.querySelector(containerId);
        if (!dropdown) return;

        const toggle = dropdown.querySelector('.dropdown-toggle');
        const options = dropdown.querySelectorAll('.dropdown-item');
        const id = containerId.replace('#', '').replace('-dropdown', '') + '-hidden-inputs';
        const hiddenInputs = document.querySelector(`#${id}`);
        const tagContainer = dropdown.querySelector('.selected-tags');

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            document.querySelectorAll('.custom-dropdown.open').forEach(el => {
                if (el !== dropdown) el.classList.remove('open');
            });
            dropdown.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        options.forEach(item => {
            item.addEventListener('click', function (e) {
                e.stopPropagation();
                const val = item.dataset.value;
                const label = item.textContent;

                const existingInput = hiddenInputs.querySelector(`input[data-value="${val}"]`);
                if (existingInput) {
                    existingInput.remove();
                    item.classList.remove('active');
                    const tag = tagContainer.querySelector(`.dropdown-tag[data-value="${val}"]`);
                    tag?.remove();
                    return;
                }

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = inputName + '[]';
                input.value = val;
                input.setAttribute('data-value', val);
                hiddenInputs.appendChild(input);

                const tag = document.createElement('span');
                tag.className = 'dropdown-tag';
                tag.setAttribute('data-value', val);
                tag.textContent = label;

                const remove = document.createElement('span');
                remove.className = 'remove';
                remove.innerHTML = '&times;';
                remove.addEventListener('click', function (ev) {
                    ev.stopPropagation();
                    tag.remove();
                    input.remove();
                    item.classList.remove('active');
                });

                tag.appendChild(remove);
                tagContainer.appendChild(tag);
                item.classList.add('active');
            });
        });

        hiddenInputs.querySelectorAll('input[data-value]').forEach(input => {
            const val = input.dataset.value;
            const label = dropdown.querySelector(`.dropdown-item[data-value="${val}"]`)?.textContent;

            const tag = document.createElement('span');
            tag.className = 'dropdown-tag';
            tag.setAttribute('data-value', val);
            tag.textContent = label;

            const remove = document.createElement('span');
            remove.className = 'remove';
            remove.innerHTML = '&times;';
            remove.addEventListener('click', function (ev) {
                ev.stopPropagation();
                tag.remove();
                input.remove();
                dropdown.querySelector(`.dropdown-item[data-value="${val}"]`)?.classList.remove('active');
            });

            tag.appendChild(remove);
            tagContainer.appendChild(tag);
            dropdown.querySelector(`.dropdown-item[data-value="${val}"]`)?.classList.add('active');
        });
    }

    initDropdownFilter('#lokasyon-dropdown', 'custom_lokasyon');
    initDropdownFilter('#evtipi-dropdown', 'custom_ev_tipi');

    const clearBtn = document.getElementById('clear-filters');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            if (startInput) startInput.value = '';
            if (endInput) endInput.value = '';
            document.querySelectorAll('.dropdown-tag').forEach(el => el.remove());
            document.querySelectorAll('input[type="hidden"][data-value]').forEach(el => el.remove());

            const shopUrl = window.location.origin + window.location.pathname.replace(/\/$/, '');
            window.location.href = shopUrl;
        });
    }
});
