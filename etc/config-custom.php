<?php

// Custom configs for config.php goes here

//=========================================================================
// 20 CUSTOM MODULE TRANSLATION 
//=========================================================================
$CFG->customstringmanager = 'local_string_override_manager';
//=========================================================================
// ALL DONE!  To continue installation, visit your main page with a browser
//=========================================================================

// Use the following flag to completely disable the installation of plugins
// (new plugins, available updates and missing dependencies) and related
// features (such as cancelling the plugin installation or upgrade) via the
// server administration web interface.
$CFG->disableupdateautodeploy = true;

// S3
$CFG->alternative_file_system_class = '\tool_objectfs\s3_file_system';

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
