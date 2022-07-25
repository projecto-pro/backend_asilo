<?php

namespace App\Imports;

use App\Models\Examen;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ExamenImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $value) {
            if (!is_null($value)) {
                $data = new Examen();
                $data->nombre = $value[0];
                $data->precio = $value[1];
                $data->save();
                echo "{$data->nombre} | Q {$data->precio}" . PHP_EOL;
            }
        }
    }
}
