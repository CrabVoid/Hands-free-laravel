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

document.querySelectorAll('[data-tag-manager]').forEach((manager) => {
    const form = manager.querySelector('[data-tag-form]');
    const input = manager.querySelector('[data-tag-input]');
    const suggestionList = manager.querySelector('[data-tag-suggestions]');
    const tagList = manager.querySelector('[data-tag-list]');
    let suggestions = [];
    let activeSuggestionIndex = -1;
    let searchController;

    const closeSuggestions = () => {
        suggestions = [];
        activeSuggestionIndex = -1;
        suggestionList.replaceChildren();
        suggestionList.hidden = true;
        input.setAttribute('aria-expanded', 'false');
    };

    const selectedTagNames = () => Array.from(tagList.querySelectorAll('input[name="tags[]"]'))
        .map((tagInput) => tagInput.value.toLocaleLowerCase());

    const addTag = (name) => {
        const normalizedName = name.trim();

        if (!normalizedName || selectedTagNames().includes(normalizedName.toLocaleLowerCase())) {
            input.value = '';
            closeSuggestions();

            return;
        }

        const chip = document.createElement('li');
        chip.className = 'tag-chip';
        chip.dataset.tagChip = '';

        const label = document.createElement('span');
        label.textContent = normalizedName;

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'tags[]';
        hiddenInput.value = normalizedName;

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.textContent = '×';
        removeButton.setAttribute('aria-label', `Noņemt birku ${normalizedName}`);
        removeButton.dataset.removeTag = '';

        chip.append(label, hiddenInput, removeButton);
        tagList.append(chip);
        input.value = '';
        closeSuggestions();
        input.focus();
    };

    const renderSuggestions = () => {
        suggestionList.replaceChildren();

        suggestions.forEach((tag, index) => {
            const item = document.createElement('li');
            item.setAttribute('role', 'option');

            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = tag.name;
            button.setAttribute('aria-selected', String(index === activeSuggestionIndex));
            button.addEventListener('click', () => addTag(tag.name));

            item.append(button);
            suggestionList.append(item);
        });

        suggestionList.hidden = suggestions.length === 0;
        input.setAttribute('aria-expanded', String(suggestions.length > 0));
    };

    input.addEventListener('input', async () => {
        const query = input.value.trim();
        searchController?.abort();

        if (!query) {
            closeSuggestions();

            return;
        }

        searchController = new AbortController();
        const searchUrl = new URL(manager.dataset.searchUrl, window.location.origin);
        searchUrl.searchParams.set('query', query);

        try {
            const response = await fetch(searchUrl, {
                headers: { Accept: 'application/json' },
                signal: searchController.signal,
            });

            if (!response.ok) {
                closeSuggestions();

                return;
            }

            const results = await response.json();
            const selected = selectedTagNames();
            suggestions = results.filter((tag) => !selected.includes(tag.name.toLocaleLowerCase()));
            activeSuggestionIndex = suggestions.length > 0 ? 0 : -1;
            renderSuggestions();
        } catch (error) {
            if (error.name !== 'AbortError') {
                closeSuggestions();
            }
        }
    });

    input.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowDown' && suggestions.length > 0) {
            event.preventDefault();
            activeSuggestionIndex = (activeSuggestionIndex + 1) % suggestions.length;
            renderSuggestions();
        } else if (event.key === 'ArrowUp' && suggestions.length > 0) {
            event.preventDefault();
            activeSuggestionIndex = (activeSuggestionIndex - 1 + suggestions.length) % suggestions.length;
            renderSuggestions();
        } else if (event.key === 'Enter') {
            event.preventDefault();
            addTag(suggestions[activeSuggestionIndex]?.name ?? input.value);
        } else if (event.key === 'Escape') {
            closeSuggestions();
        }
    });

    tagList.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-remove-tag]');

        if (removeButton) {
            removeButton.closest('[data-tag-chip]').remove();
        }
    });

    form.addEventListener('submit', () => closeSuggestions());
});
