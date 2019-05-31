<?php

/**
 * .
 *
 * @package    block_newblock
 * @copyright  Producciones didactic ltda.
 */

defined('MOODLE_INTERNAL') || die();

class block_block_sence extends block_base
{
    function init()
    {   
        $this->title = get_string('titulo', 'block_block_sence');
    }

    function has_config()
    {
        return true;
    }
    
    public function instance_allow_multiple()
    {
          return true;
    }

    function applicable_formats()
    {
        return array('all' => true);
    }
    
    function get_content()
    {
        global $CFG, $OUTPUT, $USER, $DB, $SESSION;
        require_once('asistencia.php');
        
        $resultado = 0;
        
        if ($this->content !== null)
        {
            return $this->content;
        }

        if (empty($this->instance))
        {
            $this->content = '';
            return $this->content;
        }
        
        if($SESSION->sence != $this->page->course->id)
        {
            $SESSION->sence = '';
        }
        
        //Obtiene el rol del usuario
        $query    = "SELECT ra.roleid FROM mdlag_context c, mdlag_role_assignments ra WHERE c.id = ra.contextid AND c.instanceid = ? AND ra.userid = ?";
        $roleUser = $DB->get_record_sql($query, array($this->page->course->id, $USER->id));
        
        if ($this->page->course->id != SITEID && !empty($this->page->course->idnumber) && $roleUser->roleid == 5)
        {
            $SESSION->sence = $this->page->course->id;
        }
        
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
        $registro[3] = $this->page->course->id;
        $registro[4] = $this->page->course->idnumber;
        $registro[5] = $value['urlsence'];
        $registro[6] = $value['rutotec'];
        $registro[7] = $value['claveotec'];

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $this->content->text .= "<p><br />".$value['msjsence']."<br /></p>";
        
        // user/index.php expect course context, so get one if page has module context.
        $currentcontext = $this->page->context->get_course_context(false);
        
        //Pregunta si el curso no tiene codigo sence y si el usuario no es estudiante
        if ($this->page->course->id != SITEID && empty($this->page->course->idnumber) && $roleUser->roleid != 5)
        {
            //$this->content->text .= "<p>".get_string('curso_sin_clave', 'block_block_sence')."</p>";
            return $this->content;
        }
        
        //Clave sence del estudiante
        $claveSenceUser = trim($USER->profile['clavesence']);
        //echo $registro[1];

        //Cierra la asistencia del estudiante
        if(empty($SESSION->sence) || $this->page->course->id == SITEID)
        {
            //Obtiene el ultimo registro del estudiante
            $query = "SELECT * FROM mdlag_block_sence_log WHERE userid = ".$USER->id." AND 
                      id = (SELECT MAX(b.id) FROM mdlag_block_sence_log b WHERE b.userid = ".$USER->id.")";
            $dato  = $DB->get_record_sql($query);

            //Si el registro es input, registra el output
            if(empty($this->page->course->idnumber) && !empty($dato) && $dato->input == 'input')
            {
                //Pregunta si el usuario tiene clave sence
                if(!empty($claveSenceUser))
                {
                    $query         = "SELECT idnumber FROM mdlag_course WHERE id = ".$dato->courseid;
                    $idnumbervacio = $DB->get_record_sql($query);

                    $registro[3]   = $dato->courseid;
                    $registro[4]   = $idnumbervacio->idnumber;
                    $resultado     = $asistencia->registro($registro, 2);

                }else {
                    $resultado = 1;
                }
            }
        }
        
        //Inicia la asistencia del estudiante
        if($roleUser->roleid == 5 && $this->page->course->id != SITEID && !empty($this->page->course->idnumber))
        {
            if(empty($claveSenceUser)){
                $resultado = 1;
            }else{
                
                //Obtiene el ultimo registro del estudiante
                $query = "SELECT * FROM mdlag_block_sence_log WHERE userid = ".$USER->id." AND 
                          id = (SELECT MAX(b.id) FROM mdlag_block_sence_log b WHERE b.userid = ".$USER->id." AND b.courseid = ".$this->page->course->id.")";
                $dato  = $DB->get_record_sql($query);

                if(!empty($dato))
                {
                    //Fecha y hora del ultimo registro
                    $fecha = date("Y-m-d", strtotime($dato->date));
                    $hora  = date("H", strtotime($dato->date));

                    //Fecha y hora actual
                    $fechaactual = date('Y-m-d');
                    $horaactual  = date('H');

                    if($dato->input == 'output')
                    {
                        $resultado = $asistencia->registro($registro, 1);

                    //Si el ultimo registro no es igual a la fecha actual con un rango de 2 horas crea un registro nuevo
                    }else if($fecha == $fechaactual && $hora >= ($horaactual+2) && $hora <= ($horaactual-2)){
                        $resultado = $asistencia->registro($registro, 1);
                    }

                }else {
                    $resultado = $asistencia->registro($registro, 1);
                }
            }
        }
        
//        //Mensajes de error
       switch($resultado)
        {
           //El error 32, 41 y 43 deben mostrar el mensaje del error 41
           case  2: $this->content->text .= "<p>TEST</p>"; break;
           case 10: $this->content->text .= "<p>".get_string('error10', 'block_block_sence')."</p>"; break;
          case 32: $this->content->text .= "<p>".get_string('error41', 'block_block_sence')."</p>"; break;
          case 41: $this->content->text .= "<p>".get_string('error41', 'block_block_sence')."</p>"; break;
           case 43: $this->content->text .= "<p>".get_string('error41', 'block_block_sence')."</p>"; break;
           case 50: $this->content->text .= "<p>".get_string('error50', 'block_block_sence')."</p>"; break;
           case  1: $this->content->text .= "<p>".get_string('actualiza_clave_p1', 'block_block_sence')."</p><p><em>".get_string('actualiza_clave_p2', 'block_block_sence')."</em></p>"; break;
       }

        return $this->content;
    }
    
    function instance_config_save($data, $nolongerused = false)
    {
        global $DB;

        $config = clone($data);
        // Move embedded files into a proper filearea and adjust HTML links to match
        $config->text = file_save_draft_area_files($data->text['itemid'], $this->context->id, 'block_html', 'content', 0, array('subdirs'=>true), $data->text['text']);
        $config->format = $data->text['format'];

        parent::instance_config_save($config, $nolongerused);
    }
    
    function content_is_trusted()
    {
        global $SCRIPT;

        if(!$context = context::instance_by_id($this->instance->parentcontextid, IGNORE_MISSING))
        {
            return false;
        }
        //find out if this block is on the profile page
        if($context->contextlevel == CONTEXT_USER)
        {
            if($SCRIPT === '/my/index.php')
            {
                // this is exception - page is completely private, nobody else may see content there
                // that is why we allow JS here
                return true;
            }else {
                // no JS on public personal pages, it would be a big security issue
                return false;
            }
        }

        return true;
    }
    
    function html_attributes() {
        return array(
            'class'          => 'block_'. $this->name()." block",
            'onbeforeunload' => 'alert("Mouseover on our block!");'
        );
    }

    public function cron()
    {
        mtrace("Cron ejecutado correctamente");
        return true;
    }
}
