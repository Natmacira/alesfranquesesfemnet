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
            'periode' => '25 de febrer',
            'dies'    => '25 de febrer',
        ],
        'CAN MANEL' => [
            'oficina' => 'Antigues Escoles de Marata',
            'adreca'  => 'Plaça de Marata, s/n',
            'periode' => '25 de febrer',
            'dies'    => '25 de febrer',
        ],
        'CAN PARERA' => [
            'oficina' => 'Antigues Escoles de Marata',
            'adreca'  => 'Plaça de Marata, s/n',
            'periode' => '25 de febrer',
            'dies'    => '25 de febrer',
        ],
        'CAN PERICA' => [
            'oficina' => 'Antigues Escoles de Marata',
            'adreca'  => 'Plaça de Marata, s/n',
            'periode' => '25 de febrer',
            'dies'    => '25 de febrer',
        ],
        'CAN PONETA' => [
            'oficina' => 'Antigues Escoles de Marata',
            'adreca'  => 'Plaça de Marata, s/n',
            'periode' => '25 de febrer',
            'dies'    => '25 de febrer',
        ],

        // ── CORRÓ D'AMUNT ────────────────────────────────────────────────────
        'CA L\'AIXIQUET' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAL MARGE' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAMELIA' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAN BASSO' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAN BERTRAN' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAN BESSÓ NOU' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAN CAMP' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAN MONTASELL' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAN PROFITOS' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAN SUQUET' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAN VIUDEZ' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CAN XICU' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'CORRO DE MUNT' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'GLADIOL' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'LLIRI' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'SANT JERONI' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'TIBIDABO' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'TRAVESSIA DE GRACIA' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'TULIPA' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],
        'UNIO' => [
            'oficina' => 'Antigues Escoles de Corró d\'Amunt',
            'adreca'  => 'Carretera de Cànoves, s/n',
            'periode' => 'Del 26 al 28 de febrer',
            'dies'    => 'Del 26 al 28 de febrer',
        ],

        // ── LLERONA ──────────────────────────────────────────────────────────
        'ANGEL GUIMERA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'BOU D\'EN' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CA L\'ARIMANY' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CABAL' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CABIROL' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CADI' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAL NEN' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN BOU' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN CALVET' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN COLL D\'OCA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN CONGOST NOU' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN GESA NOU' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN LLOREDA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN MAS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN MINGUET' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN PERICAS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN POSAS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN POUS DALT' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN ROF' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN ROGET' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN SOLELLA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CAN TINET NOU' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CANIGO' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CARDENAL JUBANY' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'CONCA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'DELTA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'DORCA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'ESGLESIA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'ESTUARI' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'FABRICA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'FERRERET' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'FONT DE LA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'GORCS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'JOSEP M BOIXAREU' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'LLUIS COMPANYS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'MARIA MARGENS' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'RIBERA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'SANTA DIGNA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'SELVA DEL PLA' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'TORRENT' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],
        'TURO MENTIDES' => [
            'oficina' => 'Antigues Escoles de Llerona',
            'adreca'  => 'Camí de Can Toni, s/n',
            'periode' => 'Del 2 al 7 de març',
            'dies'    => 'Del 2 al 7 de març',
        ],

        // ── BELLAVISTA ───────────────────────────────────────────────────────
        'ALZINA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'AMETLLERS' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['portals' => [1, 3], 'dies' => 'Del 16 al 21 de març'],
                ['portals' => 'resta', 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
        ],
        'ANDALUSIA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 14,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 1,  'max' => 13,  'parell' => false, 'dies' => 'Del 16 al 21 de març'],
                ['min' => 16, 'max' => 24,  'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 15, 'max' => 115, 'parell' => false, 'dies' => 'Del 7 a l\'11 d\'abril'],
            ],
        ],
        'ARAGO' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 10,  'parell' => true, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 12, 'max' => 50,  'parell' => true, 'dies' => 'Del 16 al 21 de març'],
                ['min' => 68, 'max' => 138, 'parell' => true, 'dies' => 'Del 7 a l\'11 d\'abril'],
            ],
        ],
        'BARCELONA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 34,  'parell' => true,  'dies' => 'Del 23 al 28 de març'],
                ['min' => 36, 'max' => 60,  'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 1,  'max' => 999, 'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
        ],
        'BOSC' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'CANTABRIA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 7 a l\'11 d\'abril',
        ],
        'CANUDAS' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'CARDEDEU' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'CAN REVERTER' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'EIVISSA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'EMPORDA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'ESBARJO' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'ESCALA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'ESPANYA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['portals' => [1, 2, 3], 'dies' => 'Del 16 al 21 de març'],
                ['portals' => [6, 7, 9, 11], 'dies' => 'Del 23 al 28 de març'],
            ],
        ],
        'EXTREMADURA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 68,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 1,  'max' => 51,  'parell' => false, 'dies' => 'Del 7 a l\'11 d\'abril'],
                ['min' => 53, 'max' => 73,  'parell' => false, 'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 70, 'max' => 76,  'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
        ],
        'GIRONA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 1,   'max' => 49,  'parell' => false, 'dies' => 'Del 16 al 21 de març'],
                ['min' => 8,   'max' => 42,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 51,  'max' => 115, 'parell' => false, 'dies' => 'Del 7 a l\'11 d\'abril'],
                ['min' => 44,  'max' => 116, 'parell' => true,  'dies' => 'Del 7 a l\'11 d\'abril'],
                ['min' => 290, 'max' => 308, 'parell' => true,  'dies' => 'Del 23 al 28 de març'],
            ],
        ],
        'ILLES MEDES' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 20, 'parell' => true, 'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 22, 'max' => 28, 'parell' => true, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 999,'parell' => false,'dies' => 'Del 23 al 28 de març'],
            ],
        ],
        'LLEIDA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'MALLORCA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'MENORCA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'NORD' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 7 a l\'11 d\'abril',
        ],
        'NOVA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'ORIENT' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 40,  'parell' => true,  'dies' => 'Del 7 a l\'11 d\'abril'],
                ['min' => 1,  'max' => 999, 'parell' => false, 'dies' => 'Del 7 a l\'11 d\'abril'],
                ['min' => 60, 'max' => 102, 'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
        ],
        'PAIS BASC' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 7 a l\'11 d\'abril',
        ],
        'PERE EL GRAN' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'PONENT' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'PROVENCA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 18,  'parell' => true,  'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 27,  'parell' => false, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 20, 'max' => 30,  'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 29, 'max' => 41,  'parell' => false, 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
        ],
        'RIOJA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 7 a l\'11 d\'abril',
        ],
        'ROSSELLO' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 28,  'parell' => true,  'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 35,  'parell' => false, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 38, 'max' => 54,  'parell' => true,  'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 37, 'max' => 39,  'parell' => false, 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
        ],
        'TARRAGONA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'TERME' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'TORRE PINOS' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 32, 'parell' => null, 'dies' => 'Del 30 de març al 4 d\'abril'],
                ['min' => 34, 'max' => 42, 'parell' => null, 'dies' => 'Del 23 al 28 de març'],
            ],
        ],
        'TRAVESSIA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 8,   'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 10, 'max' => 30,  'parell' => true,  'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 999, 'parell' => false, 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
        ],
        'VALENCIA' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'VIA FERROCARRIL' => [
            'oficina' => 'Centre Cultural Bellavista',
            'adreca'  => 'Carrer Navarra, s/n',
            'periode' => 'Del 16 de març al 18 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],

        // ── CORRÓ D'AVALL ────────────────────────────────────────────────────
        'AGUDES' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'BALMES' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'BRUC' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'CALMA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'CANARI' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'CARBONELL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'CARDERNERA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'CASERIU' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'CELLECS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 2,  'max' => 12,  'parell' => true, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 999, 'parell' => false,'dies' => 'Del 23 al 28 de març'],
                ['min' => 18, 'max' => 22,  'parell' => true, 'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
        ],
        'CIRCUNVALLACIO' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'COLOM' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'CONGOST' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'DIAGONAL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'ESCORXADOR' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'ESPOLSADA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'ESPORTS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'FERRERIA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'FRANQUESAS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'JACINT VERDAGUER' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'JAUME I' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'JOAN MARAGALL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'JOAN OLIVER' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'LLEVANT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'MIGDIA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'MIL PINS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'MIQUEL MARTI I POL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'MONTSENY' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'ONZE SETEMBRE' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 16, 'max' => 20,  'parell' => true,  'dies' => 'Del 9 al 14 de març'],
                ['min' => 33, 'max' => 43,  'parell' => false, 'dies' => 'Del 9 al 14 de març'],
                ['min' => 2,  'max' => 14,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 1,  'max' => 31,  'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
        ],
        'PAU CASALS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 30 de març al 4 d\'abril',
        ],
        'PEDRAFORCA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'PUIGGRACIOS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'PUIGSACALM' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'RAFAEL ALBERTI' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'RIBES' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
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
        ],
        'SANT ANTONI' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'SANT ISIDRE' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 42, 'max' => 50,  'parell' => true,  'dies' => 'Del 9 al 14 de març'],
                ['min' => 2,  'max' => 40,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 3,  'max' => 3,   'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
        ],
        'SANT JOAN' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'SANT JOAQUIM' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 82,  'max' => 138, 'parell' => true,  'dies' => 'Del 9 al 14 de març'],
                ['min' => 81,  'max' => 125, 'parell' => false, 'dies' => 'Del 9 al 14 de març'],
                ['min' => 2,   'max' => 80,  'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 1,   'max' => 79,  'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
        ],
        'SANT JORDI' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'SANT JOSEP' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'SANT PERE' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'SANT PONC' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'SANT TOMAS' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 9 al 14 de març',
        ],
        'SANTA EULALIA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['portals' => [2, 4], 'dies' => 'Del 30 de març al 4 d\'abril'],
                ['portals' => 'resta', 'dies' => 'Del 23 al 28 de març'],
            ],
        ],
        'SERRA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 19, 'max' => 37,  'parell' => false, 'dies' => 'Del 23 al 28 de març'],
                ['min' => 1,  'max' => 999, 'parell' => null,  'dies' => 'Del 30 de març al 4 d\'abril'],
            ],
        ],
        'SOL' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
        'TAGAMANENT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 102, 'max' => 146, 'parell' => true,  'dies' => 'Del 9 al 14 de març'],
                ['min' => 105, 'max' => 171, 'parell' => false, 'dies' => 'Del 9 al 14 de març'],
                ['min' => 58,  'max' => 100, 'parell' => true,  'dies' => 'Del 16 al 21 de març'],
                ['min' => 81,  'max' => 103, 'parell' => false, 'dies' => 'Del 16 al 21 de març'],
            ],
        ],
        'VERGE DE LA MERCE' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'rangs'   => [
                ['min' => 1, 'max' => 999, 'parell' => false, 'dies' => 'Del 9 al 14 de març'],
                ['min' => 1, 'max' => 999, 'parell' => true,  'dies' => 'Del 16 al 21 de març'],
            ],
        ],
        'VERGE DE MONTSERRAT' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'VERGE DE NURIA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 16 al 21 de març',
        ],
        'VESPRADA' => [
            'oficina' => 'Ajuntament de Corró d\'Avall',
            'adreca'  => 'Carretera de Ribes, 2',
            'periode' => 'Del 9 de març al 4 d\'abril',
            'dies'    => 'Del 23 al 28 de març',
        ],
    ];

    // ─── LÒGICA DE CERCA ────────────────────────────────────────────────────

    $resultat = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['residus_nonce']) &&
        wp_verify_nonce($_POST['residus_nonce'], 'buscador_residus')) {

        $carrer_input = isset($_POST['carrer']) ? sanitize_text_field($_POST['carrer']) : '';
        $portal_input = isset($_POST['portal']) ? intval($_POST['portal']) : 0;

        if ($carrer_input && $portal_input > 0) {
            $carrer_norm = residus_normalitzar($carrer_input);

            foreach ($carrers as $clau => $dades) {
                $clau_norm = residus_normalitzar($clau);
                if (strpos($carrer_norm, $clau_norm) !== false || strpos($clau_norm, $carrer_norm) !== false) {

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
                            $es_parell = ($portal_input % 2 === 0);
                            $dins_rang = ($portal_input >= $rang['min'] && $portal_input <= $rang['max']);
                            $parell_ok = ($rang['parell'] === null) ||
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
                <label for="carrer">Carrer</label>
                <input type="text" id="carrer" name="carrer"
                       placeholder="Ex: Carrer Aragó"
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
                    <h3>La teva oficina de residus</h3>
                    <p><strong>Oficina:</strong> <?php echo esc_html($resultat['oficina']); ?></p>
                    <p><strong>Adreça:</strong> <?php echo esc_html($resultat['adreca']); ?></p>
                    <p><strong>Pots venir a buscar el kit:</strong> <?php echo esc_html($resultat['periode']); ?></p>
                    <?php if ($resultat['dies']): ?>
                        <p><strong>Dies preferents per evitar cues:</strong> <?php echo esc_html($resultat['dies']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <style>
   .residus-buscador { max-width: 500px; margin: 20px auto; font-family: sans-serif; margin-bottom: 100px; }
        .residus-camp { margin-bottom: 15px; }
        .residus-camp label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
        .residus-camp input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; transition: border-color 0.2s; }
        .residus-camp input:focus { outline: none; border-color: #d50911; }
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
    $prefixos = ['CARRER ', 'CARRETERA ', 'AVINGUDA ', 'PASSATGE ', 'PASSEIG ',
                 'PLAÇA ', 'PLACA ', 'RONDA ', 'CAMÍ ', 'CAMI ', 'TRAVESSERA ',
                 'CL ', 'CR ', 'AV ', 'PZ ', 'PS ', 'PJ ', 'TR ', 'RD ', 'DS ', 'CM '];
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
    return str_replace($from, $to, $text);
}