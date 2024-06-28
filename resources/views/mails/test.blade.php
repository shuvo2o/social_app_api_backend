

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body>
    <table>
        <tr>
            <td>{{ $subject }}</td>
        </tr>
        <tr>
            <td>Hii Mr/Mrs <strong>{{ $data ['name']}}</strong></td>
        </tr>

        <tr>
            <td>{{ $data['body'] }}</td>
        </tr>
    </table>
</body>
</html>