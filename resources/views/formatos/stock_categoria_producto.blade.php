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
                <td colspan="6">{{ $title }}</td>
            </tr>

            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Bodega</th>
                <th>Stock actual</th>
                <th>Activo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $item->codigo }}</td>
                <td>{{ $item->nombre_producto }}</td>
                <td>{{ $item->nombre_categoria }}</td>
                <td>{{ $item->nombre_bodega }}</td>
                <td>{{ $item->stock_inicial }}</td>
                <td>{{ $item->activo ? 'SI' : 'NO' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>