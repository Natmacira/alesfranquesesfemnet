<?php
/**
 * Shortcode: Calculadora Taxa Residus 2027
 * Ús: [calc_taxa] (ca) · [calc_taxa_es] (es) · [calc_taxa_fr] (fr) · [calc_taxa_ar] (ar)
 */

function calc_taxa_strings( $lang ) {
    $strings = [
        'ca' => [
            // Explicació
            'intro'           => "La taxa de l'any 2027 té dos components:",
            'comp_fixa'       => '<strong>Part fixa (bàsica):</strong> depèn del tipus d\'habitatge',
            'comp_var'        => '<strong>Part variable:</strong> depèn dels hàbits de separació de residus',
            'intro2'          => 'Si separes bé, la part variable pot arribar a ser de <strong>0 euros!</strong><br>A més, si utilitzes la deixalleria pots obtenir una bonificació sobre la part bàsica.',
            // Pregunta 1
            'q1_title'        => "1. Tipus d'habitatge",
            'q1_desc'         => "Quina és la tipologia del teu habitatge?",
            'tipus_rural'     => 'Rural',
            'tipus_petit'     => 'No rural fins 100 m²',
            'tipus_gran'      => 'No rural més de 100 m²',
            'tipus_aillat'    => 'No rural unifamiliar aïllat',
            // Pregunta 2
            'q2_title'        => '2. Ús del contenidor de resta (anual)',
            'q2_desc'         => 'Quantes vegades diposites residus que no es poden reciclar al contenidor gris?',
            'no_participo'    => 'No participo',
            'poc'             => 'Poc',
            'de_tant_en_tant' => 'De tant en tant',
            'sovint'          => 'Sovint',
            'molt_sovint'     => 'Molt sovint',
            'continuament'    => 'Contínuament',
            'vegades'         => 'vegades',
            // Pregunta 3
            'q3_title'        => "3. Ús de bolquers o animals de companyia",
            'q3_desc'         => "Has declarat que a la teva llar s'utilitzen bolquers o hi ha animals de companyia?",
            'no'              => 'No',
            'si'              => 'Sí',
            // Pregunta 4
            'q4_title'        => "4. Ús del contenidor d'orgànica (anual)",
            'q4_desc'         => 'Quantes vegades diposites restes de menjar i orgàniques al contenidor marró?',
            'molt_sovint_org' => 'Molt sovint o compostatge',
            // Pregunta 5
            'q5_title'        => '5. Aportacions a la deixalleria (anual)',
            'q5_desc'         => 'Quantes vegades has portat residus a la deixalleria fixa?',
            'cap'             => 'Cap o menys de 4',
            'baixa'           => 'Entre 4 i 7',
            'mitja'           => 'Entre 8 i 11',
            'alta'            => '12 o més',
            // Resultats
            'taxa_2026'       => 'Taxa estimada 2026 *',
            'nota_2026'       => "* Càlcul realitzat tenint en compte la franja de bonificacions de la deixalleria que s'ha especificat.",
            'taxa_2027'       => 'Taxa estimada 2027',
            'part_fixa'       => 'Part fixa (bàsica)',
            'part_var_resta'  => 'Part variable RESTA',
            'bonif_org'       => 'Bonificació ORGÀNICA',
            // Ordenança
            'ord_title'       => "Coneix l'ordenança fiscal de les Franqueses",
            'ord_sub'         => "En el següent enllaç podràs veure l'Ordenança Fiscal 12: Taxa prestació servei de gestió de residus municipals 2027.",
            'ord_btn'         => 'Veure ordenança fiscal',
            // JS i18n
            'js_tram2_bolquers'   => '(tram 2 per bolquers/animals)',
            'js_quota_baixa'      => '(quota ≤2 obertures)',
            'js_tram'             => 'tram',
            'js_sense_bonif'      => 'sense bonificació',
            'js_compostatge'      => 'compostatge o ús màxim',
            'js_deixalleria'      => 'deixalleria',
            'js_note_bolquers'    => "Tarifa especial RESTA (tram 2) aplicada per bolquers o animals de companyia.",
            'js_note_resta_baixa' => "Molt poques obertures al RESTA: s'aplica quota especial.",
            'js_note_org_baixa'   => "Molt poques obertures a l'ORGÀNICA: s'aplica quota especial (càrrec).",
        ],
        'es' => [
            'intro'           => 'La tasa del año 2027 tiene dos componentes:',
            'comp_fixa'       => '<strong>Parte fija (básica):</strong> depende del tipo de vivienda',
            'comp_var'        => '<strong>Parte variable:</strong> depende de los hábitos de separación de residuos',
            'intro2'          => 'Si separas bien, la parte variable puede llegar a ser de <strong>0 euros!</strong><br>Además, si utilizas el punto limpio puedes obtener una bonificación sobre la parte básica.',
            'q1_title'        => '1. Tipo de vivienda',
            'q1_desc'         => '¿Cuál es la tipología de tu vivienda?',
            'tipus_rural'     => 'Rural',
            'tipus_petit'     => 'No rural hasta 100 m²',
            'tipus_gran'      => 'No rural más de 100 m²',
            'tipus_aillat'    => 'No rural unifamiliar aislado',
            'q2_title'        => '2. Uso del contenedor de resto (anual)',
            'q2_desc'         => '¿Cuántas veces depositas residuos que no se pueden reciclar en el contenedor gris?',
            'no_participo'    => 'No participo',
            'poc'             => 'Poco',
            'de_tant_en_tant' => 'De vez en cuando',
            'sovint'          => 'A menudo',
            'molt_sovint'     => 'Muy a menudo',
            'continuament'    => 'Continuamente',
            'vegades'         => 'veces',
            'q3_title'        => '3. Uso de pañales o animales de compañía',
            'q3_desc'         => '¿Has declarado que en tu hogar se utilizan pañales o hay animales de compañía?',
            'no'              => 'No',
            'si'              => 'Sí',
            'q4_title'        => '4. Uso del contenedor de orgánica (anual)',
            'q4_desc'         => '¿Cuántas veces depositas restos de comida y orgánicos en el contenedor marrón?',
            'molt_sovint_org' => 'Muy a menudo o compostaje',
            'q5_title'        => '5. Aportaciones al punto limpio (anual)',
            'q5_desc'         => '¿Cuántas veces has llevado residuos al punto limpio fijo?',
            'cap'             => 'Ninguna o menos de 4',
            'baixa'           => 'Entre 4 y 7',
            'mitja'           => 'Entre 8 y 11',
            'alta'            => '12 o más',
            'taxa_2026'       => 'Tasa estimada 2026 *',
            'nota_2026'       => '* Cálculo realizado teniendo en cuenta la franja de bonificaciones del punto limpio que se ha especificado.',
            'taxa_2027'       => 'Tasa estimada 2027',
            'part_fixa'       => 'Parte fija (básica)',
            'part_var_resta'  => 'Parte variable RESTO',
            'bonif_org'       => 'Bonificación ORGÁNICA',
            'ord_title'       => 'Conoce la ordenanza fiscal de Les Franqueses',
            'ord_sub'         => 'En el siguiente enlace podrás ver la Ordenanza Fiscal 12: Tasa prestación servicio de gestión de residuos municipales 2027.',
            'ord_btn'         => 'Ver ordenanza fiscal',
            'js_tram2_bolquers'   => '(tramo 2 por pañales/animales)',
            'js_quota_baixa'      => '(cuota ≤2 aperturas)',
            'js_tram'             => 'tramo',
            'js_sense_bonif'      => 'sin bonificación',
            'js_compostatge'      => 'compostaje o uso máximo',
            'js_deixalleria'      => 'punto limpio',
            'js_note_bolquers'    => 'Tarifa especial RESTO (tramo 2) aplicada por pañales o animales de compañía.',
            'js_note_resta_baixa' => 'Muy pocas aperturas al RESTO: se aplica cuota especial.',
            'js_note_org_baixa'   => 'Muy pocas aperturas a la ORGÁNICA: se aplica cuota especial (cargo).',
        ],
        'fr' => [
            'intro'           => "La taxe de l'année 2027 a deux composantes :",
            'comp_fixa'       => "<strong>Partie fixe (de base) :</strong> dépend du type de logement",
            'comp_var'        => '<strong>Partie variable :</strong> dépend des habitudes de tri des déchets',
            'intro2'          => 'Si vous triez bien, la partie variable peut arriver à <strong>0 euros !</strong><br>De plus, si vous utilisez la déchetterie, vous pouvez obtenir une réduction sur la partie fixe.',
            'q1_title'        => '1. Type de logement',
            'q1_desc'         => 'Quelle est la typologie de votre logement ?',
            'tipus_rural'     => 'Rural',
            'tipus_petit'     => "Non rural jusqu'à 100 m²",
            'tipus_gran'      => 'Non rural plus de 100 m²',
            'tipus_aillat'    => 'Non rural unifamilial isolé',
            'q2_title'        => '2. Utilisation du bac ordures ménagères (annuel)',
            'q2_desc'         => 'Combien de fois déposez-vous des déchets non recyclables dans le bac gris ?',
            'no_participo'    => 'Je ne participe pas',
            'poc'             => 'Peu',
            'de_tant_en_tant' => 'De temps en temps',
            'sovint'          => 'Souvent',
            'molt_sovint'     => 'Très souvent',
            'continuament'    => 'En continu',
            'vegades'         => 'fois',
            'q3_title'        => '3. Utilisation de couches ou animaux de compagnie',
            'q3_desc'         => 'Avez-vous déclaré que votre foyer utilise des couches ou possède des animaux de compagnie ?',
            'no'              => 'Non',
            'si'              => 'Oui',
            'q4_title'        => '4. Utilisation du bac organique (annuel)',
            'q4_desc'         => 'Combien de fois déposez-vous des restes alimentaires et organiques dans le bac marron ?',
            'molt_sovint_org' => 'Très souvent ou compostage',
            'q5_title'        => '5. Apports en déchetterie (annuel)',
            'q5_desc'         => 'Combien de fois avez-vous apporté des déchets à la déchetterie ?',
            'cap'             => 'Aucun ou moins de 4',
            'baixa'           => 'Entre 4 et 7',
            'mitja'           => 'Entre 8 et 11',
            'alta'            => '12 ou plus',
            'taxa_2026'       => 'Taxe estimée 2026 *',
            'nota_2026'       => '* Calcul réalisé en tenant compte de la tranche de réductions de la déchetterie spécifiée.',
            'taxa_2027'       => 'Taxe estimée 2027',
            'part_fixa'       => 'Partie fixe (de base)',
            'part_var_resta'  => 'Partie variable ORDURES',
            'bonif_org'       => 'Réduction ORGANIQUE',
            'ord_title'       => "Découvrez l'ordonnance fiscale de Les Franqueses",
            'ord_sub'         => "Dans le lien suivant, vous pourrez consulter l'Ordonnance Fiscale 12 : Taxe de prestation du service de gestion des déchets municipaux 2027.",
            'ord_btn'         => "Voir l'ordonnance fiscale",
            'js_tram2_bolquers'   => '(tranche 2 pour couches/animaux)',
            'js_quota_baixa'      => '(quota ≤2 ouvertures)',
            'js_tram'             => 'tranche',
            'js_sense_bonif'      => 'sans réduction',
            'js_compostatge'      => 'compostage ou usage maximum',
            'js_deixalleria'      => 'déchetterie',
            'js_note_bolquers'    => "Tarif spécial ORDURES (tranche 2) appliqué pour couches ou animaux de compagnie.",
            'js_note_resta_baixa' => "Très peu d'ouvertures ORDURES : quota spécial appliqué.",
            'js_note_org_baixa'   => "Très peu d'ouvertures ORGANIQUE : quota spécial appliqué (supplément).",
        ],
        'ar' => [
            'intro'           => 'تتكون رسوم عام 2027 من مكونين:',
            'comp_fixa'       => '<strong>الجزء الثابت (الأساسي):</strong> يعتمد على نوع المسكن',
            'comp_var'        => '<strong>الجزء المتغير:</strong> يعتمد على عادات فرز النفايات',
            'intro2'          => 'إذا فرزت جيداً، يمكن أن يصل الجزء المتغير إلى <strong>0 يورو!</strong><br>علاوة على ذلك، إذا استخدمت مركز إعادة التدوير، يمكنك الحصول على خصم على الجزء الثابت.',
            'q1_title'        => '1. نوع المسكن',
            'q1_desc'         => 'ما هو نوع مسكنك؟',
            'tipus_rural'     => 'ريفي',
            'tipus_petit'     => 'غير ريفي حتى 100 م²',
            'tipus_gran'      => 'غير ريفي أكثر من 100 م²',
            'tipus_aillat'    => 'غير ريفي منفرد معزول',
            'q2_title'        => '2. استخدام حاوية النفايات العامة (سنوياً)',
            'q2_desc'         => 'كم مرة تضع النفايات غير القابلة لإعادة التدوير في الحاوية الرمادية؟',
            'no_participo'    => 'لا أشارك',
            'poc'             => 'نادراً',
            'de_tant_en_tant' => 'أحياناً',
            'sovint'          => 'كثيراً',
            'molt_sovint'     => 'كثيراً جداً',
            'continuament'    => 'باستمرار',
            'vegades'         => 'مرة',
            'q3_title'        => '3. استخدام الحفاضات أو الحيوانات الأليفة',
            'q3_desc'         => 'هل أعلنت أن منزلك يستخدم الحفاضات أو يمتلك حيوانات أليفة؟',
            'no'              => 'لا',
            'si'              => 'نعم',
            'q4_title'        => '4. استخدام حاوية المواد العضوية (سنوياً)',
            'q4_desc'         => 'كم مرة تضع بقايا الطعام والمواد العضوية في الحاوية البنية؟',
            'molt_sovint_org' => 'كثيراً جداً أو سماد',
            'q5_title'        => '5. الزيارات إلى مركز إعادة التدوير (سنوياً)',
            'q5_desc'         => 'كم مرة حملت النفايات إلى مركز إعادة التدوير الثابت؟',
            'cap'             => 'لا شيء أو أقل من 4',
            'baixa'           => 'بين 4 و 7',
            'mitja'           => 'بين 8 و 11',
            'alta'            => '12 أو أكثر',
            'taxa_2026'       => 'الرسوم المقدرة لعام 2026 *',
            'nota_2026'       => '* الحساب مبني على نطاق الخصومات المحدد لمركز إعادة التدوير.',
            'taxa_2027'       => 'الرسوم المقدرة لعام 2027',
            'part_fixa'       => 'الجزء الثابت (الأساسي)',
            'part_var_resta'  => 'الجزء المتغير للنفايات العامة',
            'bonif_org'       => 'خصم المواد العضوية',
            'ord_title'       => 'تعرف على الأنظمة الضريبية في ليس فرانكيسيس',
            'ord_sub'         => 'في الرابط التالي يمكنك الاطلاع على الأنظمة الضريبية 12: رسوم خدمة إدارة النفايات البلدية 2027.',
            'ord_btn'         => 'عرض الأنظمة الضريبية',
            'js_tram2_bolquers'   => '(الشريحة 2 للحفاضات/الحيوانات)',
            'js_quota_baixa'      => '(حصة ≤2 مرة)',
            'js_tram'             => 'الشريحة',
            'js_sense_bonif'      => 'بدون خصم',
            'js_compostatge'      => 'سماد أو الحد الأقصى',
            'js_deixalleria'      => 'مركز إعادة التدوير',
            'js_note_bolquers'    => 'تطبيق تعرفة خاصة للنفايات العامة (الشريحة 2) بسبب الحفاضات أو الحيوانات الأليفة.',
            'js_note_resta_baixa' => 'عدد قليل جداً من فتحات النفايات العامة: تطبيق حصة خاصة.',
            'js_note_org_baixa'   => 'عدد قليل جداً من فتحات العضوية: تطبيق حصة خاصة (رسوم إضافية).',
        ],
    ];
    return $strings[ $lang ] ?? $strings['ca'];
}

