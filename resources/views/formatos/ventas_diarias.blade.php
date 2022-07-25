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
                <th>Fecha</th>
                <th>Código</th>
                <th>NIT</th>
                <th>Persona</th>
                <th>Sub Total</th>
                <th>Total</th>
                <th>Cambio</th>
                <th>Pagado</th>
                <th>Artículos</th>
                <th>Anulado</th>
                <th>Cajero</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $item->fecha }}</td>
                <td>{{ $item->codigo }}</td>
                <td>{{ $item->nit }}</td>
                <td>{{ $item->persona }}</td>
                <td>{{ $item->subtotal }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ $item->cambio }}</td>
                <td>{{ $item->pagado }}</td>
                <td>{{ $item->articulos }}</td>
                <td>{{ $item->anulado ? 'SI' : 'NO' }}</td>
                <td>{{ $item->usuarios->getFullNameAttribute() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>