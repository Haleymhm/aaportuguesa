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
    
    if (isset($_POST[save])){
        $fecha_ini=implode('-',array_reverse(explode('/',$_POST["fecha_ini"]))); 
        $fecha_fin=implode('-',array_reverse(explode('/',$_POST["fecha_fin"]))); 

        echo "<script type='text/javascript'>window.location.href='reportes/imprimir_censos.php?fecha_ini=$fecha_ini&fecha_fin=$fecha_fin'</script>";
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

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div align="center">
                <font color="red" style="text-decoration:blink;">
                    <?php $error_accion_ms[$error_cod]?>
                </font>
            </div>  

            <div class="panel-heading">
                REPORTE DE SOLICITUDES POR PARROQUIA
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){  ?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                     
                        <h1>Datos registrados con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=solicitante_load_view<?php echo '&cedula_rif='.$cedula_rif;?>";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script>                       
                        [<a href="?view=solicitante_load_view<?php echo '&cedula_rif='.$cedula_rif;?>" name="Continuar"> Continuar </a>]
                    </font>                         
                </h3>
            </div>

<?php   }else{  ?>   <!-- Mostrar formulario Original --> 
                
            <div class="panel-body">
                <div class="row">
                    <form id="QForm" name="QForm" method="POST" action="reportes/imprimir_lista_ticket_parroquia.php" enctype="multipart/form-data" target="_Blank">
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>ESTADO</label>
                                <select id="codest" name="codest" class="form-control" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
                                    <option value="">----</option>
                                    <?php 
                                        $consulta_sql=pg_query("SELECT * FROM estados order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
                                        while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                            if ($array_consulta[1]==$result_solicitantes[codest]){
                                                echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                            }else {
                                                echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                            }
                                        }
                                        pg_free_result($consulta_sql);
                                    ?>
                                </select>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>MUNICIPIO</label>
                                <div id="contenedor2">
                                    <select name="codmun" id="codmun" class="form-control" onChange="cargarContenidoParroquia();">
                                        <option value="">----</option>
                                        <?php                                       
                                            $consultax1="SELECT * from municipios where codest='$result_solicitantes[codest]' order by codmun";
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                if ($vector[2]==$result_solicitantes[codmun]){
                                                    echo '<option value="'.$vector[2].'" selected="selected">'.$vector[2].'</option>';
                                                }else {
                                                    echo '<option value="'.$vector[2].'">'.$vector[2].'</option>';
                                                }
                                            }
                                            pg_free_result($ejec_consultax1);
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>PARROQUIA</label>
                                <div id="contenedor3">
                                    <select name="codpar" id="codpar" class="form-control" onchange="cargarContenidoComunidad();" >
                                        <option value="">----</option>
                                        <?php 
                                            $consultax1="SELECT * from parroquias where codest='$result_solicitantes[codest]' and codmun='$result_solicitantes[codmun]' order by codpar";
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                if ($vector[3]==$result_solicitantes[codpar]){
                                                    echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                }else {
                                                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                }
                                            }
                                            pg_free_result($ejec_consultax1);                                                                       
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>AÑO</label>
                                <select name="year" id="year" class="form-control" onchange="javascript: submit_ticket_load();" >
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
                            <input type="submit" class="button" name="save" value="  REPORTE  " >
                            <input  class="button" type="button" onclick="javascript:window.location.href='?view=home'" value="Cerrar" name="cerrar" />
                        </div>
                    </form>
                </div>
            </div>

<?php }  ?> 

        </div>   
    </div>
</div>