document.addEventListener("DOMContentLoaded", function () {
    // Анимация при загрузке страницы
    function initAnimations() {
        // Анимация появления элементов при загрузке
        const heroContent = document.querySelector(".hero .container");
        if (heroContent) {
            heroContent.style.opacity = "0";
            heroContent.style.transform = "translateY(20px)";
            heroContent.style.transition = "all 0.6s ease-out";

            setTimeout(() => {
                heroContent.style.opacity = "1";
                heroContent.style.transform = "translateY(0)";
            }, 200);
        }

        // Анимация появления услуг
        const serviceItems = document.querySelectorAll(".service-item");
        serviceItems.forEach((item, index) => {
            item.style.opacity = "0";
            item.style.transform = "translateY(20px)";
            item.style.transition = `all 0.4s ease-out ${index * 0.1}s`;

            setTimeout(() => {
                item.style.opacity = "1";
                item.style.transform = "translateY(0)";
            }, 300 + index * 100);
        });

        // Анимация появления формы аутентификации
        const authForm = document.querySelector(".auth-form");
        if (authForm) {
            authForm.style.opacity = "0";
            authForm.style.transform = "translateY(20px)";
            authForm.style.transition = "all 0.6s ease-out";

            setTimeout(() => {
                authForm.style.opacity = "1";
                authForm.style.transform = "translateY(0)";
            }, 200);
        }
    }

    // Запускаем анимации при загрузке
    initAnimations();

    // Обработчик клика на бургер-меню
    const burgerIcon = document.getElementById('burger-icon');
    const mobileNav = document.getElementById('mobile-nav');

    if (burgerIcon && mobileNav) {
        burgerIcon.addEventListener('click', function() {
            // Переключаем класс 'active' для иконки бургер-меню
            this.classList.toggle('active');

            // Переключаем класс 'active' для мобильной навигации
            mobileNav.classList.toggle('active');
        });

        // Закрываем мобильное меню при клике на ссылку
        mobileNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                burgerIcon.classList.remove('active');
                mobileNav.classList.remove('active');
            });
        });
    }

    // Функция для загрузки слотов времени
    function loadTimeSlots(employeeId, date, cartItemId) {
        const slotsContainer = document.getElementById(`slots-container-${cartItemId}`);
        const appointmentTimeInput = document.getElementById(`appointment_time_${cartItemId}`);

        if (!slotsContainer || !appointmentTimeInput) return;

        // Очищаем контейнер слотов
        slotsContainer.innerHTML = '<p>Загрузка доступного времени...</p>';

        // Отправляем запрос на сервер для получения занятых слотов
        fetch(`/cart/busy-slots?employee_id=${employeeId}&date=${date}&cart_item_id=${cartItemId}`)
            .then(response => response.json())
            .then(data => {
                // Очищаем контейнер слотов
                slotsContainer.innerHTML = '';

                // Создаем кнопки для каждого доступного слота
                data.availableSlots.forEach(slot => {
                    const slotButton = document.createElement('button');
                    slotButton.type = 'button';
                    slotButton.className = 'time-slot';
                    slotButton.textContent = slot;

                    // Если этот слот уже выбран, добавляем класс 'selected'
                    const currentAppointmentTime = appointmentTimeInput.value;
                    if (currentAppointmentTime && currentAppointmentTime.includes(slot)) {
                        slotButton.classList.add('selected');
                    }

                    // Добавляем обработчик клика
                    slotButton.addEventListener('click', function() {
                        // Убираем класс 'selected' у всех кнопок
                        document.querySelectorAll(`#slots-container-${cartItemId} .time-slot`).forEach(btn => {
                            btn.classList.remove('selected');
                        });

                        // Добавляем класс 'selected' к нажатой кнопке
                        this.classList.add('selected');

                        // Обновляем значение скрытого поля
                        const selectedDate = document.getElementById(`appointment_date_${cartItemId}`).value;
                        appointmentTimeInput.value = `${selectedDate}T${slot}`;
                    });

                    slotsContainer.appendChild(slotButton);
                });

                if (data.availableSlots.length === 0) {
                    slotsContainer.innerHTML = '<p>Нет доступного времени на выбранную дату</p>';
                }
            })
            .catch(error => {
                console.error('Error loading time slots:', error);
                slotsContainer.innerHTML = '<p>Ошибка при загрузке доступного времени</p>';
            });
    }

    // Функция для обновления цены при выборе сотрудника
    function updatePrice(cartItemId, employeeId) {
        const cartItem = document.querySelector(`.cart-item[data-id="${cartItemId}"]`);
        if (!cartItem) return;

        const priceElement = cartItem.querySelector('.cart-item-price');
        const totalElement = cartItem.querySelector('.cart-item-total');
        const quantityElement = cartItem.querySelector('.quantity');
        const totalCartElement = document.querySelector('.total-price');

        if (!priceElement || !totalElement || !quantityElement || !totalCartElement) return;

        // Находим выбранного сотрудника
        const employeeRadio = document.querySelector(`input[name="employee_id"][value="${employeeId}"]`);
        if (!employeeRadio) return;

        // Получаем цену из карточки сотрудника
        const employeePriceElement = employeeRadio.closest('.employee-option').querySelector('.employee-price');
        if (!employeePriceElement) return;

        // Извлекаем цену из текста элемента
        const priceText = employeePriceElement.textContent.trim();
        const price = parseFloat(priceText.replace(/\D/g, ''));

        if (isNaN(price)) return;

        // Обновляем цену за единицу услуги
        priceElement.textContent = `${number_format(price, 0, '', ' ')} руб.`;

        // Обновляем общую сумму для элемента
        const quantity = parseInt(quantityElement.textContent);
        totalElement.textContent = `${number_format(price * quantity, 0, '', ' ')} руб.`;

        // Обновляем итоговую сумму корзины
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const itemPrice = parseFloat(item.querySelector('.cart-item-price').textContent.replace(/\D/g, ''));
            const itemQuantity = parseInt(item.querySelector('.quantity').textContent);
            total += itemPrice * itemQuantity;
        });

        totalCartElement.textContent = number_format(total, 0, '', ' ');
    }

    // Функция для форматирования чисел (аналог PHP функции number_format)
    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        let n = !isFinite(+number) ? 0 : +number;
        let prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
        let sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
        let dec = (typeof dec_point === 'undefined') ? '.' : dec_point;

        let s = '';
        let toFixedFix = function (n, prec) {
            let k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };

        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    // Обработчики событий для выбора сотрудника
    document.querySelectorAll('input[name="employee_id"]').forEach(input => {
        input.addEventListener('change', function() {
            const cartItemId = this.closest('.cart-employee-selection').dataset.cartItemId;
            const employeeId = this.value;
            const dateInput = document.getElementById(`appointment_date_${cartItemId}`);

            // Обновляем цену
            updatePrice(cartItemId, employeeId);

            // Загружаем слоты времени, если дата уже выбрана
            if (dateInput.value) {
                loadTimeSlots(employeeId, dateInput.value, cartItemId);
            }
        });
    });

    // Обработчики событий для выбора даты
    document.querySelectorAll('input[name="appointment_date"]').forEach(input => {
        input.addEventListener('change', function() {
            const cartItemId = this.id.replace('appointment_date_', '');
            const employeeIdInput = document.querySelector(`input[name="employee_id"]:checked`);

            if (employeeIdInput && this.value) {
                loadTimeSlots(employeeIdInput.value, this.value, cartItemId);
            }
        });
    });

    // Делаем функции доступными глобально
    window.selectTimeSlot = selectTimeSlot;
    window.loadTimeSlots = loadTimeSlots;
    window.updatePrice = updatePrice;
});
