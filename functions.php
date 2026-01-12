<?php
/**
 * Enqueue parent + child styles
 */

function alesfranqueses_child_styles() {
    // Parent theme style
    wp_enqueue_style(
        'twentytwentyfive-style',
        get_template_directory_uri() . '/style.css'
    );

    // Child theme style
    wp_enqueue_style(
        'alesfranqueses-child-style',
        get_stylesheet_uri(),
        array('twentytwentyfive-style'),
        wp_get_theme()->get('Version')
    );
}

add_action('wp_enqueue_scripts', 'alesfranqueses_child_styles');

wp_enqueue_style(
    'child-main',
    get_stylesheet_directory_uri() . '/css/main.css',
    array(),
    '1.0'
  );