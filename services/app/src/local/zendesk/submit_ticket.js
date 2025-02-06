document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('zendesk-ticket-form');

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            console.log('Form submitted:', form);

            const formData = new FormData(form);
            const actionUrl = form.getAttribute('action');
            const uploadUrl = form.getAttribute('uploadurl');

            // Se è presente un allegato, caricalo prima di inviare il form
            if (formData.get('attachment')) {
                fetch(uploadUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Upload response:', data);
                    if (data.success === true) {
                        // Aggiungi il token dell'upload al formData
                        formData.append('upload_token', data.upload_token);
                        // Invia il form
                        return fetch(actionUrl, {
                            method: 'POST',
                            body: formData
                        });
                    } else {
                        throw new Error(data.response);
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Ticket submitted:', data);
                    alert('Ticket submitted successfully!');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the ticket.');
                });
            } else {
                // Invia il form direttamente se non ci sono allegati
                fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Ticket submitted:', data);
                    alert('Ticket submitted successfully!');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the ticket.');
                });
            }
        });
    } else {
        console.error('Form not found in the DOM');
    }
});
