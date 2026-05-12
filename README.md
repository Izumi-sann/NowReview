# NowReview

## Descrizione del progetto

NowReview è un sito web dedicato alle recensioni di prodotti tecnologici e alla condivisione di opinioni tra utenti.  
Il progetto nasce con l’obiettivo di creare una piattaforma semplice e intuitiva dove gli utenti possano confrontarsi su dispositivi e accessori tech come tastiere, SSD, cuffie e altri prodotti informatici.

L’interfaccia è stata progettata per essere semplice, chiara e facile da utilizzare anche per utenti con poca esperienza.

Gli utenti possono:

- registrarsi
- effettuare il login
- pubblicare recensioni
- creare domande nel forum
- rispondere alle domande di altri utenti

Il progetto è stato realizzato come esercitazione scolastica utilizzando tecnologie web base.

---

# Componenti del gruppo

## Frontend
### IQBAL UMAR

Sviluppo di:

- struttura HTML e CSS delle pagine
- gestione interfaccia utente e navigazione del sito
- merge tra frontend e backend
- preparazione delle form per l’invio al backend
- realizzazione del logo del progetto

---

## Backend
### GAMBA ALESSANDRO

Sviluppo di:

- database MySQL
- gestione login e registrazione
- gestione recensioni
- gestione forum e risposte
- gestione backend PHP

---

# Tecnologie utilizzate

- HTML5
- CSS3
- PHP
- MySQL
- Apache
- XAMPP
- GitHub

---

# Funzionalità implementate

## Home page
Pagina iniziale con introduzione al progetto e logo del sito.

## Login e registrazione
Gli utenti possono registrarsi ed effettuare il login tramite form dedicate.

## Profilo utente
Visualizzazione dell’utente attualmente loggato.

## Sistema recensioni
Gli utenti possono:

- scegliere un prodotto
- scrivere una recensione
- visualizzare le recensioni pubblicate

## Forum
Gli utenti possono:

- creare domande
- visualizzare le domande pubblicate
- rispondere alle domande degli altri utenti

## Navigazione multipagina
Ogni sezione del sito possiede un file HTML separato:

- Home
- Login
- Profilo
- Recensioni
- Forum

---

# Installazione e avvio del progetto

## Requisiti

Prima di avviare il progetto è necessario avere installato:

- XAMPP
- un browser web
- il file ZIP del progetto

---

## Procedura di installazione

- Scaricare il file ZIP contenente il progetto NowReview.

- Estrarre il contenuto dello ZIP in una cartella del computer.

- Aprire la cartella di installazione di XAMPP ed entrare nella cartella:

```text
htdocs
```

- Copiare all’interno di `htdocs` la cartella del progetto chiamata:

```text
website
```

- Rinominare successivamente la cartella in:

```text
nowreview
```

- Aprire phpMyAdmin dal browser tramite XAMPP.

- Creare un nuovo database ed importare il file SQL presente nella cartella del progetto:

```text
nowreview.sql
```

- Aprire il pannello di controllo di XAMPP ed avviare:

  - Apache
  - MySQL

- Aprire il browser e digitare il seguente indirizzo:

```text
localhost/nowreview/frontend/home.html
```
Previo utilizzo del sito è necessario registrarsi per poi effettuare il login con le medesime credenziali

Il sito verrà aperto automaticamente nel browser.
---

# Problemi riscontrati

Durante lo sviluppo sono stati riscontrati alcuni problemi:

- gestione della comunicazione tra pagine HTML separate
- aggiornamento dinamico delle recensioni e delle domande
- organizzazione del codice PHP per il backend
- gestione del collegamento tra frontend e database
- quando si fa la modifica non viene aggiunto direttamente il prodotto nel menu a tendina
---

# Possibili sviluppi futuri

In futuro il progetto potrebbe essere migliorato con:

- sistema di votazione recensioni
- autenticazione più sicura
- immagini profilo per gli utenti
- sistema di ricerca avanzata
- miglioramento della grafica responsive
- utilizzo di Node.js per il backend
- il database dispone di un numero limitato di prodotti: in futuro verrà implementata una funzione per permettere all'utente di inserire un link e prendere i dati del prodotto direttamente dal sito web indicato
---

# Obiettivo del progetto

L’obiettivo del progetto era realizzare un semplice sito web dinamico utilizzando HTML, CSS, PHP e MySQL, simulando una piattaforma online di recensioni e discussione tra utenti.
