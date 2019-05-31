
<?php
//Cierra la asistencia del estudiante en el logout de la platafotrma
global $DB, $SESSION;
require_once('asistencia.php');

//Obtiene los valores de la configuracion global
$array = array('rutotec','claveotec','urlsence','msjsence');
for($i=0; $i<count($array); $i++)
{
    $query             = "SELECT value FROM mdlag_config_plugins WHERE plugin = 'sence' AND name = '".$array[$i]."'";
    $dato              = $DB->get_record_sql($query);
    $value[$array[$i]] = $dato->value;
}

$registro    = array();
$registro[0] = $USER->id;
$registro[1] = substr($USER->idnumber, 0);
$registro[2] = trim($USER->profile['clavesence']);
$registro[3] = '';
$registro[4] = '';
$registro[5] = $value['urlsence'];
$registro[6] = $value['rutotec'];
$registro[7] = $value['claveotec'];

//Obtiene el ultimo registro del estudiante
$query = "SELECT * FROM mdlag_block_sence_log WHERE userid = ".$registro[0]." AND 
          id = (SELECT MAX(b.id) FROM mdlag_block_sence_log b WHERE b.userid = ".$registro[0].")";
$dato  = $DB->get_record_sql($query);

//Si el registro es input, registra el output
if(!empty($dato) && $dato->input == 'input')
{
    //Pregunta si el usuario tiene clave sence
    if(!empty($registro[2]))
    {
        $query         = "SELECT idnumber FROM mdlag_course WHERE id = ".$dato->courseid;
        $idnumbervacio = $DB->get_record_sql($query);

        $registro[3]   = $dato->courseid;
        $registro[4]   = $idnumbervacio->idnumber;
        $resultado     = $asistencia->registro($registro, 2);

    }
}

?>
