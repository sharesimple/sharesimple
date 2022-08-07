// Function to delete a file extension button
function extension_remove(this_element) {
    // Remove element
    this_element.remove();
    // Get the button text
    button_text = this_element.innerText;
    // Run the script extension_remove.php
    $.ajax({
        url: "extension_remove.php",
        type: "POST",
        data: {
            button: button_text
        },
        success: function(data) {
            console.log(data);
        }
    });
}

// Function to add a file extension
function addFileExtension() {
    // Prompt for file extension
    file_extension = prompt("File extension");
    file_extension = file_extension.replace(".", "");
    // Run the script extension_add.php
    $.ajax({
        url: "extension_add.php",
        type: "POST",
        data: {
            extension: file_extension
        },
        success: function(data) {
            console.log(data);
            location.reload();
        }
    });
}

function setAllowAllExtensions(bool) {
    document.getElementById("all_extensions_button_on").classList = undefined;
    document.getElementById("all_extensions_button_off").classList = undefined;
    if (bool) {
        document.getElementById("all_extensions_button_on").classList.add("button_active");
        document.getElementById("all_extensions_button_off").classList.add("button_inactive");
    } else {
        document.getElementById("all_extensions_button_off").classList.add("button_active");
        document.getElementById("all_extensions_button_on").classList.add("button_inactive");
    }
    $.ajax({
        url: "set_allow_all_extension.php",
        type: "POST",
        data: {
            bool: bool
        },
        success: function(data) {
            console.log(data);
        }
    });
}

// contrast change on all active
window.setInterval(() => {
    if (document.getElementById("all_extensions_button_on").classList.contains("button_active")) {
        document.getElementById("allowed_file_extensions_buttons").style.filter = "saturate(0) blur(1px)";
    } else {
        document.getElementById("allowed_file_extensions_buttons").style.filter = "saturate(1)";
    }
}, 10);