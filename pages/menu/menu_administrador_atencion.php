<div class="sidebar-nav navbar-collapse">
    <ul class="nav" id="side-menu">
        <li class="sidebar-search">
            <div class="input-group custom-search-form">
                <input type="text" class="form-control" placeholder="Buscar...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
            <!-- /input-group -->
        </li>

        <li>
            <a href="index2.php?view=home">
                <i class="fa fa-user fa-fw"></i> 
                Inicio
            </a>
        </li>

        <li>
            <a href="#">
                <i class="fa fa-dashboard fa-fw"></i> 
                Configuraciones
                <span class="fa arrow"></span>
            </a>

            <ul class="nav nav-second-level">
                <li>
                    <a href="index2.php?view=empresa">Configuracion del Sistema</a>
                </li>

                <li>
                    <a href="index2.php?view=usuarios">Usuarios del Sistema</a>
                </li>

                <li>
                    <a href="index2.php?view=edos_tramites">Estados de Tramites</a>
                </li>

                <li>
                    <a href="index2.php?view=categorias">Categorias</a>
                </li>

                <li>
                    <a href="index2.php?view=unidades">Unidades</a>
                </li>

                <li>
                    <a href="index2.php?view=tipo_solicitantes">Tipo de Solicitantes</a>
                </li>

                <li>
                    <a href="index2.php?view=comunidades">Comunidades</a>
                </li>

                <li>
                    <a href="index2.php?view=profesion_solicitante">Profesiones</a>
                </li>

                <li>
                    <a href="index2.php?view=ente_publico">Entes Publicos</a>
                </li>

                <li>
                    <a href="index2.php?view=partido_politico">Partidos Politicos</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#">
                <i class="fa fa-pie-chart fa-fw"></i> 
                Estadisticas
            </a>

            <ul class="nav nav-second-level">
                <li>
                    <a href="flot.html">Atención Por Unidades</a>
                </li>
                <li>
                    <a href="morris.html">Atención Mensual</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#">
                <i class="fa fa-hourglass fa-fw"></i> 
                Tareas
                <span class="fa arrow"></span>
            </a>

            <ul class="nav nav-second-level">
                <?php 
                if($_SESSION['solicitar_punto']==1){
                    echo '<li><a href="index2.php?view=punto_cuenta">Puntos de Cuenta</a></li>';
                }?>
                <?php 
                if($_SESSION['solicitar_punto']==1){
                    echo '<li><a href="morris.html">Ordenes</a></li>';
                }?>
            </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#">
                <i class="fa fa-search fa-fw"></i> 
                Busqueda de Solicitud
                <span class="fa arrow"></span>
            </a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="index2.php?view=gestion_tickets_load">Gestion de Solicitud</a>
                </li>
                <li>
                    <a href="index2.php?view=gestion_tickets">Busqueda de Solicitud</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="index2.php?view=tickets">
                <i class="fa fa-edit fa-fw"></i> 
                Nueva Solicitud
            </a>
        </li>

        <li>
            <a href="index2.php?view=solicitante_load_view">
                <i class="fa fa-users fa-fw"></i> 
                Buscar Solicitante
            </a>
        </li>

        <li>
            <a href="#">
                <i class="fa fa-mobile fa-fw"></i> 
                Mensajeria
                <span class="fa arrow"></span>
            </a>

            <ul class="nav nav-second-level">
                <li>
                    <a href="blank.html">Enviar Mensaje Masivo</a>
                </li>
                <li>
                    <a href="blank.html">Bandeja de Enviados</a>
                </li>
                <li>
                    <a href="login.html">Bandeja de Recibidos</a>
                </li>
                <li>
                    <a href="login.html">Bandeja Por Enviar</a>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#">
                <i class="fa fa-file-text-o fa-fw"></i> 
                Reportes
                <span class="fa arrow"></span>
            </a>

            <ul class="nav nav-second-level">
                <li>
                    <a href="#">Reporte General
                        <span class="fa arrow"></span>
                    </a>

                    <ul class="nav nav-third-level">
                        <li>
                            <a href="reportes/imprimir_lista_etramites.php" target="true">Estados de las Tramites</a>
                        </li>

                        <li>
                            <a href="reportes/imprimir_lista_comunidades.php" target="true">Comunidades</a>
                        </li>
                        <li>
                            <a href="reportes/imprimir_lista_categorias.php" target="true">Categorias</a>
                        </li>
                        <li>
                            <a href="reportes/imprimir_lista_unidades.php" target="true">Unidades</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#">Reporte Especificos
                        <span class="fa arrow"></span>
                    </a>

                    <ul class="nav nav-third-level">
                        <li>
                            <a href="index2.php?view=gestion_reporte_est_fecha" target="true">Estadisticos de Ticket por Fecha</a>
                        </li>

                        <li>
                            <a href="index2.php?view=gestion_reporte_est_year" target="true">Estadisticos de Ticket por Año</a>
                        </li>
                        <li>
                            <a href="index2.php?view=gestion_reporte_tipo_tramite" target="true">Solicitudes por Tipo de Tramite</a>
                        </li>
                        <li>
                            <a href="index2.php?view=gestion_reporte_categoria" target="true">Solicitudes por Categoria</a>
                        </li>
                        <li>
                            <a href="index2.php?view=gestion_reporte_municipio" target="true">Solicitudes por Municipio</a>
                        </li>
                        <li>
                            <a href="index2.php?view=gestion_reporte_parroquia" target="true">Solicitudes por Parroquis</a>
                        </li>
                        <li>
                            <a href="index2.php?view=gestion_reporte_comunidad" target="true">Solicitudes por Comunidad</a>
                        </li>
                        <li>
                            <a href="index2.php?view=gestion_reporte_unidad" target="true">Solicitudes por Unidad</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- /.nav-second-level -->
        </li>

        <li>
            <a href="#">
                <i class="fa fa-wrench fa-fw"></i> 
                Herramientas
                <span class="fa arrow"></span>
            </a>

            <ul class="nav nav-second-level">
                <li>
                    <a href="index2.php?view=solicitante_mantenimiento">Mantenimiento de Tickets</a>
                </li>
                <li>
                    <a href="index2.php?view=solicitante_mantenimiento_phone">Mantenimiento de Solicitantes</a>
                </li>
            </ul>
        </li>
    </ul>
</div>