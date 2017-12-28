<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
     //RECIBIENDO VALORES
    if (isset($_GET['id_punto'])){
        $id_punto=$_GET['id_punto'];
    }

    $pdf=new PDF('P','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
        
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,18,'42','','jpg','http://www.portuguesa.gob.ve');
    
    $query="SELECT * FROM punto_cuenta, usuarios WHERE punto_cuenta.cedula_usuario = usuarios.cedula_usuario and punto_cuenta.id_punto=$id_punto";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_ticket=pg_fetch_array($result);	
    pg_free_result($result);

    $pdf->SetFillColor(255,255,255);//blanco
    $pdf->SetTextColor(0,0,0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->SetLeftMargin(55);
    
    $pdf->SetY(15);
    $pdf->MultiCell(110,6,utf8_decode('PUNTO DE CUENTA AL CIUDADANO 
GOBERNADOR DEL ESTADO PORTUGUESA'),0,0,'C',1);//
    
    //$pdf->SetFillColor(200,220,255); //AZUL
    $pdf->SetFillColor(91,155,213);//GRIS
    $pdf->SetTextColor(0,0,0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFont('Times','B',9);
    $pdf->SetLeftMargin(175);
    $pdf->SetY(15);
    
    $pdf->Cell(0,4,utf8_decode('PUNTO NRO.'),1,1,'C',1);
    $pdf->SetFontSize(11);
    $pdf->Cell(0,5,utf8_decode(str_pad($resultados_ticket['id_punto'],10,"0",STR_PAD_LEFT)),1,1,'C',0);
    $pdf->Ln(1);
    $pdf->SetFont('Times','B',9);
    $pdf->Cell(0,4,utf8_decode('FECHA PUNTO'),1,1,'C',1);
    $pdf->SetFontSize(11);
    $pdf->Cell(0,5,utf8_decode(date_format(date_create($resultados_ticket['fecha_punto']), 'd/m/Y')),1,0,'C',0);
     
    $pdf->SetLeftMargin(10);
    $pdf->SetX(10);
    $pdf->SetY(38);
    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->SetFont('Times','B',11);
    $pdf->SetLeftMargin(10);


    $pdf->SetFont('Times','B',9);
    $pdf->Cell(0,4,utf8_decode('DATOS DEL SOLICITANTE'),1,1,'C',1);
    $pdf->Cell(30,4,utf8_decode('CI'),1,0,'C',0);
    $pdf->Cell(0,4,utf8_decode('NOMBRE DEL SOLICITANTE'),1,0,'C',0);
    
    $pdf->Ln(1);
    $pdf->SetY(46);
    $pdf->SetFontSize(12);
    $pdf->SetFont('');
    $pdf->Cell(30,8,utf8_decode(substr_replace($resultados_ticket['cedula_usuario'],'-',1,0)),1,0,'C',0);
    $pdf->Cell(0,8,utf8_decode($resultados_ticket['nombre_usuario']." ".$resultados_ticket['apellido_usuario']),1,0,'L',0);
    
    $pdf->SetY(54);
    $pdf->Ln(1);
    $pdf->SetFontSize(10);
    $pdf->SetFont('','B');
    $pdf->Cell(0,4,utf8_decode('ASUNTO'),1,1,'L',1);
    $pdf->Cell(0,30,'',1,1,'C',0);
    
    $pdf->SetY(60);
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->MultiCell(0, 4, utf8_decode($resultados_ticket['asunto']),0, 'J',0,5);

    $pdf->SetY(89);
    $pdf->Ln(1);
    $pdf->SetFontSize(10);
    $pdf->SetFont('','B');
    $pdf->Cell(0,4,utf8_decode('SINTECIS'),1,1,'L',1);
    $pdf->Cell(0,30,'',1,1,'C',0);
    
    $pdf->SetY(95);
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->MultiCell(0, 4, utf8_decode($resultados_ticket['sintesis']),0, 'J',0,5);
    
    $pdf->SetY(124);
    $pdf->Ln(1);
    $pdf->SetFontSize(10);
    $pdf->SetFont('','B');
    $pdf->Cell(0,4,utf8_decode('INSTRUCIONES'),1,1,'L',1);
    $pdf->Cell(0,30,'',1,1,'C',0);

    $pdf->SetY(130);
    $pdf->SetFontSize(10);
    $pdf->SetFont('');
    $pdf->MultiCell(0, 4, utf8_decode($resultados_ticket['instruccion']),0, 'J',0,5);

    $pdf->SetY(159);
    $pdf->Ln(1);
    $pdf->SetFontSize(10);
    $pdf->SetFont('','B');
    $pdf->Cell(0,5,utf8_decode('DECISIÓN DEL CIUDADANO GOBERNADOR DEL ESTADO'),1,1,'L',1);
    if($resultados_ticket['decision']==1) $decision='APROBADO'; elseif($resultados_ticket['decision']==2) $decision='NEGADO'; else $decision='DIFERIDO';
    
    $pdf->SetFillColor(255,255,255);//blanco
    $pdf->Cell(0,5,utf8_decode($decision),1,0,'C',0,5);
    
    $pdf->SetY(170);
    $pdf->Ln(1);
    $pdf->Cell(0,61,'',1,1,'C',0);
    
    
    $pdf->SetFillColor(255,255,255);//blanco

    $pdf->SetY(216);
    $pdf->SetFont('Times','B',9);
    $pdf->Cell(98,5,utf8_decode('PRESENTANTE'),0,0,'C',0);
    $pdf->Cell(0,5,utf8_decode('GOBERNADOR DEL ESTADO PORTUGUESA'),0,0,'C',0);
    
    $pdf->Ln(1);
    $pdf->SetY(221);
    $pdf->SetFontSize(12);
    $pdf->SetFont('');
    $pdf->Cell(98,5,utf8_decode($resultados_ticket['nombre_usuario']." ".$resultados_ticket['apellido_usuario']),0,0,'C',0);
    $pdf->Cell(0,5,'RAFAEL CALLES',0,0,'C',0);

    $pdf->SetY(232);
    $pdf->Ln(1);
    $pdf->SetFontSize(10);
    $pdf->SetFont('','B');
    $pdf->MultiCell(0,6,utf8_decode('Nota: El presentante será responsable que el Punto de Cuenta aprobado se ajuste a la normativa legal y reglamentaria aplicable; y que cuenta con la debida disponibilidad presupuestaria de ser el caso.'),1,1,'J',0);
    pg_close();
    $pdf->Output("PC-".$id_punto.".pdf","I");
?>
