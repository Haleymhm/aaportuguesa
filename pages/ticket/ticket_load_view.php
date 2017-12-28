<?php
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }	

    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
	
    //verifica la recepcion de los datos para buscar y mostrar
    if (isset($_POST[buscar])){
        $cod_ticket=intval($_POST['cod_ticket']);
    }

    if (isset($_GET['cod_ticket'])){
        $cod_ticket=intval($_GET['cod_ticket']);
    } 
    
    if ($cod_ticket){  // consulta de los datos para Mostrar
        if($_SESSION['nivel']==1){
            $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades,categorias". 
                " WHERE ticket.cod_ticket='$cod_ticket'  AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND ticket.cedula_rif=solicitantes.cedula_rif AND ticket.cod_tramite=tramites.cod_tramite ".
                " AND tramites.cod_categoria=categorias.cod_categoria AND tramites.cod_unidad=unidades.cod_unidad";
            
            $result = pg_query($query)or die(pg_last_error());
            $total_result_ticket= pg_num_rows($result);
            $resultados_ticket=pg_fetch_array($result);	
            pg_free_result($result);

            if ($resultados_ticket[0]){
                if($resultados_ticket[cod_unidad]==$_SESSION[cod_unidad]){
                    $total_result_ticket_unidad_inicial=1;
                    if($resultados_ticket[cod_subticket]!=""){
                        $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                            " WHERE detalles_ticket.cod_detalle_ticket='$resultados_ticket[cod_subticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                            " AND detalles_ticket.cod_unidad=unidades.cod_unidad";
                        $result = pg_query($query)or die(pg_last_error());
                        $resultados_subticket=pg_fetch_array($result);	
                        pg_free_result($result);


                        $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                                " WHERE detalles_ticket.cod_ticket='$cod_ticket' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                                " AND detalles_ticket.cod_unidad=unidades.cod_unidad ORDER BY cod_detalle_ticket";
                        $result_detalle_ticket = pg_query($query)or die(pg_last_error());
                        $total_result_detalle_ticket= pg_num_rows($result_detalle_ticket);
                    }else{
                        $total_result_detalle_ticket=0;
                        $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; Fue Registrado en Linea! En espera por Asignación</font></h3></div>';
                    }
                
                }else{
                    $total_result_ticket_unidad_inicial=0;
                    if($resultados_ticket[cod_subticket]!=""){

                            $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                                " WHERE detalles_ticket.cod_detalle_ticket='$resultados_ticket[cod_subticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                                " AND detalles_ticket.cod_unidad=unidades.cod_unidad";
                            $result = pg_query($query)or die(pg_last_error());
                            $resultados_subticket=pg_fetch_array($result);	
                            pg_free_result($result);
                            
                            if($resultados_subticket[cod_unidad]==$_SESSION[cod_unidad]){
                                $total_result_ticket_unidad_encargada=1;
                            }else{
                                $total_result_ticket_unidad_encargada=0;
                            }
                            $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                                    " WHERE detalles_ticket.cod_ticket='$resultados_ticket[cod_ticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                                    " AND detalles_ticket.cod_unidad=unidades.cod_unidad ORDER BY cod_detalle_ticket";
                            $result_detalle_ticket = pg_query($query)or die(pg_last_error());
                            $total_result_detalle_ticket= pg_num_rows($result_detalle_ticket);
                    }else{
                        $total_result_detalle_ticket=0;
                        $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; Fue Registrado en Linea! En espera por Asignación</font></h3></div>';
                    } 
                }
            }

            if (isset($_POST[submit])){
                if($total_result_ticket==0){
                    $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; No Exite Registrado! </font></h3></div>';		
                }else{
                    if($total_result_ticket_unidad_inicial==0 AND $total_result_ticket_unidad_encargada==0 ){
                        $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; No Exite Asignado a la Unidad! </font></h3></div>';		
                    }
                }
            } 
        }else{
            $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades,categorias". 
                " WHERE ticket.cod_ticket='$cod_ticket'  AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND ticket.cedula_rif=solicitantes.cedula_rif AND ticket.cod_tramite=tramites.cod_tramite ".
                " AND tramites.cod_categoria=categorias.cod_categoria AND tramites.cod_unidad=unidades.cod_unidad";
            
            $result = pg_query($query)or die(pg_last_error());
            $total_result_ticket= pg_num_rows($result);
            $resultados_ticket=pg_fetch_array($result);	
            pg_free_result($result);

            if ($resultados_ticket[0]){
        		$total_result_ticket_unidad_inicial=1;
        		$total_result_ticket_unidad_encargada=1;
                if($resultados_ticket[cod_subticket]!=""){
                    $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                        " WHERE detalles_ticket.cod_detalle_ticket='$resultados_ticket[cod_subticket]' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                        " AND detalles_ticket.cod_unidad=unidades.cod_unidad";
                    $result = pg_query($query)or die(pg_last_error());
                    $resultados_subticket=pg_fetch_array($result);	
                    pg_free_result($result);


                    $query="SELECT * FROM detalles_ticket,estados_tramites,unidades". 
                            " WHERE detalles_ticket.cod_ticket='$cod_ticket' AND detalles_ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                            " AND detalles_ticket.cod_unidad=unidades.cod_unidad ORDER BY cod_detalle_ticket";
                    $result_detalle_ticket = pg_query($query)or die(pg_last_error());
                    $total_result_detalle_ticket= pg_num_rows($result_detalle_ticket);
                    
                }else{
                    $total_result_detalle_ticket=0;
                    $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; Fue Registrado en Linea! En espera por Asignación</font></h3></div>';  
                }
            }

            if (isset($_POST[submit]) AND ($total_result_ticket==0)){
                $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">El Numero del Ticket: <font color="blue">'.$cod_ticket.'</font>; No Exite Registrado!</font></h3></div>';		
            }
        }
    }			
