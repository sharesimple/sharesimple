if (window.innerWidth <= 700) {
    document.querySelector(".main_container_right").replaceWith(...document.querySelector(".main_container_right").childNodes);
    // main_container_right.replaceWith(...main_container_right.childNodes);
    document.querySelector(".mobile_warning-overlay").style.display = "fixed";
}

if ((window.matchMedia('(display-mode: standalone)').matches) || (window.navigator.standalone) || document.referrer.includes('android-app://'))
    document.querySelector(".pwa_warning-overlay").style.display = "fixed";

var lastsize = window.innerWidth;
window.addEventListener('resize', () => {
    if (window.innerWidth <= 700) {
        document.querySelector(".main_container_right").replaceWith(...document.querySelector(".main_container_right").childNodes);
        document.querySelector(".mobile_warning-overlay").style.display = "fixed";
    } else if (window.innerWidth > lastsize) {
        window.location.reload();
    }
    lastsize = window.innerWidth;
});