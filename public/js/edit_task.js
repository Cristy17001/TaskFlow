document.addEventListener('DOMContentLoaded', function () {
    const editForms = document.querySelectorAll('.edit-modal-form');

    editForms.forEach(editForm => {
        const saveButton = editForm.querySelector('input[type="submit"]');
        const assigneUserSelect = editForm.querySelector('.assigne-user select');
        const avatarsContainer = editForm.querySelector('.avatars-container');
        const addLabelButton = editForm.querySelector('.add-label-button');
        const labelsContainer = editForm.querySelector('.container-labels');
        const labelsInput = editForm.querySelector('#add-labels');

        // Initialize the addedAssignees array with existing assignees
        const existingAssignees = avatarsContainer.querySelectorAll('.member-container');
        let addedAssignees = Array.from(existingAssignees).map(existingAssignee => {
            const assigneeId = existingAssignee.querySelector('p').dataset.userId;
            const removeButton = existingAssignee.querySelector('.remove-member-from-task');

            removeButton.addEventListener('click', function () {
                // Remove the parent member-container
                existingAssignee.remove();

                // Remove the assignee from the addedAssignees array
                const indexToRemove = addedAssignees.indexOf(assigneeId);
                if (indexToRemove !== -1) {
                    addedAssignees.splice(indexToRemove, 1);
                }

                // Log the updated array to the console
                console.log('Team:', addedAssignees);
            });

            return assigneeId;
        });

        // Initialize the addedLabels array with existing labels
        const existingLabels = labelsContainer.querySelectorAll('span:not(.remove-label)');
        let addedLabels = Array.from(existingLabels).map(existingLabel => {
            return Array.from(existingLabel.childNodes)
                .filter(node => node.nodeType === Node.TEXT_NODE)
                .map(textNode => textNode.textContent.trim())
                .join('');
        });

        // Event delegation for removing labels
        labelsContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-label')) {
                const removedLabel = event.target.parentNode.dataset.labelName;
                event.target.parentNode.remove();

                // Remove the label from the addedLabels array
                const indexToRemove = addedLabels.indexOf(removedLabel);
                if (indexToRemove !== -1) {
                    addedLabels.splice(indexToRemove, 1);
                }

                // Log the updated array to the console
                console.log('Labels:', addedLabels);
            }
        });

        addLabelButton.addEventListener('click', function (event) {
            event.preventDefault();
            const newLabel = labelsInput.value.trim();

            // Check if the label is not empty and not already in the addedLabels array
            if (newLabel !== '' && !addedLabels.includes(newLabel)) {
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

                    // Remove the label from the addedLabels array
                    const indexToRemove = addedLabels.indexOf(newLabel);
                    if (indexToRemove !== -1) {
                        addedLabels.splice(indexToRemove, 1);
                    }

                    // Log the updated array to the console
                    console.log('Labels:', addedLabels);
                });

                newLabelSpan.appendChild(removeLabelButton);
                labelsContainer.appendChild(newLabelSpan);

                // Add the label to the addedLabels array
                addedLabels.push(newLabel);

                // Log the updated array to the console
                console.log('Labels:', addedLabels);

                // Clear the input field after adding the label
                labelsInput.value = '';
            }
        });

        // Add an event listener for the assigneUserSelect change event
        assigneUserSelect.addEventListener('change', function () {
            const selectedAssignee = this.options[this.selectedIndex];
            const userId = selectedAssignee.dataset.userId;

            // Check if the assignee is not already in the addedAssignees array
            if (!addedAssignees.includes(userId)) {
                // Add a new entry to avatarsContainer
                const newMemberContainer = document.createElement('div');
                newMemberContainer.classList.add('member-container');

                const newMemberName = document.createElement('p');
                newMemberName.textContent = selectedAssignee.text;
                newMemberName.dataset.userId = userId;

                const removeButton = document.createElement('button');
                removeButton.classList.add('remove-member-from-task');
                removeButton.innerHTML = '<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.41131 10.6737C1.41131 11.0534 1.96881 11.5747 2.34048 11.5747H7.91553C8.2872 11.5747 8.84471 11.0534 8.84471 10.6737V3.46559H1.41131V10.6737ZM9.58805 1.40992H7.60581L6.61469 0.221924H3.64133L2.65021 1.40992H0.667969V2.59791H9.58805V1.40992Z" fill="white"></path></svg>';

                removeButton.addEventListener('click', function () {
                    // Remove the parent member-container
                    newMemberContainer.remove();

                    // Remove the assignee from the addedAssignees array
                    const indexToRemove = addedAssignees.indexOf(userId);
                    if (indexToRemove !== -1) {
                        addedAssignees.splice(indexToRemove, 1);
                    }

                    // Log the updated array to the console
                    console.log('Team:', addedAssignees);
                });

                newMemberContainer.appendChild(newMemberName);
                newMemberContainer.appendChild(removeButton);

                avatarsContainer.appendChild(newMemberContainer);

                // Add the assignee to the addedAssignees array
                addedAssignees.push(userId);

                console.log(`Assignee changed to: ${selectedAssignee.text}`);
            }
        });

        saveButton.addEventListener('click', function (event) {
            event.preventDefault();

            // Retrieve values for the current form
            const title = editForm.querySelector('#title').value;
            const description = editForm.querySelector('#description').value;
            const dueDate = editForm.querySelector('#due-date').value;
            const prioritySelect = editForm.querySelector('#priority');
            const selectedPriority = prioritySelect.options[prioritySelect.selectedIndex].value;
            const statusSelect = editForm.querySelector('#status');
            const selectedStatus = statusSelect.options[statusSelect.selectedIndex].value;

            // Use the retrieved values as needed for the current form
            console.log(`Form ID: ${editForm.id}`);
            console.log('Title:', title);
            console.log('Description:', description);
            console.log('Due Date:', dueDate);
            console.log('Priority:', selectedPriority);
            console.log('Status:', selectedStatus);
            console.log('Team:', addedAssignees);
            console.log('Labels:', addedLabels);

            // Construct the data object
            const taskId = this.getAttribute('data-task-id');
            const data = {
                title: title,
                description: description,
                dueDate: dueDate,
                priority: selectedPriority,
                status: selectedStatus,
                team: addedAssignees,
                labels: addedLabels,
                taskId: taskId
            };

            // Make a PUT request using the fetch API
            const projectId = this.getAttribute('data-project-id');

            fetch(`/projects/${projectId}/saveTaskChanges`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle the response data if needed
                    console.log('PUT request successful', data);
                    window.location.href = `/projects/${projectId}`;
                })
                .catch(error => {
                    // Handle errors during the fetch
                    console.error('Error during PUT request', error);
                });
        });
    });
});
