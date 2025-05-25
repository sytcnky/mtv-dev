document.addEventListener('DOMContentLoaded', function () {
    const rangeInput = document.querySelector("#booking_range");
    const startInput = document.querySelector("#start_date");
    const endInput = document.querySelector("#end_date");

    if (!rangeInput || !startInput || !endInput || !window.villaBookingData) return;

    const {
        unitPrice,
        komisyonOrani,
        minimumStay
    } = window.villaBookingData;

    const disabled = window.disabledDates || [];

    const totalEl = document.getElementById("booking-total");
    const nowEl = document.getElementById("booking-now");
    const warningEl = document.getElementById("booking-warning");
    const infoEl = document.getElementById("booking-info");
    const pricesEl = document.getElementById("booking-prices");

    // Sepete ekle ve hemen al butonlarını bul
    const addToCartBtn = document.querySelector("button.single_add_to_cart_button");
	const buyNowBtn =
    document.querySelector("button#wd-add-to-cart") || // senin DOM
    document.querySelector("button.wd-buy-now-btn") || // alternatif
    document.querySelector("button.wd-buy-now-button") || // bazı eski woodmart
    document.querySelector("button.btn-buy-now"); // ekstra önlem


	// Sayfa ilk açıldığında butonlar pasif gelsin
	if (addToCartBtn) addToCartBtn.disabled = true;
	if (buyNowBtn) buyNowBtn.disabled = true;

    function updateUI(days) {
        if (!infoEl || !totalEl || !nowEl || !addToCartBtn) return;

        infoEl.style.display = 'block';

        if (days < minimumStay) {
            warningEl.style.display = 'block';
            pricesEl.style.display = 'none';
            addToCartBtn.disabled = true;
            if (buyNowBtn) buyNowBtn.disabled = true;
        } else {
            const totalPrice = days * unitPrice;
            const upfront = Math.ceil(totalPrice * (komisyonOrani / 100));

            totalEl.textContent = `₺${totalPrice.toLocaleString("tr-TR")}`;
            nowEl.textContent = `₺${upfront.toLocaleString("tr-TR")}`;

            warningEl.style.display = 'none';
            pricesEl.style.display = 'block';
            addToCartBtn.disabled = false;
            if (buyNowBtn) buyNowBtn.disabled = false;
        }
    }

    flatpickr(rangeInput, {
        mode: "range",
        locale: "tr",
        dateFormat: "Y-m-d",
        minDate: "today",
        inline: true,
        disable: disabled,

        onDayCreate: function (dObj, dStr, fp, dayElem) {
            const dayDate = dayElem.dateObj;
            const formatted = flatpickr.formatDate(dayDate, "Y-m-d");

            if (disabled.includes(formatted)) {
                dayElem.classList.add("dolu-gun");
                dayElem.setAttribute("aria-disabled", "true");
                dayElem.setAttribute("title", "Bu tarih dolu");
            }
        },

        onChange: function (selectedDates) {
            if (selectedDates.length === 2) {
                const [start, end] = selectedDates;

                startInput.value = flatpickr.formatDate(start, "Y-m-d");
                endInput.value = flatpickr.formatDate(end, "Y-m-d");

                const msPerDay = 1000 * 60 * 60 * 24;
                const diffDays = Math.round((end - start) / msPerDay);

                updateUI(diffDays);
            } else {
                startInput.value = "";
                endInput.value = "";
                infoEl.style.display = 'none';
                if (addToCartBtn) addToCartBtn.disabled = true;
                if (buyNowBtn) buyNowBtn.disabled = true;
            }
        }
    });
});
