var usernameRegistrato = "";

var emailRegistrata = "";

var passwordRegistrata = "";

var utenteLoggato = "";

var numeroDomande = 0;

function mostraPagina(id){

    var pagine = document.getElementsByClassName("pagina");

    for(var i = 0; i < pagine.length; i++){

        pagine[i].classList.remove("attiva");

    }

    document.getElementById(id).classList.add("attiva");

}

function registrati(){

    var username = document.getElementById("reg-username").value;

    var email = document.getElementById("reg-email").value;

    var password = document.getElementById("reg-password").value;

    if(username == "" || email == "" || password == ""){

        document.getElementById("messaggio-registrazione").innerHTML = "Compila tutti i campi";

    }
    else{

        usernameRegistrato = username;

        emailRegistrata = email;

        passwordRegistrata = password;

        document.getElementById("messaggio-registrazione").innerHTML = "Registrazione completata";

    }

}

function login(){

    var email = document.getElementById("email").value;

    var password = document.getElementById("password").value;

    if(email == emailRegistrata && password == passwordRegistrata){

        utenteLoggato = usernameRegistrato;

        document.getElementById("messaggio-login").innerHTML = "Login effettuato";

        document.getElementById("nome-profilo").innerHTML = "Benvenuto " + utenteLoggato;

    }
    else{

        document.getElementById("messaggio-login").innerHTML = "Dati sbagliati";

    }

}

function aggiungiRecensione(){

    var prodotto = document.getElementById("prodotto").value;

    var testo = document.getElementById("testo").value;

    if(utenteLoggato == ""){

        document.getElementById("messaggio-recensione").innerHTML = "Devi fare il login";

    }
    else if(testo == ""){

        document.getElementById("messaggio-recensione").innerHTML = "Scrivi una recensione";

    }
    else{

        var recensione = "<div class='box'><b>" + prodotto + "</b><br>" + testo + "<br><br>Scritto da: " + utenteLoggato + "</div>";

        document.getElementById("lista-recensioni").innerHTML += recensione;

        document.getElementById("messaggio-recensione").innerHTML = "Recensione pubblicata";

        document.getElementById("testo").value = "";

    }

}

function aggiungiDomanda(){

    var domanda = document.getElementById("domanda").value;

    if(utenteLoggato == ""){

        document.getElementById("messaggio-domanda").innerHTML = "Devi fare il login";

    }
    else if(domanda == ""){

        document.getElementById("messaggio-domanda").innerHTML = "Scrivi una domanda";

    }
    else{

        numeroDomande++;

        var nuovaDomanda = "<div class='box' id='domanda" + numeroDomande + "'><b>Domanda " + numeroDomande + "</b><br>" + domanda + "<br><br>Scritto da: " + utenteLoggato + "</div>";

        document.getElementById("lista-domande").innerHTML += nuovaDomanda;

        document.getElementById("messaggio-domanda").innerHTML = "Domanda inviata";

        document.getElementById("domanda").value = "";

    }

}

function aggiungiRisposta(){

    var numero = document.getElementById("numero-domanda").value;

    var risposta = document.getElementById("risposta").value;

    if(utenteLoggato == ""){

        document.getElementById("messaggio-risposta").innerHTML = "Devi fare il login";

    }
    else if(numero == "" || risposta == ""){

        document.getElementById("messaggio-risposta").innerHTML = "Compila tutti i campi";

    }
    else{

        var testoRisposta = "<br><br><b>Risposta di " + utenteLoggato + ":</b><br>" + risposta;

        document.getElementById("domanda" + numero).innerHTML += testoRisposta;

        document.getElementById("messaggio-risposta").innerHTML = "Risposta inviata";

        document.getElementById("risposta").value = "";

    }

}