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
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_POST[save])) {
        $codest= $_POST['codest'];	
        $codmun=$_POST['codmun'];
        $codpar=$_POST['codpar'];
        $comunidad=$_POST['comunidad'];

        if (($codmun!="") && ($codest!="") && ($codpar!="") && ($comunidad!="") ) {
            // Consultamos si existe
            $query="Select MAX(codcom) as max_codmun from comunidades where codest='$codest' and codmun='$codmun' and codpar='$codpar'";
            $result = pg_query($query)or die(pg_last_error());
            $result_max=pg_fetch_array($result);
            pg_free_result($result);

            $codcom=str_pad($result_max['max_codmun']+1,3,"0",STR_PAD_LEFT);
            $query="insert into comunidades (codest,codmun,codpar,codcom,descom) values ('$codest','$codmun','$codpar','$codcom','$comunidad')";
            $result = pg_query($query)or die(pg_last_error());
            if(pg_affected_rows($result)){
                $error="bien";
            }
            
        } else {
            $error="Error";
            $div_menssage='<div align="left">
                        <h3 class="error">
                            <font color="red" style="text-decoration:blink;">
                                Error: Datos Incompletos, por favor verifique los datos!
                            </font>
                        </h3>
                    </div>';	
        }
    }//fin del add   
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
                    COMUNIDAD:
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">					
                        <h1>Datos registrados con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=comunidades";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=comunidades" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div>

<?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>ESTADO</label>
                                <select id="codest"  name="codest" class="form-control" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
                                    <option value="">----</option>
                                    <?php 
                                        $consulta_sql=pg_query("SELECT * FROM estados order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
//                                            while ($array_consulta= mysql_fetch_array($consulta_sql)){
                                        while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                            if ($error!=""){
                                                if ($array_consulta[1]==$codest){
                                                    echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                }else {
                                                    echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                }
                                            }else {
                                                if ($array_consulta[1]==$cod_estado){
                                                    echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                }else {
                                                    echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                }
                                            }
                                        }
                                        pg_free_result($consulta_sql);
//                                            mysql_free_result($consulta_sql);
                                    ?>
                                </select>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>MUNICIPIO</label>
                                <div id="contenedor2">
                                    <?php                                       
                                        if ($error!=""){
                                            echo '<select name="codmun" class="form-control" id="codmun"  onChange="cargarContenidoParroquia();" onClick="cargarContenidoParroquia();>';
                                            echo '<option value="">----</option>';
                                            $consultax1="SELECT * from municipios where codest='$codest' order by codmun";
//                                                $ejec_consultax1=mysql_query($consultax1);
//                                                while($vector=mysql_fetch_array($ejec_consultax1)){                                                
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                if ($vector[2]==$codmun){
                                                    echo '<option value="'.$vector[2].'" selected="selected">'.$vector[3].'</option>';
                                                }else {
                                                    echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
                                                }
                                            }
                                            echo '</select>';
                                            pg_free_result($ejec_consultax1);   
//                                                mysql_free_result($ejec_consultax1);  
                                        }else {
                                            echo '<select name="codmun" id="codmun" class="form-control" onChange="cargarContenidoParroquia();">';
                                            echo '<option value="">----</option>';
                                            $consultax1="SELECT * from municipios where codest='$cod_estado' order by codmun";
//                                                $ejec_consultax1=mysql_query($consultax1);
//                                                while($vector=mysql_fetch_array($ejec_consultax1)){
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                if ($vector[2]==$cod_municipio){
                                                    echo '<option value="'.$vector[2].'" selected="selected">'.$vector[3].'</option>';
                                                }else {
                                                    echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
                                                }
                                            }
                                            echo '</select>';
//                                                mysql_free_result($ejec_consultax1);
                                            pg_free_result($ejec_consultax1);
                                        }   
                                    ?>                                                          
                                </div> 
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>PARROQUIA</label>
                                <div id="contenedor3">
                                    <?php 
                                        if ($error!=""){
                                            echo '<select name="codpar" id="codpar" class="form-control" ';
                                            echo '<option value="">----</option>';
                                            $consultax1="SELECT * from parroquias where codest='$codest' and codmun='$codmun' order by codpar";
//                                                $ejec_consultax1=mysql_query($consultax1);
//                                                while($vector=mysql_fetch_array($ejec_consultax1)){
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                if ($vector[3]==$codpar){
                                                    echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                }else {
                                                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                }
                                            }
                                            echo '</select>';
//                                                mysql_free_result($ejec_consultax1);  
                                            pg_free_result($ejec_consultax1);   
                                        }else {
                                            echo '<select name="codpar" id="codpar" class="form-control" ';
                                            echo '<option value="">----</option>';
                                            $consultax1="SELECT * from parroquias where codest='$cod_estado' and codmun='$cod_municipio' order by codpar";
//                                                $ejec_consultax1=mysql_query($consultax1);
//                                                while($vector=mysql_fetch_array($ejec_consultax1)){
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                            }
                                            echo '</select>';
//                                                mysql_free_result($ejec_consultax1);                                                                      
                                            pg_free_result($ejec_consultax1);                                                                       
                                        } 
                                    ?>
                                </div> 
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>NOMBRE DE LA COMUNIDAD</label>
                                <textarea class="form-control" name="comunidad" id="comunidad" cols="70" rows="3"><?php if ($error!='') echo $comunidad;?></textarea>
                            </div>
                            <input type="submit" class="btn btn-default" name="save" value="  Guardar  " >
                            <input class="btn btn-default" type="button" onclick="javascript:window.location.href='?view=comunidades'" value="Cerrar" name="cerrar" />  
                        </div>	
                    </form>
                </div>
            </div>
            <?php }  ?>	
        </div>
    </div>
</div>