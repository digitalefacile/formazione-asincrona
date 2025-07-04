<?php
require(__DIR__ . '/config.php');
// Se l'utente è loggato, reindirizza alla home principale
if (isloggedin() && !isguestuser()) {
    redirect(new moodle_url('/'));
}

// Imposta il contesto e layout come frontpage
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/home-alt.php');
$PAGE->set_pagelayout('frontpage'); // importante per mostrare i blocchi come block_cocoon_parallax_white
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading($SITE->fullname);

// Mostra intestazione + blocchi della home
echo $OUTPUT->header();
// echo $OUTPUT->main_content(); // <- fondamentale per mostrare i blocchi

?>
<div class="d-none d-lg-block ">
    <section class="inner_page_breadcrumb ccn_breadcrumb_s ccn_breadcrumb_xs  ccn-clip-l  ccn-caps-capitalize  ccn-breadcrumb-title-h  ccn-breadcrumb-trail-v ">
      <div class="container">
        <div class="breadcrumb_content">
          <div class="row">
              <div class="col-xl-12">
                <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="https://competenze.repubblicadigitale.gov.it/">Home</a>
      </li>
      <li class="breadcrumb-item">
        <a href="https://competenze.repubblicadigitale.gov.it/?redirect=0">Home</a>
      </li>
      <li class="breadcrumb-item active ">Privacy</li>
</ol>
              </div>
          </div>
        </div>
      </div>
    </section>
