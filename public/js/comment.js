document.addEventListener('DOMContentLoaded', function () {
    const modal_tasks = document.querySelectorAll('.modal-task-card');
    modal_tasks.forEach(modal_task => {
        form_comment = modal_task.querySelector('.writing');

        if (form_comment) {
            form_comment.addEventListener('submit', function (event) {
                event.preventDefault();

                // Retrieve the content of the textarea
                const commentTextArea = modal_task.querySelector('textarea[name="comment"]');
                const commentContent = commentTextArea ? commentTextArea.value : null;

                // Get the task_id from the form or use your preferred method to obtain it
                const task_id = this ? this.getAttribute('data-task-id') : null;

                // Check if required elements are present
                if (!commentContent || !task_id) {
                    console.error('Comment content or task ID is missing!');
                    return;
                }

                // Create the data object for the Ajax request
                const data = {
                    comment: commentContent,
                    task_id: task_id,
                };
                console.log(data);

                // Perform the Ajax request
                const projectId = this ? this.getAttribute('data-project-id') : null;
                if (projectId) {
                    fetch(`/projects/${projectId}/createComment`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.csrfToken,
                        },
                        body: JSON.stringify(data),
                    })
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            } else {
                                console.error('Failed to create a comment!');
                            }
                        })
                        .then(data => {
                            // Access the HTML code from the response
                            const commentHtml = data ? data.html : null;
                            console.log(commentHtml);
                            console.log("Comment successfully submitted!");

                            // If comment already there 
                            const commentsContainer = modal_task.querySelector('.container-comments');

                            if (commentHtml && commentsContainer) {
                                // Create a temporary div element to hold the new comment HTML
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = commentHtml;

                                // Prepend the new comment to the comments container
                                commentsContainer.insertBefore(tempDiv.firstChild, commentsContainer.firstChild);
                                commentTextArea.value = '';
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                        });
                }
            });
        }
    });
});
