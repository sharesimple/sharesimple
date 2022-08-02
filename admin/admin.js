// Function to delete a file extension button
function delete_button(this_element) {
    // Remove element
    this_element.remove();
    // Get the button text
    button_text = this_element.innerText;
    // Run the script delete_button.php
    $.ajax({
        url: "delete_button.php",
        type: "POST",
        data: {
            button: button_text
        },
        success: function(data) {
            console.log(data + " " + data);
        }
    });
}

// Function to add a file extension
function addFileExtension() {
    // Prompt for file extension
    file_extension = prompt("File extension");
    file_extension = file_extension.replace(".", "");
    // Run the script add_extension.php
    $.ajax({
        url: "add_extension.php",
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