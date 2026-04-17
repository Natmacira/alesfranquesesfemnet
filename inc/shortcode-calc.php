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

    <!-- Mòdul 2: Explicació -->
    <section class="calc-info">
        <div class="calc-destacat">
            <p>La taxa de l'any 2027 té dos components:</p>
            <ul>
                <li><strong>Part fixa (bàsica):</strong> depèn del tipus d'habitatge</li>
                <li><strong>Part variable:</strong> depèn dels hàbits de separació de residus</li>
            </ul>
            <p>Si separes bé, la part variable pot arribar a ser de <strong>0 euros!</strong><br>
            A més, si utilitzes la deixalleria pots obtenir una bonificació sobre la part bàsica.</p>
        </div>
    </section>

    <!-- Mòdul 3: Calculadora -->
    <div class="calc">

        <div class="calc-question">
            <div class="section-title">1. Tipus d'habitatge</div>
            <p class="question-desc">Quina és la tipologia del teu habitatge?</p>
            <div class="pill-group" id="tipus">
                <button class="pill" data-val="rural">Rural</button>
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
                <button class="pill" data-val="gairebe_mai">No participo</button>
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
                <button class="pill" data-val="no">No</button>
                <button class="pill" data-val="si">Sí</button>
            </div>
        </div>

        <hr>

        <div class="calc-question">
            <div class="section-title">4. Ús del contenidor d'orgànica (anual)</div>
            <p class="question-desc">Quantes vegades diposites restes de menjar i orgàniques al contenidor marró?</p>
            <div class="pill-group" id="organica">
                <button class="pill" data-val="gairebe_mai">No participo</button>
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
                <button class="pill" data-val="cap">Cap o menys de 4</button>
                <button class="pill" data-val="baixa">Entre 4 i 7</button>
                <button class="pill" data-val="mitja">Entre 8 i 11</button>
                <button class="pill" data-val="alta">12 o més</button>
            </div>
        </div>

        <div class="result-2026">
            <div class="result-row result-secondary">
                <span class="label">Taxa estimada 2026 *</span>
                <span id="r-total-2026">—</span>
            </div>
            <div class="note">* Càlcul realitzat tenint en compte la franja de bonificacions de la deixalleria que s'ha especificat.</div>
        </div>
        <div class="result-card">
            <div class="result-row result-main">
                <span class="label">Taxa estimada 2027</span>
                <span id="r-total-2027">—</span>
            </div>
            <div class="result-breakdown">
                <div class="result-row"><span class="label">Part fixa (bàsica)</span><span id="r-fixa">—</span></div>
                <div class="result-row"><span class="label">Part variable RESTA</span><span id="r-resta">—</span></div>
                <div class="result-row discount"><span class="label">Bonificació ORGÀNICA</span><span id="r-org">—</span></div>
            </div>
            <div class="note" id="r-note"></div>
        </div>

    </div>

    <!-- Mòdul 4: Ordenança fiscal -->
    <section class="calc-ordenanca">
        <h2 class="calc-ordenanca__title">Coneix l'ordenança fiscal de les Franqueses</h2>
        <p class="calc-ordenanca__subtitle">En el següent enllaç podràs veure l'Ordenança Fiscal 12: Taxa prestació servei de gestió de residus municipals 2027.</p>
        <a class="main-btns body-btns"
           href="https://seu-e.cat/documents/2663179/20828850/OF+12.+Taxa+prestaci%C3%B3+servei+de+gesti%C3%B3+de+residus+municipals+2027.pdf/fe7a0ba4-aa2e-4341-a7c6-47686d9ab7e8"
           target="_blank"
           rel="noopener noreferrer">
            Veure ordenança fiscal
        </a>
    </section>

    <?php
    return ob_get_clean();
});
