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
                <td colspan="3">{{ $title }}</td>
            </tr>

            <tr>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>Producto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $item->vencimiento }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>{{ $item->nombre }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>