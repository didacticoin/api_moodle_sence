<?php

class block_sence_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $CFG;
        
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // A sample string variable with a default value.
//        $mform->addElement('text', 'config_text', get_string('blockstring', 'block_block_sence'));
//        $mform->setDefault('config_text', 'default value');
//        $mform->setType('config_text', PARAM_MULTILANG);

    }
}
