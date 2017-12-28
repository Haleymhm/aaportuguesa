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
    $pagina=$pag.'?view='.$type;

    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?>

<?php //seccion para recibir los datos y modificarlos.
    if (isset($_GET['idcom'])){
        $datos_modificar= $_GET['idcom'];
        $cod_estado=$_GET['estado'];
        $cod_municipio=$_GET['munic'];
        $cod_parroquia=$_GET['parroq'];

        $query="SELECT * FROM comunidades WHERE comunidades.idcom=$datos_modificar";
        $result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);
        pg_free_result($result);
    }
?> 

<?php 
    if (isset($_POST[save])) {
        $idcom= $_POST['idcom'];	
        $comunidad=$_POST['comunidad'];

        $error="bien";	
        $query="SELECT update_comunidad($idcom,'$comunidad')";
        $result = pg_query($query)or die(pg_last_error());
        pg_close($db_conexion);	//exit;	   
    }//fin del procedimiento modificar.
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div align="center">
                <font color="red" style="text-decoration:blink;">       
                    <?php echo $div_menssage;?>
                </font>
            </div>

            <div class="panel-heading">
                COMUNIDAD:
            </div>
        
<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->
            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2"> 					
                        <h1>Datos Modificados con &eacute;xito</h1>
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
                        <input id="idcom" name="idcom" value="<?php echo $datos_modificar; ?>" type="hidden"/>
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>ESTADO</label>
                                <input type="hidden" id="codestado" name="codestado"  value="<?php echo $cod_estado;?>" />
                                <select id="codest" disabled="true"  name="codest" class="form-control" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
                                    <option value="">----</option>
                                    <?php 
                                        $consulta_sql=pg_query("SELECT * FROM estados where estados.idest=$cod_estado order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
                                        while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                            if ($error!=""){
                                                if ($array_consulta[1]==$cod_estestado){
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
                                    ?>
                                </select>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>MUNICIPIO</label>
                                <div id="contenedor2">
                                    <input type="hidden" id="codmunicipio" name="codmunicipio"  value="<?php echo $cod_municipio;?>" />
                                    <select id="codest" disabled="true"  name="codest" class="form-control">
                                        <?php 
                                            $consulta_sql=pg_query("SELECT * from municipios where idmun=$cod_municipio") or die('La consulta fall&oacute;: ' . pg_last_error());
                                            while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                                if ($error!=""){

                                                    if ($array_consulta[2]==$cod_estestado){
                                                        echo '<option value="'.$array_consulta[2].'" selected="selected">'.$array_consulta[3].'</option>';
                                                    }else {
                                                        echo '<option value="'.$array_consulta[2].'">'.$array_consulta[3].'</option>';
                                                    }
                                                }else {
                                                    if ($array_consulta[2]==$cod_estado){
                                                        echo '<option value="'.$array_consulta[2].'" selected="selected">'.$array_consulta[3].'</option>';
                                                    }else {
                                                        echo '<option value="'.$array_consulta[2].'">'.$array_consulta[3].'</option>';
                                                    }
                                                }
                                            }
                                            pg_free_result($consulta_sql);
                                        ?>
                                    </select>

                                                                                            
                                </div> 
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>PARROQUIA</label>
                                <div id="contenedor3">
                                    <input type="hidden" id="codparroquia" name="codparroquia" value="<?php echo $cod_parroquia;?>" />
                                        <?php 
                                        if ($error!=""){
                                            echo '<select name="codpar" id="codpar" disabled="true" class="form-control"';
                                            echo '<option value="">----</option>';
                                            $consultax1="SELECT * from parroquias where idpar=$cod_parroquia";
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                if ($vector[3]==$codpar){
                                                    echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                }else {
                                                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                }
                                            }
                                            echo '</select>';
                                            pg_free_result($ejec_consultax1);   
                                        }else {
                                            echo '<select name="codpar" id="codpar" disabled="true" class="form-control"';
                                            echo '<option value="">----</option>';
                                            $consultax1="SELECT * from parroquias where idpar=$cod_parroquia";
                                            $ejec_consultax1=pg_query($consultax1);
                                            while($vector=pg_fetch_array($ejec_consultax1)){
                                                if ($vector[3]==$cod_parroquia){
                                                    echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                }else {
                                                    echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                }
                                            }
                                            echo '</select>';                                                  
                                            pg_free_result($ejec_consultax1);
                                        } 
                                    ?>
                                </div> 
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>NOMBRE DE LA COMUNIDAD</label>
                                <textarea class="form-control" name="comunidad" id="comunidad" cols="70" rows="3"><?php if ($error!='') echo $comunidad; else echo $resultado[descom];?></textarea>
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

