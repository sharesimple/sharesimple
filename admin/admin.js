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