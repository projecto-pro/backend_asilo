<?php

namespace App\Imports;

use App\Models\Medicina;
use App\Traits\Utileria;
use App\Models\Presentacion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\MedicinaPresentacion;
use Maatwebsite\Excel\Concerns\ToCollection;

class MedicinaImport implements ToCollection
{
    use Utileria;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $index => $tupla) {
            if (!is_null($tupla)) {
                try {
                    DB::beginTransaction();

                    $medicina = Medicina::where('nombre', $tupla[0])->first();

                    if (is_null($medicina)) {
                        $medicina = new Medicina();
                        $medicina->codigo = $this->generadorCodigo('M-', $index + 1);
                        $medicina->nombre = $tupla[0];
                        $medicina->descripcion = $tupla[1];
                        $medicina->foto = null;
                        $medicina->usuario_id = 1;
                        $medicina->save();

                        $medicina->codigo = $this->generadorCodigo('M-',  $medicina->id);
                        $medicina->save();
                    }

                    $presentacion = Presentacion::where('nombre', $tupla[2])->first();
                    if (is_null($presentacion)) {
                        $presentacion = new Presentacion();
                        $presentacion->nombre = $tupla[2];
                        $presentacion->save();
                    }

                    $medicina_presentacion = new MedicinaPresentacion();
                    $medicina_presentacion->nombre_medicina = $medicina->nombre;
                    $medicina_presentacion->nombre_presentacion = $presentacion->nombre;
                    $medicina_presentacion->precio = intval($tupla[3]);
                    $medicina_presentacion->medicina_id = $medicina->id;
                    $medicina_presentacion->presentacion_id = $presentacion->id;
                    $medicina_presentacion->save();

                    echo "{$medicina->nombre} | {$presentacion->nombre} | Q {$medicina_presentacion->precio}" . PHP_EOL;

                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    echo $th->getMessage() . PHP_EOL;
                }
            }
        }
    }
}
