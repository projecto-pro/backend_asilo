<?php

namespace App\Imports;

use App\Models\Municipio;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MunicipioImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $value) {
            if (!is_null($value)) {
                $municipality = new Municipio();
                $municipality->nombre = $value[1];
                $municipality->codigo_original = $value[0];
                $municipality->codigo = substr($value[0], 2);
                $municipality->departamento_id = $value[2];
                $municipality->save();
                echo "{$municipality->codigo_original} | {$municipality->codigo} - {$municipality->nombre}" . PHP_EOL;
            }
        }
    }
}
