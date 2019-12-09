/*jslint browser:true */
/*global google, $, mapas, config, i18n */

/**
 * Modulo de configuracion de especie
 */
config.especie = {
    URL_ESPECIE: '<?php echo path("especie"); ?>',
    
    TAGLIST_DEFAULT: {
        tags: [],
        width: 'resolve',
        dropdownCssClass: 'hidden',
        separator: ',',
        tokenSeparators: [','],
        selectOnBlur: true
    },
    
    TROCAR_DEFAULTS: {
        on: 'always',
        off: 'blur',
        selectOnEdit: false,
        endOnEnter: true
    },
    
    URL_BASE_ESPECIE: '<?php echo path("especie"); ?>',
    URL_BASE_GALERIA: '<?php echo path("get-especie-imaxes"); ?>',
    URL_ESPECIE_TIPO: '<?php echo path("check-especie-tipo"); ?>'
};