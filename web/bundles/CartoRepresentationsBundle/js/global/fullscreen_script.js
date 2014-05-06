function fullscreenClick () {
    var element = document.getElementById("LaTableComplete");
    var contentCen = document.getElementById("contentCenter");
    var  monSVG = contentCen.getElementsByTagName("svg");
    if (monSVG.item() != null) {
        if (document.webkitIsFullScreen || document.mozFullscreen){
            if(document.cancelFullScreen) {
                    //fonction officielle du w3c
                    document.cancelFullScreen();
            } else if(document.webkitCancelFullScreen) {
                    //fonction pour Google Chrome
                    document.webkitCancelFullScreen();
            } else if(document.mozCancelFullScreen){
                    //fonction pour Firefox
                    document.mozCancelFullScreen();
            }
        }
        else{
            
            if(element.requestFullScreen) {
                    //fonction officielle du w3c
                    element.requestFullScreen();
            } else if(element.webkitRequestFullScreen) {
                    //fonction pour Google Chrome (on lui passe un argument pour autoriser le plein écran lors d'une pression sur le clavier)
                    element.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if(element.mozRequestFullScreen){
                    //fonction pour Firefox
                    element.mozRequestFullScreen();
            } else {
                    alert('Votre navigateur ne supporte pas le mode plein écran, il est temps de passer à un plus récent ;)');
            }
            
        }
    $("#form_recherche").submit();
    }
}