<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\JobTicket;
use App\Models\Product;
use App\Models\Profile;
use Carbon\Carbon;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JobTicketExcelImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new JobTicket([
            'code' => auth()->user()->profile->generateNextJobCode(),
            'doc_no' => $row['doc_no'],
            'doc_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['doc_date']),
            'qty' => $row['qty'],
            'remarks' => $row['remarks'] ?? $row['detail_description_2'],
            'customer_id' =>
                            Customer::firstOrCreate(['code' => $row['debtor_code']], ['is_company' => true,      'company_name'=> $row['debtor_name'], 'profile_id' => auth()->user()->profile->id])->id,
            'product_id' =>
                            Product::firstOrCreate(['code' => $row['item_code']], ['name'=> $row['detail_description']])->id,
            'status_id' => 3
        ]);
    }
}
