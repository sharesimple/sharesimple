function step1() {

    // Run the script checkDatabases.php
    $.ajax({
        url: "checkDatabases.php",
        type: "POST",
        data: {},
        success: function(data) {
            if (data == "conn_fail") {

                // Redirect to the step1.html page
                window.location.href = "step1.html";

            } else if (data == "db_exists") {

                // Redirect to already_exists.html
                window.location.href = "already_exists.html";
            } else if (data = "all_good") {

                // Redirect to step1.html
                window.location.href = "step1.html";
            }
        }
    });

    location.assign("step1.html");
}

function createDatabases() {

    // Run the script createDatabases.php
    $.ajax({
        url: "createDatabases.php",
        type: "POST",
        data: {},
        success: function(data) {

            // If the script returns an error, show it
            if (data.indexOf("error") >= 0) {
                $("#step1_error").html(data);
                $("#step1_error").show();
            }
            // If the script returns success, go to the next step
            else {

                // Redirect to step2.html
                window.location.href = "step2.html";
            }
        }
    });
}

function finishInstall() {

    // Redirect to finished.html
    window.location.href = "finished.html";
}