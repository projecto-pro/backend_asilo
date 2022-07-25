<?php

namespace App\Traits;

use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Storage;

final class TicketCajaRestaurante extends Fpdf
{
    private $nombreEmpresa;
    private $direccionEmpresa;
    private $fecha;
    private $altoCelda;
    private $incrementoCelda;
    private $total = 0.00;

    function __construct($orientation, $unit, $size)
    {
        $this->nombreEmpresa = 'Hotel y Restaurante El Mirador';
        $this->direccionEmpresa = 'Chiquimulilla, Santa Rosa';
        $this->fecha = date('d/m/Y H:i:s');
        $this->altoCelda = 5;
        $this->incrementoCelda = 6;
        parent::__construct($orientation, $unit, $size);
    }

    public function setHeader()
    {
        $this->SetFont('Helvetica','',8);
        $this->setY(2);
        $this->setX($this->getCenterPositionX());
        $this->Cell(5,$this->getHeightCell(),strtoupper($this->nombreEmpresa),0,0,'C');

        $this->setX($this->getCenterPositionX());
        $this->Cell(5,$this->getHeightCell(),strtoupper($this->direccionEmpresa),0,0,'C');

        $this->setX($this->getCenterPositionX());
        $this->Cell(5,$this->getHeightCell(),'-----------------------------------------------',0,0,'C');
    }

    public function setBody($cash)
    {
        $this->ln(6);
        $this->SetFont('Helvetica','',8);
        $this->setX($this->getCenterPositionX() + 5);
        $this->Cell(5,$this->getHeightCell(),'SALDO INICIAL: Q. '.number_format($cash->saldo_inicial,2),0,0,'R');
        $this->setX($this->getCenterPositionX() + 5);
        $this->Cell(5,$this->getHeightCell(),'INGRESOS: Q. '.number_format($cash->ingresos,2),0,0,'R');
        $this->setX($this->getCenterPositionX() + 5);
        $this->Cell(5,$this->getHeightCell(),'GASTOS: Q. '.number_format($cash->egresos,2),0,0,'R');
        $this->setX($this->getCenterPositionX() + 5);
        $this->SetFont('Helvetica','B',8);
        $this->Cell(5,$this->getHeightCell(),'TOTAL: Q. '.number_format(($cash->saldo_inicial + ($cash->ingresos - $cash->egresos)),2),0,0,'R');
    }

    public function setFooter($cash)
    {
        $this->ln(4);
        $this->SetFont('Helvetica','',8);
        $this->setX($this->getCenterPositionX());
        $this->Cell(5,$this->getHeightCell(),'APERTURA '.$cash->fecha_apertura.' '.$cash->hora_apertura,0,0,'C');
        $this->setX($this->getCenterPositionX());
        $this->Cell(5,$this->getHeightCell(),'CIERRE '.$cash->fecha_cierre.' '.$cash->hora_cierre,0,0,'C');
        $this->Close();
    }

    private function getHeightCell()
    {
        $this->altoCelda += $this->incrementoCelda;
        return $this->altoCelda;
    }

    private function getCenterPositionX()
    {
        return 35;
    }

    private function getInitialPositionX()
    {
        return 3;
    }
}
