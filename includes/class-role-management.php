<?php

class Role_Management {
    public static function init() {
        register_activation_hook( __FILE__, [ __CLASS__, 'add_roles' ] );
        add_action( 'init', [ __CLASS__, 'add_roles' ] );
        add_action( 'admin_menu', [ __CLASS__, 'init_admin_page' ] );
    }

    public static function add_roles() {
        // Add roles only if they are not already registered
        if ( ! get_role( 'cool_kid' ) ) {
            add_role( 'cool_kid', 'Cool Kid' );
        }
        if ( ! get_role( 'cooler_kid' ) ) {
            add_role( 'cooler_kid', 'Cooler Kid' );
        }
        if ( ! get_role( 'coolest_kid' ) ) {
            add_role( 'coolest_kid', 'Coolest Kid' );
        }
    }
    
    public static function init_admin_page() {
        add_menu_page(
            'Cool Kids Admin',
            'Cool Kids Admin',
            'manage_options',
            'cool-kids-admin',
            [ __CLASS__, 'render_admin_page' ],
            'dashicons-admin-users',
            50
        );
    }
    
    public static function render_admin_page() {
        if ( isset( $_POST['update_role'] ) ) {
            $user = get_user_by( 'email', sanitize_email( $_POST['email'] ) );
            if ( $user && in_array( $_POST['role'], [ 'cool_kid', 'cooler_kid', 'coolest_kid' ], true ) ) {
                wp_update_user( [ 'ID' => $user->ID, 'role' => sanitize_text_field( $_POST['role'] ) ] );
                echo '<div class="updated"><p>Role updated successfully.</p></div>';
            } else {
                echo '<div class="error"><p>Invalid email or role.</p></div>';
            }
        }
    
        ?>
        <div class="wrap">
            <h1>Cool Kids Role Management</h1>
            <form method="post">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="email">User Email</label></th>
                        <td><input name="email" type="email" id="email" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="role">New Role</label></th>
                        <td>
                            <select name="role" id="role">
                                <option value="cool_kid">Cool Kid</option>
                                <option value="cooler_kid">Cooler Kid</option>
                                <option value="coolest_kid">Coolest Kid</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button( 'Update Role', 'primary', 'update_role' ); ?>
            </form>
        </div>
        <?php
    }    
}
