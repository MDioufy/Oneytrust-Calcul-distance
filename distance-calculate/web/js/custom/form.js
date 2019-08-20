$(document).ready(function () {
    addRequiredInfo($('body'));
    disabledDoubleClickSubmit();
});

/** Ne pas déplier le contenu si on clique sur un champs de formulaire */
$(document).on('click', '[data-toggle="collapse"] input, [data-toggle="collapse"] select', function(e) {
    e.stopPropagation();
});

/* Ajouter l'info pour les champs requis  */
function addRequiredInfo(parentSelector)
{
    parentSelector.find('input, select, textarea').each(function() {
        let label = $('label[for="'+ $(this).attr('id') +'"].required');
        if (label.find('span.star').length === 0) {
            label.append('<span class="star text-om-danger font-weight-bold">&nbsp;*</span>');
        }
    });
}

/* Désactiver les doubles clic sur les boutons de soumission */
function disabledDoubleClickSubmit() {
    $('form:not(.noCheckDoubleClick)').submit(function () {
         $('[type=submit]').attr('disabled', 'disabled');
    });
}

               

