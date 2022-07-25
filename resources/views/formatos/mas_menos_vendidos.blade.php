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
                <td colspan="2">{{ $title }}</td>
            </tr>
    </table>
    <hr>
    <table border="1">
        <thead>
            <tr>
                <td colspan="2">Más vendidos</td>
            </tr>

            <tr>
                <th>Cantidad</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mas_vendidos as $item)
            <tr>
                <td>{{ $item->cantidad }}</td>
                <td>{{ $item->nombre }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <table border="1">
        <thead>
            <tr>
                <td colspan="2">Menos vendidos</td>
            </tr>

            <tr>
                <th>Cantidad</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menos_vendidos as $item)
            <tr>
                <td>{{ $item->cantidad }}</td>
                <td>{{ $item->nombre }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>