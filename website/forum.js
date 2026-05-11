// forum.js

caricaDomande();

function caricaDomande() {
    fetch("api/domande.php")
        .then(function(risposta) { return risposta.json(); })
        .then(function(elenco) {
            var contenitore = document.getElementById("lista-domande");
            contenitore.innerHTML = "";

            if (elenco.length == 0) {
                contenitore.innerHTML = "<p>Nessuna domanda ancora.</p>";
                return;
            }

            for (var i = 0; i < elenco.length; i++) {
                var domanda = elenco[i];
                contenitore.innerHTML +=
                    "<div class='box'>" +
                        "<b>" + domanda.titolo + "</b><br>" +
                        domanda.testo + "<br><br>" +
                        "<i>Scritto da: " + domanda.nomeutente + " il " + domanda.data + "</i>" +
                        "&nbsp;&nbsp;" +
                        "<a href='risposta.html?id=" + domanda.id_interazione + "' target='_blank'>" +
                            "<button>Rispondi</button>" +
                        "</a>" +
                    "</div>";
            }
        })
        .catch(function() {
            document.getElementById("lista-domande").innerHTML = "<span class='errore'>Errore nel caricamento delle domande.</span>";
        });
}

function aggiungiDomanda() {
    var titolo = document.getElementById("titolo-domanda").value;
    var testo = document.getElementById("testo-domanda").value;

    if (titolo == "" || testo == "") {
        document.getElementById("messaggio-domanda").innerHTML = "<span class='errore'>Compila tutti i campi</span>";
        return;
    }

    var dati = new FormData();
    dati.append("titolo", titolo);
    dati.append("testo", testo);

    fetch("api/nuova_domanda.php", { method: "POST", body: dati })
        .then(function(risposta) { return risposta.json(); })
        .then(function(datiRisposta) {
            if (datiRisposta.successo) {
                document.getElementById("titolo-domanda").value = "";
                document.getElementById("testo-domanda").value = "";
                document.getElementById("messaggio-domanda").innerHTML = "<span class='ok'>Domanda inviata!</span>";
                caricaDomande();
            } else {
                document.getElementById("messaggio-domanda").innerHTML = "<span class='errore'>" + datiRisposta.errore + "</span>";
            }
        });
}