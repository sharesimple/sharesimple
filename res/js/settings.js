const main_container_right = document.querySelector(".main_container_right");
const download_container = document.querySelector(".download_container");
const download_id_field = document.querySelector("#download_id");
const download_passcode_field = document.querySelector("#download_passcode");
const download_start_button = document.querySelector(".download_start-button");

function openDownloadSettings() {
    main_container_right.style.gridTemplateRows = "3fr 9fr";
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