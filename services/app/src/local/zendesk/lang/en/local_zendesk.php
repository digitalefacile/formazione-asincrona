<?php
$string['pluginname'] = 'Zendesk Integration';
$string['api_token'] = 'Zendesk API Token';
$string['api_token_desc'] = 'Enter your Zendesk API token';
$string['zendesk_email'] = 'Zendesk Email';
$string['zendesk_email_desc'] = 'Enter the email associated with your Zendesk account';
$string['form_id'] = 'Form ID';
$string['form_id_desc'] = 'Enter the ID of the form to send data to';
$string['subdomain'] = 'Zendesk Subdomain';
$string['subdomain_desc'] = 'Enter your Zendesk subdomain (e.g., yoursubdomain.zendesk.com)';
$string['success'] = 'Ticket inviato con successo';
$string['custom_fields'] = 'Custom Fields';
$string['custom_fields_desc'] = 'Enter custom fields in JSON format [<b><u><a href="https://jsonlint.com/" target="_blank">JSON VALIDATOR &raquo;</a></u></b>], example: <pre>
[
    {
        "id": "18309754260636",
        "name": "Campo 1",
        "type": "text"
    },
    {
        "id": "18309754260637",
        "name": "Campo 2",
        "type": "select",
        "options": [
            {
                "name": "Opzione 1",
                "value": "1"
            },
            {
                "name": "Opzione 2",
                "value": "2"
            }
        ]
    }
]</pre>';