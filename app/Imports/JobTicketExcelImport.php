<?php

namespace App\Imports;

use App\Models\Address;
use App\Models\Customer;
use App\Models\DeliveryMethod;
use App\Models\JobTicket;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Uom;
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
        $customer = null;
        $uom = null;
        $product = null;
        $address = null;
        $deliveryMethod = null;


        $customer = Customer::firstOrCreate(['code' => $row['debtor_code']], ['is_company' => true, 'company_name'=> $row['debtor_name'], 'profile_id' => auth()->user()->profile->id]);

        if($row['uom']) {
            $uom = Uom::firstOrCreate(['name' => $row['uom']]);
        }

        $product = Product::firstOrCreate(['code' => $row['item_code']], ['name'=> $row['detail_description'], 'uom_id' => $uom ? $uom->id : null]);

        if($row['delivery_address_1'] or $row['delivery_address_2'] or $row['delivery_address_3'] or $row['delivery_address_4']) {
            $slugAddress = '';
            if($row['delivery_address_1']) {
                $slugAddress .= $row['delivery_address_1'];
            }
            if($row['delivery_address_2']) {
                $slugAddress .= ' '.$row['delivery_address_2'];
            }
            if($row['delivery_address_3']) {
                $slugAddress .= ' '.$row['delivery_address_3'];
            }
            if($row['delivery_address_4']) {
                $slugAddress .= ' '.$row['delivery_address_4'];
            }

            $address = $customer->addresses()->firstOrCreate(['name' => $row['delivery_contact'], 'slug_address' => $slugAddress], ['is_delivery' => true, 'is_billing' => false, 'is_active' => true, 'country_id' => 1, 'contact' => $row['delivery_phone']]);
        }

        if($row['ship_via']) {
            $deliveryMethod = DeliveryMethod::firstOrCreate(['name' => $row['ship_via']]);
        }

        return new JobTicket([
            'code' => auth()->user()->profile->generateNextJobCode(),
            'doc_no' => $row['doc_no'],
            'doc_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['doc_date']),
            'qty' => $row['qty'],
            'remarks' => $row['remarks'] ?? $row['detail_description_2'],
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'status_id' => 3,
            'delivery_address_id' => $address ? $address->id : null,
            'delivery_method_id' => $deliveryMethod ? $deliveryMethod->id : null,
            'delivery_remarks' => $row['ship_info']
        ]);
    }
}
