<?php
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
?>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i>GRAFICA DE EFICIENCIA DE GESTION GENERAL
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            GENERAL
                            <span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu pull-right" role="menu">
                            <li><a href="#">UNIDAD</a>
                            </li>
                            <li><a href="#">CATEGORIA</a>
                            </li>
                            <li><a href="#">TRAMITE</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="#">COMUNIDAD</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div id="morris-area-chart"></div>
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
    <!-- /.row -->
</div>