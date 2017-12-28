<?php
// chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "") {
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
 
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$type;

    //Conexion a la base de datos
    include("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    $year=date("Y");
    $tipo_estado_tramite=1;
    $unidad_inicial=1;
    if (isset($_POST['year'])){
        $year=$_POST['year'];	
        $tipo_estado_tramite=$_POST['tipo_estado_tramite'];	
        $unidad_inicial=$_POST['unidad_inicial'];
        $cod_unidad=$_POST['cod_unidad'];
    }

    if($_SESSION['nivel']==0){
        $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades". 
            " WHERE date_part('year',ticket.fecha_registro)= '$year' AND  ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite".
            " AND estados_tramites.tipo_estado_tramite::text like  '%$tipo_estado_tramite%' AND ticket.cedula_rif=solicitantes.cedula_rif ".
            " AND ticket.cod_tramite=tramites.cod_tramite AND tramites.cod_unidad::text like  '%$cod_unidad%' AND tramites.cod_unidad=unidades.cod_unidad";
        $result = pg_query($query)or die(pg_last_error());
    }elseif($_SESSION['nivel']==1){
        $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades". 
            " WHERE date_part('year',ticket.fecha_registro)= '$year' AND  ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite".
            " AND estados_tramites.tipo_estado_tramite::text like  '%$tipo_estado_tramite%' AND ticket.cedula_rif=solicitantes.cedula_rif ".
            " AND ticket.cod_tramite=tramites.cod_tramite AND tramites.cod_unidad::text like  '%$cod_unidad%' AND tramites.cod_unidad=unidades.cod_unidad";
        $result = pg_query($query)or die(pg_last_error());
    }elseif($_SESSION['nivel']==4){
        $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades". 
            " WHERE date_part('year',ticket.fecha_registro)= '$year' AND  ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite".
            " AND estados_tramites.tipo_estado_tramite::text like  '%$tipo_estado_tramite%' AND ticket.cedula_rif=solicitantes.cedula_rif ".
            " AND ticket.cod_tramite=tramites.cod_tramite AND tramites.cod_unidad::text like  '%$cod_unidad%' AND tramites.cod_unidad=unidades.cod_unidad";
        $result = pg_query($query)or die(pg_last_error());
    }else{
        if ($unidad_inicial==1){
            $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket FROM ticket,tramites,solicitantes,estados_tramites,unidades". 
                " WHERE date_part('year',ticket.fecha_registro)= '$year' AND  ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite".
                " AND estados_tramites.tipo_estado_tramite::text like  '%$tipo_estado_tramite%' ".
                " AND ticket.cod_tramite=tramites.cod_tramite AND tramites.cod_unidad=unidades.cod_unidad".
                " AND unidades.cod_unidad='$_SESSION[cod_unidad]' AND ticket.cedula_rif=solicitantes.cedula_rif";
            $result = pg_query($query)or die(pg_last_error());
        }else{
            $query="SELECT *, ticket.fecha_registro AS fecha_registro_ticket,  detalles_ticket.fecha_registro AS fecha_registro_detalles_ticket FROM ticket,tramites,unidades,solicitantes,detalles_ticket,estados_tramites WHERE".
                    " ticket.cod_tramite=tramites.cod_tramite AND  tramites.cod_unidad=unidades.cod_unidad AND ticket.cedula_rif=solicitantes.cedula_rif AND ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite  AND estados_tramites.tipo_estado_tramite::text like  '%$tipo_estado_tramite%'".
                    " AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket  AND tramites.cod_unidad<>detalles_ticket.cod_unidad AND detalles_ticket.cod_unidad='$_SESSION[cod_unidad]' AND date_part('year',ticket.fecha_registro)= '$year'";
            $result = pg_query($query)or die(pg_last_error());
        }
    } 

if (!isset($_GET['accion']))
{
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div align="center">
                <font color="red" style="text-decoration:blink;">
                    <?php $error_accion_ms[$error_cod]?>
                </font>
            </div> 

            <div class="panel-heading">
                SOLICITUDES
            </div>

            <form name="ticket_load" method="POST" action="" enctype="multipart/form-data"> 
                <div class="col-lg-4">              
                    <div class="form-group">
                        <label>AÑO:</label>   
                        <select name="year" id="year" onchange="javascript: submit_ticket_load();" class="form-control">
                            <?php 
                            $consulta_sql=pg_query("SELECT date_part('year',fecha_registro) AS year FROM ticket  group by date_part('year',fecha_registro) order by date_part('year',fecha_registro) DESC");                                
                            while ($array_consulta=pg_fetch_array($consulta_sql)){
                                if ($year!=""){
                                    if ($array_consulta[0]==$year){
                                        echo '<option value="'.$array_consulta[0].'" selected="selected">'.$array_consulta[0].'</option>';
                                    }else {
                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[0].'</option>';
                                    }
                                }else {
                                    echo '<option value="'.$array_consulta[0].'">'.$array_consulta[0].'</option>';
                                }
                            }
                            pg_free_result($consulta_sql);                                  
                            ?>
                        </select> 
                    </div> 
                </div>      

                    
                <div class="col-lg-4">
<?php if(($_SESSION['nivel']==0) OR ($_SESSION['nivel']==1) OR ($_SESSION['nivel']==4)){ ?>
                    <div class="form-group">
                        <label>Unidad/Dependencia:</label>  
                        <select name="cod_unidad" id="cod_unidad" class="form-control" onchange="javascript: submit_ticket_load();">
                            <option selected="selected" value="">TODAS LAS UNIDADES/DEPENDENCIAS</option>
                            <?php 
                                $consulta_sql=pg_query("SELECT * FROM unidades where status_unidad=1 order by nombre_unidad");
                                while ($array_consulta=pg_fetch_array($consulta_sql)){
                                    if ($cod_unidad==$array_consulta[0]){ 
                                        echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';
                                    }else{
                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';
                                    }
                                }                                                                                                                                                       
                                pg_free_result($consulta_sql);                              
                            ?>              
                        </select> 
                    </div>

<?php }else{ ?>

                    <div class="form-group">
                        <label>Asignación Unidad:</label>    
                        <select name="unidad_inicial" id="unidad_inicial" class="form-control" onchange="javascript: submit_ticket_load();" >
                            <?php 
                                if ($unidad_inicial!=""){
                                    if ($unidad_inicial==1){
                                        echo '<option value="1" selected="selected">INICIAL</option>';
                                        echo '<option value="2" >ESCALADA</option>';
                                    }else {
                                        echo '<option value="1" >INICIAL</option>';
                                        echo '<option value="2" selected="selected">ESCALADA</option>';
                                    }
                                }else {
                                    echo '<option value="1" selected="selected">INICIAL</option>';
                                    echo '<option value="2" >ESCALADA</option>';
                                }
                            ?>
                        </select> 
                    </div>

<?php }?>

                </div> 
                    <div class="form-group col-lg-4">
                        <label>ESTADO DEL TICKET:</label>
                        <select name="tipo_estado_tramite" id="tipo_estado_tramite" class="form-control" onchange="javascript: submit_ticket_load();" >
                            <?php
                                if($tipo_estado_tramite=="1") {
                                    echo '<option value="1" selected="selected">Pendiente</option>';
                                    echo '<option value="2" >Completado</option>';
                                    echo '<option value="3" >Cancelado</option>';
                                }elseif($tipo_estado_tramite=="2") {
                                    echo '<option value="1" >Pendiente</option>';
                                    echo '<option value="2" selected="selected">Completado</option>';
                                    echo '<option value="3" >Cancelado</option>';
                                }else{
                                    echo '<option value="1" >Pendiente</option>';
                                    echo '<option value="2" >Completado</option>';
                                    echo '<option value="3" selected="selected">Cancelado</option>';
                                }                                                                               
                            ?>
                        </select> 
                    </div> 
            </form>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-gestion">
                    <thead>
                        <tr>
                            <th>Nº TICKET</th>
                            <th>FECHA REGISTRO</th>
                            <th>SOLICITANTE</th>
                            <th>DIRECCION</th>
                            <th>TELEFONO</th>
                            <th>UNIDAD INICIAL ASIGNADA</th>
                            <th>TIPO TRAMITE</th>
                        </tr>
                    </thead>
<?php
$xxx=0;
while($resultados_ticket = pg_fetch_array($result))
{
    $xxx=$xxx+1;
?>
                    <tbody>
                        <tr class="gradeA">
                            <td>
                                <?php
                                    if($resultados_ticket[prioridad_ticket]==1){
                                        echo '<a title="Gestionar Ticket" href="?view=gestion_tickets&cod_ticket='.$resultados_ticket[cod_ticket].'"><font color="black">'.str_pad($resultados_ticket[cod_ticket],10,"0",STR_PAD_LEFT).'</font></a>';
                                    }elseif($resultados_ticket[prioridad_ticket]==2){
                                        echo '<a title="Gestionar Ticket" href="?view=gestion_tickets&cod_ticket='.$resultados_ticket[cod_ticket].'"><font color="ffba00">'.str_pad($resultados_ticket[cod_ticket],10,"0",STR_PAD_LEFT).'</font></a>';
                                    }else{
                                        echo '<a title="Gestionar Ticket" href="?view=gestion_tickets&cod_ticket='.$resultados_ticket[cod_ticket].'"><font color="red">'.str_pad($resultados_ticket[cod_ticket],10,"0",STR_PAD_LEFT).'</font></a>';
                                    }
                                    
                                ?>
                            </td>

                            <td>
                                <?php echo date_format(date_create($resultados_ticket['fecha_registro_ticket']), 'd/m/Y g:i A.') ;?> 
                            </td>

                            <td>
                                <?php echo $resultados_ticket['cedula_rif'];?><?php echo "-";?><?php echo $resultados_ticket['nombre_solicitante'];?>
                            </td>

                            <td>
                                <?php echo $resultados_ticket['direccion_habitacion'];?> 
                            </td>

                            <td>
                                <?php echo $resultados_ticket['telefono_movil'];?> 
                            </td>

                            <td>
                                <?php echo $resultados_ticket['nombre_unidad'];?> 
                            </td>

                            <td>
                                <?php echo $resultados_ticket['nombre_tramite'];?> 
                            </td>
                        </tr>
                    </tbody>

<?php } ?>

                    <tfoot>
                        <tr align="center">
                            <th colspan="10" align="center">
                                <div id="cpanel">
                                    <div style="float:right;">
                                        <div class="icon">
                                            <a href="index2.php?view=home" class="btn btn-default">
                                                Salir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

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

<?php
pg_free_result($datos_consulta);
pg_close();
}
?>





