// risposta.js

var parametri = new URLSearchParams(window.location.search);
var idDomanda = parametri.get("id");

caricaThread();

function caricaThread() {
    fetch("api/thread.php?id=" + idDomanda)
        .then(function(risposta) { return risposta.json(); })
        .then(function(datiRisposta) {

            // mostro la domanda in cima
            var domanda = datiRisposta.domanda;
            document.getElementById("testo-domanda").innerHTML =
                "<b>" + domanda.titolo + "</b><br>" +
                domanda.testo + "<br><br>" +
                "<i>Scritto da: " + domanda.nomeutente + " il " + domanda.data + "</i>";

            // mostro le risposte
            var contenitore = document.getElementById("lista-risposte");
            contenitore.innerHTML = "";

            if (datiRisposta.risposte.length == 0) {
                contenitore.innerHTML = "<p>Nessuna risposta ancora.</p>";
            } else {
                for (var i = 0; i < datiRisposta.risposte.length; i++) {
                    var risposta = datiRisposta.risposte[i];
                    contenitore.innerHTML +=
                        "<div class='box'>" +
                            risposta.testo + "<br><br>" +
                            "<i>Scritto da: " + risposta.nomeutente + " il " + risposta.data + "</i>" +
                        "</div>";
                }
            }
        })
        .catch(function() {
            document.getElementById("testo-domanda").innerHTML = "<span class='errore'>Errore nel caricamento.</span>";
        });
}

function aggiungiRisposta() {
    var testo = document.getElementById("testo-risposta").value;

    if (testo == "") {
        document.getElementById("messaggio-risposta").innerHTML = "<span class='errore'>Scrivi una risposta</span>";
        return;
    }

    var dati = new FormData();
    dati.append("id_domanda", idDomanda);
    dati.append("testo", testo);

    fetch("api/nuova_risposta.php", { method: "POST", body: dati })
        .then(function(risposta) { return risposta.json(); })
        .then(function(datiRisposta) {
            if (datiRisposta.successo) {
                document.getElementById("testo-risposta").value = "";
                document.getElementById("messaggio-risposta").innerHTML = "<span class='ok'>Risposta inviata!</span>";
                caricaThread();
            } else {
                document.getElementById("messaggio-risposta").innerHTML = "<span class='errore'>" + datiRisposta.errore + "</span>";
            }
        });
}