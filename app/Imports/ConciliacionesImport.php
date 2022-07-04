<?php

namespace App\Imports;

use App\Models\cyb\ConciliacionImport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ConciliacionesImport implements ToModel,WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ConciliacionImport([
            'fecha' => $row['fecha'],
            'referencia' => $row['referencia'],
            'debito' => $row['debito'],
            'credito' => $row['credito'],

        ]);
    }

    public function prepareForValidation($data, $index)
    {
        return [
            'fecha' => Date::excelToDateTimeObject($data['fecha'])->format('Y-m-d'),
            'referencia' => $data['referencia'],
            'debito' => $data['debito'],
            'credito' => $data['credito'],
        ];
        //...
    }
    public function rules(): array
    {
        return [
            'fecha'=>'required|date_format:Y-m-d',
            //..
        ];
    }
}
