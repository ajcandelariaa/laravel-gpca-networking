function menuButtonClicked(){
    let mainContainer = document.getElementById('main-container');
    let sideBar = document.getElementById('sidebar');
    let gpcaNetworkingLogo = document.getElementById('sidebar-image');
    let mainNavigation = document.getElementById('main-navigation');
    let sideBarTitles = mainNavigation.querySelectorAll('p');
    let sideBarAnchor = mainNavigation.querySelectorAll('a');

    if(sideBar.classList.contains('sidebar-full')){
        sideBar.classList.remove("sidebar-full");
        sideBar.classList.add('sidebar-half');

        mainContainer.classList.remove('main-grid-full');
        mainContainer.classList.add('main-grid-half');

        gpcaNetworkingLogo.classList.remove('sidebar-full-image');
        gpcaNetworkingLogo.classList.add('sidebar-half-image');

        sideBarTitles.forEach(element =>  {
            element.classList.add('sidebar-title');
        });

        sideBarAnchor.forEach(element =>  {
            element.classList.add('justify-center');
        });
        
    } else {
        sideBar.classList.add("sidebar-full");
        sideBar.classList.remove('sidebar-half');

        mainContainer.classList.add('main-grid-full');
        mainContainer.classList.remove('main-grid-half');

        gpcaNetworkingLogo.classList.add('sidebar-full-image');
        gpcaNetworkingLogo.classList.remove('sidebar-half-image');

        sideBarTitles.forEach(element =>  {
            element.classList.remove('sidebar-title');
        });

        sideBarAnchor.forEach(element =>  {
            element.classList.remove('justify-center');
        });
    }
}