const main_container_right = document.querySelector(".main_container_right");
const download_container = document.querySelector(".download_container");

function openDownloadSettings() {
    main_container_right.style.gridTemplateRows = "3fr 9fr";
    download_container.style.display = "block";
    window.setTimeout(download_container.style.opacity = 1, 1000);
}

openDownloadSettings();