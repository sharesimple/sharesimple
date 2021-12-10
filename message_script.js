// If there are no arguments in the url redirect to index
if (window.location.href.indexOf("?") == -1) {
    window.location.href = "index.html";
}


// Get the error message and show it
function setErrorMessage() {
    if (location.search.substring().includes("message=")) {
        errorMessageSub = location.search.substring(1);
        errorMessage = errorMessageSub.split("&")[0];
        errorMessage = errorMessage.split("=")[1];
        while (errorMessage.includes("%22")) {
            errorMessage = errorMessage.replace("%22", "");
        }
        while (errorMessage.includes("%20")) {
            errorMessage = errorMessage.replace("%20", " ");
        }

        document.getElementById("errorBox").innerHTML = errorMessage;
    }
}
setErrorMessage();

// Get the password and show it
function setPassCode() {
    if (location.search.substring().includes("passcode=")) {
        passCodeSub = location.search.substring(1);
        passCode = passCodeSub.split("&")[2];
        passCode = passCode.split("=")[1];
        while (passCode.includes("%22")) {
            passCode = passCode.replace("%22", "");
        }
        while (passCode.includes("%20")) {
            passCode = passCode.replace("%20", " ");
        }
        if (passCode.length > 5) {
            document.getElementById("code").innerHTML = passCode;
        } else {
            document.getElementById("code").innerHTML = "Passcode: " + passCode;
        }
    } else {
        document.getElementById("code").style.display = "none";
    }
}
setPassCode();

// Get the password and show it
function setFileId() {
    if (location.search.substring().includes("file=")) {
        fileIdSub = location.search.substring(1);
        fileId = fileIdSub.split("&")[3];
        fileId = fileId.split("=")[1];
        while (fileId.includes("%22")) {
            fileId = fileId.replace("%22", "");
        }
        while (fileId.includes("%20")) {
            fileId = fileId.replace("%20", " ");
        }
        if (fileId) {
            document.getElementById("fileid").innerHTML = "Datei-ID: " + fileId;
        }
    } else {
        document.getElementById("fileid").style.display = "none";
    }
}
setFileId();

// Check if the report button should be shown
function showReportButton() {
    if (location.search.substring().includes("showReportButton=false")) {
        document.getElementById("reportButton").style.display = "none";
    }
}
showReportButton();