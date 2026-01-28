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
        filemtime( get_stylesheet_directory() . '/css/main.css' )
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


/* AGENDA */ 

add_shortcode('query_agenda', function () {
    $q = new WP_Query([
        'post_type' => 'agenda',
        'posts_per_page' => 10,
        'post_status' => 'publish',
    ]);

    if (!$q->have_posts()) return '<p>No hay items de agenda.</p>';

    ob_start();

    echo '<div class="agenda-home-grid-container">';
    echo '<div class="agenda-home-grid">';

    while ($q->have_posts()) {
        $q->the_post();
        ?>
        <article class="agenda-card">
            <?php if (has_post_thumbnail()) the_post_thumbnail('medium'); ?>
            <p><?php the_content(); ?></p>
        </article>
        
        <?php
    }

    echo '</div>';
    echo '<span class="arrow-direction"></span>';    
    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
});


add_action('init', function () {
    $post_type_object = get_post_type_object('agenda');

    $post_type_object->template = [
        ['core/image', [
            'align' => 'wide'
        ]],
        ['core/heading', [
            'level' => 3,
            'placeholder' => 'Títol de l’activitat'
        ]],
        ['core/list', [
            'placeholder' => "📍 Ubicació\n🕒 Data i hora"
        ]]
    ];

    $post_type_object->template_lock = 'insert';
});

/* BODY CLASSES */

add_filter('body_class', function ($classes) {

    if (is_singular()) {
        global $post;

        if ($post) {
            $classes[] = 'page-' . sanitize_html_class($post->post_name);
        }
    }

    return $classes;
});

add_action('wp_head', function () {
    echo '<!-- cache-bust-' . time() . ' -->';
}, 0);