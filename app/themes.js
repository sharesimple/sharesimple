// themes.js by CuzImBisonratte
// generated on https://themes-js.github.io/generator/
// this is based on v3.0.0 of https://github.com/themes-js/themes.js


//
// Colorcodes
//

var themes_info_list = [{"name":"Dunkel","background_color":"#000000","text_color":"#ffffff","nav_background_color":"#000000","nav_text_color":"#ffffff","extra_color_one":"#000000","extra_color_two":"#ffffff"},{"name":"Hell","background_color":"#ffffff","text_color":"#000000","nav_background_color":"#ffffff","nav_text_color":"#000000","extra_color_one":"#ffffff","extra_color_two":"#000000"}];


//
// Variables
//

var doc_element = document.documentElement;
var number_of_themes = themes_info_list.length;
var current_theme = 0;


//
// Functions
//

// The function that changes the theme
function change_theme(change_to) {
  doc_element.style.setProperty('--body-background-color', themes_info_list[change_to].background_color);
  doc_element.style.setProperty('--body-text-color', themes_info_list[change_to].text_color);
  doc_element.style.setProperty('--nav-background-color', themes_info_list[change_to].nav_background_color);
  doc_element.style.setProperty('--nav-text-color', themes_info_list[change_to].nav_text_color);
  doc_element.style.setProperty('--extra-color-one', themes_info_list[change_to].extra_color_one);
  doc_element.style.setProperty('--extra-color-two', themes_info_list[change_to].extra_color_two);
  document.getElementById('themeToggleButton').innerHTML = themes_info_list[change_to].name;}
// The function thats run on page load
function startTheme() {
    // Get the saved theme
    try {
    theme=localStorage.getItem('theme');
    } catch(e) {
        // If there is no saved theme, set the theme to 0
        theme = 0;
    }
    // Change to the theme
    change_theme(theme);
}
// Run the function on page load
startTheme();



// The function that runs on button click
function toggleTheme() {
    // Add 1 to the current theme
    current_theme++;
    // If the current theme is greater than the number of themes, set it to 0
    if (current_theme > number_of_themes) {
        current_theme = 0;
    }
    // Change the theme
    change_theme(current_theme);
    // Save the theme
    localStorage.setItem('theme', current_theme);
}
