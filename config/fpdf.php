<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default configuration for FPDF
    |--------------------------------------------------------------------------
    |
    | Specify the default values for creating a PDF with FPDF
    |
    */

    'orientation'       => 'P', // (P) Vertical - (L) Horizontal
    'unit'              => 'mm', // (pt) Punto - (mm) Milímetro - (cm) Centímetro - (in) Pulgada
    'size'              => array(80, 150), // A3 - A4 (Por defecto) - A5 - Carta - Documento legal - Tamaño tickt 80mm x 150 mm (largo aprox) array(x ,y) 

    /*
    |--------------------------------------------------------------------------
    | With Laravel Vapor hosting
    |--------------------------------------------------------------------------
    |
    | If the application is to be hosted in the Laravel Vapor hosting platform,
    | a special header needs to be attached to each download response.
    |
    */
    'useVaporHeaders'  => env('FPDF_VAPOR_HEADERS', false),

];
