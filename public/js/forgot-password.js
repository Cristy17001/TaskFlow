document.addEventListener('DOMContentLoaded', function () {
    let anchor = this.getElementById('forgot-password-a');
    let modal = this.getElementById('forgot-password-modal');
    anchor.addEventListener('click', function (event) {
        event.preventDefault();
        modal.showModal();
    });

    modal.addEventListener("click", (e) => {
        const dialogDimensions = modal.getBoundingClientRect();
        if (
            e.clientX < dialogDimensions.left ||
            e.clientX > dialogDimensions.right ||
            e.clientY < dialogDimensions.top ||
            e.clientY > dialogDimensions.bottom
        ) {
            modal.close();
        }
    });
    const sendEmailBtn = document.getElementById("send-email-btn");
    sendEmailBtn.addEventListener("click", function (event) {
        event.preventDefault(); 
        const email = document.getElementById("forgot-email").value;
        const token = document.head.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token

        fetch(`/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token 
            },
            body: JSON.stringify({ email: email }),
        })
        .then(response => {
            console.log('Email sent successfully');
        })
        .catch(error => {
            console.error('Error sending email:', error);
        });
    });

    const googleButton = document.getElementById('google-btn');
    googleButton.addEventListener('click', function() {
        window.location.href = '/auth/google'; 
    });
});