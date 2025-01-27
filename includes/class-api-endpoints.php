<?php

class API_Endpoints {
    public static function init() {
        add_action( 'rest_api_init', [ __CLASS__, 'register_routes' ] );
    }

    public static function register_routes() {
        register_rest_route( 'cool-kids/v1', '/update-role', [
            'methods'             => 'POST',
            'callback'            => [ __CLASS__, 'update_user_role' ],
            'permission_callback' => [ __CLASS__, 'authenticate_request' ],
        ] );
    }

    public static function update_user_role( $request ) {
        $params = $request->get_params();

        // Validate parameters.
        if ( empty( $params['email'] ) && ( empty( $params['first_name'] ) || empty( $params['last_name'] ) ) ) {
            return new WP_Error( 'missing_parameters', 'Email or first/last name required.', [ 'status' => 400 ] );
        }

        // Find user.
        if ( ! empty( $params['email'] ) ) {
            $user = get_user_by( 'email', sanitize_email( $params['email'] ) );
        } elseif ( ! empty( $params['first_name'] ) && ! empty( $params['last_name'] ) ) {
            $user_query = new WP_User_Query( [
                'meta_query' => [
                    [
                        'key'   => 'first_name',
                        'value' => sanitize_text_field( $params['first_name'] ),
                    ],
                    [
                        'key'   => 'last_name',
                        'value' => sanitize_text_field( $params['last_name'] ),
                    ],
                ],
            ] );
            $user = $user_query->get_results()[0] ?? null;
        }

        if ( ! $user ) {
            return new WP_Error( 'user_not_found', 'User not found.', [ 'status' => 404 ] );
        }

        // Update role.
        $allowed_roles = [ 'cool_kid', 'cooler_kid', 'coolest_kid' ];
        if ( ! in_array( $params['role'], $allowed_roles, true ) ) {
            return new WP_Error( 'invalid_role', 'Invalid role provided.', [ 'status' => 400 ] );
        }

        wp_update_user( [ 'ID' => $user->ID, 'role' => sanitize_text_field( $params['role'] ) ] );

        return rest_ensure_response( [ 'message' => 'Role updated successfully.' ] );
    }

    public static function authenticate_request( $request ) {
    
        $auth_key = $request->get_header( 'Authorization' );

        if ( $auth_key !== 'Bearer COOL_KIDS_SECRET' ) {
            return new WP_Error( 'unauthorized', 'Invalid authorization.', [ 'status' => 401 ] );
        }
        return true;
    }
}
