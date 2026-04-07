<?php
/**
 * Shortcode: Calculadora Taxa Residus 2027
 * Ús: [calc_taxa]
 */

add_shortcode('calc_taxa', function () {
    wp_enqueue_script(
        'calc-taxa',
        get_stylesheet_directory_uri() . '/js/calc.js',
        array(),
        filemtime( get_stylesheet_directory() . '/js/calc.js' ),
        true
    );

    ob_start();
    ?>
    <div class="calc">

        <div class="calc-question">
            <div class="section-title">1. Tipus d'habitatge</div>
            <p class="question-desc">Aquesta informació defineix la part bàsica de la quota.</p>
            <div class="pill-group" id="tipus">
                <button class="pill active" data-val="rural">Habitatge rural</button>
                <button class="pill" data-val="no_rural_petit">No rural fins 100 m²</button>
                <button class="pill" data-val="no_rural_gran">No rural més de 100 m²</button>
                <button class="pill" data-val="no_rural_aillat">No rural unifamiliar aïllat</button>
            </div>
        </div>

        <hr>

        <div class="calc-question">
            <div class="section-title">2. Ús del contenidor de resta (anual)</div>
            <p class="question-desc">Quantes vegades diposites residus que no es poden reciclar al contenidor gris?</p>
            <div class="pill-group" id="resta">
                <button class="pill active" data-val="gairebe_mai">Gairebé mai<span class="pill-sub">0–2 vegades</span></button>
                <button class="pill" data-val="poc">Poc<span class="pill-sub">3–26 vegades</span></button>
                <button class="pill" data-val="de_tant_en_tant">De tant en tant<span class="pill-sub">27–52 vegades</span></button>
                <button class="pill" data-val="sovint">Sovint<span class="pill-sub">53–78 vegades</span></button>
                <button class="pill" data-val="molt_sovint">Molt sovint<span class="pill-sub">79–104 vegades</span></button>
                <button class="pill" data-val="continuament">Contínuament<span class="pill-sub">+104 vegades</span></button>
            </div>
        </div>

        <hr>

        <div class="calc-question">
            <div class="section-title">3. Ús de bolquers o animals de companyia</div>
            <p class="question-desc">Has declarat que a la teva llar s'utilitzen bolquers o hi ha animals de companyia?</p>
            <div class="pill-group" id="bolquers-animals">
                <button class="pill active" data-val="no">No</button>
                <button class="pill" data-val="si">Sí</button>
            </div>
        </div>

        <hr>

        <div class="calc-question">
            <div class="section-title">4. Ús del contenidor d'orgànica (anual)</div>
            <p class="question-desc">Quantes vegades diposites restes de menjar i orgàniques al contenidor marró?</p>
            <div class="pill-group" id="organica">
                <button class="pill active" data-val="gairebe_mai">Gairebé mai<span class="pill-sub">0–2 vegades</span></button>
                <button class="pill" data-val="poc">Poc<span class="pill-sub">3–26 vegades</span></button>
                <button class="pill" data-val="de_tant_en_tant">De tant en tant<span class="pill-sub">27–52 vegades</span></button>
                <button class="pill" data-val="sovint">Sovint<span class="pill-sub">53–78 vegades</span></button>
                <button class="pill" data-val="molt_sovint">Molt sovint o compostatge<span class="pill-sub">+78 vegades</span></button>
            </div>
        </div>

        <hr>

        <div class="calc-question">
            <div class="section-title">5. Aportacions a la deixalleria (anual)</div>
            <p class="question-desc">Quantes vegades has portat residus a la deixalleria fixa?</p>
            <div class="pill-group" id="deixalleria">
                <button class="pill active" data-val="cap">Cap o menys de 4</button>
                <button class="pill" data-val="baixa">Entre 4 i 7</button>
                <button class="pill" data-val="mitja">Entre 8 i 11</button>
                <button class="pill" data-val="alta">12 o més</button>
            </div>
        </div>

        <div class="result-card">
            <div class="result-row result-main">
                <span class="label">Taxa estimada 2027</span>
                <span id="r-total-2027">—</span>
            </div>
            <div class="result-breakdown">
                <div class="result-row"><span class="label">Part fixa</span><span id="r-fixa">—</span></div>
                <div class="result-row"><span class="label">Part variable RESTA</span><span id="r-resta">—</span></div>
                <div class="result-row discount"><span class="label">Bonificació ORGÀNICA</span><span id="r-org">—</span></div>
            </div>
            <hr>
            <div class="result-row result-secondary">
                <span class="label">Taxa estimada 2026 *</span>
                <span id="r-total-2026">—</span>
            </div>
            <div class="note">* Càlcul realitzat tenint en compte la franja de bonificacions de la deixalleria que s'ha especificat.</div>
            <div class="note" id="r-note"></div>
        </div>

    </div>
    <?php
    return ob_get_clean();
});
