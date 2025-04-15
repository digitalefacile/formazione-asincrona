document.addEventListener('DOMContentLoaded', function() {
    var totalModalBody = document.getElementById('zendesk-modal-body');
    if (totalModalBody) {
        var modalBodyRoleName = totalModalBody.getAttribute('rolename');
        if (modalBodyRoleName == 'guest') {
            var modalLoginButton = document.querySelector('.color-icon-user.login-button-container');
            // inside get a.login-button
            var modalLoginButton = modalLoginButton.querySelector('a.login-button');
            if (modalLoginButton) {
                var modalLoginButtonHref = modalLoginButton.getAttribute('href');
                // console.log('Initial Modal login button href:', modalLoginButtonHref);
                // Crea un MutationObserver per monitorare i cambiamenti
                var observer = new MutationObserver(function(mutationsList) {
                    mutationsList.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'href') {
                            modalLoginButtonHref = modalLoginButton.getAttribute('href');
                            // console.log('Updated Modal login button href:', modalLoginButtonHref);
                        }
                    });
                });
                // Configura l'osservatore per monitorare gli attributi
                observer.observe(modalLoginButton, { attributes: true });
                // interrompere l'osservazione quando non è più necessaria
                // observer.disconnect();
            }
        }
        // console.log('Modal body role name:', modalBodyRoleName);
    }
    require(['core/modal_factory', 'core/modal_events', 'core/str'], function(ModalFactory, ModalEvents, str) {
        str.get_strings([
            {key: 'modalheader', component: 'local_zendesk_modal'},
            {key: 'close', component: 'local_zendesk_modal'},
            {key: 'confirm', component: 'local_zendesk_modal'},
            {key: 'modaltitle', component: 'local_zendesk_modal'},
            {key: 'login', component: 'local_zendesk_modal'},
        ]).done(function(strings) {
            document.querySelectorAll('.open-zendesk-modal-button').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var modalbody = document.getElementById('zendesk-modal-body').innerHTML;
                    var modalFooter = `
                        <button type="button" class="btn btn-cancel" data-action="close">${strings[1]}</button>
                        <button type="button" class="btn btn-confirm" data-action="confirm">${strings[2]} 
                            <svg width="22" height="22" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.8787 13.9443L22.3524 1.4706H15.4411C15.035 1.4706 14.7058 1.1414 14.7058 0.735309C14.7058 0.329217 15.035 1.54972e-05 15.4411 1.54972e-05H23.5293V0C23.7022 0 23.8682 0.0298433 24.0223 0.0846602C24.592 0.287307 24.9999 0.831317 24.9999 1.4706H24.9999V9.55881C24.9999 9.9649 24.6707 10.2941 24.2646 10.2941C23.8585 10.2941 23.5293 9.9649 23.5293 9.55881V2.3734L10.9186 14.9841C10.7233 15.1794 10.4067 15.1794 10.2115 14.9841L9.8787 14.6514C9.68344 14.4561 9.68344 14.1395 9.8787 13.9443ZM20.6029 12.5C20.6029 12.0939 20.9321 11.7647 21.3382 11.7647C21.7329 11.7725 22.051 12.0906 22.0588 12.4853V20.5882C22.0588 23.0248 20.0836 25 17.6471 25H4.41176C1.97521 25 0 23.0248 0 20.5882V7.35296C0 4.91641 1.97521 2.9412 4.41176 2.9412H11.7647C12.1675 2.94903 12.4922 3.27369 12.5 3.6765C12.5 4.08259 12.1708 4.41179 11.7647 4.41179H4.41176C2.7874 4.41179 1.47059 5.7286 1.47059 7.35296V20.5882C1.47059 22.2126 2.7874 23.5294 4.41176 23.5294H17.6618C19.2861 23.5294 20.6029 22.2126 20.6029 20.5882V12.5Z" fill="white"/>
                            </svg>
                        </button>
                    `;
                    if (modalBodyRoleName == 'editingteacher' || modalBodyRoleName == 'coursecreator' || modalBodyRoleName == 'teacher') {
                        var modalFooter = `<button type="button" class="btn btn-cancel" data-action="close">${strings[1]}</button>`;
                    }
                    if (modalBodyRoleName == 'guest') {
                        var modalFooter = `
                        <button type="button" class="btn btn-cancel" data-action="close">${strings[1]}</button>
                        <button type="button" class="btn btn-confirm" data-action="login">${strings[4]}</button>
                        `;
                    }
                    ModalFactory.create({
                        type: ModalFactory.types.DEFAULT,
                        title: '',
                        body: modalbody,
                        footer: modalFooter,
                        large: false // Aggiungi questa linea per una modale più grande
                    }).done(function(modal) {
                        modal.getRoot().addClass('zendesk-modal'); // Aggiungi la classe personalizzata
                        modal.getRoot().on(ModalEvents.hidden, function() {
                            modal.destroy();
                        });
                        modal.getRoot().on('click', '[data-action="close"]', function() {
                            modal.hide();
                        });
                        modal.getRoot().on('click', '[data-action="confirm"]', function() {
                            // Aggiungi qui la logica per il pulsante di conferma
                            window.open(M.cfg.wwwroot + '/local/zendesk', '_blank');
                            modal.hide();
                        });
                        modal.getRoot().on('click', '[data-action="login"]', function() {
                            // Aggiungi qui la logica per il pulsante di login
                            window.location.href = modalLoginButtonHref;
                            modal.hide();
                        });
                        modal.show();
                    });
                });
            });
        }).fail(function() {
            console.error('Failed to load strings for zendesk modal.');
        });
    });

    // if page loaded, you are in the home page and there is a get parameter gotohometab in the url then scroll to the home tab
    // if (window.location.pathname === '/' && window.location.search.indexOf('gotohometab') > -1) {
    //     setTimeout(goToHomeTab, 1000);
    // }

    // function goToHomeTab() {
    //     var targetElement = document.getElementById('myTab');
    //     if (targetElement) {
    //         targetElement.scrollIntoView({ block: 'start' });
    //         window.scrollBy(0, -150); // Scroll 150px più in alto

    //         // inside targetElement, find the first tab li.nav-item and click it
    //         var tab = targetElement.querySelector('li.nav-item');
    //         if (tab) {
    //             // find a
    //             var a = tab.querySelector('a');
    //             if (a) {
    //                 a.click();
    //             }
    //         }
    //     }
    // }

});