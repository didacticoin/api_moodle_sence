 * @package    block_newblock
 * @copyright  Producciones didactic ltda. www.didactic.cl

<?php

class asistencia
{
    function registro($array, $input)
    {
        //Contenido del $array -> [0]=$iduser ; [1]=$rut ; [2]=$claveSenceUser ; [3]=$idcourse ; [4]=$idnumber ; [5]=$urlsence ; [6]=$rutotec ; [7]=$claveotec
        global $DB;
        
        //Parametros obligatorios para el envio al Web Service
        $soapURL                           = $array[5];
        $soapFunction                      = "RegistrarActividad";
        $soapParameters['codigoSence']     = $array[4];
        $soapParameters['rutAlumno']       = $array[1];
        $soapParameters['claveAlumno']     = $array[2];
        $soapParameters['rutOtec']         = $array[6];
        $soapParameters['claveOtec']       = $array[7];
        $soapParameters['estadoActividad'] = $input;

        //Envio de datos al Web Service de sence
        $soapClient = new SoapClient($soapURL);
        $soapResult = $soapClient->__soapCall($soapFunction, array($soapParameters));

        //Registra los logs de asistencias
        $insert           = new stdClass();
        $insert->courseid = $array[3];
        $insert->userid   = $array[0];
        $insert->result   = $soapResult->RegistrarActividadResult;
        $insert->date     = date('Y-m-d H:i:s');
        $insert->input    = ($input == 1)? 'input' : 'output' ;
        $lastinsertid     = $DB->insert_record('block_sence_log', $insert);
        
        return $soapResult->RegistrarActividadResult;
    }
}

$asistencia = new asistencia();
?>
