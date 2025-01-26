<?php

class User_Registration {
    public static function init() {
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        add_shortcode( 'cool_kids_register', [ __CLASS__, 'render_registration_form' ] );
        add_action( 'wp_ajax_register_user', [ __CLASS__, 'register_user' ] );
        add_action( 'wp_ajax_nopriv_register_user', [ __CLASS__, 'register_user' ] );
    }

    public static function enqueue_scripts() {
        // Ensure jQuery is loaded
        wp_enqueue_script( 'jquery' );
    
        // Custom JavaScript for AJAX login
        wp_enqueue_script(
            'cool-kids-register-script',
            plugins_url( 'assets/js/register.js', dirname( __FILE__ ) ), 
            [ 'jquery' ],
            null,
            true
        );
    
        // Pass AJAX URL to the script
        wp_localize_script( 'cool-kids-register-script', 'coolKidsAjax', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        ] );
    }

    public static function render_registration_form() {
        ob_start();
        ?>
        <!-- <form id="cool-kids-register">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Register</button>
        </form>
        <div id="register-result"></div> -->
        <form id="cool-kids-register" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-md text-sm">
            <input type="email" name="email" placeholder="Enter your email" required
                class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="w-full py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                Register
            </button>
        </form>

        <div id="register-result" class="mt-4 text-center text-white p-4 rounded-md"></div>
       
        <?php
        return ob_get_clean();
    }

    public static function register_user() {
        if ( empty( $_POST['email'] ) || ! is_email( $_POST['email'] ) ) {
            wp_send_json_error( [ 'message' => 'Invalid email address.' ] );
        }
    
        $email = sanitize_email( $_POST['email'] );
    
        if ( email_exists( $email ) ) {
            wp_send_json_error( [ 'message' => 'Email already registered.' ] );
        }
    
        // set default password (optional since passwords aren't required for this POC)
        $password = "CoolKidHere";
    
        // Create the user with a default role of 'cool_kid'
        $user_id = wp_create_user( $email, $password, $email );
    
        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( [ 'message' => 'Error creating user.' ] );
        }
    
        // // Assign the default role explicitly
        $user = new WP_User( $user_id );
        $user->set_role( 'cool_kid' );
    
        // Fetch random character data
        $random_user = json_decode( wp_remote_retrieve_body( wp_remote_get( 'https://randomuser.me/api/' ) ), true );
        $data = $random_user['results'][0];
    
        // Save user meta (character data)
        update_user_meta( $user_id, 'first_name', $data['name']['first'] );
        update_user_meta( $user_id, 'last_name', $data['name']['last'] );
        update_user_meta( $user_id, 'country', $data['location']['country'] );

        // Update user role using wp_update_user()
        wp_update_user( [
            'ID' => $user_id,
            'role' => 'cool_kid',
        ] );
    
        wp_send_json_success( [ 'message' => 'User registered successfully.' ] );
    }
    
}
