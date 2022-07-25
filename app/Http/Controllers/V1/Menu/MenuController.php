<?php

namespace App\Http\Controllers\V1\Menu;

use App\Models\Menu;
use App\Http\Controllers\ApiController;

class MenuController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            return $this->showAll(Menu::all());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse($e->getMessage());
        }
    }
}
