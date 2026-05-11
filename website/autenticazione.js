// autenticazione.js

function faiRegistrazione() {
    var nomeutente = document.getElementById("reg-nomeutente").value;
    var email = document.getElementById("reg-email").value;
    var parolaChiave = document.getElementById("reg-parola-chiave").value;

    if (nomeutente == "" || email == "" || parolaChiave == "") {
        document.getElementById("messaggio-registrazione").innerHTML = "<span class='errore'>Compila tutti i campi</span>";
        return;
    }

    var dati = new FormData();
    dati.append("nomeutente", nomeutente);
    dati.append("email", email);
    dati.append("parola_chiave", parolaChiave);

    fetch("api/registrazione.php", { method: "POST", body: dati })
        .then(function(risposta) { return risposta.json(); })
        .then(function(datiRisposta) {
            if (datiRisposta.successo) {
                document.getElementById("messaggio-registrazione").innerHTML = "<span class='ok'>Registrazione completata! Ora puoi fare il login.</span>";
            } else {
                document.getElementById("messaggio-registrazione").innerHTML = "<span class='errore'>" + datiRisposta.errore + "</span>";
            }
        });
}

function faiLogin() {
    var email = document.getElementById("email").value;
    var parolaChiave = document.getElementById("parola-chiave").value;

    var dati = new FormData();
    dati.append("email", email);
    dati.append("parola_chiave", parolaChiave);

    fetch("api/login.php", { method: "POST", body: dati })
        .then(function(risposta) { return risposta.json(); })
        .then(function(datiRisposta) {
            if (datiRisposta.successo) {
                document.getElementById("messaggio-login").innerHTML = "<span class='ok'>Benvenuto " + datiRisposta.nomeutente + "!</span>";
                // piccolo ritardo poi va al profilo
                setTimeout(function() { window.location.href = "profilo.html"; }, 1000);
            } else {
                document.getElementById("messaggio-login").innerHTML = "<span class='errore'>" + datiRisposta.errore + "</span>";
            }
        });
}

function faiLogout() {
    fetch("api/logout.php")
        .then(function() {
            window.location.href = "home.html";
        });
}