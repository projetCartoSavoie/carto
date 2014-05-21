function vieWClick() {
    var element = document.getElementById("LaTableComplete");
    var contentCen = document.getElementById("contentCenter");
    var  monSVG = contentCen.getElementsByTagName("svg");
    //recuperation des options et du mot à rechercher
    var valeurs = [];
    $('input:checked[name = options]').each(function() {
                valeurs.push($(this).val());
    });
    var profondeur = $("#quantite").val();
    var search = $('#search').val(); //Récupération du mot demandé
    var wordnet = $('#WN').attr('checked'); //Récupération de la source de données demandée
    
    if (monSVG.item() != null) {
        $(document).ready(function(){
            if (wordnet) {
                alert(search);
                $("#form_recherche").submit();
            }
            else
            {
                alert("OK DBPEDIA ! ");
            }
        });
    }
}