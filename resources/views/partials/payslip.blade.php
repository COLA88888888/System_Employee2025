{{-- Reusable payslip partial with company header --}}
<div style="text-align:center; padding: 10px; border-bottom: 2px solid #333; margin-bottom: 15px;">
  {{-- Company logo if available --}}
  @if(file_exists(public_path('img/logo.png')))
    <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 50px; margin-bottom: 10px;">
  @endif
  
  {{-- Company name --}}
  <h3 style="margin: 5px 0; font-family: 'Saysettha OT';">ບໍລິສັດ/ອົງການ</h3>
</div>

<div style="font-family: 'Saysettha OT'; padding: 10px;">
  <table style="width:100%; border-collapse:collapse; font-size:18px;">
    <tr><td style="width:40%;"><b>ຊື່ພະນັກງານ:</b></td><td>{{ $data->employee_name }}</td></tr>
    <tr><td><b>ພະແນກ:</b></td><td>{{ $data->department_name }}</td></tr>
    <tr><td><b>ຕຳແໜ່ງ:</b></td><td>{{ $data->position_name }}</td></tr>
    <tr><td><b>ວັນທີຈ່າຍ:</b></td><td>{{ \Carbon\Carbon::parse($data->pay_month)->format('d-m-Y') }}</td></tr>
    <tr><td><b>ເງິນເດືອນ:</b></td><td>{{ number_format($data->salary) }} ກີບ</td></tr>
    <tr><td><b>ເງິນໂບນັດ:</b></td><td>{{ number_format($data->bonus) }} ກີບ</td></tr>
    <tr><td><b>ຫັກເງິນ:</b></td><td>{{ number_format($data->del_salary) }} ກີບ</td></tr>
    <tr><td><b>ລວມ:</b></td><td>{{ number_format($data->amount) }} ກີບ</td></tr>
  </table>
</div>
