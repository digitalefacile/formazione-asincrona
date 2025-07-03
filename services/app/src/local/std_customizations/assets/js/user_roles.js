document.addEventListener('DOMContentLoaded', () => {
    const jsonDataElement = document.getElementById('user-roles-data');

    // Check if the URL contains 'home-alt.php'
    const urlContainsHomeAlt = window.location.href.includes('home-alt.php');

    let hasStd2 = false;

    if (jsonDataElement) {
        try {
            const roles = JSON.parse(jsonDataElement.textContent);
            console.log('User roles:', roles);

            // Check if roles contain 'std2'
            hasStd2 = roles.includes('std2');
        } catch (error) {
            console.error('Failed to parse user roles JSON:', error);
        }
    }

    // Apply the same behavior if 'std2' is in roles or 'home-alt.php' is in the URL
    if (hasStd2 || urlContainsHomeAlt) {
        console.log("Condition met: 'std2' in roles or 'home-alt.php' in URL");

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
                        innerDiv1.innerHTML = 'titolo';
                        // change innerhTML of the second inner div
                        innerDiv2.innerHTML = 'descrizione';
                    }
                }
            }
        }

        // Add your custom logic here
    } else {
        console.log("Condition not met");
    }
});
