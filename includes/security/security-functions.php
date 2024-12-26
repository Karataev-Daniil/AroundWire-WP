<?php
function add_x_frame_options_header() {
    header("X-Frame-Options: SAMEORIGIN");
}
add_action('send_headers', 'add_x_frame_options_header');
?>