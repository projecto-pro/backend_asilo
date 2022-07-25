<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <table border="1">
        <thead>
            <tr>
                <td colspan="7">{{ $title }}</td>
            </tr>

            <tr>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Venta</th>
                <th>Compra</th>
                <th>Ganancia</th>
                <th>Pérdida</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ganancias as $item)
            <tr>
                <td>{{ $item->fecha_venta }}</td>
                <td>{{ $item->producto }}</td>
                <td>{{ $item->cantidad_venta }}</td>
                <td>{{ "* {$item->precio_venta} = " }}{{ (floatval($item->cantidad_venta) *
                    floatval($item->precio_venta)) }}</td>
                <td>{{ "* {$item->precio_compra} = " }}{{ (floatval($item->cantidad_venta) *
                    floatval($item->precio_compra)) }}</td>
                <td>{{ (floatval($item->cantidad_venta) * floatval($item->precio_compra)) -
                    (floatval($item->cantidad_venta) * floatval($item->precio_venta)) > -1 ?
                    (floatval($item->cantidad_venta) * floatval($item->precio_compra)) -
                    (floatval($item->cantidad_venta) * floatval($item->precio_venta)) : null }}</td>
                <td>{{ (floatval($item->cantidad_venta) * floatval($item->precio_compra)) -
                    (floatval($item->cantidad_venta) * floatval($item->precio_venta)) < 0 ? (floatval($item->
                        cantidad_venta) * floatval($item->precio_compra)) -
                        (floatval($item->cantidad_venta) * floatval($item->precio_venta)) : null }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Totales Q</td>
                <td>{{ $total_ganancia }}</td>
                <td>{{ $total_perdida }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>