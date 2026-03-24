# local_corsi — Pagina catalogo Corsi

Plugin locale Moodle che fornisce la pagina `/local/corsi/index.php`: un catalogo corsi suddiviso per sezioni (DigComp, Trasversali, Altri) con header, strip "In evidenza" e banner CTA finale.

Il contenuto dei corsi è gestito tramite istanze del blocco **[Cocoon] Courses grid** (`block_cocoon_courses_grid`), configurabili dall'amministratore direttamente nell'interfaccia.

## Struttura della pagina

| Sezione | Descrizione |
|---------|-------------|
| **Header** | Titolo e sottotitolo (stringhe `pagetitle` / `pagesubtitle`) |
| **Strip "In evidenza"** | Tre card-link che puntano alle ancore delle sezioni sottostanti |
| **Regione blocchi** | Blocchi `cocoon_courses_grid` inseriti nella regione `fullwidth-top` del layout `frontpage` |
| **Banner CTA** | Invito a tornare alla dashboard ("Vai al mio percorso") |

La sidebar viene nascosta automaticamente dal tema Starter Starter tramite `ccn_themehandler.php` quando il layout è `frontpage`.

## Configurazione del layout

### 1. Installare / aggiornare i plugin

Assicurarsi che `local_corsi` e `block_cocoon_courses_grid` siano presenti in `src/local/corsi/` e `src/blocks/cocoon_courses_grid/`. Poi eseguire l'upgrade:

```bash
php admin/cli/upgrade.php --non-interactive
```

### 2. Aggiungere la voce di menu

Da **Amministrazione del sito → Aspetto → Impostazioni tema → Voci di menu personalizzate** (o equivalente), aggiungere un link a `/local/corsi/index.php` con etichetta "Corsi".

In alternativa, il plugin registra la voce di navigazione tramite la stringa `navlabel`.

### 3. Aggiungere i blocchi corsi

1. Navigare a `/local/corsi/index.php`
2. Attivare la **modalità di modifica** ("Turn editing on" / "Attiva modifica")
3. Nella regione **fullwidth-top**, cliccare **Aggiungi un blocco**
4. Selezionare **[Cocoon] Courses grid**

### 4. Configurare ogni blocco

Per ciascuna istanza del blocco:

- **Corsi**: selezionare i corsi da mostrare nella sezione
- **Colonne**: impostare a **3**
- **Stile**: selezionare **Standard**

Per riprodurre il layout originale servono **3 istanze** del blocco, una per ciascuna sezione:

| Istanza | Corsi da selezionare | Ancora HTML (id) |
|---------|---------------------|-------------------|
| 1 | Corsi DigComp | `sezione-digcomp` |
| 2 | Corsi sulle competenze trasversali | `sezione-trasversali` |
| 3 | Tutti gli altri corsi | `sezione-altri` |

### 5. (Opzionale) Visibilità e permessi

Dalla configurazione di ciascun blocco è possibile:

- Limitare la visibilità per **ruolo** (es. solo utenti autenticati)
- Configurare la **posizione** e il **peso** per controllare l'ordine di visualizzazione

## Personalizzazione stringhe

Tutte le etichette della pagina sono definite in `lang/it/local_corsi.php` e possono essere sovrascritte da **Amministrazione del sito → Lingua → Personalizzazione lingua** senza modificare il codice.