?>

<!-- sincronizar mensaje cuando de muestra al usuario -->
<?php if($div_menssage) { ?>					
	<script type="text/javascript">
		function ver_msg(){
		 	Effect.Fade('msg');
		}  
		setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
	</script>
 <?php } ?>	

 <!--aqui es donde esta el diseño del formulario-->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div align="center">
                <font color="red" style="text-decoration:blink;">
                    <?php $error_accion_ms[$error_cod]?>
                </font>
            </div>  

            <div class="panel-heading">
                GESTIÓN DEL TICKET
            </div>

            <div class="panel-body">
                <!-- Formulario de la Busqueda -->
                <form method="POST" action="?view=gestion_tickets" id="QForm" name="QForm" enctype="multipart/form-data">	
                    <div class="form-group" autofocus="true">
                        <label>NRO. TICKET</label>
                        <input id="cod_ticket" autofocus="true" name="cod_ticket"  class="form-control" type="text"  value="<?php echo $cod_ticket;?>"/>
                    </div>
                    <input class="btn btn-default" type="submit" name="buscar" value="CONTINUAR" />
                </form>
            </div>

<?php 
if ($total_result_ticket!=0 AND $total_result_ticket_unidad_inicial!=0 OR $total_result_ticket_unidad_encargada!=0 )
{ 
?>

            <div class="panel-body">
                <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2>INF TICKET N: <?php echo str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT);?></h2>
                            <?php
                                if($resultados_ticket['prioridad_ticket']==1){
                                    echo '<label class="radio-inline">
                                            <input id="prioridad" name="prioridad" value="1" disabled="true" checked="true" type="radio"> NORMAL
                                        </label>

                                        <label class="radio-inline">
                                            <input id="prioridad" name="prioridad" value="2" disabled="true"  type="radio"> <font color="ffd200">ALTA</font>
                                        </label>

                                        <label class="radio-inline">
                                            <input id="prioridad" name="prioridad" value="3" disabled="true" type="radio"> <font color="Red">URGENTE</font>
                                        </label>';
                                }elseif($resultados_ticket['prioridad_ticket']==2){
                                    echo '<label class="radio-inline">
                                            <input id="prioridad" name="prioridad" value="1" disabled="true" type="radio"> NORMAL
                                        </label>

                                        <label class="radio-inline">
                                            <input id="prioridad" name="prioridad" value="2"  disabled="true" checked="true" type="radio"><font color="ffd200">ALTA</font>
                                        </label>

                                        <label class="radio-inline">
                                            <input id="prioridad" name="prioridad" value="3" disabled="true" type="radio"><font color="Red">URGENTE</font>
                                        </label>';
                                }else{
                                    echo '<label class="radio-inline">
                                            <input id="prioridad" name="prioridad" value="1" disabled="true" type="radio"> NORMAL
                                        </label>

                                        <label class="radio-inline">
                                            <input id="prioridad" name="prioridad" value="2" disabled="true"  type="radio"><font color="ffd200">ALTA</font>
                                        </label>

                                        <label class="radio-inline">
                                            <input id="prioridad" name="prioridad" value="3" disabled="true" checked="true" type="radio"><font color="Red">URGENTE</font>
                                        </label>';
                                }
                            ?>
                            <label>FECHA EMISIÓN: </label>
                            <label>
                                <?php echo date_format(date_create($resultados_ticket['fecha_registro_ticket']), 'd/m/Y');?>
                            </label>

                            <div class="form-group">
                                <label>PERSONA CONTACTO / DEP.</label>
                                <input disabled type="text" id="persona_contacto_dep" name="persona_contacto_dep" value="<?php echo $resultados_ticket[persona_contacto_dep];?>" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label>TIPO DE TRAMITE</label>
                                <input disabled type="text" id="nombre_tramite" name="nombre_tramite" value="<?php echo $resultados_ticket[nombre_tramite];?>" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label>DESCRIPCION DEL TRAMITE</label>
                                <textarea disabled name="descripcion_ticket" id="descripcion_ticket" class="form-control" rows="5" ><?php echo $resultados_ticket[descripcion_ticket];?></textarea>
                            </div>

                            <div class="form-group">
                                <label>MONTO DE SOLICITUD</label>
                                <input style="text-align:right" class="form-control" disabled type="text" id="monto_solicitud"  name="monto_solicitud" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="<?php echo $resultados_ticket[monto_solicitud];?>" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h2>INF SOLICITANTE</h2>
                            <div class="form-group">
                                <label>C&Eacute;DULA / RIF</label>
                                <input size="10" disabled type="text" name="cedula1" class="form-control" value="<?php echo substr_replace($resultados_ticket['cedula_rif'],'-',1,0);?>" /> 
                            </div>

                            <div class="form-group">
                                <label>NOMBRE DEL SOLICITANTE</label>
                                <input disabled type="text" id="nombreapellido" name="nombreapellido" value="<?php echo $resultados_ticket[nombre_solicitante];?>" class="form-control" size="50" maxlength="50"/>
                            </div>

                            <div class="form-group">
                                <label>TELEFONOS DEL SOLICITANTE</label>
                                <input disabled type="text" id="nombreapellido" name="nombreapellido" value="<?php echo $resultados_ticket[telefono_movil]; echo " "; echo $resultados_ticket[telefono_fijo];?>" class="form-control" size="50" maxlength="50"/>
                            </div>

                            <div class="form-group">
                                <label>DIRECCION DEL SOLICITANTE</label>
                                <textarea disabled name="descripcion_ticket" id="descripcion_ticket" class="form-control" rows="3" ><?php echo $resultados_ticket[direccion_habitacion];?></textarea>
                            </div>

                            <div class="form-group">
                                <label>UNIDAD INICIAL ASIGNADA</label>
                                <input disabled type="text" id="nombre_unidad" name="nombre_unidad" value="<?php echo $resultados_ticket[nombre_unidad];?>" class="form-control"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <h2>Ultima actuación del ticket: <?php echo date_format(date_create($resultados_subticket['fecha_registro_update']), 'd/m/Y');?></h2>

                            <div class="form-group">
                                <label>ULTIMA UNIDAD ENCARGADA</label>
                                <input disabled  type="text" id="nombre_unidad_ult" name="nombre_unidad_ult" value="<?php echo $resultados_subticket[nombre_unidad];?>" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label>ESTADO DEL TICKET</label>
                                <input disabled type="text" id="estado_ticket" name="estado_ticket" value="<?php echo $resultados_subticket[descripcion_estado_tramite];?>" class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label>FECHA CITA</label>
                                <input type="text" disabled id="fecha_cita_programada" name="fecha_cita_programada" value="<?php if ($resultados_subticket['fecha_cita_programada']==NULL) echo ''; else echo date_format(date_create($resultados_subticket['fecha_cita_programada']), 'd/m/Y');?>"  size="10" /> 
                                <label>HORA PROGRAMADA</label>
                                <input type="text" disabled id="hora_cita_programada" name="hora_cita_programada" value="<?php if ($resultados_subticket['hora_cita_programada']==NULL) echo ''; else echo date_format(date_create($resultados_subticket['hora_cita_programada']), 'g:i A.');?>"  size="10" /> 
                                <label>FECHA ATENCIÓN</label>
                                <input type="text" disabled id="fecha_atencion" name="fecha_atencion" value="<?php if ($resultados_subticket['fecha_atencion']==NULL) echo ''; else echo date_format(date_create($resultados_subticket['fecha_atencion']), 'd/m/Y');?>"  size="10" />
                                <label>HORA ATENCIÓN</label>
                                <input type="text" disabled id="hora_atencion" name="hora_atencion" value="<?php if ($resultados_subticket['hora_atencion']==NULL) echo ''; else echo date_format(date_create($resultados_subticket['hora_atencion']), 'g:i A.');?>"  size="10" />
                            </div>

                            <div class="form-group">
                                <label>SITUACIÓN ACTUAL</label>
                                <textarea disabled name="situacion_actual" id="situacion_actual" cols="84" rows="2" class="form-control"><?php echo $resultados_subticket[situacion_actual];?></textarea>
                            </div>

                            <div class="form-group">
                                <label>ACTUACIÓN</label>
                                <textarea disabled name="actuacion" id="actuacion" cols="84" rows="2"  class="form-control"><?php echo $resultados_subticket[actuacion];?></textarea>
                            </div>

                            <div class="form-group">
                                <label>MONTO AUTORIZADO</label>
                                <input  style="text-align:right" disabled type="text" id="monto_autorizado" class="form-control" name="monto_autorizado" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="<?php echo $resultados_subticket[monto_autorizado];?>" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                            </div>

                            <div class="form-group">
                                <label>OBSERVACIONES</label>
                                <textarea disabled name="observaciones" id="observaciones" cols="84" rows="2" class="form-control"><?php echo $resultados_subticket[observaciones];?></textarea>
                            </div>

                            <div class="form" align="right">
                                <?php
                                    switch ($resultados_ticket[cod_estado_tramite]){
                                    case 1: // Administrador
                                    echo '<a href="index2.php?view=ticket_status_update&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Programar Ticket">
                                                <img border="0" src="images/tramites28.png" alt="borrar">
                                            </a>
                                            <a href="index2.php?view=ticket_status_escalar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Escalar Ticket">
                                                <img border="0" src="images/escalar_ticket.png" alt="borrar">
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="index2.php?view=ticket_status_completar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Completar Ticket">
                                                <img border="0" src="images/completar_ticket2.png" alt="borrar">
                                            </a>
                                            <a onclick="return confirm(\'Esta seguro que desea Cancelar el Ticket?\');" href="index2.php?view=ticket_status_cancelar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Cancelar Ticket">
                                                <img border="0" src="images/cancelar_ticket.png" alt="borrar">
                                            </a>
                                            <a onclick="return confirm(\'Esta seguro que desea Anular el Ticket?\');" href="index2.php?view=ticket_status_anular&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Anular Ticket">
                                                <img border="0" src="images/borrar28.png" alt="borrar">
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="index2.php?view=ticket_update&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Editar los datos del Ticket">
                                                <img border="0" src="images/modificar.png" alt="borrar">
                                            </a>';
                                    break;			
                                    case 2: // Administrador
                                    echo '<a href="index2.php?view=ticket_status_update&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Programar Ticket">
                                                <img border="0" src="images/tramites28.png" alt="borrar">
                                            </a>
                                            <a href="index2.php?view=ticket_status_escalar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Escalar Ticket">
                                                <img border="0" src="images/escalar_ticket.png" alt="borrar">
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="index2.php?view=ticket_status_completar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Completar Ticket">
                                                <img border="0" src="images/completar_ticket2.png" alt="borrar">
                                            </a>
                                            <a onclick="return confirm(\'Esta seguro que desea Cancelar el Ticket?\');" href="index2.php?view=ticket_status_cancelar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Cancelar Ticket">
                                                <img border="0" src="images/cancelar_ticket.png" alt="borrar">
                                            </a>
                                            <a onclick="return confirm(\'Esta seguro que desea Anular el Ticket?\');" href="index2.php?view=ticket_status_anular&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Anular Ticket">
                                                <img border="0" src="images/borrar28.png" alt="borrar">
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="index2.php?view=ticket_update&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Editar los datos del Ticket">
                                                <img border="0" src="images/modificar.png" alt="borrar">
                                            </a>';
                                    break;			
                                    case 3: // Administrador
                                    echo '<a href="index2.php?view=ticket_status_escalar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Escalar Ticket">
                                                <img border="0" src="images/escalar_ticket.png" alt="borrar">
                                            </a>
                                            <a href="index2.php?view=ticket_status_reprogramar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Reprogramar Ticket">
                                                <img border="0" src="images/reprogramar28.png" alt="borrar">
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="index2.php?view=ticket_status_completar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Completar Ticket">
                                                <img border="0" src="images/completar_ticket2.png" alt="borrar">
                                            </a>
                                            <a onclick="return confirm(\'Esta seguro que desea Cancelar el Ticket?\');" href="index2.php?view=ticket_status_cancelar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Cancelar Ticket">
                                                <img border="0" src="images/cancelar_ticket.png" alt="borrar">
                                            </a>
                                            <a onclick="return confirm(\'Esta seguro que desea Anular el Ticket?\');" href="index2.php?view=ticket_status_anular&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Anular Ticket">
                                                <img border="0" src="images/borrar28.png" alt="borrar">
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;';
                                    break;			
                                    case 4: // Administrador
                                    echo '<a href="index2.php?view=ticket_status_escalar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Escalar Ticket">
                                                <img border="0" src="images/escalar_ticket.png" alt="borrar">
                                            </a>
                                            <a href="index2.php?view=ticket_status_reprogramar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Reprogramar Ticket">
                                                <img border="0" src="images/reprogramar28.png" alt="borrar">
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="index2.php?view=ticket_status_completar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Completar Ticket">
                                                <img border="0" src="images/completar_ticket2.png" alt="borrar">
                                            </a>
                                            <a onclick="return confirm(\'Esta seguro que desea Cancelar el Ticket?\');" href="index2.php?view=ticket_status_cancelar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Cancelar Ticket">
                                                <img border="0" src="images/cancelar_ticket.png" alt="borrar">
                                            </a>
                                            <a onclick="return confirm(\'Esta seguro que desea Anular el Ticket?\');" href="index2.php?view=ticket_status_anular&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Anular Ticket">
                                                <img border="0" src="images/borrar28.png" alt="borrar">
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;';
                                    break;			
                                    case 5: // Administrador
                                    echo '<a href="index2.php?view=ticket_status_update&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Programar Ticket">
                                                <img border="0" src="images/tramites28.png" alt="borrar">
                                            </a>
                                            <a href="index2.php?view=ticket_status_escalar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Escalar Ticket">
                                                <img border="0" src="images/escalar_ticket.png" alt="borrar">
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="index2.php?view=ticket_status_completar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Completar Ticket">
                                                <img border="0" src="images/completar_ticket2.png" alt="borrar">
                                            </a>
                                            <a onclick="return confirm(\'Esta seguro que desea Cancelar el Ticket?\');" href="index2.php?view=ticket_status_cancelar&cod_ticket='.$resultados_ticket[cod_ticket].'"  title="Cancelar Ticket">
                                                <img border="0" src="images/cancelar_ticket.png" alt="borrar">
                                            </a>
                                            <a onclick="return confirm(\'Esta seguro que desea Anular el Ticket?\');" href="index2.php?view=ticket_status_anular&cod_ticket='.$resultados_ticket[cod_ticket].'" title="Anular Ticket">
                                                <img border="0" src="images/borrar28.png" alt="borrar">
                                            </a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;';
                                    break;   
                                    }
                                    
                                    if($resultados_ticket[name_file_upload]!=""){
                                        $dir_upload='upload_file/'; // Nombre del Directorio de las subidas de archivos
                                        $archivo = $dir_upload.$resultados_ticket[name_file_upload];														
                                        if (file_exists($archivo)){
                                            echo '<a href="'.$archivo.'" download="'.$archivo.'" target="_blank" title="Descargar Archivo para Visualizar">
                                                    <img src="images/download28.png" name="Image_Encab"  border="0"/>
                                                </a>';
                                        }
                                        
                                    }
                                    
                                    echo '<a title="Imprimir Ticket" target="_Blank" href="reportes/imprimir_tac.php?cod_ticket='.$resultados_ticket[cod_ticket].'&user='.$_SESSION[user].'">
                                            <img  border="0" name="Image_Encab" src="images/printer28.png">
                                        </a>
                                        &nbsp;&nbsp;';
                                    
                                ?>
                            </div>
                        </div>
                    </div>
                <
            </div>           
                
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-gestion">
                    <thead>
                        <label>DETALLES DE LAS ACTUACIONES DEL TICKET</label>
                        </tr>

                        <tr>											
                            <th>ASIGNACÓN</th>											
                            <th>ESTADO</th>							
                            <th>UNIDAD ENCARGADA</th>
                            <th>CITA</th>
                            <th>ATENCIÓN</th>
                            <th>ACCIÓN</th>											
                        </tr>
                    </thead>
