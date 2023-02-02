// General elements
const main_container_right = document.querySelector(".main_container_right");
const upload_overlay = document.querySelector(".upload_overlay");
// Upload elements
const upload_container = document.querySelector(".upload_container");
const upload_file_field = document.querySelector("#upload-input");
const upload_start_button = document.querySelector(".upload_start-button");
const upload_button_icon = document.querySelector(".upload-button_icon > i");
const upload_input = document.querySelector("#upload-input");
const autodelete_check = document.querySelector("#autodelete_check");
const autodelete_time = document.querySelector("#autodelete_time");
// Download elements
const download_container = document.querySelector(".download_container");
const download_id_field = document.querySelector("#download_id");
const download_passcode_field = document.querySelector("#download_passcode");
const download_start_button = document.querySelector(".download_start-button");

// 
// Download
// 

function openDownloadSettings() {
    main_container_right.style.gridTemplateRows = "3fr 9fr";
    upload_container.style.display = "none";
    download_container.style.display = "block";
    window.setTimeout(() => download_container.style.opacity = 1, 1000);
    download_id_field.addEventListener("input", checkDownloadSettingsInput);
}

function checkDownloadSettingsInput() {
    if (download_id_field.value.length != 4) {
        download_start_button.style.visibility = "hidden";
        download_passcode_field.style.visibility = "hidden";
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
        success: function(data) {
            if (data == "NOPASS") {
                download_start_button.style.visibility = "visible";
                download_passcode_field.closest("p").style.visibility = "hidden";
                return;
            }
            download_passcode_field.addEventListener("input", checkDownloadSettingsInput);
            if (data == "FALSEPASS") {
                download_start_button.style.visibility = "hidden";
                download_passcode_field.style.visibility = "visible";
                return;
            }
            if (data == "TRUEPASS") {
                download_start_button.style.visibility = "visible";
                download_passcode_field.style.visibility = "visible";
                return;
            }
        }
    });
}

function download() {
    $.ajax({
        url: "/res/php/getDownloadToken.php",
        type: "POST",
        data: {
            file_id: download_id_field.value,
            file_passcode: download_passcode_field.value
        },
        success: function(data) {
            if (!data.startsWith("?DT=")) {
                download_start_button.style.color = "#f00";
                return;
            }
            window.location.assign("/download/" + data);
        }
    });
}

// 
// Upload
//
upload_file_field.addEventListener("change", openUploadSettings);

function openUploadSettings() {
    main_container_right.style.gridTemplateRows = "3fr 9fr";
    upload_container.style.display = "block";
    download_container.style.display = "none";
    window.setTimeout(() => upload_container.style.opacity = 1, 1000);
}

function upload() {
    let form_data = new FormData();
    form_data.append("upload", upload_input.files[0]);
    if (autodelete_check.checked) form_data.append("use_passcode", 1);
    else form_data.append("use_passcode", 0);
    if (autodelete_check.checked) form_data.append("autodelete", new Date(autodelete_time.value).toISOString());
    else form_data.append("autodelete", false);
    let overlay_open_timestamp = Date.now();
    $.ajax({
        url: "./res/php/upload.php",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            document.documentElement.style.setProperty('--animation-state', "running");
            upload_overlay.style.display = "grid";
            closeSettings();
        },
        success: function(data) {
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
                    if (data.file_passcode != null) var alerttext = "File id: " + data.file_id + "\nFile passcode: " + data.file_passcode;
                    else var alerttext = "File id: " + data.file_id;
                    alert(alerttext);
                }, 3000 - Math.floor((Date.now() - overlay_open_timestamp) / 1000));
            }
        },
        error: function(e) {
            console.log("Error");
            console.log(e);
        }
    });
}

autodelete_check.addEventListener("change", autodeleteCheck);

function autodeleteCheck() {
    if (autodelete_check.checked) autodelete_time.style.visibility = "visible";
    else autodelete_time.style.visibility = "hidden";
}
autodeleteCheck();
const currenttime = (new Date((new Date).getTime() + 3 * 24 * 60 * 60 * 1000)).toISOString().split(":");
autodelete_time.value = currenttime[0] + ":" + currenttime[1];

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