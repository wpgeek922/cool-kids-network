<?php

class Login_Handler {
    public static function init() {
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

        add_shortcode( 'cool_kids_login', [ __CLASS__, 'render_login_form' ] );
        add_shortcode( 'cool_kids_user_list', [ __CLASS__, 'display_user_list' ] ); 
        add_shortcode( 'cool_kids_profile', [ __CLASS__, 'display_user_profile' ] );   
        add_action( 'wp_ajax_login_user', [ __CLASS__, 'login_user' ] );
        add_action( 'wp_ajax_nopriv_login_user', [ __CLASS__, 'login_user' ] ); 
    }

    public static function enqueue_scripts() {
        // Ensure jQuery is loaded
        wp_enqueue_script( 'jquery' );
    
        // Custom JavaScript for AJAX login
        wp_enqueue_script(
            'cool-kids-login-script',
            plugins_url( 'assets/js/login.js', dirname( __FILE__ ) ), 
            [ 'jquery' ],
            null,
            true
        );
    
        // Pass AJAX URL to the script
        wp_localize_script( 'cool-kids-login-script', 'coolKidsAjax', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        ] );
    }

    public static function render_login_form() {
        ob_start();
        ?>

        <form id="cool-kids-login" class="max-w-md mx-auto p-6 bg-white shadow-lg rounded-md text-sm">
            <input type="email" name="email" placeholder="Enter your email" required
                class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="w-full py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
                Login
            </button>
        </form>

        <div id="login-result" class="mt-4 text-center text-white p-4 rounded-md"></div>
     
        <?php
        return ob_get_clean();
    }

    public static function login_user() {
        if ( empty( $_POST['email'] ) || ! is_email( $_POST['email'] ) ) {
            wp_send_json_error( [ 'message' => 'Invalid email address.' ] );
        }
    
        $email = sanitize_email( $_POST['email'] );
    
        if ( ! email_exists( $email ) ) {
            wp_send_json_error( [ 'message' => 'No user found with this email.' ] );
        }
    
        // Log in the user
        $user = get_user_by( 'email', $email );
        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID, true ); // Set the authentication cookie
    
        wp_send_json_success( [ 'message' => 'Login successful.' ] );
    }
    

    public static function display_user_list() {
        if ( ! is_user_logged_in() ) {
            return '<p class="text-red-500 font-semibold">You must log in to view this page.</p>';
        }
    
        $current_user = wp_get_current_user();
        $role = $current_user->roles[0];
    
        if ( $role === 'cool_kid' ) {
            return '<p class="text-red-500 font-semibold">You do not have permission to view this data.</p>';
        }
    
        $users = get_users();
        $output = '<div class="overflow-x-auto p-4 text-sm">';
        $output .= '<table class="min-w-full bg-white shadow-md rounded-lg">';
        $output .= '<thead class="bg-gray-100">';
        $output .= '<tr><th class="py-2 px-4 text-left">Name</th><th class="py-2 px-4 text-left">Country</th>';
    
        if ( $role === 'coolest_kid' ) {
            $output .= '<th class="py-2 px-4 text-left">Email</th><th class="py-2 px-4 text-left">Role</th>';
        }
    
        $output .= '</tr></thead><tbody>';
        foreach ( $users as $user ) {
            $output .= '<tr class="border-b">';
            $output .= '<td class="py-2 px-4">' . esc_html( get_user_meta( $user->ID, 'first_name', true ) ) . ' ' . esc_html( get_user_meta( $user->ID, 'last_name', true ) ) . '</td>';
            $output .= '<td class="py-2 px-4">' . esc_html( get_user_meta( $user->ID, 'country', true ) ) . '</td>';
    
            if ( $role === 'coolest_kid' ) {
                $output .= '<td class="py-2 px-4">' . esc_html( $user->user_email ) . '</td>';
                $output .= '<td class="py-2 px-4">' . esc_html( implode( ', ', $user->roles ) ) . '</td>';
            }
    
            $output .= '</tr>';
        }
        $output .= '</tbody></table></div>';
    
        return $output;
    }

    public static function display_user_profile() {
        if ( ! is_user_logged_in() ) {
            return '<p class="text-red-500 font-semibold">You must log in to view this page.</p>';
        }
    
        $current_user = wp_get_current_user();
    
        $first_name = get_user_meta( $current_user->ID, 'first_name', true );
        $last_name = get_user_meta( $current_user->ID, 'last_name', true );
        $country = get_user_meta( $current_user->ID, 'country', true );
        $email = $current_user->user_email;
        $role = implode( ', ', $current_user->roles );
    
        ob_start();
        ?>
        <div class="max-w-3xl mx-auto bg-white p-6 shadow-md rounded-lg text-sm">
            <h2 class="text-2xl font-semibold mb-4">Your Character</h2>
            <table class="w-full">
                <tr class="border-b">
                    <th class="py-2 px-4 text-left font-medium">First Name:</th>
                    <td class="py-2 px-4"><?php echo esc_html( $first_name ); ?></td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left font-medium">Last Name:</th>
                    <td class="py-2 px-4"><?php echo esc_html( $last_name ); ?></td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left font-medium">Country:</th>
                    <td class="py-2 px-4"><?php echo esc_html( $country ); ?></td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left font-medium">Email:</th>
                    <td class="py-2 px-4"><?php echo esc_html( $email ); ?></td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left font-medium">Role:</th>
                    <td class="py-2 px-4"><?php echo esc_html( ucfirst( $role ) ); ?></td>
                </tr>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }
        

}
