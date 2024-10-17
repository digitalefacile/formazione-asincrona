// domcontentready
document.addEventListener('DOMContentLoaded', function() {
    var pageTitle = document.querySelector('title').innerText;
    // get element header.ccnHeader2
    var ccnHeader2 = document.querySelector('header.ccnHeader2');
    // console.log(ccnHeader2);
    // get nav > ul > li
    var navUlLi = ccnHeader2.querySelectorAll('nav > ul > li');
    // remove last li
    navUlLi = Array.prototype.slice.call(navUlLi, 0, -1);
    // console.log(navUlLi);

    var facilitaATag = null;
    var ilMioPercorsoATag = null;

    // loop through navUlLi
    for (var i = 0; i < navUlLi.length; i++) {
        // get a element
        var a = navUlLi[i].querySelector('a');
        var lowerCaseText = a.innerText.toLowerCase();
        // check if a contains the word 'Facilita'
        if (lowerCaseText.includes('facilita')) {
            facilitaATag = a;
            // get li parent of a and style float right
            var li = a.parentElement;
            // console.log(li);
            li.style.float = 'right';
        }

        if (lowerCaseText == 'il mio percorso') {
            ilMioPercorsoATag = a;
            // console.log('Il mio percorso found');
            // console.log(ilMioPercorsoATag);
        }
    }

    // from page title use space as separator and loop through the words
    var pageTitleWords = pageTitle.split(' ');
    for (var i = 0; i < pageTitleWords.length; i++) {
        // get the word in lower case
        var lowerCaseWord = pageTitleWords[i].toLowerCase();
        // check if word contains 'facilita'
        if (lowerCaseWord === 'facilita') {
            // get span.after-element from ilMioPercorsoATag and set to display none
            // settimeout 0.8s
            setTimeout(function() {
                var afterElement = ilMioPercorsoATag.querySelector('span.after-element');
                afterElement.style.display = 'none';
            }, 300);
            // add span.after-element to facilitaATag
            var afterElement = document.createElement('span');
            afterElement.className = 'after-element';
            facilitaATag.appendChild(afterElement);
        }
    }
});