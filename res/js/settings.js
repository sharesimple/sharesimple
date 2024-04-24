// General elements
const main_container_right = document.querySelector(".main_container_right");
const upload_overlay = document.querySelector(".upload_overlay");
// Upload elements
const upload_container = document.querySelector(".upload_container");
const upload_file_field = document.querySelector("#upload-input");
const upload_start_button = document.querySelector(".upload_start-button");
const upload_button_icon = document.querySelector(".upload-button_icon > i");
const upload_input = document.querySelector("#upload-input");
const password_check = document.querySelector("#password_check");
var autodelete_time;
// Download elements
const download_container = document.querySelector(".download_container");
const download_id_field = document.querySelector("#download_id");
const download_passcode_field = document.querySelector("#download_passcode");
const download_start_button = document.querySelector(".download_start-button");
const download_passcode_container = document.querySelector("#download_passcode-container");

// 
// Download
// 

function openDownloadSettings() {
    open_window = "download";
    main_container_right.style.gridTemplateRows = "3fr 9fr";
    upload_container.style.display = "none";
    download_container.style.display = "block";
    window.setTimeout(() => download_container.style.opacity = 1, 1000);
    download_id_field.addEventListener("input", checkDownloadSettingsInput);
}

// Only run checkDownloadSettingsInput if download_id_field exists (on the page and not on upload/success)
if (download_id_field) checkDownloadSettingsInput();

function checkDownloadSettingsInput() {
    if (download_id_field.value.length != 4) {
        download_start_button.style.visibility = "hidden";
        download_passcode_container.style.visibility = "hidden";
        return;
    }
    // Check passcode
    $.ajax({
        url: "/res/php/checkPasscode.php",
        type: "POST",
        data: {
            file_passcode: download_passcode_field.value,
            file_id: download_id_field.value
        },
        success: function (data) {
            if (data == "NOPASS") {
                download_start_button.style.visibility = "visible";
                download_passcode_container.closest("p").style.visibility = "hidden";
                return;
            }
            download_passcode_field.addEventListener("input", checkDownloadSettingsInput);
            if (data == "FALSEPASS") {
                download_start_button.style.visibility = "hidden";
                download_passcode_container.style.visibility = "visible";
                return;
            }
            if (data == "TRUEPASS") {
                download_start_button.style.visibility = "visible";
                download_passcode_container.style.visibility = "visible";
                return;
            }
        }
    });
}

function download() {
    location.assign("/download/?id=" + download_id_field.value + (download_passcode_field.value ? "&pass=" + download_passcode_field.value : ""));
}

// 
// Upload
//
upload_file_field.addEventListener("change", openUploadSettings);

function setAutoDelete(time) {
    document.getElementsByClassName("autodelete-time-active")[0].classList.remove("autodelete-time-active");
    document.getElementById("autodelete-time-" + time).classList.add("autodelete-time-active");
    autodelete_time = time;
}

// Only run setAutoDelete if autodelete-time-1 exists (on the page and not on download/success)
if (document.getElementById("autodelete-time-1")) setAutoDelete(3);

function openUploadSettings() {
    open_window = "upload";
    main_container_right.style.gridTemplateRows = "3fr 9fr";
    upload_container.style.display = "block";
    download_container.style.display = "none";
    window.setTimeout(() => upload_container.style.opacity = 1, 1000);
}

let error_retries = 5;

function upload() {
    let form_data = new FormData();
    form_data.append("upload", upload_input.files[0]);
    if (password_check.checked) form_data.append("use_passcode", 1);
    else form_data.append("use_passcode", 0);
    form_data.append("autodelete", autodelete_time);
    let overlay_open_timestamp = Date.now();
    $.ajax({
        url: "./res/php/upload.php",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            document.documentElement.style.setProperty('--animation-state', "running");
            upload_overlay.style.display = "grid";
            closeSettings();
        },
        success: function (data) {
            data = JSON.parse(data);
            if (!data.success) {
                console.log("ERROR");
                return;
            }
            if (data.file_id == null) {
                console.log("No file id");
                return;
            }
            // Check if overlay is already open for at least 2 seconds
            // This is to prevent the overlay from closing too fast
            // This is so users are not confused by the overlay closing too fast
            if (Math.floor((Date.now() - overlay_open_timestamp) / 1000) < 3) {
                window.setTimeout(() => {
                    upload_overlay.style.display = "none";
                    document.documentElement.style.setProperty('--animation-state', "paused");
                    window.setTimeout(() => {
                        location.assign("/upload/success/?id=" + data.file_id + (data.file_passcode ? "&pw=" + data.file_passcode : ""));
                    }, 100);
                }, 2000 - Math.floor((Date.now() - overlay_open_timestamp) / 1000));
            }
        },
        error: function (e) {
            console.log("Error");
            console.log(e);
            if (error_retries > 0) {
                window.setTimeout(() => {
                    error_retries--;
                    upload();
                    return;
                }, 1500);
            } else {
                upload_overlay.style.display = "none";
                document.documentElement.style.setProperty('--animation-state', "paused");
                console.log("Error retries exceeded");
                status_notify("Error uploading your file!<br>Please try again later", "negative");
                return;
            }
        }
    });
}

// 
// General
// 
function closeSettings() {
    main_container_right.style.gridTemplateRows = "3fr 1fr";
    download_container.style.opacity = 1;
    upload_container.style.opacity = 1;
    window.setTimeout(() => download_container.style.display = "none", 1000);
    window.setTimeout(() => upload_container.style.display = "none", 1000);
}