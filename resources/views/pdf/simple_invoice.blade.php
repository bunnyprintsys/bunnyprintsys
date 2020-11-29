<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $data->job_id }}</title>
    <style type="text/css">
        @font-face {
            font-family: 'Calibri';
            src: {{url( storage_path('fonts/calibri-regular.ttf'))}};
            font-weight: normal;
            font-style: normal;
        }
        * {
            font-family: 'Calibri', Verdana, Arial;
        }
        table tr th {
            font-size: 16px;
        }
        table tr td {
            font-size: 15px;
        }
        .border {
            border: 1px black solid;
        }
        .underline {
            border-bottom: 1px lightgray solid;
        }
        .background-lightblue {
            background-color: lightblue;
        }
    </style>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>

<div class="container d-flex">
    <table width="100%">
        @if ($data->profile->logo_url)
            <tr>
                <td>
                    <img style="display: block; border-radius: 4px;" src="{{ $data->profile->logo_url }}" alt="" height="100" border="0">
                </td>
                <td></td>
            </tr>
        @endif
        <tr>
            <td>
                <span style="font-size: 30px">
                    <strong>{{ $data->profile->name }}</strong>
                </span>
            </td>
            <td style="padding-bottom: 0.5em; text-align: right">
                <span style="font-size: 35px">
                    <strong>Invoice</strong>
                </span>
            </td>
        </tr>
        <tr>
            <td class="text">
                @if ($data->profile->roc)
                <div>
                    SSM: {{ $data->profile->roc }}
                </div>
                @endif
                <div>
                    {{ $data->profile->address ? $data->profile->address->getFullAdress() : null }}
                </div>
                <div>
                    {{-- {{ $data->profile->getPostCodeCityState() }} --}}
                </div>
                <div>
                    {{ $data->profile->user ? $data->profile->user->name : null }} <br>
                    {{ $data->profile->user ? $data->profile->user->phone_number : null }}
                </div>
                <div>
                  {{ $data->profile->user ? $data->profile->user->email : null }}
                </div>
            </td>
            <td class="text" valign="top">
                <table align="right">
                    <tr>
                        <td>Date</td>
                        <td>:</td>
                        <th class="text-right">
                            {{ \Carbon\Carbon::parse($data->order_date)->format('Y-m-d') }}
                        </th>
                    </tr>
                    <tr>
                        <td>Inv#</td>
                        <td>:</td>
                        <th class="text-right">
                            {{ $data->job_id }}
                        </th>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

    <div class="row row-cols-2 pt-3">
        <div class="col-6 float-left">
            <table class="table table-sm table-borderless border">
                <tr class="underline background-lightblue">
                    <th>
                        Bill To
                    </th>
                </tr>
                <tr>
                    <td>
                        {{ $data->customer->is_company ?  $data->customer->company_name : $data->customer->user->name}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $data->customer->user->phone_number }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $data->customer->user->email }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-6 float-right">
            <table class="table table-sm table-borderless border">
                <tr class="underline background-lightblue">
                    <th>
                        Deliver To
                    </th>
                </tr>
                <tr>
                    <td>
                        {{ $data->customer->is_company ?  $data->customer->company_name : $data->customer->user->name}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $data->customer->user->phone_number }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $data->customer->user->email }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row pt-2">
        <div class="col">
            <table class="table table-sm table-bordered">
                <tr class="underline background-lightblue">
                    <th class="text-center col-xs-3">
                        Payment Term
                    </th>
                    <th class="text-center col-xs-3">
                        Handle By
                    </th>
                    <th class="text-center col-xs-3">
                        Job Num
                    </th>
                    <th class="text-center col-xs-3">
                        Prepare By
                    </th>
                </tr>
                <tr>
                    <td class="text-center">
                        {{ $data->customer->payment_term_id ?  $data->customer->paymentTerm->name : null}}
                    </td>
                    <td class="text-center">
                        {{ $data->designer ? $data->designer->name : null }}
                    </td>
                    <td class="text-center">
                        {{ $data->job_id }}
                    </td>
                    <td class="text-center">
                        {{ $data->creator->name }}
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <div class="row pt-2">
        <div class="col">
            <table class="table table-sm table-bordered" style="height: 600px;">
                <thead>
                    <tr class="underline background-lightblue">
                        <th class="text-center">
                            #
                        </th>
                        <th class="text-center">
                            Description
                        </th>
                        <th class="text-center">
                            Price
                        </th>
                        <th class="text-center">
                            Quantity
                        </th>
                        <th class="text-center">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->deals as $index => $item)
                    <tr>
                        <td class="text-center col-xs-1">{{ $index + 1 }}.</td>
                        <td class="text-left col-xs-5">
                            {{ $item->product->name }}
                            <span>
                                @if($item->material)
                                    <br>
                                    <span style="font-size: 14px; padding-left: 12px;">
                                        {{ $item->material->name }}
                                    </span>
                                @endif
                                @if($item->description)
                                    <br>
                                    <span style="font-size: 12px; padding-left: 12px;">
                                        {{ $item->description }}
                                    </span>
                                @endif
                            </span>
                        </td>
                        <td class="text-right col-xs-2">
                            {{ $item->price }}
                        </td>
                        <td class="text-right col-xs-2">
                            {{ $item->qty }}
                        </td>
                        <td class="text-right col-xs-2">
                            {{ $item->amount }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right" colspan="4">
                            Total:
                        </th>
                        <th class="text-right">
                            {{ $data->grandtotal }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if($data->profile->bankBinding)
    <div>
        Bank Transfer to:
        <br>
        <strong>
            {{$data->profile->bankBinding->bank->name}} (Acc Num: {{$data->profile->bankBinding->bank_account_number}}) <br>
            {{$data->profile->bankBinding->bank_account_holder}}
        </strong>
    </div>
    @endif

    <div class="pt-3">
        <table class="table table-sm table-borderless border" style="height: 130px;">
            <thead>
                <tr class="underline background-lightblue">
                    <th>
                        Remarks
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $data->remarks}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer row">
        <table width="100%">
            <tr>
                <td style="padding-bottom: 10px; text-align: center">
                    This is computer generated invoice, no signature is required.
                </td>
            </tr>
        </table>
    </div>
</div>




</body>
</html>
