<?php


defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree)
{    
    //Campos para la configuracion global del bloque sence
    //El registro queda referenciado en la tabla mdlag_config_plugins
    $settings->add(new admin_setting_heading('sampleheader', get_string('headerconfig', 'block_block_sence'), get_string('descconfig', 'block_block_sence')));
    $settings->add(new admin_setting_configtext('sence/rutotec',  get_string('rut_otec', 'block_block_sence'),  get_string('rut_otec_desc', 'block_block_sence'), get_string('rut_otec_default', 'block_block_sence'), PARAM_TEXT));
    $settings->add(new admin_setting_configtext('sence/claveotec',  get_string('clave_otec', 'block_block_sence'),  get_string('clave_otec_desc', 'block_block_sence'), get_string('clave_otec_default', 'block_block_sence'), PARAM_TEXT));
    $settings->add(new admin_setting_configtext('sence/urlsence',  get_string('url_sence', 'block_block_sence'),  get_string('url_sence_desc', 'block_block_sence'), get_string('url_sence_default', 'block_block_sence'), PARAM_TEXT));
    $settings->add(new admin_setting_configtextarea('sence/msjsence',  get_string('msj_sence', 'block_block_sence'),  get_string('msj_sence_desc', 'block_block_sence'), get_string('msj_sence_default', 'block_block_sence'), PARAM_TEXT));
}
