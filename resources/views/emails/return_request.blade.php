<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>

<body>
    <table>
        <tr>
            <td>Dear {{ $userDetails['name'] }}!</td>
        </tr>
        <tr>
            <td>Your return request for Order no. {{ $returnDetails['order_id'] }} with E-commerce website
                is {{ $return_request }}.</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Thanks & Regards;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Frezh Website..</td>
        </tr>
    </table>
</body>

</html>