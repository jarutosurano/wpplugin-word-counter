<?php
/*
* Plugin Name: Word Counter
* Plugin URI: https://jarutosurano.io
* Description: Easily count words and characters in your posts and pages.
* Version: 1.0.0
* Author: jarutosurano
* Author URI: https://jarutosurano.io
* Text Domain: word-counter
*/

class CallMeWordCounter
{
    function __construct()
    {
        add_action('admin_menu', [$this, 'adminPage']); // Add menu page for the plugin
        add_action('admin_init', [$this, 'settings']);  // Register settings on admin initialization
    }

    function settings()
    {
        // Register a new settings section for Word Counter
        add_settings_section('cwc_first_section', null, null, 'callme-wordcounter');

        // Register field to set display location (beginning/end of post)
        add_settings_field('cwc_location', 'Display Location', [$this, 'locationHTML'], 'callme-wordcounter', 'cwc_first_section');
        register_setting('callmewordcountergroup', 'cwc_location', [
            'sanitize_callback' => 'sanitize_text_field', // Sanitize input
            'default' => '0'
        ]);

        // Register field for headline text
        add_settings_field('cwc_headline', 'Headline Text', [$this, 'headlineHTML'], 'callme-wordcounter', 'cwc_first_section');
        register_setting('callmewordcountergroup', 'cwc_headline', [
            'sanitize_callback' => 'sanitize_text_field', // Sanitize input
            'default' => 'Post Statistics'
        ]);

        // Register checkbox for word count visibility
        add_settings_field('cwc_wordcount', 'Word Count', [$this, 'checkboxHTML'], 'callme-wordcounter', 'cwc_first_section', ['theName' => 'cwc_wordcount']);
        register_setting('callmewordcountergroup', 'cwc_wordcount', [
            'sanitize_callback' => 'sanitize_text_field', // Sanitize input
            'default' => '1'
        ]);

        // Register checkbox for character count visibility
        add_settings_field('cwc_charcount', 'Character Count', [$this, 'checkboxHTML'], 'callme-wordcounter', 'cwc_first_section', ['theName' => 'cwc_charcount']);
        register_setting('callmewordcountergroup', 'cwc_charcount', [
            'sanitize_callback' => 'sanitize_text_field', // Sanitize input
            'default' => '0'
        ]);

        // Register checkbox for read time visibility
        add_settings_field('cwc_readtime', 'Read Time', [$this, 'checkboxHTML'], 'callme-wordcounter', 'cwc_first_section', ['theName' => 'cwc_readtime']);
        register_setting('callmewordcountergroup', 'cwc_readtime', [
            'sanitize_callback' => 'sanitize_text_field', // Sanitize input
            'default' => '1'
        ]);
    }

    // Output checkbox HTML for settings page
    function checkboxHTML($args)
    { ?>
        <input type="checkbox" name="<?php echo esc_attr($args['theName']); ?>" value="1" <?php checked(get_option($args['theName']), '1'); ?>>
    <?php }

    // Output headline text field for settings page
    function headlineHTML()
    { ?>
        <input type="text" name="cwc_headline" value="<?php echo esc_attr(get_option('cwc_headline')); ?>">
    <?php }

    // Output location dropdown for settings page
    function locationHTML()
    { ?>
        <select name="cwc_location">
            <option value="0" <?php selected(get_option('cwc_location', '0')); ?>>Beginning of Post</option>
            <option value="1" <?php selected(get_option('cwc_location', '1')); ?>>End of Post</option>
        </select>
    <?php }

    // Add Word Count settings page to the admin menu
    function adminPage()
    {
        add_options_page(
            'Word Count Settings',   // Page title
            'Word Count',            // Menu title
            'manage_options',        // Capability required
            'callme-wordcounter',    // Menu slug
            [$this, 'ourHTML']       // Function to display page content
        );
    }

    // Render the settings form on the settings page
    function ourHTML()
    { ?>
        <div class="wrap">
            <h1>Word Count Settings</h1>
            <form action="options.php" method="POST">
                <?php
                settings_fields('callmewordcountergroup'); // Output necessary hidden fields for settings submission
                do_settings_sections('callme-wordcounter'); // Output the settings sections
                submit_button(); // Submit button
                ?>
            </form>
        </div>
    <?php }
}

$callMeWordCounter = new CallMeWordCounter();
