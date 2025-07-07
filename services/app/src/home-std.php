<?php
require(__DIR__ . '/config.php');
// Se l'utente è loggato, reindirizza alla home principale
if (isloggedin() && !isguestuser()) {
    redirect(new moodle_url('/'));
}

// Imposta il contesto e layout come frontpage
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/home-std.php');
$PAGE->set_pagelayout('frontpage'); // importante per mostrare i blocchi come block_cocoon_parallax_white
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading($SITE->fullname);

// Mostra intestazione + blocchi della home
echo $OUTPUT->header();
// echo $OUTPUT->main_content(); // <- fondamentale per mostrare i blocchi

?>

<aside id="block-region-fullwidth-top" class="block-region" data-blockregion="fullwidth-top" data-droptarget="1">
    <div id="inst11" class="block_cocoon_parallax_white block" role="complementary" data-block="cocoon_parallax_white">
        <a href="#sb-1" class="sr-only sr-only-focusable">Salta [Cocoon] Parallax White</a>
        
        <div class="ccnBlockContent" id="yui_3_17_2_1_1751553669510_25">
            <section id="yui_3_17_2_1_1751553669510_24">
                <div class="container-fluid" id="yui_3_17_2_1_1751553669510_23">
                    <div class="row" style="justify-content:center;" id="yui_3_17_2_1_1751553669510_22">
                        <div class="parallax_white_first_div" id="yui_3_17_2_1_1751553669510_31">
                            <div id="yui_3_17_2_1_1751553669510_30">
                                <h3 class="parallax_white_title" data-ccn="title">
                                    Ti diamo il benvenuto sulla piattaforma per la formazione di facilitatori e volontari digitali
                                </h3>
                                
                                <p class="parallax_white_paragraph" data-ccn="subtitle" id="yui_3_17_2_1_1751553669510_29">
                                    Scopri tutte le risorse formative dedicate ai facilitatori della rete dei Punti digitale facile e ai volontari del Servizio civile digitale. Segui i corsi, rispondi ai test e completa il tuo percorso.
                                </p>
                                
                                <a data-ccn="button_text" 
                                   aria-label="Accedi al sito" 
                                   target="_self" 
                                   href="https://competenze.repubblicadigitale.gov.it/auth/saml2/login.php?wants&amp;amp;idp=6064d4fd77293c2d84cbecfda099e985&amp;amp;passive=off" 
                                   class="btn btn-primary button_parallax_white">
                                    Accedi
                                </a>
                            </div>
                        </div>
                        
                        <div class="parallax_white_second_div" id="yui_3_17_2_1_1751553669510_21">
                            <div class="about_thumb_home3 text-right">
                                <img class="img-fluid" 
                                     src="/pluginfile.php/16/block_cocoon_parallax_white/content/img4.png" 
                                     alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <span id="sb-1"></span>
    </div>
</aside>

<?php
echo $OUTPUT->footer();
