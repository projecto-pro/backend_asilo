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
                <td colspan="11">{{ $title }}</td>
            </tr>

            <tr>
                <th>Código</th>
                <th>Cantidad</th>
                <th>Producto</th>
                <th>Fecha envía</th>
                <th>Empresa envía</th>
                <th>Usuario envía</th>
                <th>Bodega envía</th>
                <th>Fecha autoriza</th>
                <th>Empresa autoriza</th>
                <th>Usuario autoriza</th>
                <th>Bodega autoriza</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $item->codigo }}</td>
                <td>{{ $item->real_autorizada }}</td>
                <td>{{ $item->nombre_producto }}</td>
                <td>{{ $item->fecha_solicita }}</td>
                <td>{{ $item->empresas_solicita }}</td>
                <td>{{ $item->usuarios_solicita }}</td>
                <td>{{ $item->bodegas_solicita }}</td>
                <td>{{ $item->fecha_autoriza }}</td>
                <td>{{ $item->empresas_autoriza }}</td>
                <td>{{ $item->usuarios_autoriza }}</td>
                <td>{{ $item->bodegas_autoriza }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>