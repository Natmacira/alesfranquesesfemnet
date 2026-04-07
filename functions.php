<?php
require_once get_stylesheet_directory() . '/inc/shortcode-calc.php';

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

/* WATHSAPP */ 

function agregar_boton_wsp() { ?>
    <a href="https://wa.me/+34670301309?text=Hola!" 
       class="wsp-flotante" 
       target="_blank" 
       rel="noopener noreferrer">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/whatsapp-icon.png" alt="WhatsApp">
    </a>
<?php }
add_action('wp_footer', 'agregar_boton_wsp');


/* og_image */ 

function custom_og_image() {

    if (is_singular()) {
        global $post;

        if (has_post_thumbnail($post->ID)) {
            $image = get_the_post_thumbnail_url($post->ID, 'full');
        } else {
            $image = 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/fem-net-logo.png';
        }

    } elseif (is_home() || is_archive()) {
        // Blog / listado de posts
        $image = 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/fem-net-logo.png';
    }

    if (!empty($image)) {
        echo '<meta property="og:image" content="' . esc_url($image) . '" />';
        echo '<meta property="og:image:width" content="1200" />';
        echo '<meta property="og:image:height" content="630" />';
    }
}

add_action('wp_head', 'custom_og_image', 5);




/**
 * Shortcode: [buscador_residus]
 * Uso: posar [buscador_residus] a qualsevol pàgina
 */

add_shortcode('buscador_residus', 'buscador_residus_shortcode');

