# Sharesimple 

Sharesimple is a web-application to share files with other people easily.  
An open sharesimple instance is available at [https://sharesimple.de](https://sharesimple.de).

# Config

To use this application, you need to create a config.php file in the root directory of the application.  

This is an example config file:

```php
<?php
    // Database credentials
    define('DB_NAME', 'DATABASE_NAME');
    define('DB_USER', 'DATABASE_USER');
    define('DB_PASS', 'DATABASE_USER_PASSWORD');
    define('DB_HOST', 'DATABASE_HOST');
    
    // The directory where the files are stored
    define('FILES_DIR', '../files/');
?>
```
Currently there is no way to set a database port.

# Important notices

- Even though there are passcodes for files, currently the files are not encrypted.
- The directory for the files is not protected in any way. It should be outside of your websites document root.

# Licenses
Used libraries:
- qrcodejs: https://github.com/davidshimjs/qrcodejs (MIT License)