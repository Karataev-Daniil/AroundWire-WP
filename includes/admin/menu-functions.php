<?php 
// nev
// Registering menus
add_action('after_setup_theme', function() {
    register_nav_menus([
        'header-menu' => 'Header Menu',
        'header-blog' => 'Header Blog Menu',
        'footer-menu' => 'Footer Menu',
    ]);
});

// rebuilding menu links
function modify_menu_item_output($item_output, $item, $depth, $args) {
    if (in_array('no-link', $item->classes)) {
        $item_output = str_replace(
            ['<a', '</a>'], 
            ['<p', '</p>'], 
            $item_output
        );
    }

    if (in_array('open-in-new-tab', $item->classes)) {
        $item_output = preg_replace('/<a(.*?)>/', '<a$1 target="_blank">', $item_output);
    }

    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'modify_menu_item_output', 10, 4);
?>