function buscador_residus_shortcode() {

    // ─── BASE DE DADES DE CARRERS ───────────────────────────────────────────
    // Format: 'NOM CARRER' => [
    //     'oficina'   => 'Nom de l\'oficina',
    //     'adreca'    => 'Adreça de l\'oficina',
    //     'periode'   => 'Període per recollir el kit',
    //     'dies'      => 'Dies preferents (si no cal per portal, és fix)',
    //     'rangs'     => [ // Opcional: si hi ha rangs per portal
    //         ['min'=>2, 'max'=>10, 'parell'=>true,  'dies'=>'Del 23 al 28 de març'],
    //         ['min'=>1, 'max'=>999,'parell'=>null,   'dies'=>'Del 2 al 7 de març'], // null = tots
    //     ]
    // ]

    $carrers = [

             // ── MARATA ──────────────────────────────────────────────────────────
             'CA L\'ERMITA' => [
                'oficina' => 'Antigues Escoles de Marata',
                'adreca'  => 'Plaça de Marata, s/n',
                'maps'    => 'https://maps.app.goo.gl/A36XhPmFpoqDArwx5',
                'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/1.-Antigues_Escoles_de_Marata.jpg',
                'periode' => '25 de febrer',
                'dies'    => '25 de febrer',
                'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',

                'tipus'   => ['DS'],
            ],
            'CAN MANEL' => [
                'oficina' => 'Antigues Escoles de Marata',
                'adreca'  => 'Plaça de Marata, s/n',
                'maps'    => 'https://maps.app.goo.gl/A36XhPmFpoqDArwx5',
                'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/1.-Antigues_Escoles_de_Marata.jpg',
                'periode' => '25 de febrer',
                'dies'    => '25 de febrer',
                'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
                'tipus'   => ['DS'],
            ],
            'CAN PARERA' => [
                'oficina' => 'Antigues Escoles de Marata',
                'adreca'  => 'Plaça de Marata, s/n',
                'maps'    => 'https://maps.app.goo.gl/A36XhPmFpoqDArwx5',
                'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/1.-Antigues_Escoles_de_Marata.jpg',
                'periode' => '25 de febrer',
                'dies'    => '25 de febrer',
                'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
                'tipus'   => ['DS'],
            ],
            'CAN PERICA' => [
                'oficina' => 'Antigues Escoles de Marata',
                'adreca'  => 'Plaça de Marata, s/n',
                'maps'    => 'https://maps.app.goo.gl/A36XhPmFpoqDArwx5',
                'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/1.-Antigues_Escoles_de_Marata.jpg',
                'periode' => '25 de febrer',
                'dies'    => '25 de febrer',
                'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
                'tipus'   => ['DS'],
            ],
            'CAN PONETA' => [
                'oficina' => 'Antigues Escoles de Marata',
                'adreca'  => 'Plaça de Marata, s/n',
                'maps'    => 'https://maps.app.goo.gl/A36XhPmFpoqDArwx5',
                'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/1.-Antigues_Escoles_de_Marata.jpg',
                'periode' => '25 de febrer',
                'dies'    => '25 de febrer',
                'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
                'tipus'   => ['DS'],
            ],

       
   // ── CORRÓ D'AMUNT ────────────────────────────────────────────────────
    'CA L\'AIXIQUET' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',

        'tipus'   => ['DS'],
    ],
    'CAL MARGE' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata i Antigues Escoles de Llerona ',
        'tipus'   => ['DS'],
    ],
    'CAMELIA' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['CL'],
    ],
    'CAN BASSO' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],
    'CAN BERTRAN' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],
    'CAN BESSÓ NOU' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],
    'CAN CAMP' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],
    'CAN MONTASELL' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],
    'CAN PROFITOS' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],
    'CAN SUQUET' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],
    'CAN VIUDEZ' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],
    'CAN XICU' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],
    'CORRO DE MUNT' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],
    'GLADIOL' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['CL'],
    ],
    'LLIRI' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['CL'],
    ],
    'SANT JERONI' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['CL'],
    ],
    'TIBIDABO' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['CL'],
    ],
    'TRAVESSIA DE GRACIA' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['CL'],
    ],
    'TULIPA' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['CL'],
    ],
    'UNIO' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['CL'],
    ],
    'XALET PAS FONT' => [
        'oficina' => 'Antigues Escoles de Corró d\'Amunt',
        'adreca'  => 'Carretera de Cànoves, s/n',
        'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
        'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
        'periode' => 'Del 26 al 28 de febrer',
        'dies'    => 'Del 26 al 28 de febrer',
        'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
        'tipus'   => ['DS'],
    ],

        'MAJOR' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
            'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
            'tipus'   => ['AV'],
        ],

        // ── LLERONA ──────────────────────────────────────────────────────────
        'ANGEL GUIMERA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'BOU D\'EN' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
        'tipus'   => ['DS'],
        ],
        'CA L\'ARIMANY' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
        'tipus'   => ['DS'],
        ],
        'CABAL' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'CABIROL' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'CADI' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'CAL NEN' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN BOU' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN CALVET' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN COLL D\'OCA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
        'tipus'   => ['DS'],
        ],
        'CAN CONGOST NOU' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN GESA NOU' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN LLOREDA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN MAS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN MINGUET' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN PERICAS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN POSAS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN POUS DALT' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN ROF' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN ROGET' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN SOLELLA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CAN TINET NOU' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'CANIGO' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'CARDENAL JUBANY' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'CONCA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'DELTA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'DORCA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'ESGLESIA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'ESTUARI' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'FABRICA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'FERRERET' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'FONT DE LA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'GORCS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['AV'],
        ],
        'JOSEP M BOIXAREU' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'LLUIS COMPANYS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'MARIA MARGENS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'RIBERA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'SANTA DIGNA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'SELVA DEL PLA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],
        'TORRENT' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CL'],
        ],
        'TURO MENTIDES' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['CM'],
        ],
        'FINCA OLIVAR LLERONA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+Del+Poble+De+Llerona/@41.649626,2.2878497,607m/data=!3m1!1e3!4m7!3m6!1s0x12a4c60fb772d707:0x159a9fe0f85dc925!8m2!3d41.6496569!4d2.29059!15sCltDYW3DrSBkZSBDYW4gVG9uaSwgcy9uIC0gMDg1MjAgTGVzIEZyYW5xdWVzZXMgZGVsIFZhbGzDqHMgKExsZXJvbmEpIC0gQmFyY2Vsb25hIChDYXRhbHVueWEpkgERZ292ZXJubWVudF9vZmZpY2XgAQA!16s%2Fg%2F11bx1qp8qt?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=e2d87850-d2c8-4dd7-9c35-d9ea32dc88d1',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/3.-Antigues-escoles-Llerona.png',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
            'oficinas' => 'Antigues Escoles de Corró d\'Amunt, Ajuntament de Corró d\'Avall i Antigues Escoles de Marata',
            'tipus'   => ['DS'],
        ],

        // ── BELLAVISTA ───────────────────────────────────────────────────────
        'ALZINA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'AMETLLERS' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['portals' => [1, 3], 'dies' => 'Del 16 al 21 de març'],
                ['portals' => 'resta', 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
            'tipus'   => ['CL'],
        ],
        'ANDALUSIA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 14,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 1,  'max' => 13,  'parell' => false, 'dies' => 'Del 16 al 21 de març'],
                ['min' => 16, 'max' => 24,  'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 15, 'max' => 115, 'parell' => false, 'dies' => 'Del 7 a l\'11 d\'abril'],
            ],
            'tipus'   => ['PS'],
        ],
        'ARAGO' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 10,  'parell' => true, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 12, 'max' => 50,  'parell' => true, 'dies' => 'Del 16 al 21 de març'],
                ['min' => 68, 'max' => 138, 'parell' => true, 'dies' => 'Del 7 a l\'11 d\'abril'],
            ],
            'tipus'   => ['CL'],
        ],
        'BARCELONA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 34,  'parell' => true,  'dies' => 'Del 23 al 28 de març'],
                ['min' => 36, 'max' => 60,  'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 1,  'max' => 999, 'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
            'tipus'   => ['CL'],
        ],
        'BOSC' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'CANTABRIA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 7 a l\'11 d\'abril',
            'tipus'   => ['CL'],
        ],
        'CANUDAS' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'CARDEDEU' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'CAN REVERTER' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['DS'],
        ],
        'EIVISSA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'EMPORDA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'ESBARJO' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['PZ'],
        ],
        'ESCALA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'ESPANYA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['portals' => [1, 2, 3], 'dies' => 'Del 16 al 21 de març'],
                ['portals' => [6, 7, 9, 11], 'dies' => 'Del 23 al 28 de març'],
            ],
            'tipus'   => ['PZ'],
        ],
        'EXTREMADURA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 68,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 1,  'max' => 51,  'parell' => false, 'dies' => 'Del 7 a l\'11 d\'abril'],
                ['min' => 53, 'max' => 73,  'parell' => false, 'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 70, 'max' => 76,  'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
            'tipus'   => ['CL'],
        ],
        'GIRONA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 1,   'max' => 49,  'parell' => false, 'dies' => 'Del 16 al 21 de març'],
                ['min' => 8,   'max' => 42,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 51,  'max' => 115, 'parell' => false, 'dies' => 'Del 7 a l\'11 d\'abril'],
                ['min' => 44,  'max' => 116, 'parell' => true,  'dies' => 'Del 7 a l\'11 d\'abril'],
                ['min' => 290, 'max' => 308, 'parell' => true,  'dies' => 'Del 23 al 28 de març'],
            ],
            'tipus'   => ['CL'],
        ],
        'ILLES MEDES' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 20, 'parell' => true, 'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 22, 'max' => 28, 'parell' => true, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 999,'parell' => false,'dies' => 'Del 23 al 28 de març'],
            ],
            'tipus'   => ['CL'],
        ],
        'LLEIDA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'MALLORCA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'MENORCA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'NORD' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 7 a l\'11 d\'abril',
            'tipus'   => ['RD'],
        ],
        'NOVA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['PZ'],
        ],
        'ORIENT' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 40,  'parell' => true,  'dies' => 'Del 7 a l\'11 d\'abril'],
                ['min' => 1,  'max' => 999, 'parell' => false, 'dies' => 'Del 7 a l\'11 d\'abril'],
                ['min' => 60, 'max' => 102, 'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
            'tipus'   => ['CL'],
        ],
        'PAIS BASC' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 7 a l\'11 d\'abril',
            'tipus'   => ['PS'],
        ],
        'PERE EL GRAN' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'PONENT' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'PROVENCA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 18,  'parell' => true,  'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 27,  'parell' => false, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 20, 'max' => 30,  'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 29, 'max' => 41,  'parell' => false, 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
            'tipus'   => ['CL', 'PZ'],
        ],
        'RIOJA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 7 a l\'11 d\'abril',
            'tipus'   => ['CL'],
        ],
        'ROSSELLO' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 28,  'parell' => true,  'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 35,  'parell' => false, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 38, 'max' => 54,  'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 37, 'max' => 39,  'parell' => false, 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
            'tipus'   => ['CL'],
        ],
        'TARRAGONA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'TERME' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'TORRE PINOS' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 32, 'parell' => null, 'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 34, 'max' => 42, 'parell' => null, 'dies' => 'Del 23 al 28 de març'],
            ],
            'tipus'   => ['CL'],
        ],
        'TRAVESSIA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 8,   'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 10, 'max' => 30,  'parell' => true,  'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 999, 'parell' => false, 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
            'tipus'   => ['CL'],
        ],
        'VALENCIA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'VIA FERROCARRIL' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],

        'CATALUNYA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'maps'    => 'https://www.google.com/maps/place/Centre+Cultural+Bellavista/@41.6203793,2.2997893,3a,75y,90t/data=!3m8!1e2!3m6!1sCIHM0ogKEICAgIC_3oqQcg!2e10!3e12!6shttps:%2F%2Flh3.googleusercontent.com%2Fgps-cs-s%2FAHVAwepHo75kLNfNIQ5G0AkxnxEn4KkGcNHiHyRnCe5tob-1ok2UGQTSIEfqJ2XeQcqAvajJcuvIQKDO_0_BN46L9tX_WhgVD8zeed6jSEWb0uUQHOedW6e962bd6COhaQZ6e_INmv07%3Dw152-h86-k-no!7i3264!8i1840!4m7!3m6!1s0x12a4c62b50000001:0x8892b8fc9d89261f!8m2!3d41.6203436!4d2.2996716!10e5!16s%2Fg%2F12qg_29ms?entry=ttu&g_ep=EgoyMDI2MDIwNC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/01/1-3.png',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['portals' => [1, 2, 3, 10], 'dies' => 'Del 23 al 28 de març'],
                ['portals' => [5, 6, 7, 9],  'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
            'tipus'   => ['PZ'],
        ],

        // ── CORRÓ D'AVALL ────────────────────────────────────────────────────
        'AGUDES' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['CL'],
        ],
        'BALMES' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'BRUC' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'CALMA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['PS'],
        ],
        'CANARI' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'CARBONELL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'CARDERNERA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'CASERIU' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'CELLECS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 12,  'parell' => true, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 999, 'parell' => false,'dies' => 'Del 23 al 28 de març'],
                ['min' => 18, 'max' => 22,  'parell' => true, 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
            'tipus'   => ['CL'],
        ],
        'CIRCUNVALLACIO' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'MAJOR AVALL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'COLOME' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'CONGOST' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'DIAGONAL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['CL'],
        ],
        'ESCORXADOR' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL', 'PZ'],
        ],
        'ESPOLSADA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['PZ'],
        ],
        'ESPORTS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'FERRERIA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'FRANQUESAS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['AV'],
        ],
        'JACINT VERDAGUER' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['CL'],
        ],
        'JAUME I' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'JOAN MARAGALL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['CL'],
        ],
        'JOAN OLIVER' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['CL'],
        ],
        'LLEVANT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'MIGDIA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'MIL PINS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'MIQUEL MARTI I POL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'MONTSENY' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['AV'],
        ],
        'ONZE SETEMBRE' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 16, 'max' => 20,  'parell' => true,  'dies' => 'Del 9 al 14 de març'],
                ['min' => 33, 'max' => 43,  'parell' => false, 'dies' => 'Del 9 al 14 de març'],
                ['min' => 2,  'max' => 14,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 1,  'max' => 31,  'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
            'tipus'   => ['CL'],
        ],
        'PAU CASALS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'PEDRAFORCA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['PS'],
        ],
        'PUIGGRACIOS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['CL'],
        ],
        'PUIGSACALM' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['CL'],
        ],
        'RAFAEL ALBERTI' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['CL'],
        ],
        'CANOVES' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 2,   'max' => 999, 'parell' => true,  'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,   'max' => 999, 'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
            'tipus'   => ['CR'],
        ],
        'RIBES' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 2,   'max' => 14,  'parell' => true,  'dies' => 'Del 9 al 14 de març'],
                ['min' => 1,   'max' => 55,  'parell' => false, 'dies' => 'Del 9 al 14 de març'],
                ['min' => 80,  'max' => 120, 'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 61,  'max' => 127, 'parell' => false, 'dies' => 'Del 16 al 21 de març'],
                ['min' => 131, 'max' => 205, 'parell' => false, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 122, 'max' => 264, 'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 209, 'max' => 273, 'parell' => false, 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
            'tipus'   => ['CR'],
        ],
        'SANT ANTONI' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'SANT ISIDRE' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 42, 'max' => 50,  'parell' => true,  'dies' => 'Del 9 al 14 de març'],
                ['min' => 2,  'max' => 40,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 3,  'max' => 3,   'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
            'tipus'   => ['CL'],
        ],
        'SANT JOAN' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['PZ'],
        ],
        'SANT JOAQUIM' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 82,  'max' => 138, 'parell' => true,  'dies' => 'Del 9 al 14 de març'],
                ['min' => 81,  'max' => 125, 'parell' => false, 'dies' => 'Del 9 al 14 de març'],
                ['min' => 2,   'max' => 80,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 1,   'max' => 79,  'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
            'tipus'   => ['CL'],
        ],
        'SANT JORDI' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'SANT JOSEP' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL', 'TR'],
        ],
        'SANT PERE' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'SANT PONC' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
                'tipus'   => ['CL'],
            ],
        'SANT TOMAS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
                'tipus'   => ['CL'],
            ],
        'SANTA EULALIA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['portals' => [2, 4], 'dies' => 'Del 30 de març al 4 d\'abril'],
                ['portals' => 'resta', 'dies' => 'Del 23 al 28 de març'],
            ],
            'tipus'   => ['AV'],
            ],
        'SERRA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 19, 'max' => 37,  'parell' => false, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 999, 'parell' => null,  'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
            'tipus'   => ['CL'],
            ],
        'SOL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
                'tipus'   => ['CL'],
            ],
        'SUI' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['PJ'],
        ],
        'TAGAMANENT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 102, 'max' => 146, 'parell' => true,  'dies' => 'Del 9 al 14 de març'],
                ['min' => 105, 'max' => 171, 'parell' => false, 'dies' => 'Del 9 al 14 de març'],
                ['min' => 58,  'max' => 100, 'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 81,  'max' => 103, 'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
            'tipus'   => ['CL', 'PJ'],
            ],
        'VERGE DE LA MERCE' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 1, 'max' => 999, 'parell' => false, 'dies' => 'Del 9 al 14 de març'],
                ['min' => 1, 'max' => 999, 'parell' => true,  'dies' => 'Del 16 al 21 de març'],
            ],
            'tipus'   => ['CL'],
        ],
        'VERGE DE MONTSERRAT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
                'tipus'   => ['CL'],
            ],
        'VERGE DE NURIA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
                'tipus'   => ['CL'],
            ],
        'AJUNTAMENT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['AV', 'PZ'],
        ],
        'ALBA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['CL'],
        ],
        'ANTIC DE VIC' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CM'],
        ],
        'ANTON NOU' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['DS'],
        ],
        'CAN BALDICH' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['DS'],
        ],
        'CAN CALET' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['RD'],
        ],
        'CAN CALSAS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['DS'],
        ],
        'CAN DUROS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['DS'],
        ],
        'CAN GOITA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['DS'],
        ],
        'CAN GRAS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['DS'],
        ],
        'CAN PAU DRACH' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['DS'],
        ],
        'CAN PRAT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['PZ'],
        ],
        'CAN PUJADAS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['DS'],
        ],
        'CASERIU DEL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['TR'],
        ],
        'CERAMICAS FONT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['DS'],
        ],
        'CORRO DE VALL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['DS'],
        ],
        'ESTADANT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'tipus'   => ['PJ'],
        ],
        'ESTANOC' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['DS'],
        ],
        'FOLCH I TORRES' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'FRANCESC MACIA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['PZ'],
        ],
        'GAUDI' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['PZ'],
        ],
        'GRANJA GRAU' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['DS'],
        ],
        'GRANOLLERS CARDEDEU' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CR'],
        ],
        'GUILLERIES' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['PJ'],
        ],
        'LLERONA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['AV'],
        ],
        'MAS COLOME' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'OM' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['CL'],
        ],
        'ONZE DE SETEMBRE' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['PJ'],
        ],
        'POMPEU FABRA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['PJ'],
        ],
        'PONT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'RIERA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['CL'],
        ],
        'ROCA CENTELLA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
            'tipus'   => ['PJ'],
        ],
        'SAGRERA LA' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'maps'    => 'https://www.google.com/maps/place/Consell+del+Poble+de+Corró+d%27Amunt+%28Antigues+Escoles%29/@41.6693896,2.3229827,1214m/data=!3m1!1e3!4m10!1m2!2m1!1sANTIGUES+ESCOLES+CORRO+D%27AMUNT+CARRETERA+DE+CANOVES!3m6!1s0x12a4cfc1320928c9:0xf93c62ee561e1a9d!8m2!3d41.6693074!4d2.3286173!15sCjNBTlRJR1VFUyBFU0NPTEVTIENPUlJPIEQnQU1VTlQgQ0FSUkVURVJBIERFIENBTk9WRVOSARdlZHVjYXRpb25hbF9pbnN0aXR1dGlvbuABAA!16s%2Fg%2F11h271njv_?entry=ttu&g_ep=EgoyMDI2MDEyOC4wIKXMDSoASAFQAw%3D%3D',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/2.-Antigues_Escoles_Corro_d_Amunt.jpg',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
            'oficinas' => 'Antigues Escoles de Marata, Ajuntament de Corró d\'Avall i Antigues Escoles de Llerona',
            'tipus'   => ['PZ'],
        ],
        'SAGRERA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
            'rangs'   => [
                ['min' => 1,  'max' => 5,   'parell' => false, 'dies' => 'Del 16 al 21 de març'],
                ['min' => 1,  'max' => 999, 'parell' => true,  'dies' => 'Del 9 al 14 de març'],
                ['min' => 23, 'max' => 999, 'parell' => false, 'dies' => 'Del 9 al 14 de març'],
            ],
            'tipus'   => ['AV', 'PZ'],
        ],
        'SALVADOR ESPRIU' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
            'tipus'   => ['PJ'],
        ],
        'TIL·LERS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['PS'],
        ],
        'TORRE SUBIRANA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
            'tipus'   => ['DS'],
        ],
        'VESPRADA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'maps'    => 'google.com/maps/place/Ajuntament+de+Les+Franqueses+del+Vallès/@41.6363281,2.2923644,607m/data=!3m3!1e3!4b1!5s0x12a4c622256c2927:0x777a3a13c660e0d8!4m6!3m5!1s0x12a4c5eb6d9388b3:0x7ae6dbc9fd77d985!8m2!3d41.6363242!4d2.2972353!16s%2Fg%2F11bw8dxskh?entry=tts&g_ep=EgoyMDI2MDEyOC4wIPu8ASoASAFQAw%3D%3D&skid=d7b7e3d5-65d3-4c1c-acac-a682dbc45b9e',
            'img'     => 'https://alesfranquesesfemnet.cat/wp-content/uploads/2026/02/4.-Ajuntament-Corro-d_Avall.jpg',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
                'tipus'   => ['CL'],
            ],
    ];

    // ─── LÒGICA DE CERCA ────────────────────────────────────────────────────

    $resultat = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['residus_nonce']) &&
        wp_verify_nonce($_POST['residus_nonce'], 'buscador_residus')) {

        $carrer_input = isset($_POST['carrer']) ? sanitize_text_field($_POST['carrer']) : '';
        $portal_input = isset($_POST['portal']) ? intval($_POST['portal']) : 0;
        $tipus_input  = isset($_POST['tipus_via']) ? sanitize_text_field($_POST['tipus_via']) : '';

        if ($carrer_input && $portal_input > 0 && $tipus_input) {
            $carrer_norm = residus_normalitzar($carrer_input);

            foreach ($carrers as $clau => $dades) {
                // Filtrar per tipus de via
                if (isset($dades['tipus']) && !in_array($tipus_input, $dades['tipus'])) {
                    continue;
                }
                $clau_norm = residus_normalitzar($clau);
                $match = (strpos(' ' . $carrer_norm . ' ', ' ' . $clau_norm . ' ') !== false) ||
                         (strpos(' ' . $clau_norm . ' ', ' ' . $carrer_norm . ' ') !== false);
                if ($match) {

                    $dies_trobats = isset($dades['dies']) ? $dades['dies'] : null;

                    // Si té rangs, buscar el rang que correspon al portal
                    if (isset($dades['rangs'])) {
                        foreach ($dades['rangs'] as $rang) {

                            // Rang per llista de portals específics
                            if (isset($rang['portals'])) {
                                if ($rang['portals'] === 'resta') {
                                    $dies_trobats = $rang['dies'];
                                } elseif (in_array($portal_input, $rang['portals'])) {
                                    $dies_trobats = $rang['dies'];
                                    break;
                                }
                                continue;
                            }

                            // Rang per min/max + parell/senar
                            if (!isset($rang['min']) || !isset($rang['max'])) {
                                continue;
                            }
                            $es_parell = ($portal_input % 2 === 0);
                            $dins_rang = ($portal_input >= $rang['min'] && $portal_input <= $rang['max']);
                            $parell_ok = (!isset($rang['parell']) || $rang['parell'] === null) ||
                                         ($rang['parell'] === true && $es_parell) ||
                                         ($rang['parell'] === false && !$es_parell);

                            if ($dins_rang && $parell_ok) {
                                $dies_trobats = $rang['dies'];
                                break;
                            }
                        }
                    }

                    $resultat = [
                        'oficina' => $dades['oficina'],
                        'adreca'  => $dades['adreca'],
                        'periode' => $dades['periode'],
                        'dies'    => $dies_trobats,
                        'carrer'  => $carrer_input,
                        'portal'  => $portal_input,
                        'maps' => isset($dades['maps']) ? $dades['maps'] : 'https://www.google.com/maps/search/' . urlencode($dades['adreca']),
                        'img'     => isset($dades['img']) ? $dades['img'] : null,
                        'oficinas'  => $dades['oficinas'] ?? null,
                    ];
                    break;
                }
            }

            if (!$resultat) {
                $resultat = ['error' => true];
            }
        }
    }

 // ─── HTML ───────────────────────────────────────────────────────────────
 ob_start();
 ?>
 <div class="residus-buscador" id="residus-buscador">
     <form method="post" action="<?php echo esc_url(get_permalink()); ?>#residus-buscador">
         <?php wp_nonce_field('buscador_residus', 'residus_nonce'); ?>
         <div class="residus-camp">
             <label for="tipus_via">Tipus de via</label>
             <select id="tipus_via" name="tipus_via" required>
                 <option value="" disabled <?php echo empty($_POST['tipus_via']) ? 'selected' : ''; ?>>Tipus de via</option>
                 <option value="AV"  <?php echo (isset($_POST['tipus_via']) && $_POST['tipus_via'] === 'AV')  ? 'selected' : ''; ?>>Avinguda (AV)</option>
                 <option value="CL"  <?php echo (isset($_POST['tipus_via']) && $_POST['tipus_via'] === 'CL')  ? 'selected' : ''; ?>>Carrer (CL / c.)</option>
                 <option value="CM"  <?php echo (isset($_POST['tipus_via']) && $_POST['tipus_via'] === 'CM')  ? 'selected' : ''; ?>>Camí (CM / Camí)</option>
                 <option value="CR"  <?php echo (isset($_POST['tipus_via']) && $_POST['tipus_via'] === 'CR')  ? 'selected' : ''; ?>>Carretera (CR / Carretera)</option>
                 <option value="DS"  <?php echo (isset($_POST['tipus_via']) && $_POST['tipus_via'] === 'DS')  ? 'selected' : ''; ?>>Disseminat (DS)</option>
                 <option value="PS"  <?php echo (isset($_POST['tipus_via']) && $_POST['tipus_via'] === 'PS')  ? 'selected' : ''; ?>>Passeig (PS)</option>
                 <option value="PJ"  <?php echo (isset($_POST['tipus_via']) && $_POST['tipus_via'] === 'PJ')  ? 'selected' : ''; ?>>Passatge (PJ)</option>
                 <option value="PZ"  <?php echo (isset($_POST['tipus_via']) && $_POST['tipus_via'] === 'PZ')  ? 'selected' : ''; ?>>Plaça (PZ / Plaça)</option>
                 <option value="RD"  <?php echo (isset($_POST['tipus_via']) && $_POST['tipus_via'] === 'RD')  ? 'selected' : ''; ?>>Ronda (RD)</option>
                 <option value="TR"  <?php echo (isset($_POST['tipus_via']) && $_POST['tipus_via'] === 'TR')  ? 'selected' : ''; ?>>Travessia (TR)</option>
             </select>
         </div>
         <div class="residus-camp">
             <label for="carrer">Carrer</label>
             <input type="text" id="carrer" name="carrer"
                    placeholder="Ex: Aragó"
                    value="<?php echo isset($_POST['carrer']) ? esc_attr($_POST['carrer']) : ''; ?>"
                    required>
         </div>
         <div class="residus-camp">
             <label for="portal">Número de portal</label>
             <input type="number" id="portal" name="portal" min="1"
                    placeholder="Ex: 24"
                    value="<?php echo isset($_POST['portal']) ? esc_attr($_POST['portal']) : ''; ?>"
                    required>
         </div>
         <button type="submit">Consulta</button>
     </form>

     <?php if ($resultat): ?>
         <?php if (!empty($resultat['error'])): ?>
             <div class="residus-resultat residus-error">
                 <p>No s'ha trobat cap oficina per a l'adreça indicada. Comprova el nom del carrer i torna-ho a intentar.</p>
             </div>
         <?php else: ?>
             <div class="residus-resultat residus-ok">
                 <?php if (!empty($resultat['img'])): ?>
                     <img src="<?php echo esc_url($resultat['img']); ?>" alt="<?php echo esc_attr($resultat['oficina']); ?>" style="width:100%;border-radius:6px;margin-bottom:15px;">
                 <?php endif; ?>
                 <h3>La teva oficina de residus</h3>
                 <p><strong>Oficina:</strong> <?php echo esc_html($resultat['oficina']); ?></p>
                 <p><strong>Adreça:</strong>
                     <?php if (!empty($resultat['maps'])): ?>
                         <a href="<?php echo esc_url($resultat['maps']); ?>" target="_blank" rel="noopener">
                             <?php echo esc_html($resultat['adreca']); ?>
                         </a>
                     <?php else: ?>
                         <?php echo esc_html($resultat['adreca']); ?>
                     <?php endif; ?>
                 </p>
                 <p><strong>Pots venir a buscar el kit:</strong> <?php echo esc_html($resultat['periode']); ?></p>
                 <?php if ($resultat['dies']): ?>
                     <p><strong>Dies preferents per evitar cues:</strong> <?php echo esc_html($resultat['dies']); ?></p>
                 <?php endif; ?>
                 <?php if ($resultat['oficinas']): ?>
                     <p><strong>Oficines secundàries:</strong> <?php echo esc_html($resultat['oficinas']); ?></p>
                 <?php endif; ?>
             </div>
         <?php endif; ?>
     <?php endif; ?>
 </div>

 <style>
     .residus-buscador { max-width: 500px; margin: 20px auto; font-family: sans-serif; margin-bottom: 100px;}
     .residus-camp { margin-bottom: 15px; }
     .residus-camp label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
     .residus-camp input, .residus-camp select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; transition: border-color 0.2s; box-sizing: border-box; background: #fff; }
     .residus-camp input:focus, .residus-camp select:focus { outline: none; border-color: #d50911; }
     .residus-buscador button { background: linear-gradient(90deg, #d51116 0%, #f6a307 100%); color: #fff; border: none; padding: 12px 30px; font-size: 16px; border-radius: 4px; cursor: pointer; transition: opacity 0.2s; }
     .residus-buscador button:hover { opacity: 0.9; }
     .residus-resultat { margin-top: 25px; padding: 20px; border-radius: 6px; }
     .residus-ok { background: rgba(250, 250, 250, 1); border-left: 4px solid #d50911; }
     .residus-error { background: rgba(250, 250, 250, 1); border-left: 4px solid #5c6569; }
     .residus-resultat h3 { margin-top: 0; background: linear-gradient(90deg, #d51116 0%, #f6a307 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
     .residus-resultat p { margin: 8px 0; color: #333; }
     .residus-resultat strong { color: #d50911; }
     .residus-error p { color: #5c6569; }
 </style>
 <?php
 return ob_get_clean();
}

// ─── FUNCIÓ DE NORMALITZACIÓ ─────────────────────────────────────────────────
function residus_normalitzar($text) {
    $text = strtoupper(trim($text));
    
    // Eliminar apòstrofs i guions, col·lapsar espais
    $text = str_replace(["'", "\u{2019}", "\u{2018}", "-", ",", "."], ' ', $text);
    $text = preg_replace('/\s+/', ' ', trim($text));
    
    $prefixos = ['CARRER ', 'CARRETERA ', 'AVINGUDA ', 'PASSATGE ', 'PASSEIG ',
                 'PLAÇA ', 'PLACA ', 'RONDA ', 'CAMÍ ', 'CAMI ', 'TRAVESSERA ',
                 'CL ', 'CR ', 'AV ', 'PZ ', 'PS ', 'PJ ', 'TR ', 'RD ', 'DS ', 'CM ',
                 'CA L ', 'CAN ', 'CAL ', 'MAS '];
    foreach ($prefixos as $p) {
        if (strpos($text, $p) === 0) {
            $text = substr($text, strlen($p));
            break;
        }
    }
    $from = ['À','Á','Â','Ã','Ä','à','á','â','ã','ä','È','É','Ê','Ë','è','é','ê','ë',
             'Ì','Í','Î','Ï','ì','í','î','ï','Ò','Ó','Ô','Õ','Ö','ò','ó','ô','õ','ö',
             'Ù','Ú','Û','Ü','ù','ú','û','ü','Ç','ç','Ñ','ñ','·','L·L','l·l'];
    $to   = ['A','A','A','A','A','A','A','A','A','A','E','E','E','E','E','E','E','E',
             'I','I','I','I','I','I','I','I','O','O','O','O','O','O','O','O','O','O',
             'U','U','U','U','U','U','U','U','C','C','N','N','L','LL','LL'];
    $text = str_replace($from, $to, $text);
    $text = str_replace('L L', 'LL', $text); // "til lers" escrit amb espai en lloc de punt volat
    return $text;
}