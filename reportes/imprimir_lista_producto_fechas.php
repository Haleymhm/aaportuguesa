<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    //RECIBIENDO VALORES
    if (isset($_POST['fecha_ini'])){
        $fecha_i=$_POST['fecha_ini']; 
        $fecha_f=$_POST['fecha_fin'];
        $fecha_1=implode('/',array_reverse(explode('-',$_POST['fecha_ini'])));
        $fecha_2=implode('/',array_reverse(explode('-',$_POST['fecha_fin'])));
        $producto=$_POST['codigo_concepto'];
    }
    
    $query= "Select * from empresa";
    $result = pg_query($query);
    $resultados_empresa=pg_fetch_array($result);
    pg_free_result($result);
    
    $query= "Select * FROM concepto_factura WHERE codigo_concepto='$producto'";
    $result = pg_query($query);
    $resultados_producto=pg_fetch_array($result);
    pg_free_result($result);
    
    $pdf=new PDF('P','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();

    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'32','','jpg','http://www.portuguesa.gob.ve');
    $pdf->Image('./logo/logo_busportuguesa.png',130,10,'70','','png','http://busportuguesa.com.ve/');
    $pdf->Image('./logo/logo_proveduria_busportuguesa.png',130,20,'50','','png','http://busportuguesa.com.ve/');

    $pdf->SetLeftMargin(50);
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);

    $pdf->SetFontSize(10);
    $pdf->MultiCell(70,4,utf8_decode($resultados_empresa['nombre_empresa']),0,'C',0);//
    $pdf->Ln(0);
    $pdf->SetFontSize(12);
    $pdf->MultiCell(70,6,utf8_decode($resultados_empresa['siglas_empresa']),0,'C',0);//
    $pdf->Ln(0);
    $pdf->SetFontSize(10);
    $pdf->MultiCell(70,6,'RIF: '.$resultados_empresa['rif_empresa'],0,'C',0);//

    $pdf->Ln(1);
    $pdf->SetFontSize(8);
    $pdf->Cell(16,6,utf8_decode('Dirección:'),'T',0,'L',0);//
    $pdf->SetFont('');
    $pdf->MultiCell(70,6,utf8_decode($resultados_empresa['direccion_empresa']),'T','J',0);
    $pdf->Line(10,41,200,41);
    
    $pdf->SetFont('Arial','B',10);
    $pdf->SetLeftMargin(8);

    $pdf->SetFillColor(255,255,255);
    $pdf->SetY(40);
    $pdf->SetX(0);

    //
    $pdf->Ln(2);
    $pdf->MultiCell(0,6,utf8_decode('FACTURACIÓN DESDE EL '.$fecha_1.' AL '.$fecha_2),0,C,1);//
    $pdf->MultiCell(0,6,utf8_decode(' Del Producto '.$resultados_producto['nombre_concepto']),0,C,1);//
    $pdf->SetX(10);
    $pdf->Ln(3);    

    //Colores, ancho de linea y fuente en negrita
    $pdf->SetFillColor(85,186,243);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('Arial','B',10);

    //Cabecera
    $pdf->SetLeftMargin(15);
    $pdf->SetFillColor(85,186,243);	
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',9);	

    $pdf->SetWidths(array(22,100,20,20,20));	//196 para P; 259.4 para L
    $pdf->SetAligns(array('C','C','C','C','C'));

    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("Fáctura"),utf8_decode("Razón Social"),utf8_decode("Fecha"),utf8_decode("Cantidad"),utf8_decode("Status")));

    //Restauracion de colores y fuentes
    $pdf->SetFillColor(246,246,246);
    $pdf->SetTextColor(0);

//    $query="SELECT factura.n_factura, factura.cedula_rif,solicitantes.nombre_solicitante, factura.fecha_factura, factura.status,COUNT(*) as nro_detalles
//            FROM detalle_factura INNER JOIN factura ON detalle_factura.n_factura=factura.n_factura INNER JOIN solicitantes ON solicitantes.cedula_rif = factura.cedula_rif
//            WHERE to_date(to_char(factura.fecha_factura,'YYYY-MM-DD'),'YYYY-MM-DD') >= '$fecha_i' and to_date(to_char(factura.fecha_factura,'YYYY-MM-DD'),'YYYY-MM-DD') <= '$fecha_f' and factura.status::text like  '%$condicion%'
//            AND detalle_factura.codigo_concepto=$producto GROUP BY factura.n_factura,solicitantes.cedula_rif ORDER BY factura.n_factura";
    $query="SELECT * FROM factura,detalle_factura,solicitantes,concepto_factura WHERE detalle_factura.n_factura=factura.n_factura AND detalle_factura.codigo_concepto=concepto_factura.codigo_concepto AND
            factura.cedula_rif=solicitantes.cedula_rif AND to_date(to_char(factura.fecha_factura,'YYYY-MM-DD'),'YYYY-MM-DD') >= '$fecha_i' and to_date(to_char(factura.fecha_factura,'YYYY-MM-DD'),'YYYY-MM-DD') <= '$fecha_f'
            AND factura.status::text like  '%$condicion%'
            AND detalle_factura.codigo_concepto='$producto' ORDER BY factura.n_factura";
    $result_detalle = pg_query($query);

    $nro=0;
    $art=0;
    while ($row=pg_fetch_array($result_detalle)){
        //DATOS PRINCIPALES	
        $nro++;                 //contamos las facturas
        $cant=$row[cantidad];
        $art+=$cant;            //contamos los productos
        $factura=str_pad($row['n_factura'],9,"0",STR_PAD_LEFT);
        $nombre=substr_replace($row['cedula_rif'],'-',1,0).'  -  '.utf8_decode($row['nombre_solicitante']);
        $fecha=date_format(date_create($row['fecha_factura']), 'd/m/Y');
        if ($row['status']==0){
            $status='ANULADO';
        }else {
            $status='ACTIVO';
        }
//        $status=$row['status'];
        
        $pdf->SetFillColor(230,235,255);
        $pdf->SetWidths(array(22,100,20,20,20));	//196 para P; 259.4 para L
        $pdf->SetAligns(array('L','L','C','C','C'));

        $fill = false;
        $pdf->fill("$fill");
        $pdf->Row(array($factura,$nombre,$fecha,$cant,$status));
    };
    $pdf->SetFillColor(230,235,255);
    $pdf->SetWidths(array(142,40));	//196 para P; 259.4 para L
    $pdf->SetAligns(array('R','C'));
    $pdf->Row(array("TOTALES  --> Facturas: ".number_format($nro),number_format($art).utf8_decode(" Artículos")));

    $pdf->Ln(25);
    $pdf->SetX(0);
    $pdf->SetLeftMargin(10);
    $pdf->Cell(65,5,utf8_decode($resultados_empresa['nombre_administrador']),'T',0,'C');
    $pdf->Cell(65,5,"",0,0,'C');
    $pdf->Cell(65,5,utf8_decode($cajero),'T',0,'C');
    $pdf->Ln(5);
    $pdf->Cell(65,5,utf8_decode('Administrador'),0,0,'C');
    $pdf->Cell(65,5,"",0,0,'C');
    $pdf->Cell(65,5,utf8_decode('Cajero'),0,0,'C');
    
    pg_close();
    $pdf->Output("resumen-producto-".$fecha_cierre.".pdf","I");
?>
