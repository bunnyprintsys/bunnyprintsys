<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $data->job_id }}</title>

    <style type="text/css">
        html {
            height: 100%;
        }
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        .container {
            margin: 70px;
        }
        .container table tr td {
            margin: 12px;
        }
        .container .text {
            font-size: 12px;
        }
        .container .text div {
            padding-bottom: 5px;
        }
        .items {
            margin-top: 40px;
        }
        .items table {
            border-collapse: collapse;
        }
        .items table tr th {
            padding: 10px;
            border: 1px solid black;
        }
        .items table tr td {
            font-size: 12px;
            padding: 10px;
            border: 1px solid black;
        }
        .items tfoot tr td {
            font-size: 12px;
        }
        .footer {
            font-size: 12px;
        }
        .items table {
            border-collapse: collapse;
        }
        .section-item table tr th {
            border: 1px solid black;
        }
        .section-item table tr td {
            border: 1px solid black;
        }

    </style>

</head>
<body>

<div class="container">
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
                <span style="font-size: 20px">
                    <strong>{{ $data->profile->name }}</strong>
                </span>
            </td>
            <td style="padding-bottom: 0.5em; text-align: right">
                <span style="font-size: 20px">
                    <strong>Invoice</strong>
                </span>
            </td>
        </tr>
        <tr>
            <td class="text" style="font-size: 11px">
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
            <td class="text" valign="top" style="text-align: right">
                <table align="right">
                    <tr>
                        <td>Date</td>
                        <td>:</td>
                        <td style="text-align: right">{{ \Carbon\Carbon::parse($data->order_date)->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <td>Invoice #</td>
                        <td>:</td>
                        <td style="text-align: right">
                            <strong>
                                {{ $data->job_id }}
                            </strong>
                        </td>
                    </tr>

{{--
                    @if ($data->isPaymentPaid())
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td style="text-align: right">Paid</td>
                        </tr>
                        <tr>
                            <td>Payment Datetime</td>
                            <td>:</td>
                            <td style="text-align: right">{{ $data->paymentLog->payment_datetime }}</td>
                        </tr>
                    @endif --}}
                </table>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td>
                <div class="items">
                <table align="left">
                    <tr style="font-size: 16px">
                        <td>
                            <strong>Bill To</strong>
                        </td>
                    </tr>
                    <tr style="font-size: 11px">
                        <td>
                            {{ $data->customer->is_company ?  $data->customer->company_name : $data->customer->user->name}}
                        </td>
                    </tr>
                    <tr style="font-size: 11px">
                        <td>
                            {{ $data->customer->user->phone_number }}
                        </td>
                    </tr>
                    <tr style="font-size: 11px">
                        <td>
                            {{ $data->customer->user->email }}
                        </td>
                    </tr>
                </table>
                </div>
            </td>
            <td >
                <span style="font-size: 16px">
                    <strong>Deliver To</strong>
                </span>
            </td>
        </tr>
    </table>

@php
    // dd($data->deals->toArray());
@endphp
    <div class="items" style="padding-top: 50px;">
        <table width="100%" >
            <thead>
            <tr>
                <th align="left">#</th>
                <th align="left">Description</th>
                <th align="right">Price</th>
                <th align="right">Quantity</th>
                <th align="right">Total</th>
            </tr>
            </thead>
            <tbody>
                @foreach($data->deals as $index => $item)
                    <tr>
                        <td width="20px">{{ $index + 1 }}.</td>
                        <td>
                            {{ $item->product->name }}
                            @if($item->description)
                                <br>
                                <span style="font-size: 10px">{{ $item->description }}</span>
                            @endif
                        </td>
                        <td align="right">{{ $item->price }}</td>
                        <td align="right">{{ $item->qty }}</td>
                        <td align="right">{{ $item->amount }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td align="right" colspan="4">Total: </td>
                    <td align="right">{{ $data->grand_total }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="footer" style="width: 100%; text-align: center;">
        <div style="position: absolute; width: 100%; bottom: 0;">
            <table width="100%">
                <tr>
                    <td style="padding-bottom: 10px; text-align: center">
                        This is computer generated invoice, no signature is required.
                    </td>
                </tr>
{{--
                <tr>
                    <td style="text-align: center">
                        <p>
                            Powered by <img align="middle" width="100px" src="{{ public_path('/img/icon.png') }}">
                        </p>
                    </td>
                </tr> --}}
            </table>
        </div>
    </div>
</div>




</body>
</html>
