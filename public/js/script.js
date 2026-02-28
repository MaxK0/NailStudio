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
});
