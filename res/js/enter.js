let open_window = null;

// On keyboard enter
document.addEventListener("keydown", (event) => {
    if (event.key == "Enter") {
        if (open_window == "download") download();
        else if (open_window == "upload") upload();
    }
});