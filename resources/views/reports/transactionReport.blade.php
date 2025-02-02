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

        #transactionReportForm {
            visibility: hidden;
        }

        #transactionReportExcelBtn {
            visibility: hidden;
        }

        #transactionReportPrintBtn {
            visibility: hidden;
        }

        #navbarButtonsSidebar {
            visibility: hidden;
        }

        #mainNavbar {
            visibility: hidden;
        }

        #transactionReportTable {
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

<div class="container mt-2 mb-3" id="transactionReportForm">
    <h6 class="text-center">Transaction Report</h6>
    <form action="{{ route('transactionReport') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label for="" class="form-label fs-07rem">Select Salesperson</label>
                <select name="userId" id="userId" class="form-select fs-07rem p-1">
                    @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'salesPerson'))
                        <option value="{{ Auth()->user()->id }}">{{ Auth()->user()->assign_to }} -
                            {{ Auth()->user()->user_name }}
                        </option>
                    @else
                        <option value="all" selected>All Salesperson</option>
                        @foreach ($salesPersons as $item)
                            @if ($item->assign_to)
                                <option value="{{ $item->id }}">{{ $item->assign_to }} - {{ $item->user_name }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label fs-07rem">Select Customer</label>
                <select name="customerId" id="customerId" class="form-select fs-07rem p-1">
                    @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'salesPerson'))
                        <option value="all" selected>All Customer</option>
                        @foreach (Auth()->user()->clientInfo as $item)
                            <option value="{{ $item->id }}">{{ $item->customer_name }}</option>
                        @endforeach
                    @else
                        <option value="all" selected>All Customer</option>
                        @foreach ($customerList as $item)
                            <option value="{{ $item->id }}">{{ $item->customer_name }}</option>
                        @endforeach
                    @endif

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
        <button id="transactionReportExcelBtn" onclick="exportExcel()"
            class="btn btn-darkblue btm-sm fs-07rem p-1 float-end m-2">Excel Report</button>
        <button id="transactionReportPrintBtn" onclick="printTransactionReport()"
            class="btn btn-darkblue btm-sm fs-07rem p-1 float-end m-2">Print Report</button>
        <div id="transactionReportTable">
            <table class="table table-bordered border-dark fs-07rem table-hover">
                <thead class="thead">
                    <tr>
                        <td colspan="19" class="p-1 text-center">PNL Holdings Limited - Transaction Report</td>
                    </tr>
                    <tr>
                        <td colspan="19" class="p-1 text-center">Invoice Date From:
                            {{ date('d-M-Y', strtotime($fromDate)) }}
                            To:
                            {{ date('d-M-Y', strtotime($toDate)) }}</td>
                    </tr>
                    <tr class="fixed-header">
                        <td class="p-1"></td>
                        <td class="p-1 text-center">Customer Name</td>
                        <td class="p-1 text-center">Customer Code</td>
                        <td class="p-1 text-center">PO Number</td>
                        <td class="p-1 text-center">PO Date</td>
                        <td class="p-1 text-center">Invoice Date</td>
                        <td class="p-1 text-center">Invoice Number</td>
                        <td class="p-1 text-center">Invoice Amount</td>
                        <td class="p-1 text-center">Receive Base Amount</td>
                        <td class="p-1 text-center">Receive Other Amount</td>
                        <td class="p-1 text-center">Outstanding Balance</td>
                        <td class="p-1 text-center">Receive VAT Amount</td>
                        <td class="p-1 text-center">Receive TAX Amount</td>
                        <td class="p-1 text-center">Fraction Adjust</td>
                        <td class="p-1 text-center">Excess Receive</td>
                        <td class="p-1 text-center">Total Outstanding Balance</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotalOutstanding = 0;
                    @endphp
                    @foreach ($reportData as $item)
                        @php
                            $outStandingWithoutVatTax = $item->invoice_amount - $item->baseAmount - $item->otherAmount;
                            $outStandingTotal = $outStandingWithoutVatTax - ($item->vatAmount + $item->taxAmount);
                            $grandTotalOutstanding = $grandTotalOutstanding + $outStandingTotal;
                        @endphp
                        <tr>
                            <td class="p-1"><small class="badge badge-success">Lead ID: {{ $item->id }}</small></td>
                            <td class="p-1">{{ $item->customer_name }}</td>
                            <td class="p-1 text-center">{{ $item->sap_id }}</td>
                            <td class="p-1 text-center">{{ $item->quotation_po }}</td>
                            <td class="p-1">{{ date('d-M-Y', strtotime($item->quotation_po_date)) }}</td>
                            <td class="p-1">{{ date('d-M-Y', strtotime($item->invoice_date)) }}</td>
                            <td class="p-1 text-center"><a href="{{ route('detailsLog', ['leadId' => $item->id]) }}"
                                    target="_blank">{{ $item->sap_invoice }}</a></td>
                            <td class="p-1 text-end">{{ number_format((float) $item->invoice_amount, 2, '.', ',') }}
                            </td>
                            <td class="p-1 text-end">{{ number_format((float) $item->baseAmount, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->otherAmount, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">
                                {{ number_format((float) $outStandingWithoutVatTax, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->vatAmount, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->taxAmount, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->fractionAmount, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->excessAmount, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $outStandingTotal, 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #c49e77">
                        <td colspan="15" class="p-1 text-center fw-bold">Grand Total</td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) $grandTotalOutstanding, 2, '.', ',') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif

<script>
    $("#userId").select2({
        allowClear: false
    });
    $("#customerId").select2({
        allowClear: false
    });

    const myInput = document.querySelector(".flatpickr");
    const fp = flatpickr(myInput, {
        mode: "range",
        dateFormat: "d-M-Y",
        defaultDate: [new Date(), new Date()]
    }); // flatpickr

    function printTransactionReport() {
        window.print();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/table2excel@1.0.4/dist/table2excel.min.js"></script>
<script>
    function exportExcel() {
        let table2excel = new Table2Excel();
        let fileName = 'Transaction Report.xlsx';
        table2excel.export(document.querySelector("#transactionReportTable table"), fileName);
    }
</script>
