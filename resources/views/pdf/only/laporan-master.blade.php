<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <style type="text/css">
        @page {
            margin: 3cm;
        }
        .vertical-space-sign {
            margin-bottom: 72pt;
        }
        .vertical-space-paragraph {
            margin-bottom: 120pt;
        }
        .text-center {
            text-align:center;
        }
        .bold{
            font-weight:bold;
        }
        .font-14{
            font-size: 14pt;
            font-weight:bold;
        }
        .font-20{
            font-size: 20pt;
            font-weight:bold;
        }
        .table-center{
            margin-left:auto;
            margin-right:auto;
        }
        .page-break {
            page-break-after: always;
            vertical-align: middle;
        }
        .table,
        .table th,
        .table td {
            padding:5px;
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    @php
        $data = [
            'my_map' => $my_map,
            'maps' => $maps,
        ]
    @endphp
    @include('pdf.ONLY.cover',$data)
    @include('pdf.ONLY.cover-back',$data)
    @include('pdf.ONLY.pengesahan',$data)
</body>
</html>
