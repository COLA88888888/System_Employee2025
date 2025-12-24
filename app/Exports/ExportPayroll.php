<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;

use App\Models\Payroll;

class ExportPayroll implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Payroll::join('employees', 'payrolls.employee_id', '=', 'employees.id')
            ->join('departments','payrolls.department_id','=','departments.id')
            ->join('positions','payrolls.position_id','=','positions.id')
            ->select(
                'payrolls.id',
                'employees.fname as employee_name',
                'departments.name as department_name',
                'positions.pos_name as position_name',
                'payrolls.pay_month',
                'payrolls.salary',
                'payrolls.bonus',
                'payrolls.del_salary',
                'payrolls.amount',
            )
            ->orderBy('payrolls.id','desc')
            ->get();
    }

    public function headings(): array {
        return [
            'ລະຫັດ',
            'ຊື່ພະນັກງານ',
            'ພະແນກ',
            'ຕຳແໜ່ງ',
            'ວັນທີເດືອນປີຈ່າຍ',
            'ເງິນເດືອນ',
            'ໂບນັດ',
            'ເງິນຫັກ',
            'ເງິນລວມ',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        //ກຳນົດ Saysettha OT ໃຫ້ແຖວຫົວຕາຕະລາງ
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'name' => 'Saysettha OT',
            ],
            'alignment' => [
                'horizontal' => 'center',
            ],
        ]);

        // 🎨 ກຳນົດຟ້ອນ Saysettha OT ໃຫ້ທັງໝົດ
        $sheet->getStyle('A2:I999')->getFont()->setName('Saysettha OT')->setSize(12);

        return [];
    }
}
