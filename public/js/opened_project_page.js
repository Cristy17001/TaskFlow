document.addEventListener("DOMContentLoaded", function () {
  /////////////////////////////////////////////////////////////////////////////////////////////////////
  // Making so that when the window is opened with the id of the task it opens it
  // Get the hash part of the current URL
  const hash = window.location.hash;
  const tasks = document.querySelectorAll(".modal-task-card");

  // Check if a hash is present
  if (hash) {
    // Remove the leading '#' and parse the remaining part as an integer
    const id = parseInt(hash.substring(1));

    // Check if the parsing was successful
    if (!isNaN(id)) {
      console.log("ID:", id);
      tasks.forEach((modal, _) => {
        const itemId = modal.getAttribute("itemid");

        if (itemId && parseInt(itemId) === id) {
          modal.showModal();
        }
      });
    } else {
      console.log("Invalid ID in the URL");
    }
  } else {
    console.log("No hash found in the URL");
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////

  /////////////////////////////////////////////////////////////////////////////////////////////////////
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

  /////////////////////////////////////////////////////////////////////////////////////////////////
  const task_cards = document.querySelectorAll(".task-card");

  // Logic to open the task details
  task_cards.forEach((card, _) => {
    const itemId = card.getAttribute("itemid");

    if (itemId) {
      // Use querySelector with the attribute selector to select the card
      const modal_selected_card = document.querySelector(
        `.modal-task-card[itemid="${itemId}"]`
      );

      if (modal_selected_card) {
        handleModal(card, modal_selected_card);
      } else {
        console.log(`No modal found for itemid: ${itemId}`);
      }

      console.log(itemId);
    } else {
      console.log("Itemid is null");
    }
  });
  //////////////////////////////////////////////////////////////////////////////////////////////////
  // Logic to use the expand button that shows details of the project
  const expand_button = document.querySelector(".btn-expand-info");
  if (expand_button) {
    expand_button.addEventListener("click", function () {
      const project_details = document.querySelector(".project-details");
      const team = document.querySelector(".container-team");

      if (project_details && team) {
        project_details.classList.remove("hidden");
        expand_button.classList.add("hidden");
        team.classList.add("hidden");
        console.log("Expand");
      } else {
        console.log("Project details or team is null");
      }
    });
  }

  const close_details = document.querySelector(".btn-close-details");
  if (close_details) {
    close_details.addEventListener("click", function () {
      const project_details = document.querySelector(".project-details");
      const team = document.querySelector(".container-team");

      if (project_details && team) {
        project_details.classList.add("hidden");
        expand_button.classList.remove("hidden");
        team.classList.remove("hidden");
        console.log("closed");
      } else {
        console.log("Project details or team is null");
      }
    });
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////

  // Logic to edit the details of the project
  const modal_edit_details = document.getElementById("modal-edit-project");
  const add_edit_details_btn = document.querySelector(".btn-edit-details");
  handleModal(add_edit_details_btn, modal_edit_details);

  //////////////////////////////////////////////////////////////////////////////////////////////////
  // Logic to Manage Team
  const modal_manage_team = document.getElementById("modal-manage-team");
  const manage_team_btn = document.querySelector(".btn-manage-team");
  handleModal(manage_team_btn, modal_manage_team);

  //////////////////////////////////////////////////////////////////////////////////////////////////

  // Logic to Create Task
  const modal_create_task = document.getElementById("modal-create-task");
  const create_task_btn = document.querySelector(".btn-create-task");
  handleModal(create_task_btn, modal_create_task);

  //////////////////////////////////////////////////////////////////////////////////////////////////

  // Logic to edit the task information
  const modals_edit_task = document.querySelectorAll(".modal-task-card");
  modals_edit_task.forEach((modal_task) => {
    const edit_btn = modal_task.querySelector(".edit");
    const edit_modal = document.querySelector(
      `.modal-task-card-edit[itemid="${modal_task.getAttribute("itemid")}"]`
    );

    if (edit_btn && edit_modal) {
      edit_btn.addEventListener("click", () => {
        modal_task.close();
        edit_modal.showModal();
        addCloseModalOnClickOutside(edit_modal);
      });
    } else {
      console.log("Edit button or edit modal is null");
    }
  });

  const create_task_form = document.getElementById("taskForm");
  const leaveProjectBtn = document.getElementById('leaveProjectBtn');

  const archiveProjectBtn = document.getElementById('archiveProjectBtn');
  const unarchiveProjectBtn = document.getElementById('unarchiveProjectBtn');


  const addLabelButton = create_task_form ? create_task_form.querySelector('.add-label-button') : null;
  const labelsContainer = create_task_form ? create_task_form.querySelector('.container-labels') : null;
  const labelsInput = create_task_form ? create_task_form.querySelector('.add-labels-input') : null;
  const labelNames = [];

  // Add labels
  if (addLabelButton) {
    addLabelButton.addEventListener('click', function (event) {
      event.preventDefault();
      const newLabel = labelsInput.value.trim();

      // Check if the label is not empty, not only whitespace, and not already in the labelNames array
      if (newLabel !== '' && !labelNames.includes(newLabel)) {
        // Add a new entry to labelsContainer
        const newLabelSpan = document.createElement('span');
        newLabelSpan.textContent = newLabel;
        newLabelSpan.dataset.labelName = newLabel;

        const removeLabelButton = document.createElement('span');
        removeLabelButton.classList.add('remove-label');
        removeLabelButton.textContent = 'X';

        removeLabelButton.addEventListener('click', function () {
          // Remove the parent label
          newLabelSpan.remove();

          // Remove the label from the labelNames array
          const indexToRemove = labelNames.indexOf(newLabel);
          if (indexToRemove !== -1) {
            labelNames.splice(indexToRemove, 1);
          }

          // Log the updated array to the console
          console.log('Labels:', labelNames);
        });

        newLabelSpan.appendChild(removeLabelButton);
        labelsContainer.appendChild(newLabelSpan);

        // Add the label to the labelNames array
        labelNames.push(newLabel);

        // Log the updated array to the console
        console.log('Labels:', labelNames);

        // Clear the input field after adding the label
        labelsInput.value = '';
      }
    });
  }

  if (create_task_form) {
    create_task_form.addEventListener("submit", function (event) {
      event.preventDefault();

      const formData = new FormData(create_task_form);

      formData.append('actual_labels[]', labelNames);

      fetch('/tasks/createTask', {
        method: 'POST',
        body: formData
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok.');
          }
          return response.json();
        })
        .then(data => {
          console.log('Task added successfully:', data);
          location.reload();
        })
        .catch(error => {
          console.error('There was a problem adding the task:', error);
        });
    });
  }


  // Get all elements with the class 'btn-remove-member'
  const removeMemberBtns = document.querySelectorAll('.btn-remove-member');

  // Iterate through each button
  removeMemberBtns.forEach(removeMemberBtn => {
    removeMemberBtn.addEventListener('click', function () {
      // Ask for confirmation
      const confirmLeave = confirm('Are you sure you want to remove that member from the project?');

      // If the user confirms, proceed with the action
      if (confirmLeave) {
        const projectId = this.getAttribute('data-project-id');
        const userId = this.getAttribute('data-user-id');
        const data = {
          user_id: userId,
          project_id: projectId
        };

        fetch(`/projects/deleteMember`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
          },
          body: JSON.stringify(data),
        })
          .then(response => {
            if (response.ok) {
              window.location.href = `/projects/${projectId}`;
            } else {
              console.error('Failed to remove member from the project');
            }
          });
      }
    });
  });

  // Get all elements with the class 'btn-promote-member'
  const promoteMemberBtns = document.querySelectorAll('.btn-promote-member');

  // Iterate through each button
  promoteMemberBtns.forEach(promoteMemberBtn => {
    promoteMemberBtn.addEventListener('click', function () {
      // Ask for confirmation
      const confirmLeave = confirm('Are you sure you want to promote that member in that project?');

      // If the user confirms, proceed with the action
      if (confirmLeave) {
        const projectId = this.getAttribute('data-project-id');
        const userId = this.getAttribute('data-user-id');
        const data = {
          user_id: userId,
          project_id: projectId
        };

        fetch(`/projects/promoteMember`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
          },
          body: JSON.stringify(data),
        })
          .then(response => {
            if (response.ok) {
              window.location.href = `/projects/${projectId}`;
            } else {
              console.error('Failed to promote member from the project');
            }
          });
      }
    });
  });


  if (unarchiveProjectBtn) {
    unarchiveProjectBtn.addEventListener('click', function () {
      // Ask for confirmation
      const confirmLeave = confirm('Are you sure you want to unarchive the project?');

      // If the user confirms, proceed with the action
      if (confirmLeave) {
        const projectId = this.getAttribute('data-project-id');
        fetch(`/projects/${projectId}/unarchiveProject`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
          },
        })
          .then(response => {
            if (response.ok) {
              window.location.href = '/projects';
            } else {
              console.error('Failed to archive the project');
            }
          });
      }
    });
  }

  if (archiveProjectBtn) {
    archiveProjectBtn.addEventListener('click', function () {
      // Ask for confirmation
      const confirmLeave = confirm('Are you sure you want to archive the project?');

      // If the user confirms, proceed with the action
      if (confirmLeave) {
        const projectId = this.getAttribute('data-project-id');
        fetch(`/projects/${projectId}/archiveProject`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
          },
        })
          .then(response => {
            if (response.ok) {
              window.location.href = '/projects';
            } else {
              console.error('Failed to archive the project');
            }
          });
      }
    });
  }

  if (leaveProjectBtn) {
    leaveProjectBtn.addEventListener('click', function () {
      // Ask for confirmation
      const confirmLeave = confirm('Are you sure you want to leave the project?');

      // If the user confirms, proceed with the action
      if (confirmLeave) {
        const projectId = this.getAttribute('data-project-id');
        fetch(`/projects/${projectId}/leaveProject`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
          },
        })
          .then(response => {
            if (response.ok) {
              window.location.href = '/projects';
            } else {
              console.error('Failed to leave the project');
            }
          });
      }
    });
  }

  if (create_task_btn) {
    create_task_btn.addEventListener("click", function () {
      create_task_form.reset();
    });
  }

  // Mark as favourite
  const starCheckbox = document.querySelector('.container input[type=checkbox]');

  starCheckbox.addEventListener('change', function () {
    // Check if the checkbox is checked
    const data = {
      isFavorite: starCheckbox.checked,
    };
    const projectId = this.getAttribute('data-project-id');
    fetch(`/projects/${projectId}/markFavourite`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.csrfToken,
      },
      body: JSON.stringify(data),
    })
      .then(response => {
        if (response.ok) {
          console.log('Manage to mark favourite as', isFavorite);
          console.log(response.json);
        } else {
          console.error('Failed to mark project as favourite!');
        }
      });
  });

});