<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Traits\ConsultasGlobal;
use App\Traits\Utileria;

class ApiController extends Controller
{
    use ApiResponse, ConsultasGlobal, Utileria;

    public function __construct()
    {
        $this->middleware('auth:passport'); //Es protección para que no puedan consultar sin estar logueado
    }
}
