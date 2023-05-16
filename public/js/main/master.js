function menuButtonClicked(){
    let sideBar = document.getElementById('sidebar');
    let gpcaNetworkingLogo = document.getElementById('sidebar-image');
    let sideBarTitles = document.querySelectorAll('.sidebar-title');

    if(sideBar.classList.contains('sidebar-full')){
        sideBar.classList.remove("sidebar-full");
        sideBar.classList.add('sidebar-half');

        gpcaNetworkingLogo.classList.remove('sidebar-full-image');
        gpcaNetworkingLogo.classList.add('sidebar-half-image');

        sideBarTitles.forEach(element =>  {
            element.classList.add('scale-0');
        });
        
    } else {
        sideBar.classList.add("sidebar-full");
        sideBar.classList.remove('sidebar-half');

        gpcaNetworkingLogo.classList.add('sidebar-full-image');
        gpcaNetworkingLogo.classList.remove('sidebar-half-image');

        sideBarTitles.forEach(element =>  {
            element.classList.remove('scale-0');
        });
    }
}