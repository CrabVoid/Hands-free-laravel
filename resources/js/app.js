document.documentElement.classList.add('js');

document.querySelectorAll('[data-quantity-form]').forEach((form) => {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const feedback = document.querySelector('[data-quantity-feedback]');
        const quantity = document.querySelector('[data-quantity-value]');
        const formData = new FormData(form);
        const csrfToken = formData.get('_token');

        submitButton.disabled = true;
        feedback.textContent = 'Saglabā izmaiņas…';

        try {
            const response = await fetch(form.action, {
                method: 'PATCH',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new URLSearchParams(formData),
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message ?? 'Neizdevās atjaunināt produkta daudzumu.');
            }

            quantity.textContent = result.quantity;
            feedback.textContent = result.message;
            feedback.setAttribute('role', 'status');
        } catch (error) {
            feedback.textContent = error.message;
            feedback.setAttribute('role', 'alert');
        } finally {
            submitButton.disabled = false;
        }
    });
});