</div>

          <div class="container" id="yui_3_17_2_1_1751624470516_26">
              <div class="row">
                      <div class="col-md-12 col-lg-12 col-xl-12">
                        <a href="https://competenze.repubblicadigitale.gov.it/" class="button_custom_torna_indietro" aria-label="Torna al corso Privacy Page">
                        <svg xmlns="http://www.w3.org/2000/svg" style="margin-right:5px" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M6.73333 12.6665L7.2 12.1998L3.66667 8.59984L14 8.59984L14 7.93317L3.66667 7.93317L7.2 4.33317L6.73333 3.8665L2.33333 8.2665L6.73333 12.6665Z" fill="#0065CC"></path>
                        </svg>
                        Torna indietro
                        </a>
                      </div>
                    </div>
            <div class="row" id="yui_3_17_2_1_1751624470516_25">
              <div id="row-cols" class="
                 col-md-12 col-lg-12 col-xl-12 
                
                
                
              ">
                <div id="region-main" aria-label="Contenuto">
                    <aside id="block-region-above-content" class="block-region" data-blockregion="above-content" data-droptarget="1"></aside>
                  <div id="ccn-main">
                    <span class="notifications" id="user-notifications"></span>
                        <span id="maincontent"></span>
                        <div class="activity-header" data-for="page-activity-header"></div>
                    <div class="box py-3 generalbox center clearfix" id="yui_3_17_2_1_1751624470516_24"><div class="no-overflow" id="yui_3_17_2_1_1751624470516_23"><div style="color:#000000!important" id="yui_3_17_2_1_1751624470516_22">

    <h1 id="yui_3_17_2_1_1751624470516_21">Informative sul trattamento dei dati personali e sui cookie</h1>

    <p><a href="#anchor1" aria-label="Vai alla sezione Informativa generale sulla navigazione del sito e sui cookie">Informativa generale sulla navigazione del sito e sui cookie</a></p>
    <p><a href="#anchor2" aria-label="Vai alla sezione Informativa sul trattamento dei dati personali per gli utenti afferenti alla Misura 1.7.1. - “Servizio civile digitale”">Informativa sul trattamento dei dati personali per gli utenti afferenti alla Misura 1.7.1. - “Servizio civile digitale”</a></p>

    <p><a href="#anchor3" aria-label="Vai alla sezione Informativa sul trattamento dei dati personali per gli utenti afferenti alla Misura 1.7.2. - “Rete di servizi di facilitazione digitale”">Informativa sul trattamento dei dati personali per gli utenti afferenti alla Misura 1.7.2. - “Rete di servizi di facilitazione digitale”</a></p>


    <h2>Introduzione</h2>
    <p>Ai sensi del Regolamento (UE) 2016/679 del Parlamento europeo e del Consiglio del 27 aprile 2016 recante la disciplina europea per la protezione delle persone fisiche con riguardo al trattamento dei dati personali, nonché alla libera circolazione di tali dati (General Data Protection Regulation, in seguito "Regolamento"), e nel rispetto del decreto legislativo 30 giugno 2003, n. 196 e s.m.i., questo documento descrive le modalità di trattamento dei dati personali che ci fornisci direttamente o di cui comunque potremo venire in possesso tramite contatto con il sito web <a href="https://competenze.repubblicadigitale.gov.it" target="_blank">https://competenze.repubblicadigitale.gov.it</a> (di seguito, il “Sito”), inclusi i dati personali dei facilitatori della Rete di servizi di facilitazione digitale e dei volontari del Servizio civile digitale nell’ambito dei servizi di e-learning per facilitatori e volontari erogati tramite il Sito. La presente informativa è resa ai sensi degli articoli 13 e 14 del Regolamento.</p>

    <h2 id="anchor1">Informativa generale sulla navigazione del sito e sui cookie</h2>
    <p>
        L’informativa riportata in questa sezione riguarda in maniera generalizzata tutti gli utenti del “Sito” in quanto si riferisce alle procedure software preposte al funzionamento di questo Sito e la possibilità di acquisire alcuni dati personali la cui trasmissione è implicita nell’uso dei protocolli di comunicazione di Internet per effetto delle attività di navigazione e consultazione del “Sito”.
    </p>

    <h3>Titolare del trattamento e Responsabile per la protezione dei dati</h3>
    <p>
        Il Titolare del trattamento è la Presidenza del Consiglio dei ministri - Dipartimento per la trasformazione digitale, con sede in Largo Pietro di Brazzà 86, 00187 Roma (il “Dipartimento per la trasformazione digitale”), contattabile ai seguenti recapiti:
    </p>
    <span class="sr-only">Inizio elenco recapiti</span>
    <ul aria-label="Elenco recapiti">
        <li>E-mail: <a href="mailto:segreteria.trasformazionedigitale@governo.it">segreteria.trasformazionedigitale@governo.it</a></li>
        <li>PEC: <a href="mailto:diptrasformazionedigitale@pec.governo.it">diptrasformazionedigitale@pec.governo.it</a></li>
    </ul>
    <span class="sr-only">Fine elenco recapiti</span>
    <p>
        Il Responsabile per la protezione dei dati - Data Protection Officer è contattabile ai seguenti recapiti:
    </p>
    <span class="sr-only">Inizio elenco recapiti</span>
    <ul aria-label="Elenco recapiti">
        <li>E-mail: <a href="mailto:responsabileprotezionedatipcm@governo.it">responsabileprotezionedatipcm@governo.it</a></li>
        <li>PEC: <a href="mailto:rpd@pec.governo.it">rpd@pec.governo.it</a></li>
    </ul>
    <span class="sr-only">Fine elenco recapiti</span>
    <h3>Tipologia di dati trattati, finalità del trattamento e periodo di conservazione</h3>

    <h4>Dati di navigazione</h4>
    <p>
        I sistemi informatici e le procedure software preposte al funzionamento di questo Sito acquisiscono, nel corso del loro normale esercizio, alcuni dati personali la cui trasmissione è implicita nell’uso dei protocolli di comunicazione di Internet. In questa categoria di dati rientrano gli indirizzi IP o i nomi a dominio dei computer e dei terminali utilizzati dagli utenti, gli indirizzi in notazione URI/URL (Uniform Resource Identifier/Locator) delle risorse richieste, l’orario della richiesta, il metodo utilizzato nel sottoporre la richiesta al server, la dimensione del file ottenuto in risposta, il codice numerico indicante lo stato della risposta data dal server (buon fine, errore, ecc.) ed altri parametri relativi al sistema operativo e all’ambiente informatico dell’utente. Tali dati, necessari per la fruizione dei servizi web, vengono anche trattati allo scopo di controllare il corretto funzionamento del Sito e dei servizi offerti, ottenere informazioni statistiche sull’uso dei servizi (pagine più visitate, numero di visitatori per fascia oraria o giornaliera, aree geografiche di provenienza, ecc.) e al fine di garantire la sicurezza della navigazione. I dati di navigazione verranno conservati per il tempo strettamente necessario a fornire i servizi richiesti e a svolgere le correlate operazioni tecniche e di sicurezza, fatta salva la necessità di conservarli ulteriormente per eventuali necessità di accertamento di reati da parte dell’Autorità giudiziaria.
    </p>

    <h4>Dati comunicati dall’utente</h4>
    <p>
        L’invio facoltativo, esplicito e volontario di messaggi agli indirizzi di contatto del Sito, nonché i messaggi privati inviati dagli utenti ai relativi profili/pagine istituzionali sui social media (laddove questa possibilità sia prevista), comportano l’acquisizione dei dati di contatto del mittente, necessari a rispondere, nonché di eventuali ulteriori dati personali inclusi nelle comunicazioni. Tali dati saranno trattati dal Dipartimento per la trasformazione digitale, esclusivamente al fine di gestire le interazioni con l’utenza e per il periodo strettamente necessario.
    </p>

    <h3>Base giuridica del trattamento</h3>
    <p>
        I dati personali saranno trattati dal Dipartimento per la trasformazione digitale nell’esecuzione dei propri compiti di interesse pubblico o comunque connessi all’esercizio dei propri pubblici poteri (art. 6, par. 1, lett. e del Regolamento), con riferimento al Regolamento (UE) 2021/241 del Parlamento europeo e del Consiglio del 12 febbraio 2021 che istituisce il dispositivo per la ripresa e la resilienza.
    </p>


    <h4>Chi potrà conoscere i dati personali</h4>

    <p>
        I dati personali raccolti potranno essere oggetto di comunicazione a soggetti, interni o esterni al Dipartimento per la trasformazione digitale, nei confronti dei quali la comunicazione si configura come necessaria per il perseguimento delle finalità sopra specificate, compresi soggetti terzi che forniscono un servizio al Dipartimento, ad esempio per la fornitura di servizi tecnologici di questo “Sito”, e che tratteranno detti dati personali in qualità di responsabili del trattamento ai sensi e per gli effetti di cui all’articolo 28 del Regolamento.
    </p>

    <p>
        L’elenco dei responsabili del trattamento può essere richiesto al Dipartimento per la trasformazione digitale in qualsiasi momento, scrivendo a <a href="mailto:segreteria.trasformazionedigitale@governo.it">segreteria.trasformazionedigitale@governo.it</a>
    </p>

    <h4>Trasferimento dei dati</h4>

    <p>
        I dati potranno essere trasferiti fuori dal territorio nazionale a Paesi situati nell’Unione Europea. Per la fornitura di alcuni servizi tecnologici, il Dipartimento per la trasformazione digitale potrebbe avvalersi di soggetti ubicati al di fuori dell’Unione Europea. L’eventuale trasferimento dei dati in Paesi situati al di fuori dell’Unione Europea avverrà, in ogni caso, nel rispetto delle garanzie appropriate e opportune ai fini del trasferimento stesso, ai sensi della normativa applicabile e con particolare riferimento agli art. 44 e ss. del Regolamento.
    </p>

    <h4>Quali sono i tuoi diritti</h4>
    <span class="sr-only">Inizio elenco diritti</span>
    <ul aria-label="Elenco diritti">
        <li><strong>Diritto di accedere ai dati:</strong> puoi ottenere conferma e informazioni sul trattamento.</li>
        <li><strong>Diritto di rettifica:</strong> puoi rettificare dati inesatti o integrarli.</li>
        <li><strong>Diritto di cancellazione:</strong> nei casi di legge, puoi chiedere l’oblio.</li>
        <li><strong>Diritto di limitazione al trattamento:</strong> nei casi di legge, puoi chiedere di limitare il trattamento.</li>
        <li><strong>Diritto di opporsi al trattamento:</strong> per particolari motivi puoi opporti al trattamento per l’esecuzione di un compito di interesse pubblico o connesso all’esercizio di pubblici poteri.</li>
    </ul>
    <span class="sr-only">Fine elenco diritti</span>
    <p>
        Per maggiori informazioni o esercitare i tuoi diritti contatta il Dipartimento per la trasformazione digitale agli indirizzi sopra indicati. Se invece ritieni che il trattamento dei dati personali a te riferiti avvenga in violazione di quanto previsto dal Regolamento, hai anche il diritto di proporre reclamo, ai sensi dell’art. 77 del Regolamento, al Garante per la protezione dei dati personali.
    </p>
    <h3>Cookie Policy</h3>

    <p>
        Questa sezione fornisce informazioni dettagliate sull’uso dei cookie, su come sono utilizzati dal sito e su come gestirli, in attuazione dell’art. 122 del decreto legislativo 30 giugno 2003, n. 196, nonché nel rispetto delle “Linee guida cookie e altri strumenti di tracciamento” emanate dal Garante per la protezione dei dati personali con provvedimento del 10 giugno 2021.
    </p>

    <p>
        Questo sito utilizza esclusivamente cookie tecnici necessari per il suo funzionamento e per migliorare l’esperienza d’uso dei propri visitatori.
    </p>

    <h4>Come disabilitare i cookie (opt-out) sul proprio dispositivo</h4>

    <p>
        La maggior parte dei browser accetta i cookie automaticamente, ma è possibile rifiutarli. Se non si desidera ricevere o memorizzare i cookie, si possono modificare le impostazioni di sicurezza del browser utilizzato, secondo le istruzioni rese disponibili dai relativi fornitori ai link di seguito indicati. Nel caso in cui si disabilitino tutti i cookie, il Sito potrebbe non funzionare correttamente.
    </p>
    <span class="sr-only">Inizio elenco browser</span>
    <ul aria-label="Elenco browser">
        <li><a href="https://support.google.com/chrome/answer/95647?hl=it" target="_blank">Chrome</a></li>
        <li><a href="https://support.mozilla.org/it/kb/Attivare%20e%20disattivare%20i%20cookie" target="_blank">Firefox</a></li>
        <li><a href="https://support.apple.com/it-it/guide/safari/sfri11471/mac" target="_blank">Safari</a></li>
        <li><a href="https://support.microsoft.com/it-it/microsoft-edge/eliminare-i-cookie-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank">Edge</a></li>
        <li><a href="https://help.opera.com/it/latest/security-and-privacy/" target="_blank">Opera</a></li>
    </ul>
    <span class="sr-only">Fine elenco browser</span>

    <h2 id="anchor2">Informativa sul trattamento dei dati personali per gli utenti afferenti alla Misura 1.7.1. - “Servizio civile digitale”</h2>

    <p>
        A norma degli articoli 13 e 14 del Regolamento, nella presente sezione vogliamo fornirti un'informativa specifica sul trattamento dei tuoi dati conseguente alla fruizione dei servizi di e-learning presenti sul Sito, con riferimento alla formazione per la facilitazione digitale erogata in favore dei volontari del Servizio civile digitale nell’ambito della Missione 1 – Componente 1 – Asse 1 – Misura 1.7.1. “Servizio civile digitale” del PNRR.
    </p>




    <h2>Titolare del trattamento e Responsabile per la protezione dei dati</h2>

    <p>
        Il Titolare del trattamento è la Presidenza del Consiglio dei Ministri - Dipartimento per le politiche giovanili e il Servizio civile universale, con sede in Via della Ferratella in Laterano 51, 00184 Roma (il “Dipartimento per le politiche giovanili e il Servizio civile universale”), contattabile ai seguenti recapiti:
    </p>
    <span class="sr-only">Inizio elenco recapiti</span>
    <ul aria-label="Elenco recapiti">
        <li>E-mail: <a href="mailto:segreteria.trasformazionedigitale@governo.it">segreteria.trasformazionedigitale@governo.it</a></li>
        <li>PEC: <a href="mailto:giovanieserviziocivile@pec.governo.it">giovanieserviziocivile@pec.governo.it</a></li>
    </ul>
    <span class="sr-only">Fine elenco recapiti</span>
    <p>
        Il Responsabile per la protezione dei dati - Data Protection Officer è contattabile ai seguenti recapiti:
    </p>
    <span class="sr-only">Inizio elenco recapiti</span>
    <ul aria-label="Elenco recapiti">
        <li>E-mail: <a href="mailto:responsabileprotezionedatipcm@governo.it">responsabileprotezionedatipcm@governo.it</a></li>
        <li>PEC: <a href="mailto:rpd@pec.governo.it">rpd@pec.governo.it</a></li>
    </ul>
    <span class="sr-only">Fine elenco recapiti</span>

    <h3>Tipologia di dati trattati, finalità del trattamento e periodo di conservazione</h3>

    <p>
        I dati personali trasmessi per il tramite del “Sito”, attraverso autenticazione mediante identità digitale (SPID o CIE), sono nome, cognome, indirizzo e-mail e codice fiscale. Tali dati saranno trattati dal Dipartimento per le politiche giovanili e il Servizio civile universale, al solo fine di erogare le attività di formazione per la facilitazione digitale messe a disposizione sul “Sito” e conservati per il periodo necessario allo svolgimento delle citate attività di formazione e, comunque, nel rispetto delle norme previste dalla normativa vigente per la conservazione degli atti e dei documenti della P.A., anche a fini archivistici.
    </p>

    <h3>Base giuridica del trattamento</h3>

    <p>
        I dati personali saranno trattati dal Dipartimento per le politiche giovanili e il Servizio civile universale nell’esecuzione dei propri compiti di interesse pubblico o comunque connessi all’esercizio dei propri pubblici poteri (art. 6, par. 1, lett. e del Regolamento), con riferimento al Regolamento (UE) 2021/241 del Parlamento europeo e del Consiglio del 12 febbraio 2021 che istituisce il dispositivo per la ripresa e la resilienza.
    </p>

    <h3>Chi potrà conoscere i dati personali</h3>

    <p>
        Sono destinatari dei dati personali raccolti per il tramite del “Sito”, i seguenti soggetti:
    </p>

    <ul aria-label="Elenco soggetti">
        <li>Il Dipartimento per la trasformazione digitale della Presidenza del Consiglio dei ministri che opera in qualità di Responsabile del trattamento ai sensi e per gli effetti di cui all’Accordo ex articolo 28 del Regolamento, sottoscritto tra la Presidenza del Consiglio dei ministri – Dipartimento per le Politiche giovanili e il Servizio civile universale e la Presidenza del Consiglio dei ministri – Dipartimento per la trasformazione digitale, in data 10 luglio 2023, che qui si intende integralmente richiamato;</li>
        <li>Le società Enterprise Services Italia S.r.l. e DS Tech S.r.l., che operano in qualità di Sub-responsabili del trattamento all’uopo designati dal Dipartimento per la trasformazione digitale, in qualità di fornitori dei servizi di sviluppo, erogazione e gestione operativa del “Sito”;</li>
    </ul>

    <p>
        I dati personali raccolti sono altresì trattati dal personale del Dipartimento per la trasformazione digitale, che agisce sulla base di specifiche istruzioni fornite in ordine a finalità e modalità del trattamento medesimo.
    </p>



    <h3>Trasferimento dei dati</h3>

    <p>
        I dati potranno essere trasferiti fuori dal territorio nazionale a Paesi situati nell’Unione Europea. Per la fornitura di alcuni servizi tecnologici, il Dipartimento per la trasformazione digitale potrebbe avvalersi di soggetti ubicati al di fuori dell’Unione Europea. L’eventuale trasferimento dei dati in Paesi situati al di fuori dell’Unione Europea avverrà, in ogni caso, nel rispetto delle garanzie appropriate e opportune ai fini del trasferimento stesso, ai sensi della normativa applicabile e con particolare riferimento agli art. 44 e ss. del Regolamento.
    </p>

    <h3>Quali sono i tuoi diritti</h3>
    <span class="sr-only">Inizio elenco diritti</span>
    <ul aria-label="Elenco diritti">
        <li><strong>Diritto di accedere ai dati:</strong> puoi ottenere conferma e informazioni sul trattamento.</li>
        <li><strong>Diritto di rettifica:</strong> puoi rettificare dati inesatti o integrarli.</li>
        <li><strong>Diritto di cancellazione:</strong> nei casi di legge, puoi chiedere l’oblio.</li>
        <li><strong>Diritto di limitazione al trattamento:</strong> nei casi di legge, puoi chiedere di limitare il trattamento.</li>
        <li><strong>Diritto di opporsi al trattamento:</strong> per particolari motivi puoi opporti al trattamento per l’esecuzione di un compito di interesse pubblico o connesso all’esercizio di pubblici poteri.</li>
    </ul>
    <span class="sr-only">Fine elenco diritti</span>

    <p>
        Per maggiori informazioni o esercitare i tuoi diritti contatta il Dipartimento per le politiche giovanili e il Servizio civile universale agli indirizzi sopra indicati. Se invece ritieni che il trattamento dei dati personali a te riferiti avvenga in violazione di quanto previsto dal Regolamento, hai anche diritto di proporre reclamo, ai sensi dell’art. 77 del Regolamento, al Garante per la protezione dei dati personali.
    </p>


    <h2 id="anchor3">Informativa sul trattamento dei dati personali per gli utenti afferenti alla Misura 1.7.2. - “Rete di servizi di facilitazione digitale”</h2>

    <p>
        A norma degli articoli 13 e 14 del Regolamento, nella presente sezione vogliamo fornirti un'informativa semplice e chiara su come trattiamo i tuoi dati personali quando usufruisci dei servizi di e-learning presenti sul “Sito”, con riferimento alla formazione per la facilitazione digitale erogata in favore dei facilitatori nell’ambito della Missione 1 - Componente 1 - Asse 1 - Misura 1.7.2 “Rete di servizi di facilitazione digitale” del PNRR.
    </p>





    <h3>Titolare del trattamento e Responsabile per la protezione dei dati</h3>

    <p>
        Il Titolare del trattamento è la Presidenza del Consiglio dei Ministri - Dipartimento per la trasformazione digitale, con sede in Largo Pietro di Brazzà 86, 00187 Roma (il “Dipartimento per la trasformazione digitale”), contattabile ai seguenti recapiti:
    </p>
    <span class="sr-only">Inizio elenco recapiti</span>
    <ul aria-label="Elenco recapiti">
        <li>E-mail: <a href="mailto:segreteria.trasformazionedigitale@governo.it">segreteria.trasformazionedigitale@governo.it</a></li>
        <li>PEC: <a href="mailto:diptrasformazionedigitale@pec.governo.it">diptrasformazionedigitale@pec.governo.it</a></li>
    </ul>
    <span class="sr-only">Fine elenco recapiti</span>

    <p>
        Il Responsabile per la protezione dei dati - Data Protection Officer è contattabile ai seguenti recapiti:
    </p>
    <span class="sr-only">Inizio elenco recapiti</span>
    <ul aria-label="Elenco recapiti">
        <li>E-mail: <a href="mailto:responsabileprotezionedatipcm@governo.it">responsabileprotezionedatipcm@governo.it</a></li>
        <li>PEC: <a href="mailto:rpd@pec.governo.it">rpd@pec.governo.it</a></li>
    </ul>
    <span class="sr-only">Fine elenco recapiti</span>

    <h3>Tipologia di dati trattati, finalità del trattamento e periodo di conservazione</h3>

    <p>
        I dati personali trasmessi per il tramite del “Sito”, attraverso autenticazione mediante identità digitale (SPID o CIE), sono nome, cognome, indirizzo e-mail e codice fiscale. Tali dati saranno trattati dal Dipartimento per la trasformazione digitale, al solo fine di erogare le attività di formazione per la facilitazione digitale messe a disposizione sul “Sito” e conservati per il periodo necessario allo svolgimento delle citate attività di formazione e, comunque, nel rispetto delle norme previste dalla normativa vigente per la conservazione degli atti e dei documenti della P.A., anche a fini archivistici.
    </p>

    <h3>Base giuridica del trattamento</h3>

    <p>
        I dati personali saranno trattati dal Dipartimento per la trasformazione digitale nell’esecuzione dei propri compiti di interesse pubblico o comunque connessi all’esercizio dei propri pubblici poteri (art. 6, par. 1, lett. e del Regolamento), con riferimento al Piano Operativo della Strategia Nazionale per le Competenze Digitali.
    </p>

    <h3>Chi potrà conoscere i dati personali</h3>

    <p>
        Sono destinatari dei dati personali raccolti per il tramite del “Sito”, i seguenti soggetti designati dal Dipartimento per la trasformazione digitale, ai sensi dell'articolo 28 del Regolamento, quali responsabili del trattamento: Enterprise Services Italia S.r.l. e DS Tech S.r.l., quali fornitori dei servizi di sviluppo, erogazione e gestione operativa del “Sito”.
    </p>

    <p>
        I dati personali raccolti sono altresì trattati dal personale del Dipartimento per la trasformazione digitale, che agisce sulla base di specifiche istruzioni fornite in ordine a finalità e modalità del trattamento medesimo.
    </p>

    <h3>Trasferimento dei dati</h3>

    <p>
        I dati potranno essere trasferiti fuori dal territorio nazionale a Paesi situati nell’Unione Europea. Per la fornitura di alcuni servizi tecnologici, il Dipartimento per la trasformazione digitale potrebbe avvalersi di soggetti ubicati al di fuori dell’Unione Europea. L’eventuale trasferimento dei dati in Paesi situati al di fuori dell’Unione Europea avverrà, in ogni caso, nel rispetto delle garanzie appropriate e opportune ai fini del trasferimento stesso, ai sensi della normativa applicabile e con particolare riferimento agli art. 44 e ss. del Regolamento.
    </p>

    <h3>Quali sono i tuoi diritti</h3>
    <span class="sr-only">Inizio elenco diritti</span>
    <ul aria-label="Elenco diritti">
        <li><strong>Diritto di accedere ai dati:</strong> puoi ottenere conferma e informazioni sul trattamento.</li>
        <li><strong>Diritto di rettifica:</strong> puoi rettificare dati inesatti o integrarli.</li>
        <li><strong>Diritto di cancellazione:</strong> nei casi di legge, puoi chiedere l’oblio.</li>
        <li><strong>Diritto di limitazione al trattamento:</strong> nei casi di legge, puoi chiedere di limitare il trattamento.</li>
        <li><strong>Diritto di opporsi al trattamento:</strong> per particolari motivi puoi opporti al trattamento per l’esecuzione di un compito di interesse pubblico o connesso all’esercizio di pubblici poteri.</li>
    </ul>
    <span class="sr-only">Fine elenco diritti</span>

    <p>
        Per maggiori informazioni o esercitare i tuoi diritti contatta il Dipartimento per la trasformazione digitale agli indirizzi sopra indicati. Se invece ritieni che il trattamento dei dati personali a te riferiti avvenga in violazione di quanto previsto dal Regolamento, hai anche diritto di proporre reclamo, ai sensi dell’art. 77 del Regolamento, al Garante per la protezione dei dati personali.
    </p>


</div></div></div><div id="zendesk-modal-body" style="display:none;" rolename="guest"><div class="prebody-text">Assistenza</div><div class="modal-inner-body"><strong>Hai bisogno di assistenza?</strong><br>
Effettua l'accesso per inviare una richiesta<br>
<br>
<strong>Non riesci a effettuare l'accesso?</strong><br>
Scrivi all'indirizzo:<br>
<a href="mailto:problema-accesso-formazione@repubblicadigitale.gov.it">problema-accesso-formazione@repubblicadigitale.gov.it</a></div><div class="afterbody-text alt">&nbsp;</div></div>
                      
                    
              </div>
                <aside id="block-region-below-content" class="block-region" data-blockregion="below-content" data-droptarget="1"></aside>
            </div>
          </div>
        </div>
      </div>

<?php
echo $OUTPUT->footer();
