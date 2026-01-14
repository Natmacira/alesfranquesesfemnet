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

    // Child theme style (style.css)
    wp_enqueue_style(
        'alesfranqueses-child-style',
        get_stylesheet_uri(),
        array('twentytwentyfive-style'),
        wp_get_theme()->get('Version')
    );

    // Main compiled SCSS file
    wp_enqueue_style(
        'alesfranqueses-main',
        get_stylesheet_directory_uri() . '/css/main.css',
        array('alesfranqueses-child-style'),
        '1.1'
    );
}

add_action('wp_enqueue_scripts', 'alesfranqueses_child_styles');

/* TITLE */

add_filter('render_block', function($block_content, $block) {

    if (
        isset($block['blockName']) &&
        $block['blockName'] === 'core/heading'
    ) {
        $block_content = preg_replace(
            '/\{(.*?)\}/',
            '<span class="title-highlight">$1</span>',
            $block_content
        );
    }

    return $block_content;
}, 10, 2);

