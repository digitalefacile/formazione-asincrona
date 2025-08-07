// domcontentloaded and print alert
document.addEventListener('DOMContentLoaded', function() {
    // alert('The DOM has loaded');
    // check if current url end in /blog/ or /blog or /blog/index.php with or without parameters
    if (window.location.href.endsWith('/blog/') || window.location.href.endsWith('/blog') || window.location.href.endsWith('/blog/index.php') || window.location.href.includes('/blog/index.php?')) {
        // alert('This is the blog page');
        // get div#ccn-main-region and inside get div#region-main
        var ccnMainRegion = document.getElementById('ccn-main-region');
        var regionMain = ccnMainRegion.querySelector('#region-main');

        // inside regionmain find div.ccn-blog-list-entry and if is not found create a div with text inside regionmain
        var blogListEntry = regionMain.querySelectorAll('.ccn-blog-list-entry');
        setTimeout(function() {
            if (blogListEntry.length == 0) {
                var newDiv = document.createElement('div');
                newDiv.className = 'container-fluid blog-header';
                newDiv.style.backgroundColor = 'transparent';
        
                var innerDiv = document.createElement('div');
                innerDiv.className = 'ccnMdlHeading';
                innerDiv.style.backgroundColor = 'transparent';
        
                var heading = document.createElement('h2');
                heading.className = 'blogDescription';
                heading.style.color = '#FFF';
                heading.innerText = 'Al momento non ci sono annunci di interesse per la tua formazione. Ti invitiamo a consultare la bacheca al tuo prossimo accesso per non perderti le prime novità';
        
                innerDiv.appendChild(heading);
                newDiv.appendChild(innerDiv);
                regionMain.appendChild(newDiv);
            } else {
                console.log('Blog entries found');
                // get ul.page_navigation and console log the element
                var pageNavigation = regionMain.querySelector('ul.page_navigation');
                if (pageNavigation) {
                    // console.log('Page navigation found:', pageNavigation);
                    // get the A tag with aria label (strtolower) next or previous
                    var nextLink = pageNavigation.querySelector('a[aria-label="Next"]');
                    var prevLink = pageNavigation.querySelector('a[aria-label="Previous"]');
                    // if exists, make the text color white
                    if (nextLink) {
                        // Nascondi il testo "Successivo" per accessibilità e rendi l'icona bianca
                        var textNodes = [];
                        for (var i = 0; i < nextLink.childNodes.length; i++) {
                            if (nextLink.childNodes[i].nodeType === 3) { // Text node
                                textNodes.push(nextLink.childNodes[i]);
                            }
                        }
                        // Wrappa i nodi di testo in span nascosti per accessibilità
                        textNodes.forEach(function(node) {
                            if (node.textContent.trim() !== '') {
                                var hiddenSpan = document.createElement('span');
                                hiddenSpan.style.display = 'none';
                                hiddenSpan.textContent = node.textContent;
                                nextLink.insertBefore(hiddenSpan, node);
                                node.remove();
                            }
                        });
                        // Rendi l'icona bianca
                        var icon = nextLink.querySelector('.flaticon-right-arrow-1');
                        if (icon) {
                            icon.style.color = '#FFF';
                            // Aggiungi effetto hover
                            nextLink.addEventListener('mouseenter', function() {
                                icon.style.color = '#0066cc';
                            });
                            nextLink.addEventListener('mouseleave', function() {
                                icon.style.color = '#FFF';
                            });
                        }
                    }
                    if (prevLink) {
                        // Nascondi il testo "Precedente" per accessibilità e rendi l'icona bianca
                        var textNodes = [];
                        for (var i = 0; i < prevLink.childNodes.length; i++) {
                            if (prevLink.childNodes[i].nodeType === 3) { // Text node
                                textNodes.push(prevLink.childNodes[i]);
                            }
                        }
                        // Wrappa i nodi di testo in span nascosti per accessibilità
                        textNodes.forEach(function(node) {
                            if (node.textContent.trim() !== '') {
                                var hiddenSpan = document.createElement('span');
                                hiddenSpan.style.display = 'none';
                                hiddenSpan.textContent = node.textContent;
                                prevLink.insertBefore(hiddenSpan, node);
                                node.remove();
                            }
                        });
                        // Rendi l'icona bianca
                        var icon = prevLink.querySelector('.flaticon-left-arrow');
                        if (icon) {
                            icon.style.color = '#FFF';
                            // Aggiungi effetto hover
                            prevLink.addEventListener('mouseenter', function() {
                                icon.style.color = '#0066cc';
                            });
                            prevLink.addEventListener('mouseleave', function() {
                                icon.style.color = '#FFF';
                            });
                        }
                    }
                }
            }
        }, 500);
        

    }
});