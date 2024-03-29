document.addEventListener('DOMContentLoaded', function () {

    // Functions to work with modals
    function addCloseModalOnClickOutside(modalElement) {
        modalElement.addEventListener("click", (e) => {
            const dialogDimensions = modalElement.getBoundingClientRect();
            if (
                e.clientX < dialogDimensions.left ||
                e.clientX > dialogDimensions.right ||
                e.clientY < dialogDimensions.top ||
                e.clientY > dialogDimensions.bottom
            ) {
                modalElement.close();
                const formElement = modalElement.querySelector('form');
                if (formElement) {
                    formElement.reset();
                }
            }
        });
    }

    function handleClickModal(modalElement) {
        return function () {
            modalElement.showModal();
        };
    }

    function handleModal(btn, modal) {
        if (btn && modal) {
            const handleClick = handleClickModal(modal);
            btn.addEventListener("click", handleClick);
            addCloseModalOnClickOutside(modal);
        } else {
            console.log("Button or modal is null");
        }
    }
    // Example usage with modal_add_task and add_task_btn
    //   const modal_add_task = document.getElementById("your-modal-id"); // replace with your actual modal ID
    //   const add_task_btn = document.querySelector(".your-button"); // replace with your actual selector of the button

    //   const handleClick = handleClickModal(modal_add_task);
    //   add_task_btn.addEventListener("click", handleClick);

    const modal_add_user = document.getElementById("modal-add-user");
    const add_user_btn = document.getElementById("add-user-btn");

    handleModal(add_user_btn, modal_add_user);


    const btns_edit_user_info = document.querySelectorAll('.btn-edit');
    if (btns_edit_user_info) {
        btns_edit_user_info.forEach(btn_edit => {
            btn_edit.addEventListener('click', (event) => { event.preventDefault(); });

            const data_index = btn_edit.getAttribute("data-index");
            const modal_edit_info = document.querySelector(`.edit-info-modal[data-index="${data_index}"]`);
            handleModal(btn_edit, modal_edit_info);
        });
    }

});