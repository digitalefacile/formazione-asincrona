document.addEventListener('DOMContentLoaded', () => {
    const jsonDataElement = document.getElementById('user-roles-data');

    // Check if the URL contains 'percorsi-digitali.php'
    const urlContainsHomeAlt = window.location.href.includes('percorsi-digitali.php') || window.location.href.includes('percorsi-digitali') || window.location.href.includes('informativa-percorsi-digitali.php') || window.location.href.includes('informativa-percorsi-digitali');

    let hasstd = false;
    let isAdmin = false;

    if (jsonDataElement) {
        try {
            const roles = JSON.parse(jsonDataElement.textContent);
            // console.log('User roles:', roles);

            // Check if roles contain 'std'
            hasstd = roles.includes('std') && !roles.includes('editingteacher') && !roles.includes('coursecreator') && !roles.includes('rfd') && !roles.includes('scd');
            // Check if roles contain 'admin'
            isAdmin = roles.includes('editingteacher');
        } catch (error) {
            console.error('Failed to parse user roles JSON:', error);
        }
    }

    // Apply the same behavior if 'std' is in roles or 'percorsi-digitali.php' is in the URL
    if (hasstd || urlContainsHomeAlt) {
        // get every a with href that contains logout.php
        const logoutLinks = document.querySelectorAll('a[href*="logout.php"]');
        // console.log("logoutLinks", logoutLinks);
        // loop and add &isstd=1 to the href
        logoutLinks.forEach(link => {
            const url = new URL(link.href);
            url.searchParams.set('isstd', '1');
            link.href = url.toString();
        });
        // console.log("Condition met: 'std' in roles or 'percorsi-digitali.php' in URL");

        // get element header_top home2
        const headerTop = document.querySelector('.header_top.home2');
        if (headerTop) {
            // inside get container-fluid test-middle-header
            const containerFluid = headerTop.querySelector('.container-fluid.test-middle-header');
            if (containerFluid) {
                // inside get divs
                const divs = containerFluid.querySelectorAll('div');
                const div = divs[1]; // get the second div
                if (div) {
                    const innerDivs = div.querySelectorAll('div');
                    const innerDiv1 = innerDivs[0]; // get the first inner div
                    const innerDiv2 = innerDivs[1]; // get the second inner div
                    if (innerDiv1 && innerDiv2) {
                        // change innerhTML of the first inner div
                        innerDiv1.innerHTML = 'Percorsi digitali';
                        // change innerhTML of the second inner div
                        innerDiv2.innerHTML = 'La tua educazione online sul mondo digitale';
                    }
                }
            }
        }

        // get header.ccnHeader2
        const headerCcnHeader2 = document.querySelector('header.ccnHeader2');
        // console.log("headerCcnHeader2", headerCcnHeader2);
        if (headerCcnHeader2) {
            const ulElementsContainer = headerCcnHeader2.querySelector('ul.ace-responsive-menu');
            // console.log("ulElementsContainer", ulElementsContainer);
            if (ulElementsContainer ) {
                // get li
                const liElements = ulElementsContainer.querySelectorAll('li');
                // loop through every li elements and in the loop get the A tag
                liElements.forEach(li => {
                    const aTag = li.querySelector('a');
                    if (aTag) {
                        // if the innerHTML of the A transofmred to strtolower contains 'facilita', make the element display none
                        if (aTag.innerHTML.toLowerCase().includes('facilita')) {
                            li.style.display = 'none';
                        }
                    }
                });
            }
        }

        // mobile
        // get nav id menu
        const mobileMenu = document.querySelector('nav#menu');
        if (mobileMenu) {
            // div id mm-1
            const mm1Div = mobileMenu.querySelector('div#mm-1');
            if (mm1Div) {
                // get every li.mm-listitem
                const mmListItems = mm1Div.querySelectorAll('li.mm-listitem');
                // loop through every li.mm-listitem and in the loop get the A tag
                mmListItems.forEach(item => {
                    const aTag = item.querySelector('a');
                    if (aTag) {
                        // if the innerHTML of the A transofmred to strtolower contains 'facilita', make the element display none
                        if (aTag.innerHTML.toLowerCase().includes('facilita')) {
                            item.style.display = 'none';
                        }
                    }
                });
            }
        }

        // get div#page class stylehome1 home2 h0
        const pageDiv = document.querySelector('div#page.stylehome1.home2.h0');
        // console.log("pageDiv", pageDiv);
        // inside get div container-fluid and inside get the 2 divs
        if (pageDiv) {
            const containerFluid = pageDiv.querySelector('div.container-fluid');
            if (containerFluid) {
                const innerDivs = containerFluid.querySelectorAll('div');
                // get div[0]
                const firstInnerDiv = innerDivs[0];
                if (firstInnerDiv) {
                    // get the div inside and change the innerHTML to 'Percorsi digitali'
                    const innerDiv = firstInnerDiv.querySelector('div');
                    if (innerDiv) {
                        innerDiv.innerHTML = 'Percorsi digitali';
                    }
                }
            }
        }

        // elemento tab
        // get el class block_cocoon_tabs 
        const tabElement = document.querySelector('.block_cocoon_tabs');
        // console.log("tabElement", tabElement);
        // if ok get ul.nav nav-tabs
        if (tabElement) {
            // get ul.nav.nav-tabs
            const navTabs = tabElement.querySelector('ul.nav.nav-tabs');
            if (navTabs) {
                // display none
                navTabs.style.display = 'none';
            }
        }

        // page il mio percorso
        // get div.container-fluid block-myoverview block-cards block-my-journey
        const myJourneyDiv = document.querySelector('div.container-fluid.block-myoverview.block-cards.block-my-journey');
        // console.log("myJourneyDiv", myJourneyDiv);
        // get h2.subtitle and change text to lorem ipsum studenti
        if (myJourneyDiv) {
            // const title h1.title
            const title = myJourneyDiv.querySelector('h1.title');
            if (title) {
                title.innerHTML = 'Il mio percorso digitale';
            }
            const subtitle = myJourneyDiv.querySelector('h2.subtitle');
            if (subtitle) {
                subtitle.innerHTML = 'Gestisci la tua formazione e monitora i tuoi progressi passo dopo passo. Completa i corsi che hai attivato e scegli i nuovi corsi da seguire.';
            }

            const discoverCoursesSection = document.querySelector('div.container-fluid.discover-courses-section');
            // console.log("discoverCoursesSection", discoverCoursesSection);
            if (discoverCoursesSection) {
                // get div container-fluid subtitle
                const subtitleDiv = discoverCoursesSection.querySelector('div.container-fluid.subtitle');
                // console.log("subtitleDiv", subtitleDiv);
                if (subtitleDiv) {
                    // change innerHTML
                    subtitleDiv.innerHTML = 'Esplora le aree tematiche dei corsi e perfeziona le tue competenze in ogni ambito del digitale.';
                }
            }
        }

        // get div container-fluid blog-header
        const blogHeaderDiv = document.querySelector('div.container-fluid.blog-header');
        // console.log("blogHeaderDiv", blogHeaderDiv);
        // if ok get inside h2 blogDescription
        if (blogHeaderDiv) {
            const blogDescription = blogHeaderDiv.querySelector('h2.blogDescription');
            // console.log("blogDescription", blogDescription);
            if (blogDescription) {
                // change innerHTML
                blogDescription.innerHTML = 'Lo spazio dedicato alle <strong>comunicazioni da non perdere</strong> per gestire al meglio la tua formazione.</bold';
            }
        }

        // footer
        // section class footer_middle_area pt-5 pb-5  
        const footerSection = document.querySelector('section.footer_middle_area.pt-5.pb-5');
        if (footerSection) {
            // console.log("footerSection", footerSection);
            // get a tags and loop, if a tag contains privacy change url to google.com new tab (for test)
            const aTags = footerSection.querySelectorAll('a');
            aTags.forEach(a => {
                const text = a.innerHTML.trim().toLowerCase(); // Trim and convert to lowercase
                // console.log("a", text);
                if (text.includes('privacy')) {
                    // console.log("Changing privacy link to /privacy-std.php");
                    // setTimeout to avoid
                    setTimeout(() => {
                        a.href = '/informativa-percorsi-digitali.php';
                        a.target = '_self'; // open in the same tab
                    }, 1000); // Delay to ensure the DOM is ready
                }
            });
        }

        // get div.footer_one
        const footerOneSection = document.querySelector('section.footer_one');
        if (footerOneSection) {
            // get every a tag, if text contains 'facilita' set to display none
            const footerLinks = footerOneSection.querySelectorAll('a');
            footerLinks.forEach(link => {
                const text = link.innerHTML.trim().toLowerCase(); // Trim and convert to lowercase
                if (text.includes('facilita')) {
                    // console.log("Hiding facilità link in footer");
                    link.style.display = 'none';
                }
            });
        }

        // find item .section_availability.course-description-item
        const availabilityItem = document.querySelectorAll('.section_availability.course-description-item');
        if (availabilityItem) {
            availabilityItem.forEach(item => {
                // display none
                item.style.display = 'none';
            });
        }

    } else {
        // console.log("Condition not met");
    }

    if (!isAdmin) {
        // get li user_setting ccn-settings-nav
        const userSettingLi = document.querySelector('li.user_setting.ccn-settings-nav');
        if (userSettingLi) {
            // remove the li element
            userSettingLi.remove();
        }
    }

});
