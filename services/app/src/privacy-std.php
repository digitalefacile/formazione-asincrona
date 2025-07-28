<?php
require(__DIR__ . '/config.php');

// Imposta il contesto e layout come frontpage
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/privacy-std.php');
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading($SITE->fullname);

// Mostra intestazione
echo $OUTPUT->header();
?>
<!-- js script change .button_custom_torna_indietro href to /percorsi-digitali.php after 1s -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const button = document.querySelector('.button_custom_torna_indietro');
            if (button) {
                button.setAttribute('href', '/percorsi-digitali.php');
            }
        }, 1000);
    });

    // get .d-none d-lg-block and set the first to dissplay none and important
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const element = document.querySelector('.d-none.d-lg-block');
            if (element) {
                element.style.display = 'none';
                element.style.setProperty('display', 'none', 'important');
            }
        }, 1000);
    });

    // get element breadcrumb-fake-container, move after the first d-none d-lg-block
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const breadcrumbFakeContainer = document.querySelector('.breadcrumb-fake-container');
            const firstDNone = document.querySelector('.d-none.d-lg-block');
            if (breadcrumbFakeContainer && firstDNone) {
                firstDNone.parentNode.insertBefore(breadcrumbFakeContainer, firstDNone.nextSibling);
            }
        }, 1000);
    });
</script>

<style>
    .inner_page_breadcrumb {
        display: initial;
    }
    /* .breadcrumb_content {
        padding-top: 0 !important;
    } */
</style>

<div class="d-none d-lg-block breadcrumb-fake-container">
    <section class="inner_page_breadcrumb ccn_breadcrumb_s ccn_breadcrumb_xs  ccn-clip-l  ccn-caps-capitalize  ccn-breadcrumb-title-h  ccn-breadcrumb-trail-v ">
      <div class="container">
        <div class="breadcrumb_content">
          <div class="row">
              <div class="col-xl-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/percorsi-digitali.php">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="/percorsi-digitali.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active ">Privacy</li>
                </ol>
              </div>
          </div>
        </div>
      </div>
    </section>
</div>

<div class="container">

    <div class="row">
        <div class="col-12 p-0">
            <a href="/percorsi-digitali.php" class="button_custom_torna_indietro" aria-label="Torna al corso Privacy Page">
                <svg xmlns="http://www.w3.org/2000/svg" style="margin-right:5px" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M6.73333 12.6665L7.2 12.1998L3.66667 8.59984L14 8.59984L14 7.93317L3.66667 7.93317L7.2 4.33317L6.73333 3.8665L2.33333 8.2665L6.73333 12.6665Z" fill="#0065CC"></path>
                </svg>
                Torna indietro
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 p-0">
            <h1 class="mb-4">PRIVACY PER STUDENTI</h1>

            <ul class="list-unstyled">
                <li><a href="#anchor1">Informativa generale sulla navigazione del sito e sui cookie</a></li>
                <li><a href="#anchor2">Informativa sul trattamento dei dati personali per gli utenti afferenti alla Misura 1.7.1. - “Servizio civile digitale”</a></li>
                <li><a href="#anchor3">Informativa sul trattamento dei dati personali per gli utenti afferenti alla Misura 1.7.2. - “Rete di servizi di facilitazione digitale”</a></li>
            </ul>

            <h2 id="anchor1" class="mt-5">Informativa generale sulla navigazione del sito e sui cookie</h2>
            <p>
                L’informativa riportata in questa sezione riguarda in maniera generalizzata tutti gli utenti del “Sito” in quanto si riferisce alle procedure software preposte al funzionamento di questo Sito e la possibilità di acquisire alcuni dati personali la cui trasmissione è implicita nell’uso dei protocolli di comunicazione di Internet per effetto delle attività di navigazione e consultazione del “Sito”.
            </p>

            <h3>Titolare del trattamento e Responsabile per la protezione dei dati</h3>
            <p>
                Il Titolare del trattamento è la Presidenza del Consiglio dei ministri - Dipartimento per la trasformazione digitale, con sede in Largo Pietro di Brazzà 86, 00187 Roma (il “Dipartimento per la trasformazione digitale”), contattabile ai seguenti recapiti:
            </p>
            <ul>
                <li>E-mail: <a href="mailto:segreteria.trasformazionedigitale@governo.it">segreteria.trasformazionedigitale@governo.it</a></li>
                <li>PEC: <a href="mailto:diptrasformazionedigitale@pec.governo.it">diptrasformazionedigitale@pec.governo.it</a></li>
            </ul>

            <h3>Tipologia di dati trattati, finalità del trattamento e periodo di conservazione</h3>
            <p>
                I sistemi informatici e le procedure software preposte al funzionamento di questo Sito acquisiscono, nel corso del loro normale esercizio, alcuni dati personali la cui trasmissione è implicita nell’uso dei protocolli di comunicazione di Internet. In questa categoria di dati rientrano gli indirizzi IP o i nomi a dominio dei computer e dei terminali utilizzati dagli utenti, gli indirizzi in notazione URI/URL (Uniform Resource Identifier/Locator) delle risorse richieste, l’orario della richiesta, il metodo utilizzato nel sottoporre la richiesta al server, la dimensione del file ottenuto in risposta, il codice numerico indicante lo stato della risposta data dal server (buon fine, errore, ecc.) ed altri parametri relativi al sistema operativo e all’ambiente informatico dell’utente.
            </p>

            <h2 id="anchor2" class="mt-5">Informativa sul trattamento dei dati personali per gli utenti afferenti alla Misura 1.7.1. - “Servizio civile digitale”</h2>
            <p>
                A norma degli articoli 13 e 14 del Regolamento, nella presente sezione vogliamo fornirti un'informativa specifica sul trattamento dei tuoi dati conseguente alla fruizione dei servizi di e-learning presenti sul Sito, con riferimento alla formazione per la facilitazione digitale erogata in favore dei volontari del Servizio civile digitale.
            </p>

            <h2 id="anchor3" class="mt-5">Informativa sul trattamento dei dati personali per gli utenti afferenti alla Misura 1.7.2. - “Rete di servizi di facilitazione digitale”</h2>
            <p>
                A norma degli articoli 13 e 14 del Regolamento, nella presente sezione vogliamo fornirti un'informativa semplice e chiara su come trattiamo i tuoi dati personali quando usufruisci dei servizi di e-learning presenti sul “Sito”, con riferimento alla formazione per la facilitazione digitale erogata in favore dei facilitatori.
            </p>
        </div>
    </div>
</div>

<?php
echo $OUTPUT->footer();
