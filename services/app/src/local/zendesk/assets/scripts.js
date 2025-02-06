document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('zendesk-ticket-form');

    if (form) {
        const attachmentInput = document.getElementById('attachment');
        const attachmentList = document.querySelector('.attachment-list ul');
        const attachmentSpinner = document.querySelector('.attachment-container .spinner');
        const formSpinner = document.querySelector('.form-spinner');
        const submitButton = form.querySelector('button[type="submit"]');
        const successMessage = document.getElementById('zendesk-form-success-message');
        let uploadTokens = [];
    
        attachmentInput.addEventListener('change', function() {
            console.log('File selected:', this.files[0]);
            const file = this.files[0];
            if (file) {
                const formData = new FormData(form);
                showAttachmentSpinner();

                fetch(form.getAttribute('uploadurl'), {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        uploadTokens.push(data.upload_token);
                        const listItem = document.createElement('li');
                        listItem.classList.add('attachment-item');
                        listItem.innerHTML = `
                            <a>${file.name}</a>
                            <button type="button" class="btn btn-danger btn-sm remove-attachment" data-upload-token="${data.upload_token}">Remove</button>
                        `;
                        attachmentList.appendChild(listItem);

                        // reset the file input
                        attachmentInput.value = '';
    
                        listItem.querySelector('.remove-attachment').addEventListener('click', function() {
                            const token = this.getAttribute('data-upload-token');
                            uploadTokens = uploadTokens.filter(t => t !== token);
                            listItem.remove();
                            console.log(uploadTokens);
                        });

                        console.log('File uploaded:', data);
                        console.log('Upload tokens:', uploadTokens);
                        hideAttachmentSpinner();
                    } else {
                        alert('Errore durante il caricamento del file: ' + data.response);
                        hideAttachmentSpinner();
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    hideAttachmentSpinner();
                });
            }
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            console.log('Form submitted:', form);

            const formData = new FormData(form);
            const actionUrl = form.getAttribute('action');

            // Aggiungi i token degli upload al formData
            formData.append('upload_tokens', JSON.stringify(uploadTokens));

            showFormSpinner(); // Mostra lo spinner dell'intero form

            // Invia il form
            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideFormSpinner(); // Nascondi lo spinner dell'intero form
                // console.log('Ticket submitted:', data);
                if (data.success) {
                    showSuccessMessage();
                } else {
                    alert('An error occurred while submitting the ticket: ' + data.response);
                }
            })
            .catch(error => {
                hideFormSpinner(); // Nascondi lo spinner dell'intero form
                console.error('Error:', error);
                alert('An error occurred while submitting the ticket.');
            });
        });

        function showAttachmentSpinner() {
            attachmentSpinner.style.display = '';
            disableSubmitButton();
        }
        function hideAttachmentSpinner() {
            attachmentSpinner.style.display = 'none';
            enableSubmitButton();
        }
        function showFormSpinner() {
            formSpinner.style.display = '';
            disableSubmitButton();
        }
        function hideFormSpinner() {
            formSpinner.style.display = 'none';
            enableSubmitButton();
        }
        function disableSubmitButton() {
            submitButton.disabled = true;
        }
        function enableSubmitButton() {
            submitButton.disabled = false;
        }
        function showSuccessMessage() {
            successMessage.style.display = '';
            form.style.display = 'none';
        }

    } else {
        console.error('Form not found in the DOM');
    }

});
