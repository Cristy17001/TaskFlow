document.addEventListener('DOMContentLoaded', function () {
    // On input execute the function
    const usernameInput = document.querySelector("#invite-member");
    if (usernameInput) {
        usernameInput.addEventListener('input', onInputChange);
        usernameInput.addEventListener('blur', function () {
            setTimeout(() => {
                removeAutocompleteDropdown();
            }, 200);
        });
    }

    function createAutocompleteDropdown(list) {
        const listEl = document.createElement("ul");
        const wrapper = document.querySelector(".autocomplete-wrapper");
        listEl.className = "autocomplete-list";
        listEl.id = "autocomplete-list";

        list.forEach(result => {
            const listItem = document.createElement('li');
            const button = document.createElement('button');
            button.addEventListener("click", optionClick);
            button.textContent = result;
            listItem.appendChild(button);
            listEl.appendChild(listItem);
        });
        wrapper.appendChild(listEl);
    }

    function removeAutocompleteDropdown() {
        const listEl = document.querySelector(".autocomplete-list");
        if (listEl) listEl.remove();
    }

    function optionClick(e) {
        e.preventDefault();

        const buttonEl = e.target;
        usernameInput.value = buttonEl.innerHTML;

        removeAutocompleteDropdown();
    }

    function onInputChange() {
        removeAutocompleteDropdown();

        const searchTerm = usernameInput.value;
        const project_id = usernameInput.getAttribute('data-project-id');

        if (searchTerm.length === 0) return;

        if (searchTerm.trim() !== '') {
            fetch(`/searchUsername/${project_id}/${searchTerm}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            })
                .then(response => response.json())
                .then(data => {
                    createAutocompleteDropdown(data);
                })
                .catch(error => console.error('Error fetching data:', error));
        } else {
            removeAutocompleteDropdown();
        }
    }
});
