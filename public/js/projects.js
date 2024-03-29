document.addEventListener('DOMContentLoaded', function () {
    const btn_create_project = document.querySelector(".btn-create-project");
    const modal_create_project = document.getElementById("modal-create-project");

    function openCreateProject() {
        modal_create_project.showModal();
    }

    btn_create_project.addEventListener('click', openCreateProject);
    modal_create_project.addEventListener('click', e => {
        const dialogDimensions = modal_create_project.getBoundingClientRect()
        if (
            e.clientX < dialogDimensions.left ||
            e.clientX > dialogDimensions.right ||
            e.clientY < dialogDimensions.top ||
            e.clientY > dialogDimensions.bottom
        ) {
            modal_create_project.close()
        }
    })

    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //  NOTE: LOGIC TO SUBMIT THE FORM IN THE navigation.js
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    const filterSelect = document.getElementById('filter');
    if (filterSelect) {
        const url = window.location.href;
        // Extract the filter criteria from the URL
        const match = url.match(/\/projects\/filter\/(\w+)/);
        if (match && match[1]) {
            // Iterate through the options and select the one that matches the filter value
            for (let i = 0; i < filterSelect.options.length; i++) {
                if (filterSelect.options[i].value === match[1]) {
                    filterSelect.selectedIndex = i;
                    break;
                }
            }
        }

        filterSelect.addEventListener('change', function () {
            const selectedValue = filterSelect.value;
            console.log(selectedValue);

            // Redirect to the selected URL
            if (selectedValue != "All") {
                window.location.href = `/projects/filter/${selectedValue}`;
            }
            else {
                window.location.href = `/projects`;
            }
        });

    }
});