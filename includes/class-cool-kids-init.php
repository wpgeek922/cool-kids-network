<?php

class Cool_Kids_Init {
    public static function run() {
        // Load classes.
        require_once plugin_dir_path( __FILE__ ) . 'class-user-registration.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-login-handler.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-role-management.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-api-endpoints.php';

        // Initialize features.
        Role_Management::init();
        User_Registration::init();
        Login_Handler::init();
        API_Endpoints::init();

        // Enqueue styles
        self::cool_kids_enqueue_styles();
    }

    // Enqueue custom styles
    public static function cool_kids_enqueue_styles() {
        wp_enqueue_style( 'tailwind-css', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' );
        wp_enqueue_style( 
            'cool-kids-style', 
            plugins_url( 'assets/css/styles.css', dirname( __FILE__ ))
        ); 
    }
}
