/*
 Please consider that the JS part isn't production ready at all, I just code it to show the concept of merging filters and titles together !
 */
$(document).ready(function () {

    $.validator.setDefaults({

        highlight: function (element) {
            $(element)
                    .closest('.form-control')
                    .addClass('error')
        },
        unhighlight: function (element) {
            $(element)
                    .closest('.form-control')
                    .removeClass('error')
        },
        debug: false,

    });
    $("#eventForm").validate({
                errorElement: 'span',
        errorClass: 'error',
        rules: {
            category: "required",
            titre: "required",
            dateDebut: "required",
            dateFin: "required",
            prix: "required",
            nbrMax: "required",
            adresse: "required",
            codeP: "required",
            ville: "required",
            region: "required",
            departement: "required",
            image: "required",

        },
        messages: {
            category: "Veuillez choisir une catégorie",
            titre: "Veuillez saisir un titre",
            dateDebut: "Veuillez choisir la date de début",
            dateFin: "Veuillez choisir la date de fin",
            prix: "Veuillez tapper le prix",
            nbrMax: "Veuillez saisir le nombre max des participants",
            adresse: "Veuillez saisir l'adresse",
            codeP: "Veuillez saisir le code postale",
            ville: "Veuillez saisir une ville",
            region: "Veuillez saisir une région",
            departement: "Veuillez saisir un département",
            image: "Veuillez insérer une image",
        }
    });
});