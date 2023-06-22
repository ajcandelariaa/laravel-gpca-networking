// ADD EVENT LOGO & BANNER
function previewEventLogo(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('imgEventLogo');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function previewEventLogoInverted(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('imgEventLogoInverted');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function previewAppSponsorLogo(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('imgAppSponsorLogo');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}



function previewEventSplashScreen(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('imgEventSplashScreen');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function previewEventBanner(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('imgEventBanner');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function previewAppSponsorBanner(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('imgAppSponsorBanner');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}