<?php
    if($total_result_detalle_ticket==0){
        echo '<h1>NO EXISTEN REGISTROS DE ACTUACION DEL TAC</h1>';
    }
    $xxx=0;
    while($resultados_detalle_ticket = pg_fetch_array($result_detalle_ticket)) {	
        $xxx=$xxx+1;			
        if (($xxx %2)==0) $i='item_claro'; else $i='item_oscuro';
?>

                    <tbody>
                        <tr class="<?php echo $i;?>">		
                            <td align="center">
                                <?php echo date_format(date_create($resultados_detalle_ticket['fecha_registro']), 'd/m/Y') ;?> 
                            </td>

                            <td>
                                <?php
                                    if ($resultados_detalle_ticket['tipo_estado_tramite']==1){
                                        echo '<img  border="0" name="Image_Encab" src="images/help.png" title="'.$resultados_detalle_ticket['descripcion_estado_tramite'].'" >';
                                    }elseif ($resultados_detalle_ticket['tipo_estado_tramite']==2){
                                        echo '<img  border="0" name="Image_Encab" src="images/tick.png" title="'.$resultados_detalle_ticket['descripcion_estado_tramite'].'">';
                                    }elseif ($resultados_detalle_ticket['tipo_estado_tramite']==3 && $resultados_detalle_ticket['siglas_estado_tramite']=="NUL" ){
                                        echo '<img  border="0" name="Image_Encab" src="images/borrar.png" title="'.$resultados_detalle_ticket['descripcion_estado_tramite'].'">';
                                    }else{
                                        echo '<img  border="0" name="Image_Encab" src="images/delete.png" title="'.$resultados_detalle_ticket['descripcion_estado_tramite'].'">';
                                    }
                                    echo ' '.$resultados_detalle_ticket['siglas_estado_tramite']
                                ?> 
                            </td>
                            					
                            <td>
                                <?php echo $resultados_detalle_ticket['nombre_unidad']?> 
                            </td>

                            <td align="center">
                                <?php 
                                    if ($resultados_detalle_ticket['fecha_cita_programada']==NULL){
                                        echo '';
                                    }else{
                                        echo date_format(date_create($resultados_detalle_ticket['fecha_cita_programada']), 'd/m/Y').' '.date_format(date_create($resultados_detalle_ticket['hora_cita_programada']), 'g:i A.') ;
                                    }
                                ?> 
                            </td>

                            <td align="center">
                                <?php 
                                    if ($resultados_detalle_ticket['fecha_atencion']==NULL){
                                        echo '';
                                    }else{
                                        echo date_format(date_create($resultados_detalle_ticket['fecha_atencion']), 'd/m/Y').' '.date_format(date_create($resultados_detalle_ticket['hora_atencion']), 'g:i A.') ;
                                    }
                                ?> 
                            </td>

                            <td align="center">
                                <a class="fancybox fancybox.iframe" href="pages/ticket/ticket_detalle_view.php?cod_detalle_ticket=<?php echo $resultados_detalle_ticket[cod_detalle_ticket];?>"  title="Ver Detalles del Registro">
                                    <img  border="0" name="Image_Encab" src="images/ver_detalle.png">			
                                </a>
                            </td>												
                        </tr>
                    </tbody>
                
<?php } ?>

                </table>
            </div>
        </div>
    </div>
</div>

<?php } ?> 

<!-- jQuery -->
<script src="vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="vendor/metisMenu/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>
			    				
<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script>
$(document).ready(function() {
    $('#dataTables-gestion').DataTable({
        responsive: true
    });
});
</script>

<?php
pg_free_result($result);
pg_close($db_conexion);
?>