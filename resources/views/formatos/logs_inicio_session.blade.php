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
                <td colspan="4">{{ $title }}</td>
            </tr>

            <tr>
                <th>Usuario</th>
                <th>Tabla</th>
                <th>Acción</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $item->usuario }}</td>
                @if($item->tabla == ' ')
                <td colspan="2">{{ $item->descripcion }}</td>
                @else
                <td>{{ $item->tabla }}</td>
                <td>{{ $item->accion }}</td>
                @endif
                <td>{{ $item->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>