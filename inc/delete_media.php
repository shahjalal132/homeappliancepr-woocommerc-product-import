<?php
function delete_all_images() {
    $args = array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => -1,
        'post_status'    => 'any',
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            wp_delete_attachment( get_the_ID(), true );
        }
        wp_reset_postdata();
    }
}

// Hook the function to an admin action or call it directly.
add_action( 'admin_menu', function () {
    add_menu_page(
        'Delete All Images', // Page title
        'Delete All Images', // Menu title
        'manage_options',    // Capability
        'delete-all-images', // Menu slug
        function () {
            if ( isset( $_POST['delete_images'] ) ) {
                delete_all_images();
                echo '<div class="updated"><p>All image files have been deleted.</p></div>';
            }
            ?>
        <div class="wrap">
            <h2>Delete All Images</h2>
            <form method="post" action="">
                <input type="hidden" name="delete_images" value="1">
                <p>
                    <input type="submit" class="button button-primary" value="Delete All Images">
                </p>
            </form>
        </div>
        <?php
        }
    );
} );
