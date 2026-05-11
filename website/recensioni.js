// recensioni.js

// carico le recensioni dal backend appena la pagina è pronta
caricaRecensioni();

function caricaRecensioni() {
    fetch("api/recensioni.php")
        .then(function(risposta) { return risposta.json(); })
        .then(function(elenco) {
            var contenitore = document.getElementById("lista-recensioni");
            contenitore.innerHTML = "";

            if (elenco.length == 0) {
                contenitore.innerHTML = "<p>Nessuna recensione ancora.</p>";
                return;
            }

            for (var i = 0; i < elenco.length; i++) {
                var recensione = elenco[i];
                var collegamento = recensione.link_prodotto ? "<br><a href='" + recensione.link_prodotto + "' target='_blank'>Link prodotto</a>" : "";
                contenitore.innerHTML +=
                    "<div class='box'>" +
                        "<b>" + recensione.nome_prodotto + "</b><br>" +
                        recensione.testo + "<br><br>" +
                        "<i>Scritto da: " + recensione.nomeutente + " il " + recensione.data + "</i>" +
                        collegamento +
                    "</div>";
            }
        })
        .catch(function() {
            document.getElementById("lista-recensioni").innerHTML = "<span class='errore'>Errore nel caricamento delle recensioni.</span>";
        });
}

function aggiungiRecensione() {
    var idProdotto = document.getElementById("prodotto").value;
    var testo = document.getElementById("testo-recensione").value;
    var collegamento = document.getElementById("collegamento-prodotto").value;

    if (testo == "") {
        document.getElementById("messaggio-recensione").innerHTML = "<span class='errore'>Scrivi una recensione</span>";
        return;
    }

    var dati = new FormData();
    dati.append("id_prodotto", idProdotto);
    dati.append("testo", testo);
    dati.append("link_prodotto", collegamento);

    fetch("api/nuova_recensione.php", { method: "POST", body: dati })
        .then(function(risposta) { return risposta.json(); })
        .then(function(datiRisposta) {
            if (datiRisposta.successo) {
                document.getElementById("messaggio-recensione").innerHTML = "<span class='ok'>Recensione pubblicata!</span>";
                document.getElementById("testo-recensione").value = "";
                document.getElementById("collegamento-prodotto").value = "";
                caricaRecensioni();
            } else {
                document.getElementById("messaggio-recensione").innerHTML = "<span class='errore'>" + datiRisposta.errore + "</span>";
            }
        });
}