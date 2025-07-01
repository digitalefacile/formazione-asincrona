<?php
namespace local_createuserqueue\task;

defined('MOODLE_INTERNAL') || die();

class import_users extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('taskimportusers', 'local_createuserqueue');
    }

    public function execute() {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        // Leggi configurazioni
        $interval = get_config('local_createuserqueue', 'croninterval');
        if (!$interval) {
            $interval = 1;
        }

        $batchsize = get_config('local_createuserqueue', 'batchsize');
        if (!$batchsize) {
            $batchsize = 10;
        }

        $lastrun = get_config('local_createuserqueue', 'lastruntime');
        if ($lastrun && (time() - $lastrun) < ($interval * 60)) {
            mtrace("Salto esecuzione: ultimo run troppo recente.");
            return;
        }

        // Salva il tempo attuale come ultima esecuzione
        set_config('lastruntime', time(), 'local_createuserqueue');

        mtrace("Inizio task import_users - batch size: {$batchsize}");

        // Reset utenti 'processing' rimasti in sospeso da più di 10 minuti
        $this->reset_stuck_users();

        // Prendi utenti in coda da processare
        $users = $DB->get_records('local_createuserqueue_queue', 
            ['status' => 'queued'], 
            'timecreated ASC', 
            '*', 
            0, 
            $batchsize
        );

        if (empty($users)) {
            mtrace("Nessun utente in coda da processare.");
            return;
        }

        $processed = 0;
        $errors = 0;

        foreach ($users as $user) {
            mtrace("Processando utente: {$user->firstname} {$user->lastname} ({$user->email})");
            
            // Marca come "processing"
            $this->update_user_status($user->id, 'processing', time());
            
            try {
                // Controlla se l'utente esiste già
                if ($this->user_exists($user->email, $user->codicefiscale)) {
                    $this->handle_user_error($user, "Utente già esistente con email {$user->email} o codice fiscale {$user->codicefiscale}");
                    $errors++;
                    continue;
                }

                // Crea l'utente
                $newuser = $this->create_moodle_user($user);
                
                if ($newuser) {
                    // Rimuovi dalla coda se creato con successo
                    $DB->delete_records('local_createuserqueue_queue', ['id' => $user->id]);
                    mtrace("Utente creato con successo: {$newuser->username} (ID: {$newuser->id})");
                    $processed++;
                } else {
                    $this->handle_user_error($user, "Errore sconosciuto durante la creazione dell'utente");
                    $errors++;
                }

            } catch (Exception $e) {
                $this->handle_user_error($user, "Eccezione durante la creazione: " . $e->getMessage());
                $errors++;
            }
        }

        mtrace("Task completato - Processati: {$processed}, Errori: {$errors}");
    }

    /**
     * Reset utenti rimasti in stato 'processing' da troppo tempo
     */
    private function reset_stuck_users() {
        global $DB;
        
        $tenminutesago = time() - (10 * 60);
        $stuckusers = $DB->get_records_select('local_createuserqueue_queue', 
            'status = ? AND startedat < ?', 
            ['processing', $tenminutesago]
        );

        foreach ($stuckusers as $user) {
            mtrace("Reset utente rimasto in processing: {$user->firstname} {$user->lastname}");
            $this->update_user_status($user->id, 'queued', null);
        }
    }

    /**
     * Aggiorna lo status di un utente nella coda
     */
    private function update_user_status($userid, $status, $startedat = null) {
        global $DB;
        
        $update = new \stdClass();
        $update->id = $userid;
        $update->status = $status;
        
        if ($startedat !== null) {
            $update->startedat = $startedat;
        }
        
        $DB->update_record('local_createuserqueue_queue', $update);
    }

    /**
     * Controlla se un utente esiste già
     */
    private function user_exists($email, $codicefiscale) {
        global $DB;
        
        // Controlla per email
        if ($DB->record_exists('user', ['email' => $email, 'deleted' => 0])) {
            return true;
        }
        
        // Controlla per username nel formato tinit-codicefiscale
        $username = 'tinit-' . strtolower($codicefiscale);
        if ($DB->record_exists('user', ['username' => $username, 'deleted' => 0])) {
            return true;
        }
        
        return false;
    }

    /**
     * Crea un nuovo utente Moodle
     */
    private function create_moodle_user($queueuser) {
        global $DB, $CFG;
        
        // Genera username nel formato tinit-codicefiscale (tutto minuscolo)
        $username = 'tinit-' . strtolower($queueuser->codicefiscale);
        $username = clean_param($username, PARAM_USERNAME);
        
        // Controlla se l'username esiste già
        if ($DB->record_exists('user', ['username' => $username, 'deleted' => 0])) {
            throw new Exception("Username {$username} già esistente");
        }
        
        $user = new \stdClass();
        $user->auth = 'saml2'; // Auth method fisso
        $user->confirmed = 1;
        $user->mnethostid = $CFG->mnet_localhost_id;
        $user->username = $username;
        $user->password = ''; // Password non necessaria per SAML2
        $user->firstname = $queueuser->firstname;
        $user->lastname = $queueuser->lastname;
        $user->email = $queueuser->email;
        $user->idnumber = ''; // Campo vuoto, codice fiscale è solo nell'username
        $user->lang = 'it'; // Lingua fissa
        $user->timezone = '99';
        $user->timecreated = time();
        $user->timemodified = time();
        
        // Inserisci l'utente
        $userid = $DB->insert_record('user', $user);
        
        if ($userid) {
            $user->id = $userid;
            
            // Assegna ruolo di sistema fisso (rfd)
            $this->assign_system_role($userid, 'rfd');
            
            // Assegna alla coorte fissa
            $this->assign_to_cohort($userid, 'reti_28');
            
            // Imposta i campi profilo personalizzati
            $this->set_profile_fields($userid, [
                'Gruppo' => 'Studenti',
                // 'Intervento' => 'RFD', 
                // 'Regione' => 'Veneto',
                // 'Programma' => '',
                // 'Progetto' => 'H79I25000030006',
                // 'Punto' => 'Casa del Consumatore Cittadella'
            ]);
            
            // Invia email di benvenuto (senza credenziali, login tramite SPID)
            // $this->send_welcome_email($user);
            
            return $user;
        }
        
        return false;
    }

    /**
     * Genera un username unico
     */
    private function generate_unique_username($base) {
        global $DB;
        
        $username = $base;
        $counter = 1;
        
        while ($DB->record_exists('user', ['username' => $username, 'deleted' => 0])) {
            $username = $base . $counter;
            $counter++;
        }
        
        return $username;
    }

    /**
     * Genera una password sicura
     */
    private function generate_password($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        return substr(str_shuffle($chars), 0, $length);
    }

    /**
     * Invia email di benvenuto
     */
    private function send_welcome_email($user) {
        global $CFG, $SITE;
        
        $subject = "Benvenuto su " . format_string($SITE->fullname);
        
        $message = "Benvenuto/a {$user->firstname} {$user->lastname},\n\n";
        $message .= "È stato creato un account per te su " . format_string($SITE->fullname) . "\n\n";
        $message .= "Il tuo username è: {$user->username}\n\n";
        $message .= "Per accedere al sito utilizza il login SPID/CIE all'indirizzo: {$CFG->wwwroot}\n\n";
        $message .= "Non è necessaria alcuna password in quanto l'accesso avviene tramite SPID.\n\n";
        $message .= "Cordiali saluti,\n";
        $message .= "Il team di " . format_string($SITE->fullname);
        
        $admin = get_admin();
        
        email_to_user($user, $admin, $subject, $message);
        
        mtrace("Email di benvenuto inviata a: {$user->email}");
    }

    /**
     * Gestisce errori utente (retry o spostamento in tabella errori)
     */
    private function handle_user_error($user, $errormessage) {
        global $DB;
        
        mtrace("Errore per utente {$user->firstname} {$user->lastname}: {$errormessage}");
        
        $user->retries++;
        
        if ($user->retries >= 3) {
            // Sposta in tabella errori dopo 3 tentativi
            // Copia tutti i dati della coda nella tabella errori
            $error = new \stdClass();
            $error->firstname = $user->firstname;
            $error->lastname = $user->lastname;
            $error->codicefiscale = $user->codicefiscale;
            $error->email = $user->email;
            $error->errormessage = $errormessage;
            $error->timecreated = time();
            
            $DB->insert_record('local_createuserqueue_errors', $error);
            
            // Rimuovi dalla coda
            $DB->delete_records('local_createuserqueue_queue', ['id' => $user->id]);
            
            mtrace("Utente spostato in tabella errori dopo 3 tentativi falliti");
        } else {
            // Aggiorna retry count e rimetti in coda
            $update = new \stdClass();
            $update->id = $user->id;
            $update->retries = $user->retries;
            $update->status = 'queued';
            $update->startedat = null;
            
            $DB->update_record('local_createuserqueue_queue', $update);
            
            mtrace("Utente rimesso in coda (tentativo {$user->retries}/3)");
        }
    }
    
    /**
     * Assegna un ruolo di sistema all'utente
     */
    private function assign_system_role($userid, $roleshortname) {
        global $DB;
        
        try {
            $role = $DB->get_record('role', ['shortname' => $roleshortname]);
            if ($role) {
                $context = context_system::instance();
                role_assign($role->id, $userid, $context->id);
                mtrace("Ruolo {$roleshortname} assegnato all'utente {$userid}");
            } else {
                mtrace("ATTENZIONE: Ruolo {$roleshortname} non trovato");
            }
        } catch (Exception $e) {
            mtrace("Errore nell'assegnazione del ruolo: " . $e->getMessage());
        }
    }
    
    /**
     * Assegna l'utente a una coorte
     */
    private function assign_to_cohort($userid, $cohortname) {
        global $DB;
        
        try {
            $cohort = $DB->get_record('cohort', ['name' => $cohortname]);
            if (!$cohort) {
                $cohort = $DB->get_record('cohort', ['idnumber' => $cohortname]);
            }
            
            if ($cohort) {
                require_once($GLOBALS['CFG']->dirroot . '/cohort/lib.php');
                cohort_add_member($cohort->id, $userid);
                mtrace("Utente {$userid} aggiunto alla coorte {$cohortname}");
            } else {
                mtrace("ATTENZIONE: Coorte {$cohortname} non trovata");
            }
        } catch (Exception $e) {
            mtrace("Errore nell'assegnazione alla coorte: " . $e->getMessage());
        }
    }
    
    /**
     * Imposta i campi profilo personalizzati
     */
    private function set_profile_fields($userid, $fields) {
        global $DB;
        
        foreach ($fields as $fieldname => $value) {
            if (empty($value)) continue;
            
            try {
                // Trova il campo profilo
                $field = $DB->get_record('user_info_field', ['shortname' => $fieldname]);
                if (!$field) {
                    $field = $DB->get_record('user_info_field', ['name' => $fieldname]);
                }
                
                if ($field) {
                    // Controlla se esiste già un valore
                    $existing = $DB->get_record('user_info_data', [
                        'userid' => $userid,
                        'fieldid' => $field->id
                    ]);
                    
                    if ($existing) {
                        // Aggiorna
                        $existing->data = $value;
                        $DB->update_record('user_info_data', $existing);
                    } else {
                        // Inserisci nuovo
                        $data = new \stdClass();
                        $data->userid = $userid;
                        $data->fieldid = $field->id;
                        $data->data = $value;
                        $DB->insert_record('user_info_data', $data);
                    }
                    
                    mtrace("Campo profilo {$fieldname} impostato a: {$value}");
                } else {
                    mtrace("ATTENZIONE: Campo profilo {$fieldname} non trovato");
                }
            } catch (Exception $e) {
                mtrace("Errore nell'impostazione del campo profilo {$fieldname}: " . $e->getMessage());
            }
        }
    }
}
