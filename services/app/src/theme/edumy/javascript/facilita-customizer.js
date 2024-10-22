// domcontentready
document.addEventListener('DOMContentLoaded', function() {
    var pageTitle = document.querySelector('title').innerText;
    // get element header.ccnHeader2
    var ccnHeader2 = document.querySelector('header.ccnHeader2');
    // get nav > ul > li
    var navUlLi = ccnHeader2.querySelectorAll('nav > ul > li');
    // remove last li
    // navUlLi = Array.prototype.slice.call(navUlLi, 0, -1);

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
            li.style.float = 'right';
        }

        if (lowerCaseText == 'il mio percorso') {
            ilMioPercorsoATag = a;
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

            // get all elements with class breadcrumb_content
            var breadcrumbContents = document.querySelectorAll('div.breadcrumb_content');
            // iterate over each breadcrumbContent
            breadcrumbContents.forEach(function(breadcrumbContent) {
                // inside breadcrumbContent get li elements and loop through them
                var breadcrumbLi = breadcrumbContent.querySelectorAll('li');
                for (var i = 0; i < breadcrumbLi.length; i++) {
                    // get a element
                    var a = breadcrumbLi[i].querySelector('a');
                    // if a is not null get href attribute
                    if (a != null) {
                        var href = a.getAttribute('href');
                        // if href contains courses.php set li parent to display none
                        if (href.includes('courses.php')) {
                            breadcrumbLi[i].remove();
                        }
                    }
                }
            });
            // after loop get again breadcrumbContents, if there are only two elements and one has textlowercase 'home', delete breadcrumbContent
            breadcrumbContents = document.querySelectorAll('div.breadcrumb_content');
            breadcrumbContents.forEach(function(breadcrumbContent) {
                var breadcrumbLi = breadcrumbContent.querySelectorAll('li');
                console.log(breadcrumbLi);
                if (breadcrumbLi.length == 2) {
                    var a = breadcrumbLi[0].querySelector('a');
                    if (a.innerText.toLowerCase() == 'home') {
                        // style display block important, using attribute, to a parent (li)
                        a.parentElement.setAttribute('style', 'display: block !important; padding-right: .5rem;');
                    }
                    // get li[1]::before and set content to '>'
                    var activeLi = breadcrumbLi[1];
                    // add class facilita-breadcrumb-after-home
                    activeLi.classList.add('facilita-breadcrumb-after-home');
                    // beforeElement.style.content = '/';
                }
            });


            // change icon replace /page/ with /scorm/
            // get div.course-content
            var courseContent = document.querySelector('div.course-content');
            // inside get ul.flexsections
            var flexSections = courseContent.querySelector('ul.flexsections');
            // inside get li.section
            var sections = flexSections.querySelectorAll('li.section');
            // loop through sections
            sections.forEach(function(section) {
                // inside find img.courseicon
                var courseIcons = section.querySelectorAll('img.activityicon');
                // if courseIcon is not null
                if (courseIcons.length > 0) {
                    // loop through courseIcons
                    courseIcons.forEach(function(courseIcon) {
                        // get src attribute
                        var src = courseIcon.getAttribute('src');
                        // if src contains monologo
                        if (src.includes('/page/')) {
                            // replace /page/ with /scorm/
                            src = src.replace('/page/', '/scorm/');
                            // set src attribute
                            courseIcon.setAttribute('src', src);
                        }
                    });
                }
            });

            // get el #theme_boost-drawers-courseindex
            var courseIndex = document.querySelector('#theme_boost-drawers-courseindex');
            // add class facilita-course-index
            if (courseIndex != null) {
                courseIndex.classList.add('facilita-course-index');
            }
        }
    }
});