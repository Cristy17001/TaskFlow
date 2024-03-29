document.addEventListener('DOMContentLoaded', function () {
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    // Logic for the modal to create a project to work
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    const btn_create_project = document.getElementById("nav-add-btn");
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
    // Logic to hide navigation bar
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    const navigation_bar = document.querySelector("nav");
    const btn_colaspse = document.querySelector(".expand-button");
    btn_colaspse.addEventListener('click', e => {
        navigation_bar.classList.toggle('collapse_navbar');
    })


});