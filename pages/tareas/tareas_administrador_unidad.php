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

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    $cod_unidad=$_SESSION['cod_unidad'];

    $query="SELECT COUNT(*) FROM ticket, detalles_ticket WHERE ticket.cod_ticket=detalles_ticket.cod_ticket";               
    $result = pg_query($query)or die(pg_last_error());  
    $resultados_ticket=pg_fetch_row($result);   
    pg_free_result($result);

    //Informacion de Tickets Urgentes
    $query="SELECT COUNT(*) FROM ticket, detalles_ticket WHERE ticket.cod_ticket=detalles_ticket.cod_ticket and ticket.prioridad_ticket = 3 and detalles_ticket.cod_unidad=$cod_unidad";
    $result = pg_query($query)or die(pg_last_error());  
    $resultados_ticket_urgentes=pg_fetch_row($result);  
    pg_free_result($result);

    //Informacion de Tickets Alta
    $query="SELECT COUNT(*) FROM ticket, detalles_ticket WHERE ticket.cod_ticket=detalles_ticket.cod_ticket and ticket.prioridad_ticket = 2 and detalles_ticket.cod_unidad=$cod_unidad";
    $result = pg_query($query)or die(pg_last_error());  
    $resultados_ticket_alta=pg_fetch_row($result);  
    pg_free_result($result);

    //Informacion de Tickets Normal
    $query="SELECT COUNT(*) FROM ticket, detalles_ticket WHERE ticket.cod_ticket=detalles_ticket.cod_ticket and ticket.prioridad_ticket = 1 and detalles_ticket.cod_unidad=$cod_unidad";
    $result = pg_query($query)or die(pg_last_error());  
    $resultados_ticket_normal=pg_fetch_row($result);  
    pg_free_result($result);
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Panel de información</h1>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">26</div>
                        <div>Mis Tareas!</div>
                    </div>
                </div>
            </div>

            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">Ver Detalles</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>

                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $resultados_ticket_normal[0];?></div>
                        <div>Prioridad Normal!</div>
                    </div>
                </div>
            </div>

            <a href="?view=gestion_tickets_prioridad&prioridad_ticket=1">
                <div class="panel-footer">
                    <span class="pull-left">Ver Detalles</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>

                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $resultados_ticket_alta[0];?></div>
                        <div>Prioridad Alta!</div>
                    </div>
                </div>
            </div>

            <a href="?view=gestion_tickets_prioridad&prioridad_ticket=2">
                <div class="panel-footer">
                    <span class="pull-left">Ver Detalles</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>

                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $resultados_ticket_urgentes[0];?></div>
                        <div>Prioridad Urgentes!</div>
                    </div>
                </div>
            </div>

            <a href="?view=gestion_tickets_prioridad&prioridad_ticket=3">
                <div class="panel-footer">
                    <span class="pull-left">Ver Detalles</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>