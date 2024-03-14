/* Espansione automatica sezioni su clic da indice corso */
document.addEventListener("DOMContentLoaded", function() {

    // Se non sono nella pagina del corso, esco
    if (!document.body.id.startsWith("page-course-view")) {
        return;
    }

    // Se la pagina contiene un hash, si posiziona in corrispondenza dell'elemento con quell'id
    const preloader = document.querySelector('.ccn_preloader_load'); // NB: bisogna attendere che il preloader sia nascosto

    // Legge l'altezza dell'header per perfezionare lo scroll
    let headerHeight = document.querySelector('.header-nav').offsetHeight;

    // // Prima di tutto stabilisco se sono nella pagina scorm
    // scormPage = false;
    // if (window.location.href.includes('scorm')) {
    //     scormPage = true;
    // }

    function scrollToHashElement(hash) {
        let element = document.getElementById(hash);
        
        if (preloader && preloader.style.display !== 'none') {
            const observer = new MutationObserver((mutationsList, observer) => {
                for (const mutation of mutationsList) {
                    if (mutation.attributeName === 'style' && preloader.style.display === 'none') {
                        observer.disconnect();
                        element.scrollIntoView();
                        window.scrollBy(0, -headerHeight);
                    } 
                }
            });

            observer.observe(preloader, { attributes: true });
        } else {
            element.scrollIntoView();
            window.scrollBy(0, -headerHeight);
        }
    }    

    function searchAndExpand(target) {

        let sectionIds = [];
        let currentElement = document.getElementById(target);
        let topClass = '';
        let matchClass = '';

        // 1. Configura le classi da controllare per risalire l'albero a seconda dell'elemento di partenza (target)
        // 1.1 Se è un link (quindi la funzione è stata chiamata in seguito a un clic)
        if (currentElement.tagName === 'A') {
            // Perform the desired operation for <a> or <li> elements
            topClass = '.courseindex';
            matchClass = '.courseindex-section';
        }
        // 1.2 Se è un div (quindi la funzione è stata chiamata in apertura di pagina)
        else if (currentElement.tagName === 'LI') {
            // Perform the desired operation for <div> elements
            topClass = '.course-content';
            matchClass = '.course-section';
        }        

        // 2.1. Controlla se inizia con "module"
        if (target.startsWith("module")) {
            // 3. Procedi con l'espansione (se necessario)
        }
        // 2.2. Se inizia con "section"
        else if (target.startsWith("section")) {
            let sectionNumber = target.split("-")[1];
            sectionIds.push(sectionNumber);
        }

        // 3. Individua altri elementi antenati e esegui la stessa operazione
        while (!currentElement.parentElement.matches(topClass)) {
            currentElement = currentElement.parentElement;
                                // Quando incontra una sezione aggiunge l'id a quelli da espandere
            if (currentElement.matches(matchClass)) {
              let sectionId = currentElement.getAttribute('data-number');
              sectionIds.push(sectionId);
            }
        }
      
        // 4. Espandi gli elementi corrispondenti
      let lastSectionId = 0;
        sectionIds.forEach(function(sectionId) {
            let collapseToggle = document.getElementById("coursecontentcollapse" + sectionId);
          
                if (collapseToggle && !collapseToggle.matches('.show')) {

                    if (preloader && preloader.style.display !== 'none') {
                        const observer = new MutationObserver((mutationsList, observer) => {
                            for (const mutation of mutationsList) {
                                if (mutation.attributeName === 'style' && preloader.style.display === 'none') {
                                    jQuery(collapseToggle).collapse('show');
                                } 
                            }
                        });
            
                        observer.observe(preloader, { attributes: true });
                    } else {
                        jQuery(collapseToggle).collapse('show');
                    }
            
                    // var clickEvent = new Event('click');
                    // collapseToggle.dispatchEvent(clickEvent);
                }
          lastSectionId = sectionId;
            });
      
      // 5. dopo tutte le operazioni, segue il link
          if (target) {
            let collapsedDiv = document.getElementById("coursecontentcollapse" + lastSectionId);
            let anchorElement = document.getElementById(target);
            if (anchorElement) {
                // let headerHeight = document.querySelector('.header-nav').offsetHeight;

                scrollToHashElement(target);

                // // Ascolta la fine della transizione
                // collapsedDiv.addEventListener('shown.bs.collapse', function onCollapseShown() {
                //     setTimeout(function() { // Aggiunto un breve ritardo per garantire che il layout sia aggiornato
                //         anchorElement.scrollIntoView();
                //         window.scrollBy(0, -headerHeight);
                    
                //     }, 500);
                //     collapsedDiv.removeEventListener('shown.bs.collapse', onCollapseShown);
                // });

                // anchorElement.scrollIntoView();
                // window.scrollBy(0, -headerHeight);
                
            }
        }
    }

    function addClickListenerToLinks() {
        document.querySelectorAll('a.courseindex-link:not(.event-added)').forEach(function(link) {
            link.classList.add('event-added');
            link.addEventListener('click', function(event) {
                let href = this.getAttribute('href');
                let target = href.split('#')[1];    

                // if(scormPage) return; // Se sono nella pagina scorm non eseguo nulla
                
              	event.preventDefault(); // Previene l'azione di default all'inizio
              	event.stopPropagation(); // !!!!! Evita che alla fine la pag. torni su, ma non sappiamo se ci siano regressioni
                
                searchAndExpand(target);              
            });
        });
    }

    if (window.location.hash) {
        let hash = window.location.hash.substring(1);
        searchAndExpand(hash);
    }

    addClickListenerToLinks(); 
    setInterval(addClickListenerToLinks, 1000); 
});
