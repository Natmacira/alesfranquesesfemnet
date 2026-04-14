(function () {

    // ── 2027 DATA ──────────────────────────────────────────────────────────────

    const FIXA_2027 = {
        rural:           91.98,
        no_rural_petit:  211.77,
        no_rural_gran:   248.53,
        no_rural_aillat: 313.35
    };

    const RESTA_2027 = {
        rural:           [null, 15.00, 19.50, 27.00, 34.50, 45.00],
        no_rural_petit:  [null, 30.00, 39.00, 54.00, 69.00, 90.00],
        no_rural_gran:   [null, 30.00, 39.00, 54.00, 69.00, 90.00],
        no_rural_aillat: [null, 30.00, 39.00, 54.00, 69.00, 90.00],
    };

    const ORG_2027 = {
        rural:           [null, 0.00,  -5.00, -12.50, -15.00],
        no_rural_petit:  [null, 0.00, -10.00, -25.00, -30.00],
        no_rural_gran:   [null, 0.00, -10.00, -25.00, -30.00],
        no_rural_aillat: [null, 0.00, -10.00, -25.00, -30.00],
    };

    const QUOTA_RESTA_BAIXA = { rural: 45.00, no_rural_petit: 90.00, no_rural_gran: 90.00, no_rural_aillat: 90.00 };
    const QUOTA_ORG_BAIXA   = { rural:  7.50, no_rural_petit: 15.00, no_rural_gran: 15.00, no_rural_aillat: 15.00 };

    // Mapa de selecció pill → tram numèric
    const RESTA_TRAM = {
        gairebe_mai:    null, // quota especial
        poc:            1,
        de_tant_en_tant: 2,
        sovint:         3,
        molt_sovint:    4,
        continuament:   5,
    };

    const ORG_TRAM = {
        gairebe_mai:    null, // quota especial
        poc:            1,
        de_tant_en_tant: 2,
        sovint:         3,
        molt_sovint:    4,  // inclou compostatge
    };

    // ── 2026 DATA ──────────────────────────────────────────────────────────────
    // Escombraries + Selectiva (Epígrafs 1, 2, 3 de l'Ordenança Fiscal 2026)

    const TAXA_2026 = {
        //                        base    ≥8 visites (−10%)  ≥12 visites (−20%)
        rural:           { base: 99.11,  t1:  89.20, t2:  79.26 },
        no_rural_petit:  { base: 228.18, t1: 205.36, t2: 182.54 },
        no_rural_gran:   { base: 267.79, t1: 241.03, t2: 214.24 },
        no_rural_aillat: { base: 337.64, t1: 303.86, t2: 270.10 },
    };

    // ── HELPERS ────────────────────────────────────────────────────────────────

    function getActive(groupId) {
        return document.querySelector('#' + groupId + ' .pill.active')?.dataset.val;
    }

    function fmt(n) {
        const abs = Math.abs(n).toFixed(2).replace('.', ',');
        return (n < 0 ? '−' : '') + abs + ' €';
    }

    function resetResult() {
        document.getElementById('r-fixa').textContent       = '—';
        document.getElementById('r-resta').textContent      = '—';
        document.getElementById('r-org').textContent        = '—';
        document.getElementById('r-total-2027').textContent = '—';
        document.getElementById('r-total-2026').textContent = '—';
        document.getElementById('r-note').innerHTML         = '';
    }

    // ── CALC ───────────────────────────────────────────────────────────────────

    function calc() {
        const tipus       = getActive('tipus');
        const restaKey    = getActive('resta');
        const bolquersVal = getActive('bolquers-animals');
        const orgKey      = getActive('organica');
        const deixalleria = getActive('deixalleria');

        // Si falta alguna resposta, no calcular
        if (!tipus || !restaKey || !bolquersVal || !orgKey || !deixalleria) {
            resetResult();
            return;
        }

        const bolquers = bolquersVal === 'si';

        const notes = [];

        // ── RESTA 2027
        let restaVal, restaLabel;
        if (bolquers) {
            restaVal   = RESTA_2027[tipus][2];
            restaLabel = fmt(restaVal) + ' (tram 2 per bolquers/animals)';
            notes.push('Tarifa especial RESTA (tram 2) aplicada per bolquers o animals de companyia.');
        } else if (restaKey === 'gairebe_mai') {
            restaVal   = QUOTA_RESTA_BAIXA[tipus];
            restaLabel = fmt(restaVal) + ' (quota ≤2 obertures)';
            notes.push('Molt poques obertures al RESTA: s\'aplica quota especial.');
        } else {
            const tram = RESTA_TRAM[restaKey];
            restaVal   = RESTA_2027[tipus][tram];
            restaLabel = fmt(restaVal) + ' (tram ' + tram + ')';
        }

        // ── ORGÀNICA 2027
        let orgVal, orgLabel;
        if (orgKey === 'gairebe_mai') {
            orgVal   = QUOTA_ORG_BAIXA[tipus];
            orgLabel = '+' + fmt(orgVal) + ' (quota ≤2 obertures)';
            notes.push('Molt poques obertures a l\'ORGÀNICA: s\'aplica quota especial (càrrec).');
        } else {
            const tram = ORG_TRAM[orgKey];
            orgVal     = ORG_2027[tipus][tram];
            orgLabel   = tram === 1
                ? '0,00 € (tram 1, sense bonificació)'
                : fmt(orgVal) + ' (tram ' + tram + ')';
            if (orgKey === 'molt_sovint') {
                orgLabel += ' (compostatge o ús màxim)';
            }
        }

        // ── DEIXALLERIA 2027 (bonificació sobre la part fixa)
        const fixaBase2027 = FIXA_2027[tipus];
        let fixaDescompte = 0;
        if (deixalleria === 'alta')  fixaDescompte = 20;
        else if (deixalleria === 'mitja') fixaDescompte = 10;
        else if (deixalleria === 'baixa') fixaDescompte = 5;

        const fixa2027  = fixaBase2027 * (1 - fixaDescompte / 100);
        const total2027 = fixa2027 + restaVal + orgVal;

        // ── 2026
        const taxa26 = TAXA_2026[tipus];
        let total2026;
        if (deixalleria === 'alta') {
            total2026 = taxa26.t2;
        } else if (deixalleria === 'mitja') {
            total2026 = taxa26.t1;
        } else {
            total2026 = taxa26.base;
        }

        // ── DOM
        let fixaHtml = fmt(fixa2027);
        if (fixaDescompte > 0) {
            fixaHtml = '<s>' + fmt(fixaBase2027) + '</s> ' + fmt(fixa2027) + ' <span class="fixa-desc">(−' + fixaDescompte + '% deixalleria)</span>';
        }
        document.getElementById('r-fixa').innerHTML = fixaHtml;
        document.getElementById('r-resta').textContent     = restaLabel;
        document.getElementById('r-org').textContent       = orgLabel;
        document.getElementById('r-total-2027').textContent = fmt(total2027);
        document.getElementById('r-total-2026').textContent = fmt(total2026);
        document.getElementById('r-note').innerHTML        = notes.map(n => '* ' + n).join('<br>');
    }

    // ── EVENT LISTENERS ────────────────────────────────────────────────────────

    function bindPillGroup(groupId) {
        document.querySelectorAll('#' + groupId + ' .pill').forEach(p => {
            p.addEventListener('click', () => {
                document.querySelectorAll('#' + groupId + ' .pill').forEach(x => x.classList.remove('active'));
                p.classList.add('active');
                calc();
            });
        });
    }

    function init() {
        ['tipus', 'resta', 'bolquers-animals', 'organica', 'deixalleria'].forEach(bindPillGroup);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
