<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "Saysettha OT", DejaVu Sans, sans-serif;
            font-size: 14px;
            padding: 20px;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="title">ໃບຈ່າຍເງິນເດືອນ (PAYSLIP)</div>
    @include('partials.payslip', ['data' => $data])
    <br><br>
    <p><i>ໝາຍເຫດ: ໃບນີ້ເປັນໃບຢືນຢັນການຈ່າຍເງິນໃຫ້ພະນັກງານ.</i></p>
</body>
</html>
