<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }	
    
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
	
    //verifica la recepcion de los datos para buscar y mostrar
    if (isset($_POST['cedula_rif'])){
        $cedula_rif=strtoupper($_POST['cedula_rif']);
    }else {
        $cedula_rif=strtoupper($_GET['cedula_rif']);
    } 
	
    if ($cedula_rif){  // consulta de los datos para Mostrar
        
        $cedula_rif_buscar = preg_replace("/\s+/", "", $cedula_rif);
        $cedula_rif_buscar = str_replace("-", "", $cedula_rif_buscar);
        
        // Verificar si existe el Registro
        $query="SELECT * FROM solicitantes,tipo_solicitantes WHERE solicitantes.cedula_rif='$cedula_rif_buscar' AND solicitantes.cod_tipo_solicitante=tipo_solicitantes.cod_tipo_solicitante order by cedula_rif";
        $result = pg_query($query) or die(pg_last_error());
        $total_result= pg_num_rows($result);	
        $resultados_solicitantes=pg_fetch_array($result);
        pg_free_result($result);
        
        if ($total_result){
            // Verificar si existe el Registro
            $query="SELECT * FROM ticket,tramites,unidades,estados_tramites". 
                    " WHERE ticket.cedula_rif='$cedula_rif_buscar' AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                    " AND ticket.cod_tramite=tramites.cod_tramite AND tramites.cod_unidad=unidades.cod_unidad  order by cod_ticket DESC";
            $result = pg_query($query) or die(pg_last_error());
            $total_result_ticket= pg_num_rows($result);	
        }


        if (isset($_POST[buscar]) AND ($total_result==0)){
            $div_menssage='<div align="left"><h3 class="error"><font size="2" style="text-decoration:blink;">La Cédula ó RIF: <font color="blue">'.$cedula_rif.'</font>; No Exite!</font></h3></div>';		
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
                IDENTIFICACI&Oacute;N DEL SOLICITANTE
            </div>

            <div class="panel-body">
                <!-- Formulario de la Busqueda -->
                <form method="POST" action="?view=solicitante_load_view" id="QForm" name="QForm" enctype="multipart/form-data">																				
                    <div class="form-group" autofocus="true">
                        <label>C&Eacute;DULA/RIF</label>
                        <? echo $_SESSION[nivel];?>
                        <input id="cedula_rif" name="cedula_rif"  class="form-control" type="text"  value="<?php if($total_result==0) echo $cedula_rif;?>"  size="10" maxlength="12" placeholder="Escriba la primera letra en Mayuscula"/>
                        <input class="btn btn-default" type="submit" id="buscar" name="buscar" value="CONTINUAR" />	
                        <?php 
                            if (isset($_POST[buscar]) AND ($total_result==0)  AND ($_SESSION[nivel]!=6)){
                                echo "<input  class=\"button\" type=\"button\" onclick=\"javascript:window.location.href='?view=solicitante_add&cedula_rif=$cedula_rif'\" value=\"AGREGAR REGISTRO\" name=\"cerrar\" /> ";
                            }
                        ?>
                    </div>			
                </form>
            </div>

<?php if ($total_result!=0 ){ ?>

            <div class="panel-body">
                <!-- Formulario de los datos encontrados -->
                <table class="table table-striped table-bordered table-hover" align="center">
                    <thead>
                        <label>DATOS DEL SOLICITANTE</label>
                        <tr>											
                            <th>C&Eacute;DULA/RIF</th>
                            <th>NOMBRE DEL SOLICITANTE</th>
                            <th>SEXO</th>											
                            <th>FECHA NATAL</th>
                            <th>TIPO SOLICITANTE</th>	
                            <th>TELÉFONO</th>
                            <th>CELULAR</th>
                            <th>EMAIL</th>										
                            <th>ACCIONES</th>											
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="gradeA"> 
                            <td align="center"><?php  echo substr_replace($resultados_solicitantes['cedula_rif'],'-',1,0); ?></td>
                            <td><font color="blue"><?php  echo $resultados_solicitantes['nombre_solicitante']; ?></font></td>													
                            <td align="center"><?php if($resultados_solicitantes['sexo_solicitante']==1)  echo "M"; elseif($resultados_solicitantes['sexo_solicitante']==2) echo "F"; else echo "N/A"; ?></td>
                            <td align="center"><?php echo implode('/',array_reverse(explode('-',$resultados_solicitantes['fecha_nac'])));?></td>
                            <td align="center"><?php  echo $resultados_solicitantes['descripcion_tipo_solicitante']; ?></td>			
                            <td align="center"><?php  echo $resultados_solicitantes['telefono_fijo']; ?></td>			
                            <td align="center"><?php  echo $resultados_solicitantes['telefono_movil']; ?></td>			
                            <td align="center"><?php  echo $resultados_solicitantes['email']; ?></td>			
                            <td align="center">
                                <?php if($_SESSION[nivel]!=1){ ?>
                                <a href="index2.php?view=solicitante_sms&cedula_rif=<?php echo $resultados_solicitantes[cedula_rif];?>" title="Pulse para Modificar los datos registrados">
                                    <img border="0" src="images/sms.png" alt="borrar">
                                </a>
                                <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=solicitante_drop&cedula_rif=<?php echo $resultados_solicitantes[cedula_rif];?>" title="Pulse para eliminar el registro">
                                    <img border="0" src="images/borrar28.png" alt="borrar">
                                </a>
                                <a href="index2.php?view=solicitante_update&cedula_rif=<?php echo $resultados_solicitantes[cedula_rif];?>" title="Pulse para Modificar los datos registrados">
                                    <img border="0" src="images/modificar.png" alt="borrar">
                                </a>	      
                                <a href="index2.php?view=solicitante_ticket_add&cedula_rif=<?php echo $resultados_solicitantes[cedula_rif];?>" title="Pulse para Registrar un Ticket">
                                    <img border="0" src="images/tramites28.png" alt="borrar">
                                </a>	      
                                <?php }else{ ?>     													     
                                <a href="index2.php?view=solicitante_update&cedula_rif=<?php echo $resultados_solicitantes[cedula_rif];?>" title="Pulse para Modificar los datos registrados">
                                    <img border="0" src="images/modificar.png" alt="borrar">
                                </a>	      
                                <?php } ?>
                            </td>												
                        </tr>
                    </tbody>
                </table>	
            </div>

<div class=""></div>

<div class="panel-body">
    <!-- Datos de la carga familia del registro Registro -->
    <table class="table table-striped table-bordered table-hover" align="center" id="dataTables-gestion">
        <thead>
            <tr>											
                <th>Nº TICKET</th>											
                <th>FECHA REGISTRO</th>																					
                <th>UNIDAD INICIAL ASIGNADA</th>
                <th>TRAMITE</th>
                <th>ESTADO</th>
                <th>ACCIONES</th>											
            </tr>
        </thead>

<?php
    if($total_result_ticket==0){
        echo '<tr class="item_oscuro">';		
        echo '<td align="center" colspan="8"> NO EXISTEN REGISTROS DE TAC</td>';
        echo '</tr>';
    }
    $xxx=0;
    while($resultados_ticket = pg_fetch_array($result)) {	
        $xxx=$xxx+1;			
        if (($xxx %2)==0) $i='item_claro'; else $i='item_oscuro';
?>
        
        <tbody>
            <tr class="<?php echo $i;?>">		
                <td align="center">
                    <?php
                        if($resultados_ticket[prioridad_ticket]==1){
                            echo '<font color="black">'.str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT).'</font>';
                        }elseif($resultados_ticket[prioridad_ticket]==2){
                            echo '<font color="ffba00">'.str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT).'</font>';
                        }else{
                            echo '<font color="red">'.str_pad($resultados_ticket['cod_ticket'],10,"0",STR_PAD_LEFT).'</font>';
                        }
                        
                    ?>
                </td>

                <td align="center">
                    <?php echo date_format(date_create($resultados_ticket['fecha_registro']), 'd/m/Y g:i A.') ;?> 
                </td>

                <td>
                    <?php echo $resultados_ticket['nombre_unidad']?> 
                </td>
                					
                <td>
                    <?php echo $resultados_ticket['nombre_tramite']?> 
                </td>
                
                <td>
                    <?php
                        if ($resultados_ticket['tipo_estado_tramite']==1){
                            echo '<img  border="0" name="Image_Encab" src="images/help.png" title="'.$resultados_ticket['descripcion_estado_tramite'].'">';
                        }elseif ($resultados_ticket['tipo_estado_tramite']==2){
                            echo '<img  border="0" name="Image_Encab" src="images/tick.png" title="'.$resultados_ticket['descripcion_estado_tramite'].'">';
                        }elseif ($resultados_ticket['tipo_estado_tramite']==3 && $resultados_ticket['siglas_estado_tramite']=="NUL" ){
                            echo '<img  border="0" name="Image_Encab" src="images/borrar.png" title="'.$resultados_ticket['descripcion_estado_tramite'].'">';
                        }else{
                            echo '<img  border="0" name="Image_Encab" src="images/delete.png" title="'.$resultados_ticket['descripcion_estado_tramite'].'">';
                        }
                        echo ' '.$resultados_ticket['siglas_estado_tramite']
                    ?> 
                </td>

                <td align="center">	
                    <a title="Gestionar Ticket" href="?view=gestion_tickets&cod_ticket=<?php echo $resultados_ticket['cod_ticket']?>">
                    <img  border="0" name="Image_Encab" src="images/gestion_ticket28.png">
                    </a>
                    <a title="Imprimir Ticket" target="_Blank" href="reportes/imprimir_tac.php?cod_ticket=<?php echo $resultados_ticket['cod_ticket']?>&user=<?php echo $_SESSION[user]?>">
                    <img  border="0" name="Image_Encab" src="images/printer28.png">
                    </a>
                    											
                </td>												
            </tr>
        </tbody>

        <?php } ?>

    </table>
<br>
<br>
<?php } ?>	

<!-- Bootstrap Core JavaScript -->
<script src="vendor/js/jquery.js"></script>
<script src="vendor/maskedinput/jquery.maskedinput.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="vendor/metisMenu/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="vendor/datatables-responsive/dataTables.responsive.js"></script>

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

<script type="text/javascript" >
    jQuery(function($) {
          $.mask.definitions['~']='[JEVGDCH]';
          //$('#fecha_nac').mask('99/99/9999');
          //$('#fecha_deposito').mask('99/99/9999');
          $('#telefono').mask('(9999)-9999999');
          $('#celular').mask('(9999)-9999999');
          $('#telefono_trabajo').mask('(9999)-9999999');
          $('#telefono_fax').mask('(9999)-9999999');
          $('#rif').mask('~-9999?9999-9',{placeholder:" "});
          $('#cedula_rif').mask('~-9999?99999',{placeholder:" "});
          //$('#phoneext').mask("(999) 999-9999? x99999");
          //$("#tin").mask("99-9999999");
          //$("#ssn").mask("999-99-9999");
          //$("#product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("Ha escrito lo siguiente: "+this.val());}});
          //$("#eyescript").mask("~9.99 ~9.99 999");
          
       });  
</script> 				