function calc_taxa_render( $lang ) {
    wp_enqueue_script(
        'calc-taxa',
        get_stylesheet_directory_uri() . '/js/calc.js',
        array(),
        filemtime( get_stylesheet_directory() . '/js/calc.js' ),
        true
    );

    $s   = calc_taxa_strings( $lang );
    $dir = ( $lang === 'ar' ) ? ' dir="rtl"' : '';

    wp_localize_script( 'calc-taxa', 'calcI18n', [
        'tram2_bolquers'   => $s['js_tram2_bolquers'],
        'quota_baixa'      => $s['js_quota_baixa'],
        'tram'             => $s['js_tram'],
        'sense_bonif'      => $s['js_sense_bonif'],
        'compostatge'      => $s['js_compostatge'],
        'deixalleria'      => $s['js_deixalleria'],
        'note_bolquers'    => $s['js_note_bolquers'],
        'note_resta_baixa' => $s['js_note_resta_baixa'],
        'note_org_baixa'   => $s['js_note_org_baixa'],
    ]);

    ob_start();
    ?>

    <!-- Mòdul 2: Explicació -->
    <section class="calc-info"<?php echo $dir; ?>>
        <div class="calc-destacat">
            <p><?php echo esc_html( $s['intro'] ); ?></p>
            <ul>
                <li><?php echo $s['comp_fixa']; ?></li>
                <li><?php echo $s['comp_var']; ?></li>
            </ul>
            <p><?php echo $s['intro2']; ?></p>
        </div>
    </section>

    <!-- Mòdul 3: Calculadora -->
    <div class="calc"<?php echo $dir; ?>>

        <div class="calc-question">
            <div class="section-title"><?php echo esc_html( $s['q1_title'] ); ?></div>
            <p class="question-desc"><?php echo esc_html( $s['q1_desc'] ); ?></p>
            <div class="pill-group" id="tipus">
                <button class="pill" data-val="rural"><?php echo esc_html( $s['tipus_rural'] ); ?></button>
                <button class="pill" data-val="no_rural_petit"><?php echo esc_html( $s['tipus_petit'] ); ?></button>
                <button class="pill" data-val="no_rural_gran"><?php echo esc_html( $s['tipus_gran'] ); ?></button>
                <button class="pill" data-val="no_rural_aillat"><?php echo esc_html( $s['tipus_aillat'] ); ?></button>
            </div>
        </div>

        <hr>

        <div class="calc-question">
            <div class="section-title"><?php echo esc_html( $s['q2_title'] ); ?></div>
            <p class="question-desc"><?php echo esc_html( $s['q2_desc'] ); ?></p>
            <div class="pill-group" id="resta">
                <button class="pill" data-val="gairebe_mai"><?php echo esc_html( $s['no_participo'] ); ?></button>
                <button class="pill" data-val="poc"><?php echo esc_html( $s['poc'] ); ?><span class="pill-sub">3–26 <?php echo esc_html( $s['vegades'] ); ?></span></button>
                <button class="pill" data-val="de_tant_en_tant"><?php echo esc_html( $s['de_tant_en_tant'] ); ?><span class="pill-sub">27–52 <?php echo esc_html( $s['vegades'] ); ?></span></button>
                <button class="pill" data-val="sovint"><?php echo esc_html( $s['sovint'] ); ?><span class="pill-sub">53–78 <?php echo esc_html( $s['vegades'] ); ?></span></button>
                <button class="pill" data-val="molt_sovint"><?php echo esc_html( $s['molt_sovint'] ); ?><span class="pill-sub">79–104 <?php echo esc_html( $s['vegades'] ); ?></span></button>
                <button class="pill" data-val="continuament"><?php echo esc_html( $s['continuament'] ); ?><span class="pill-sub">+104 <?php echo esc_html( $s['vegades'] ); ?></span></button>
            </div>
        </div>

        <hr>

        <div class="calc-question">
            <div class="section-title"><?php echo esc_html( $s['q3_title'] ); ?></div>
            <p class="question-desc"><?php echo esc_html( $s['q3_desc'] ); ?></p>
            <div class="pill-group" id="bolquers-animals">
                <button class="pill" data-val="no"><?php echo esc_html( $s['no'] ); ?></button>
                <button class="pill" data-val="si"><?php echo esc_html( $s['si'] ); ?></button>
            </div>
        </div>

        <hr>

        <div class="calc-question">
            <div class="section-title"><?php echo esc_html( $s['q4_title'] ); ?></div>
            <p class="question-desc"><?php echo esc_html( $s['q4_desc'] ); ?></p>
            <div class="pill-group" id="organica">
                <button class="pill" data-val="gairebe_mai"><?php echo esc_html( $s['no_participo'] ); ?></button>
                <button class="pill" data-val="poc"><?php echo esc_html( $s['poc'] ); ?><span class="pill-sub">3–26 <?php echo esc_html( $s['vegades'] ); ?></span></button>
                <button class="pill" data-val="de_tant_en_tant"><?php echo esc_html( $s['de_tant_en_tant'] ); ?><span class="pill-sub">27–52 <?php echo esc_html( $s['vegades'] ); ?></span></button>
                <button class="pill" data-val="sovint"><?php echo esc_html( $s['sovint'] ); ?><span class="pill-sub">53–78 <?php echo esc_html( $s['vegades'] ); ?></span></button>
                <button class="pill" data-val="molt_sovint"><?php echo esc_html( $s['molt_sovint_org'] ); ?><span class="pill-sub">+78 <?php echo esc_html( $s['vegades'] ); ?></span></button>
            </div>
        </div>

        <hr>

        <div class="calc-question">
            <div class="section-title"><?php echo esc_html( $s['q5_title'] ); ?></div>
            <p class="question-desc"><?php echo esc_html( $s['q5_desc'] ); ?></p>
            <div class="pill-group" id="deixalleria">
                <button class="pill" data-val="cap"><?php echo esc_html( $s['cap'] ); ?></button>
                <button class="pill" data-val="baixa"><?php echo esc_html( $s['baixa'] ); ?></button>
                <button class="pill" data-val="mitja"><?php echo esc_html( $s['mitja'] ); ?></button>
                <button class="pill" data-val="alta"><?php echo esc_html( $s['alta'] ); ?></button>
            </div>
        </div>

        <div class="result-2026">
            <div class="result-row result-secondary">
                <span class="label"><?php echo esc_html( $s['taxa_2026'] ); ?></span>
                <span id="r-total-2026">—</span>
            </div>
            <div class="note"><?php echo esc_html( $s['nota_2026'] ); ?></div>
        </div>
        <div class="result-card">
            <div class="result-row result-main">
                <span class="label"><?php echo esc_html( $s['taxa_2027'] ); ?></span>
                <span id="r-total-2027">—</span>
            </div>
            <div class="result-breakdown">
                <div class="result-row"><span class="label"><?php echo esc_html( $s['part_fixa'] ); ?></span><span id="r-fixa">—</span></div>
                <div class="result-row"><span class="label"><?php echo esc_html( $s['part_var_resta'] ); ?></span><span id="r-resta">—</span></div>
                <div class="result-row discount"><span class="label"><?php echo esc_html( $s['bonif_org'] ); ?></span><span id="r-org">—</span></div>
            </div>
            <div class="note" id="r-note"></div>
        </div>

    </div>

    <!-- Mòdul 4: Ordenança fiscal -->
    <section class="calc-ordenanca"<?php echo $dir; ?>>
        <h2 class="calc-ordenanca__title"><?php echo esc_html( $s['ord_title'] ); ?></h2>
        <p class="calc-ordenanca__subtitle"><?php echo esc_html( $s['ord_sub'] ); ?></p>
        <a class="main-btns body-btns"
           href="https://seu-e.cat/documents/2663179/20828850/OF+12.+Taxa+prestaci%C3%B3+servei+de+gesti%C3%B3+de+residus+municipals+2027.pdf/fe7a0ba4-aa2e-4341-a7c6-47686d9ab7e8"
           target="_blank"
           rel="noopener noreferrer">
            <?php echo esc_html( $s['ord_btn'] ); ?>
        </a>
    </section>

    <?php
    return ob_get_clean();
}

add_shortcode( 'calc_taxa',    function () { return calc_taxa_render( 'ca' ); } );
add_shortcode( 'calc_taxa_es', function () { return calc_taxa_render( 'es' ); } );
add_shortcode( 'calc_taxa_fr', function () { return calc_taxa_render( 'fr' ); } );
add_shortcode( 'calc_taxa_ar', function () { return calc_taxa_render( 'ar' ); } );
