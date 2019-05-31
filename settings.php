<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Newblock block caps.
 *
 * @package    block_newblock
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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