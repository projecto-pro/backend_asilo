<?php

namespace App\Traits;

use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Storage;

final class TicketRestaurante extends Fpdf
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

    public function setHeader($tipoComprobante, $nit, $noComprobante, $created_at, $logo)
    {
        $this->SetFont('Helvetica', '', 8);
        $this->setY(20);
        $this->setX($this->getCenterPositionX());
        $this->Cell(5, $this->getHeightCell(), strtoupper($this->nombreEmpresa), 0, 0, 'C');

        $this->setX($this->getCenterPositionX());
        $this->Cell(5, $this->getHeightCell(), strtoupper($this->direccionEmpresa), 0, 0, 'C');

        $this->setX($this->getCenterPositionX());
        $this->Cell(5, $this->getHeightCell(), '-----------------------------------------------', 0, 0, 'C');

        $this->SetFont('Helvetica', 'B', 8);
        $this->setX($this->getInitialPositionX());
        $this->Cell(5, $this->getHeightCell(), strtoupper($tipoComprobante) . ': ' . $noComprobante);
        $this->setX($this->getInitialPositionX());
        $this->Cell(5, $this->getHeightCell(), 'FECHA: ' . Carbon::parse($created_at)->format('d-m-Y'));
    }

    public function setCustomer($customer)
    {
        $this->ln(2);
        $this->SetFont('Helvetica', '', 8);
        $this->setX($this->getInitialPositionX());
        $this->Cell(5, $this->getHeightCell(), 'CLIENTE: ' . utf8_decode($customer->cliente));
        $this->setX($this->getInitialPositionX());
        $this->Cell(5, $this->getHeightCell(), 'NIT: ' . $customer->nit);
        $this->setX($this->getInitialPositionX());
        $this->Cell(5, $this->getHeightCell(), 'DIRECCION: ' . utf8_decode(substr($customer->direccion, 0, 32)));
    }

    public function setBody($products)
    {
        $this->ln(2);
        $this->SetFont('Helvetica', '', 8);
        $this->setX($this->getCenterPositionX());
        $this->Cell(5, $this->getHeightCell(), '-------------------------------------------------', 0, 0, 'C');
        $this->SetFont('Helvetica', 'B', 8);
        $this->setX($this->getInitialPositionX() - 1);
        $this->Cell(5, $this->getHeightCell(), 'CANT.    PRODUCTO         PRECIO    SUBTOTAL');
        $this->SetFont('Helvetica', '', 8);
        $this->setX($this->getCenterPositionX());
        $this->Cell(5, $this->getHeightCell(), '-------------------------------------------------', 0, 0, 'C');

        foreach ($products as $product) {
            $celda = $this->getHeightCell();
            $subtotal = ($product->precio * $product->cantidad);
            $this->total += $subtotal;

            $this->setX($this->getInitialPositionX());
            $this->Cell(5, $celda + 6, $product->cantidad);
            $this->setX($this->getInitialPositionX() + 7);
            $this->Cell(5, $celda + 6, utf8_decode(substr($product->nombre, 0, 20)));
            $this->setX($this->getInitialPositionX() + 48);
            $this->Cell(5, $celda + 6, number_format($product->precio, 2), '0', '0', 'R');
            $this->setX($this->getInitialPositionX() + 68);
            $this->Cell(5, $celda + 6, number_format($subtotal, 2), '0', '0', 'R');
        }
    }

    public function setTotal()
    {
        $this->ln(6);
        $this->SetFont('Helvetica', 'B', 12);
        $this->setX($this->getCenterPositionX() + 17);
        $this->Cell(5, $this->getHeightCell(), 'TOTAL: Q. ' . number_format($this->total, 2), 0, 0, 'R');
    }

    public function setFooter()
    {
        $this->ln(4);
        $this->SetFont('Helvetica', 'B', 8);
        $this->setX($this->getCenterPositionX());
        $this->Cell(5, $this->getHeightCell(), 'GRACIAS POR SU COMPRA', 0, 0, 'C');
        $this->SetFont('Helvetica', '', 8);
        $this->setX($this->getCenterPositionX());
        $this->Cell(5, $this->getHeightCell(), $this->fecha, 0, 0, 'C');
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
