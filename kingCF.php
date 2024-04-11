<?php
/*
 * Plugin Name:       King Contact Form
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Create a custom contact form using a shortcode.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Ejike kingsley
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */


class CustomContactForm {
    public function __construct() {
        add_shortcode('contact_form', array($this, 'render_contact_form'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'process_contact_form'));
        add_action('admin_menu', array($this, 'CFpageSettings')); // Add the admin menu hook
        add_filter('the_content', array($this, 'custom_content_filter'));
        add_action('admin_init', array($this, 'Kingcf_initialize_settings'));
    }

    public function CFpageSettings() {
        add_menu_page(
            'KingCF',
            'KingCF',
            'manage_options',
            'kingCFedit',
            array($this, 'admin_page')
        );
    }
    public function Kingcf_initialize_settings(){
        // Register settings
        register_setting(
            'KIngcf_display_options', // Option group
            'KIngcf_display_options_options', // Option name
            array($this, 'Kingcf_validate_options') // Sanitization callback
        );
    
        // Add settings section
        add_settings_section(
            'Kingcf_display_main_section', // Section ID
            'Kingcf Settings', // Section title
            array($this, 'Kingcf_main_section_callback'), // Callback for section content
            'KIngcf_display_options' // Page slug of the options page
        );
    
        // Add settings field
        add_settings_field(
            'Kingcf_enable', // Field ID
            'Enable Kingcf', // Field title
            array($this, 'Kingcf_enable_callback'), // Callback for field content
            'KIngcf_display_options', // Page slug of the options page
            'Kingcf_display_main_section' // Section ID
        );
    }
    
    
    public function admin_page(){
        ?>
        <div class="wrap">
        <h2>Kingcf Options</h2>
        <form method="post" action="options.php">
            <?php settings_fields('KIngcf_display_options'); ?>
            <?php do_settings_sections('KIngcf_display_options'); ?>
            <?php submit_button(); ?>
        </form>
        </div>
        <?php
    }
    public function Kingcf_main_section_callback(){
        echo '<p>Customize the appearance and behavior of Kingcf.</p>';
    }
    public function Kingcf_validate_options($input){
        $output = array();
    $output['enable'] = (isset($input['enable']) && $input['enable'] == 1) ? 1 : 0;
    }
    public function Kingcf_enable_callback(){
        $options = get_option('KIngcf_display_options');
    echo '<input type="checkbox" id="Kingcf_enable" name="KIngcf_display_options[enable]" value="1" ' . checked(1, $options['enable'], false) . ' />';
    }
    public function render_contact_form() {
        ob_start(); ?>
        <form id="custom-contact-form" method="post">
            <p>
                <label for="contact-name">Your Name:</label>
                <input type="text" name="contact_name" id="contact-name" required>
            </p>
            <p>
                <label for="contact-email">Your Email:</label>
                <input type="email" name="contact_email" id="contact-email" required>
            </p>
            <p>
                <label for="contact-message">Message:</label>
                <textarea name="contact_message" id="contact-message" rows="4" required></textarea>
            </p>
            <p>
                <input type="submit" name="submit_contact" value="Submit">
            </p>
        </form>
        <?php
        return ob_get_clean();
    }

    public function enqueue_styles() {
        wp_enqueue_style('custom-contact-form-style', plugins_url('kingCFstyle.css', __FILE__));
    }

    public function enqueue_scripts() {
        wp_enqueue_script('custom-contact-form-script', plugins_url('kingCFscript.js', __FILE__), array('jquery'), '1.0', true);
    }

    public function process_contact_form() {
        if (isset($_POST['submit_contact'])) {
            $name = sanitize_text_field($_POST['contact_name']);
            $email = sanitize_email($_POST['contact_email']);
            $message = sanitize_textarea_field($_POST['contact_message']);

            // Here you can add your code to process the form submission
            // For example, sending an email, storing data in the database, etc.
            // For demonstration purposes, let's just display the submitted data
            echo "<p>Thank you for your submission!</p>";
            echo "<p>Name: $name</p>";
            echo "<p>Email: $email</p>";
            echo "<p>Message: $message</p>";

            // Optionally, you can redirect the user after form submission
            // wp_redirect(home_url('/thank-you'));
            // exit();
        }
    }
    // Define the callback function for the filter
function custom_content_filter($content) {
    // Check if the current post type is 'post'
    if (is_singular('post')) {
        // Modify the content only for single post pages
        $modified_content = $content . $this->render_contact_form();
        return $modified_content;
    } else {
        // For other post types or pages, return the original content
        return $content;
    }
}
}

$custom_contact_form = new CustomContactForm();
