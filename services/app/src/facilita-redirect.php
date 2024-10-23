<?php
require_once(__DIR__ . '/config.php');
require_login();
global $DB, $USER, $CFG;

if (!isloggedin() || $USER->username == 'guest') {
    echo "Errore: Utente non loggato.";
    exit;
}
?>
<style>
    #page {display: none !important;}
    .avviso-custom {
        padding: 20px;
        background-color: transparent;
        color: #000;
        margin-bottom: 15px;
        border: 0px solid transparent;
    }
</style>
<?php

// get current url with http/s protocol and domain
$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
$current_url = $protocol . $domain . '/course/view.php?id=';
// scd = volontario
// rfd = facilitatore
$courseId['scd'] = '25';
$courseId['rfd'] = '26';
$courseUrl['rfd'] = $current_url . $courseId['rfd'];
$courseUrl['scd'] = $current_url . $courseId['scd'];

$userid = $USER->id;

$roleid = $DB->get_field('role_assignments', 'roleid', array('userid' => $userid));
// var_dump($roleid);
?><div class="avviso-custom"><?php
if ($roleid) {
    $rolename = $DB->get_field('role', 'shortname', array('id' => $roleid));
    // echo "Ruolo dell'utente: " . $rolename . "<br>";
    if ($rolename) {     
        switch ($rolename) {
            case 'rfd':
                // echo "Redirecting to: " . $courseUrl['rfd'];
                redirect($courseUrl['rfd'], null, 0);
                break;
            case 'scd':
                // echo "Redirecting to: " . $courseUrl['scd'];
                redirect($courseUrl['scd'], null, 0);
                break;
            case 'editingteacher':
                echo "Ruolo dell'utente: " . $rolename . "<br>";
                echo 'Utente admin rilevato, clicca uno dei seguenti link per accedere ai corsi:<br>';
                echo '<a href="' . $courseUrl['scd'] . '">SCD [VOLONTARI] </a><br>';
                echo '<a href="' . $courseUrl['rfd'] . '">RFD [FACILITATORI] </a><br>';
                break;
            default:
                echo "ERRORE: Nessun corso trovato per il ruolo: " . $rolename;
                // then redirect to the home page without message
                // redirect($CFG->wwwroot, null, 0);
                break;
        }

    } else {
        echo "ERRORE: Nessun ruolo trovato per l'ID ruolo: " . $roleid;
        // then redirect to the home page without message
        // redirect($CFG->wwwroot, null, 0);
    }
} else {
    echo "ERRORE: Nessun ruolo assegnato all'utente.";
    // redirect to the home page without message
    // redirect($CFG->wwwroot, null, 0);
    // redirect('https://www.google.com', null, 0);
}



?>
</div>
<?php