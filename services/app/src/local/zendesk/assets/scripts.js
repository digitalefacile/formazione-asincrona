document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('zendesk-ticket-form');

    if (form) {
        const attachmentInput = document.getElementById('attachment');
        const attachmentList = document.querySelector('.attachment-list');
        const attachmentSpinner = document.querySelector('.attachment-container .spinner');
        const formSpinner = document.querySelector('.form-spinner');
        const submitButton = form.querySelector('button[type="submit"]');
        const successMessage = document.getElementById('zendesk-form-success-message');
        const errorMessage = document.getElementById('zendesk-form-error-message');
        const backToRequestButton = document.getElementById('back-to-request-button');
        const customFieldsContainer = form.querySelector('.custom-fields-container');
        const thematicAreaSelect = document.getElementById('thematic_area');
        const otherThematicAreaContainer = document.getElementById('other_thematic_area_container');
        const otherThematicAreaInput = document.getElementById('other_thematic_area');
        const fileNameContainer = document.getElementById('file-name');
        const uploader = document.querySelector('.fitem.uploader');
        const descriptionTextarea = document.getElementById('description');
        var descriptionTextareaContent = '';
        const descriptionErrorMessage = descriptionTextarea.nextElementSibling;
        let uploadTokens = [];
        const closeButtons = document.querySelectorAll('.zendesk-form-close-button');
        const subjectInput = document.getElementById('subject');
        const fields = form.querySelectorAll('input, textarea, select');

        // description on change console log
        descriptionTextarea.addEventListener('change', function() {
            descriptionTextareaContent = descriptionTextarea.value.trim().replace(/<[^>]+>/g, '').trim();
        });

        // Custom select logic
        var x, i, j, l, ll, selElmnt, a, b, c;
        x = document.getElementsByClassName("custom-select");
        l = x.length;
        for (i = 0; i < l; i++) {
            selElmnt = x[i].getElementsByTagName("select")[0];
            ll = selElmnt.length;
            a = document.createElement("DIV");
            a.setAttribute("class", "select-selected placeholder");
            a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
            x[i].appendChild(a);
            b = document.createElement("DIV");
            b.setAttribute("class", "select-items select-hide");
            for (j = 1; j < ll; j++) {
                c = document.createElement("DIV");
                c.innerHTML = selElmnt.options[j].innerHTML;
                c.addEventListener("click", function(e) {
                    var y, i, k, s, h, sl, yl;
                    s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                    sl = s.length;
                    h = this.parentNode.previousSibling;
                    for (i = 0; i < sl; i++) {
                        if (s.options[i].innerHTML == this.innerHTML) {
                            s.selectedIndex = i;
                            h.innerHTML = this.innerHTML;
                            y = this.parentNode.getElementsByClassName("same-as-selected");
                            yl = y.length;
                            for (k = 0; k < yl; k++) {
                                y[k].removeAttribute("class");
                            }
                            this.setAttribute("class", "same-as-selected");
                            // Trigger change event for the original select element
                            var event = new Event('change');
                            s.dispatchEvent(event);
                            break;
                        }
                    }
                    h.click();
                });
                b.appendChild(c);
            }
            x[i].appendChild(b);
            a.addEventListener("click", function(e) {
                e.stopPropagation();
                closeAllSelect(this);
                this.nextSibling.classList.toggle("select-hide");
                this.classList.toggle("select-arrow-active");
            });
        }

        function closeAllSelect(elmnt) {
            var x, y, i, xl, yl, arrNo = [];
            x = document.getElementsByClassName("select-items");
            y = document.getElementsByClassName("select-selected");
            xl = x.length;
            yl = y.length;
            for (i = 0; i < yl; i++) {
                if (elmnt == y[i]) {
                    arrNo.push(i)
                } else {
                    y[i].classList.remove("select-arrow-active");
                }
            }
            for (i = 0; i < xl; i++) {
                if (arrNo.indexOf(i)) {
                    x[i].classList.add("select-hide");
                }
            }
        }

        document.addEventListener("click", closeAllSelect);

        thematicAreaSelect.addEventListener('change', function() {
            var select = document.getElementsByClassName("select-selected");
            // remove class placeholder from select-selected
            select[0].classList.remove("placeholder");
            if (this.value === 'altro__specificare_') {
                otherThematicAreaContainer.style.display = '';
                otherThematicAreaInput.setAttribute('required', 'required');
            } else {
                otherThematicAreaContainer.style.display = 'none';
                otherThematicAreaInput.removeAttribute('required');
                otherThematicAreaInput.value = '';
            }
            toggleFormFields(this.value !== "Seleziona l'area tematica");
        });

        function toggleFormFields(enable) {
            const fields = form.querySelectorAll('.fitem');
            fields.forEach(field => {
                if (field.id !== 'thematic_area_container') {
                    if (enable) {
                        field.removeAttribute('disabled');
                    } else {
                        field.setAttribute('disabled', 'disabled');
                    }
                }
            });
        }

        toggleFormFields(thematicAreaSelect.value !== "Seleziona l'area tematica");

        attachmentInput.addEventListener('change', function() {
            handleFileUpload(this.files[0]);
        });

        uploader.addEventListener('dragover', function(event) {
            event.preventDefault();
            event.stopPropagation();
            uploader.classList.add('dragging');
        });

        uploader.addEventListener('dragleave', function(event) {
            event.preventDefault();
            event.stopPropagation();
            uploader.classList.remove('dragging');
        });

        uploader.addEventListener('drop', function(event) {
            event.preventDefault();
            event.stopPropagation();
            uploader.classList.remove('dragging');
            const file = event.dataTransfer.files[0];
            handleFileUpload(file);
        });

        function handleFileUpload(file) {
            if (file) {

                var fileSize = file.size / 1024 / 1024; // Convert to MB
                if (fileSize > 40) {
                    alert('Il file è troppo grande. La dimensione massima è di 40MB.');
                    return;
                }

                // fileNameContainer.textContent = file.name;
                const formData = new FormData(form);
                formData.append('attachment', file);
                formData.append('sesskey', form.querySelector('input[name="sesskey"]').value);
                showAttachmentSpinner();

                fetch(form.getAttribute('uploadurl'), {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        uploadTokens.push(data.upload_token);
                        const listItem = document.createElement('div');
                        listItem.classList.add('fitem', 'attachment-list-item');
                        // listItem.innerHTML = `
                        //     <a ${data.duplicate_upload ? `href="${data.upload_url}" target="_blank"` : ''}>${file.name}</a>
                        //     <button type="button" class="btn btn-danger btn-sm remove-attachment" data-upload-token="${data.upload_token}">Remove</button>
                        // `;

                        listItem.innerHTML = `
                        <div class="fitemtitle">
                            <label>ALLEGA FILE <!--<img src="/local/zendesk/assets/images/info-icon.svg">--></label>
                        </div>
                        <div class="row m-0">
                            <div class="col-6 filename pl-md-0">
                                <a ${data.duplicate_upload ? `href="${data.upload_url}" target="_blank"` : ''}><label>${file.name}</label></a>
                            </div>
                            <div class="col-6 delete pr-md-0">
                                <img src="/local/zendesk/assets/images/delete-icon.svg" alt="Delete Icon" class="remove-attachment" data-upload-token="${data.upload_token}">
                            </div>
                        </div>
                        <div class="under-text">
                            <p>Il limite di dimensione per i file è di 40MB</p>
                        </div>
                    `;

                        attachmentList.appendChild(listItem);

                        // reset the file input
                        attachmentInput.value = '';
                        // fileNameContainer.textContent = '';

                        listItem.querySelector('.remove-attachment').addEventListener('click', function() {
                            const token = this.getAttribute('data-upload-token');
                            uploadTokens = uploadTokens.filter(t => t !== token);
                            listItem.remove();
                        });

                        hideAttachmentSpinner();
                    } else {
                        // alert('Errore durante il caricamento del file: ' + data.response);
                        showErrorMessage();
                        hideAttachmentSpinner();
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    hideAttachmentSpinner();
                });
            }
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            // if descriptioncontent is empty show error message and exit, else hide error message
            if (descriptionTextareaContent === '') {
                descriptionErrorMessage.style.display = '';
                return;
            } else {
                descriptionErrorMessage.style.display = 'none';
            }

            const formData = new FormData(form);
            const actionUrl = form.getAttribute('action');
            const updatesubjecturl = form.getAttribute('updatesubjecturl');

            // manipola formdata description
            // convert to serialized string
            const description = formData.get('description');
            
            const descriptionSerialized = new URLSearchParams(description).toString();
            // // replace the description with the serialized string
            formData.set('description', descriptionSerialized);

            // const turndownService = new TurndownService();
            // // convert to markdown
            // const descriptionMarkdown = turndownService.turndown(description);
            // formData.set('description', descriptionMarkdown);

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
                if (data.success) {
                    var ticketId = data.response.ticket.id;
                    var ticketSubject = data.response.ticket.subject;

                    var newSubject = ticketSubject + " (ticket n. " + ticketId + ")";

                    var updateSubjectFormData = new FormData(form);
                    updateSubjectFormData.append('ticket_id', ticketId);
                    updateSubjectFormData.append('ticket_subject', newSubject);

                    fetch(updatesubjecturl, {
                        method: 'POST',
                        body: updateSubjectFormData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideFormSpinner(); // Nascondi lo spinner dell'intero form
                        console.log(data);
                        if (data.success) {
                            showSuccessMessage(ticketId);
                        } else {
                            hideFormSpinner(); // Nascondi lo spinner dell'intero form
                            showErrorMessage();
                        }
                    }).catch(error => {
                        hideFormSpinner(); // Nascondi lo spinner dell'intero form
                        showErrorMessage();
                        console.error('Error:', error);
                    });
                } else {
                    hideFormSpinner();
                    showErrorMessage();
                }
            })
            .catch(error => {
                hideFormSpinner(); // Nascondi lo spinner dell'intero form
                showErrorMessage();
                console.error('Error:', error);
            });
        });

        closeButtons.forEach(closeButton => {
            closeButton.addEventListener('click', function(event) {
                event.preventDefault();
                let isFormFilled = false;

                const fieldsToCheck = [thematicAreaSelect, otherThematicAreaInput, subjectInput, descriptionTextarea];
                fieldsToCheck.forEach(field => {
                    if (field === thematicAreaSelect && field.value === "Seleziona l'area tematica") {
                        // Skip the default option
                        return;
                    }
                    if (field.value.trim() !== '') {
                        isFormFilled = true;
                    }
                });

                if (isFormFilled) {
                    require(['core/modal_factory', 'core/modal_events', 'core/str'], function(ModalFactory, ModalEvents, str) {
                        str.get_strings([
                            {key: 'modalheader', component: 'local_zendesk'},
                            {key: 'modal_button_cancel', component: 'local_zendesk'},
                            {key: 'modal_button_confirm', component: 'local_zendesk'},
                            {key: 'modal_body', component: 'local_zendesk'}
                        ]).done(function(strings) {
                            // create div.img-container element, put inside img src = /local/zendesk/assets/images/form-alert-icon.svg
                            const imgContainer = document.createElement('div');
                            imgContainer.classList.add('img-container');
                            const img = document.createElement('img');
                            img.src = '/local/zendesk/assets/images/form-alert-icon.svg';
                            imgContainer.appendChild(img);
                            ModalFactory.create({
                                type: ModalFactory.types.DEFAULT,
                                // title: strings[0],
                                body: imgContainer.outerHTML + strings[3],
                                footer: `
                                    <button type="button" class="btn btn-cancel" data-action="close">${strings[1]}</button>
                                    <button type="button" class="btn btn-confirm" data-action="confirm">${strings[2]}</button>
                                `,
                                large: false
                            }).done(function(modal) {
                                modal.getRoot().addClass('zendesk-modal zendesk-form-modal');
                                modal.getRoot().on(ModalEvents.hidden, function() {
                                    modal.destroy();
                                });
                                modal.getRoot().on('click', '[data-action="close"]', function() {
                                    modal.hide();
                                });
                                modal.getRoot().on('click', '[data-action="confirm"]', function() {
                                    window.close();
                                });
                                modal.show();
                            });
                        }).fail(function() {
                            console.error('Failed to load strings for zendesk modal.');
                        });
                    });
                } else {
                    window.close();
                }
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
        function showSuccessMessage(ticketID) {
            // replace {{TICKET_ID}} with ticketID
            successMessage.innerHTML = successMessage.innerHTML.replace(/{{TICKET_ID}}/g, ticketID);
            successMessage.style.display = '';
            form.style.display = 'none';
        }
        function showErrorMessage() {
            errorMessage.style.display = '';
            form.style.display = 'none';
        }
        function returnFromErrorMessage() {
            errorMessage.style.display = 'none';
            form.style.display = '';
        }
        // if backtoRequestButton exists add event listener
        if (backToRequestButton) {
            backToRequestButton.addEventListener('click', function() {
                returnFromErrorMessage();
            });
        }

        function toBase64(str) {
            return btoa(String.fromCharCode(...new TextEncoder().encode(str)));
        }


    } else {
        console.error('Form not found in the DOM');
    }

});
