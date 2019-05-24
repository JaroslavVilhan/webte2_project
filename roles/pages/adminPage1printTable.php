<?php
session_start();
require('../../tfpdf/tfpdf.php');

$head = $_SESSION['tableHead'];
$values = $_SESSION['tableValues'];

$pdf = new tFPDF();

$pdf->AddPage('Landscape', 'A4');
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->AddFont('DejaVuBold','','DejaVuSansCondensed-Bold.ttf',true);

$columnWidth = 260/(count($head));

$pdf->SetFont('DejaVuBold','',12);

for ($i = 0; $i < count($head); $i++) {
    if($i==0){
        $pdf->Cell(15, 8, $head[$i][0], 1);
    }else if($i==1){
        $pdf->Cell(45, 8, $head[$i][0], 1);
    }else {
        $pdf->Cell($columnWidth, 8, $head[$i][0], 1);
    }
}
$pdf->Ln();
$pdf->SetFont('DejaVu','',10);

for ($i = 0; $i < count($values); $i++) {
    for ($k = 0; $k < count($values[$i]); $k++) {
        if($k==0){
            $pdf->Cell(15, 6, $values[$i][$k], 1);
        }else if($k==1){
            $pdf->Cell(45, 6, $values[$i][$k], 1);
        }else {
            $pdf->Cell($columnWidth, 6, $values[$i][$k], 1);
        }
    }
    $pdf->Ln();

}

$pdf->Output();