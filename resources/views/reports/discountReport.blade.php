@include('layouts.navbar')

<style>
    .thead {
        position: sticky;
        top: 4rem;
        left: 0;
        width: 100%;
        background-color: #FFFFFF;
    }

    .fixed-header {
        background-color: #d8e3f4;
    }

    @media print {
        @page {
            size: landscape;
        }

        #discountReportForm {
            visibility: hidden;
        }

        #discountReportPrintBtn {
            visibility: hidden;
        }

        #navbarButtonsSidebar {
            visibility: hidden;
        }

        #mainNavbar {
            visibility: hidden;
        }

        #discountReportTable {
            position: absolute;
            top: 0;
        }

        .thead {
            position: inherit;
        }

        tr,
        td {
            border: 1px solid #111;
        }

        .fixed-header {
            background: none;
        }
    }
</style>

<div class="container mt-2 mb-3" id="discountReportForm">
    <h6 class="text-center">Discount Report</h6>
    <form action="{{ route('discountReport') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label for="" class="form-label fs-07rem">Select Salesperson</label>
                <select name="userId" id="userId" class="form-select fs-07rem p-1">
                    <option value="all" selected>All Salesperson</option>
                    @foreach ($salesPersons as $item)
                        @if ($item->assign_to)
                            <option value="{{ $item->id }}">{{ $item->assign_to }} - {{ $item->user_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label fs-07rem">Select Brand</label>
                <select name="brand" id="brand" class="form-select fs-07rem p-1">
                    <option value="all" selected>All Brands</option>
                    @foreach ($brands as $item)
                        <option value="{{ $item->brand_name }}">{{ $item->brand_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label fs-07rem">Invoice Date Range <span
                        class="text-danger">*</span></label>
                <input type="text" name="invoiceDateFilter" class="flatpickr form-control  fs-07rem p-1" required>
            </div>
            <div class="col-md-3">
                {{-- <label for="" class="form-label">Invoice Date Start</label> --}}
                <button class="btn btn-darkblue mt-4 w-100">Pull Report</button>
            </div>
        </div>
    </form>
</div>

@if (isset($reportData) && count($reportData) > 0)
    <div class="m-2">
        <button id="discountReportPrintBtn" onclick="printDiscountReport()"
            class="btn btn-darkblue btm-sm fs-07rem p-1 float-end m-2">Print Report</button>
        {{-- <center>
            <h7>Discount Report</h7><br>
            <small>Invoice Date From: {{ date('d-M-Y', strtotime($fromDate)) }} To:
                {{ date('d-M-Y', strtotime($toDate)) }} </small>
        </center> --}}
        <div id="discountReportTable">
            <table class="table table-bordered fs-06rem table-hover">
                <thead class="thead">
                    <tr>
                        <td colspan="19" class="p-1 text-center">PNL Holdings Limited - Discount Report</td>
                    </tr>
                    <tr>
                        <td colspan="19" class="p-1 text-center">Invoice Date From:
                            {{ date('d-M-Y', strtotime($fromDate)) }}
                            To:
                            {{ date('d-M-Y', strtotime($toDate)) }}</td>
                    </tr>
                    <tr class="fixed-header">
                        <td class="p-1 text-center">Invoice Number</td>
                        <td class="p-1 text-center">Invoice Date</td>
                        <td class="p-1 text-center">Employee</td>
                        <td class="p-1 text-center">Customer Code</td>
                        <td class="p-1 text-center">Customer Name</td>
                        <td class="p-1 text-center">Brand</td>
                        <td class="p-1 text-center">Type</td>
                        <td class="p-1 text-center">Item Code</td>
                        <td class="p-1 text-center">Item Name</td>
                        <td class="p-1 text-center">MRP</td>
                        <td class="p-1 text-center">Qty.</td>
                        <td class="p-1 text-center">Total Price</td>
                        <td class="p-1 text-center">Total Discount</td>
                        <td class="p-1 text-center">Total Discount (%)</td>
                        <td class="p-1 text-center">Trade Discount</td>
                        <td class="p-1 text-center">Trade Discount (%)</td>
                        <td class="p-1 text-center">Special Discount</td>
                        <td class="p-1 text-center">Special Discount (%)</td>
                        <td class="p-1 text-center">Net Price</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reportData as $item)
                        @php
                            $totalPrice = $item->unit_price * $item->qty;
                            $totalTradeDiscount = $totalPrice * ($item->trade_discount / 100);
                            $specialDiscount = max($item->discount_price - $totalTradeDiscount, 0);
                            $specialDiscountPer = max($item->discount_percentage - $item->trade_discount, 0);
                            if ($item->spare_parts == 0) {
                                $type = 'Items';
                            } else {
                                $type = 'Spare Parts';
                            }
                            $netPrice = $totalPrice - $item->discount_price;
                        @endphp
                        <tr>
                            <td class="p-1 text-center">{{ $item->sap_invoice }}</td>
                            <td class="p-1">{{ date('d-M-Y', strtotime($item->invoice_date)) }}</td>
                            <td class="p-1">{{ $item->user_name }}</td>
                            <td class="p-1">{{ $item->sap_id }}</td>
                            <td class="p-1">{{ $item->customer_name }}</td>
                            <td class="p-1">{{ $item->brand_name }}</td>
                            <td class="p-1">{{ $type }}</td>
                            <td class="p-1">{{ $item->product_code }}</td>
                            <td class="p-1">{{ $item->mat_name }}</td>
                            <td class="p-1">{{ $item->unit_price }}</td>
                            <td class="p-1 text-center">{{ $item->qty }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $totalPrice, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->discount_price, 2, '.', ',') }}
                            </td>
                            <td class="p-1 text-center">{{ $item->discount_percentage }}%</td>
                            <td class="p-1 text-end">{{ number_format((float) $totalTradeDiscount, 2, '.', ',') }}</td>
                            <td class="p-1 text-center">{{ $item->trade_discount }}%</td>
                            <td class="p-1 text-end">{{ number_format((float) $specialDiscount, 2, '.', ',') }}</td>
                            <td class="p-1 text-center">{{ $specialDiscountPer }}%</td>
                            <td class="p-1 text-end">{{ number_format((float) $netPrice, 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<script>
    $("#userId").select2({
        allowClear: false
    });

    const myInput = document.querySelector(".flatpickr");
    const fp = flatpickr(myInput, {
        mode: "range",
        dateFormat: "d-M-Y",
        defaultDate: [new Date(), new Date()]
    }); // flatpickr

    function printDiscountReport() {
        window.print();
    }
</script>
