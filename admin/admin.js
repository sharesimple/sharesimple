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