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
checkDownloadSettingsInput();

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
    location.assign("/download/?id=" + download_id_field.value + "&pass=" + download_passcode_field.value);
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
setAutoDelete(3);

function openUploadSettings() {
    open_window = "upload";
    main_container_right.style.gridTemplateRows = "3fr 9fr";
    upload_container.style.display = "block";
    download_container.style.display = "none";
    window.setTimeout(() => upload_container.style.opacity = 1, 1000);
}

let error_retries = 5;

function upload() {
    let deletion_time;
    switch (autodelete_time) {
        case 1:
            deletion_time = new Date(Date.now() + 60000);
            break;
        case 2:
            deletion_time = new Date(Date.now() + 180000);
            break;
        case 3:
            deletion_time = new Date(Date.now() + 300000);
            break;
        case 4:
            deletion_time = new Date(Date.now() + 600000);
            break;
        case 5:
            deletion_time = new Date(Date.now() + 900000);
            break;
        case 6:
            deletion_time = new Date(Date.now() + 1800000);
            break;
        case 7:
            deletion_time = new Date(Date.now() + 3600000);
            break;
        case 8:
            deletion_time = new Date(Date.now() + 14400000);
            break;
        case 9:
            deletion_time = new Date(Date.now() + 86400000);
            break;
        case 10:
            deletion_time = new Date(Date.now() + 604800000);
            break;
    }

    // 
    let form_data = new FormData();
    form_data.append("upload", upload_input.files[0]);
    if (password_check.checked) form_data.append("use_passcode", 1);
    else form_data.append("use_passcode", 0);
    form_data.append("autodelete", deletion_time.toISOString());
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
            // Check if overlay is already open for at least 3 seconds
            // This is to prevent the overlay from closing too fast
            // This is so users are not confused by the overlay closing too fast
            if (Math.floor((Date.now() - overlay_open_timestamp) / 1000) < 3) {
                window.setTimeout(() => {
                    upload_overlay.style.display = "none";
                    document.documentElement.style.setProperty('--animation-state', "paused");
                    window.setTimeout(() => {
                        if (data.file_passcode != null) var alerttext = "File id: " + data.file_id + "\nFile passcode: " + data.file_passcode;
                        else var alerttext = "File id: " + data.file_id;
                        alert(alerttext);
                    }, 100);
                }, 3000 - Math.floor((Date.now() - overlay_open_timestamp) / 1000));
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