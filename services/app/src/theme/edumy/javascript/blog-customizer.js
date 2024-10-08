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
            }
        }, 500);
        

    }
});