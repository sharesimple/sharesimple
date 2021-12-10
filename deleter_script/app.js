// Database config
var host = "localhost";
var user = "root";
var password = "";
var database = "mini_projects";
var table = "sharesimple"

// Import the mysql-driver
var mysql = require('mysql');

// Import the file-system-module
var fs = require('fs');

// Create the connection to the Database
var connection = mysql.createConnection({
    host: host,
    user: user,
    password: password,
    database: database
});

// Print intro
console.log("\n\nLösch-Skript für ShareSimple von CuzImBisonratte");
console.log("Dieses Sktipt wird nicht mehr gebrauchte Dateien löschen\n\n");


// Connect to the Database
connection.connect(function(err) {
    if (err) {

        // Throw error if error happens
        throw err;
    } else {

        // Create the mysql query
        sql = "SELECT * FROM " + table;

        // Run the query
        connection.query(sql, function(err, result) {
            if (err) {

                // Throw error if error happens
                throw err;

            } else {

                // Get the unix timestamp of now
                var now = Math.round(new Date().getTime() / 1000);

                // Get the number of results
                var num_results = result.length;

                // Check if there are results
                if (num_results > 0) {

                    // Log, that the files are checked if there are some that are unneeded and if so they are deleted
                    console.log("Prüfe, ob die Dateien zu alt sind.");

                    // Loop through the results
                    for (var i = 0; i < num_results; i++) {

                        // get the filename of the file
                        var filename = result[i].filename;

                        // Log the filename
                        console.log("\nÜberprüfe, ob " + filename + " zu alt ist.");

                        // Check if the delete_on timestamp is less than the current timestamp
                        if (result[i].delete_on < now) {

                            // Log the filename and say that the file is to old
                            console.log(filename + " ist zu alt und wird gelöscht!");

                            // Delete the file 
                            // Files are stored in ../app/files/
                            fs.unlink("../app/files/" + filename, function(err) {
                                if (err) {
                                    throw err;
                                } else {
                                    console.log("Datei " + filename + " erfolgreich gelöscht!");
                                }
                            });

                            // Delete the entry from the database
                            sql = "DELETE FROM " + table + " WHERE filename = '" + filename + "'";

                            // Run the query
                            connection.query(sql, function(err, result) {
                                if (err) {

                                    // Throw error if error happens
                                    throw err;

                                } else {

                                    // Log that the file was deleted
                                    console.log("Datei " + filename + " erfolgreich aus der Datenbank gelöscht!");

                                }
                            });
                        } else {

                            // Log that the file is still to young
                            console.log(filename + " ist noch nicht zu alt und wird nicht gelöscht!");
                        }

                    };

                    // Log, that now the files are checked if there are some that don't exist in the database
                    console.log("\n\nJetzt wird geprüft, ob die Dateien in der Datenbank vorhanden sind.");

                    // Check if there are files that don't exist in the database
                    // Files are stored in ../app/files/
                    fs.readdir('../app/files/', function(err, files) {
                        if (err) {

                            // Throw error if error happens
                            throw err;

                        } else {

                            // Check if there are files that don't exist in the database
                            for (var i = 0; i < files.length; i++) {
                                var file = files[i];
                                var exists = false;

                                // Log the filename
                                console.log("\nÜberprüfe, ob " + file + " in der Datenbank vorhanden ist.");

                                // Check if the file exists in the database
                                for (var j = 0; j < num_results; j++) {
                                    if (file == result[j].filename) {
                                        exists = true;
                                    }
                                }

                                // If the file doesn't exist in the database
                                if (exists) {

                                    // Log, that the file exists
                                    console.log("Datei " + file + " ist in der Datenbank vorhanden.");
                                    console.log("Datei " + file + " wird nicht gelöscht!");

                                } else {

                                    // Log, that the file doesn't exist and will be deleted
                                    console.log("Datei " + file + " ist in der Datenbank nicht vorhanden und wird jetzt gelöscht.");

                                    // Delete the file
                                    fs.unlink('../app/files/' + file, function(err) {
                                        if (err) {

                                            // Throw error if error happens
                                            throw err;
                                        } else {

                                            // Log that the file was deleted
                                            console.log("Datei " + file + " erfolgreich gelöscht!");

                                        }
                                    });
                                };
                            };
                        }
                    });
                }
            }
        });
    }
});