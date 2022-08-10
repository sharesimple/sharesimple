<?php

    // start the session
    session_start();

    // destroy the session
    session_destroy();
    
    // Redirect to the index page:
    header('Location: ../');
?>