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

        #targetSalesReportForm {
            visibility: hidden;
        }

        #targetSalesReportPrintBtn {
            visibility: hidden;
        }

        #targetSalesReportExcelBtn {
            visibility: hidden;
        }

        #navbarButtonsSidebar {
            visibility: hidden;
        }

        #mainNavbar {
            visibility: hidden;
        }

        #targetSalesReportTable {
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

<div class="container mt-2 mb-3" id="targetSalesReportForm">
    <h6 class="text-center">Target vs Sales Report</h6>
    <form action="{{ route('targetSalesReport') }}" method="POST">
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
                <label for="" class="form-label fs-07rem">Select Financial Year</label>
                <select name="financialYear" id="financialYear" class="form-select fs-07rem p-1" required>
                    @foreach ($financialYear as $item)
                        @php
                            $nextYear = $item->financial_year + 1;
                        @endphp
                        <option value="{{ $item->financial_year }}">{{ $item->financial_year }} -
                            {{ $nextYear }}</option>
                    @endforeach
                </select>
            </div>
            {{-- <div class="col-md-3">
                <label for="" class="form-label fs-07rem">Invoice Date Range <span
                        class="text-danger">*</span></label>
                <input type="text" name="invoiceDateFilter" class="flatpickr form-control  fs-07rem p-1" required>
            </div> --}}
            <div class="col-md-3">
                {{-- <label for="" class="form-label">Invoice Date Start</label> --}}
                <button class="btn btn-darkblue mt-4 w-100">Pull Report</button>
            </div>
        </div>
    </form>
</div>

@if (isset($reportData) && count($reportData) > 0)
    <div class="m-2">
        <button id="targetSalesReportExcelBtn" onclick="exportExcel()"
            class="btn btn-darkblue btm-sm fs-07rem p-1 float-end m-2">Excel Report</button>
        <button id="targetSalesReportPrintBtn" onclick="printDiscountReport()"
            class="btn btn-darkblue btm-sm fs-07rem p-1 float-end m-2">Print Report</button>
        {{-- <center>
            <h7>Discount Report</h7><br>
            <small>Invoice Date From: {{ date('d-M-Y', strtotime($fromDate)) }} To:
                {{ date('d-M-Y', strtotime($toDate)) }} </small>
        </center> --}}
        <div id="targetSalesReportTable">
            <table class="table table-bordered border-dark fs-07rem table-hover">
                <thead class="thead">
                    <tr>
                        <td colspan="18" class="p-1 text-center">PNL Holdings Limited - Target vs Sales Report</td>
                    </tr>
                    <tr>
                        <td colspan="18" class="p-1 text-center">Financial Year: {{ (string) $reportYear }} - {{ (string) ($reportYear+1) }}</td>
                    </tr>
                    <tr class="fixed-header">
                        <td rowspan="2" class="p-1 text-center" style="align-content: start;">BD Code</td>
                        <td rowspan="2" class="p-1 text-center" style="align-content: start;">Sales Person</td>
                        <td colspan="4" class="p-1 text-center">Quarter 1</td>
                        <td colspan="4" class="p-1 text-center">Quarter 2</td>
                        <td colspan="4" class="p-1 text-center">Quarter 3</td>
                        <td colspan="4" class="p-1 text-center">Quarter 4</td>
                    </tr>
                    <tr class="fixed-header" style="background-color: #c49e77">
                        <td class="p-1 text-center">Target</td>
                        <td class="p-1 text-center">Sales</td>
                        <td class="p-1 text-center">Achieve</td>
                        <td class="p-1 text-center">Gap</td>
                        <td class="p-1 text-center">Target</td>
                        <td class="p-1 text-center">Sales</td>
                        <td class="p-1 text-center">Achieve</td>
                        <td class="p-1 text-center">Gap</td>
                        <td class="p-1 text-center">Target</td>
                        <td class="p-1 text-center">Sales</td>
                        <td class="p-1 text-center">Achieve</td>
                        <td class="p-1 text-center">Gap</td>
                        <td class="p-1 text-center">Target</td>
                        <td class="p-1 text-center">Sales</td>
                        <td class="p-1 text-center">Achieve</td>
                        <td class="p-1 text-center">Gap</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotalQ1Target = 0;
                        $grandTotalQ2Target = 0;
                        $grandTotalQ3Target = 0;
                        $grandTotalQ4Target = 0;

                        $grandTotalQ1Sales = 0;
                        $grandTotalQ2Sales = 0;
                        $grandTotalQ3Sales = 0;
                        $grandTotalQ4Sales = 0;
                    @endphp
                    @foreach ($reportData as $item)
                        @php
                            $grandTotalQ1Target = $grandTotalQ1Target + $item->Q1_Target;
                            $grandTotalQ2Target = $grandTotalQ2Target + $item->Q2_Target;
                            $grandTotalQ3Target = $grandTotalQ3Target + $item->Q3_Target;
                            $grandTotalQ4Target = $grandTotalQ4Target + $item->Q4_Target;

                            $grandTotalQ1Sales = $grandTotalQ1Sales + $item->Q1_Sales;
                            $grandTotalQ2Sales = $grandTotalQ2Sales + $item->Q2_Sales;
                            $grandTotalQ3Sales = $grandTotalQ3Sales + $item->Q3_Sales;
                            $grandTotalQ4Sales = $grandTotalQ4Sales + $item->Q4_Sales;

                            $userAchieveQ1 = ($item->Q1_Sales / $item->Q1_Target) * 100;
                            $userGapQ1 = $item->Q1_Target - $item->Q1_Sales;
                            $userAchieveQ2 = ($item->Q2_Sales / $item->Q2_Target) * 100;
                            $userGapQ2 = $item->Q2_Target - $item->Q2_Sales;
                            $userAchieveQ3 = ($item->Q3_Sales / $item->Q3_Target) * 100;
                            $userGapQ3 = $item->Q3_Target - $item->Q3_Sales;
                            $userAchieveQ4 = ($item->Q4_Sales / $item->Q4_Target) * 100;
                            $userGapQ4 = $item->Q4_Target - $item->Q4_Sales;
                        @endphp
                        <tr>
                            <td class="p-1 text-center">{{ $item->assign_to }}</td>
                            <td class="p-1">{{ $item->user_name }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->Q1_Target, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->Q1_Sales, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $userAchieveQ1, 2, '.', ',') }}%</td>
                            <td class="p-1 text-end">{{ number_format((float) $userGapQ1, 2, '.', ',') }}</td>

                            <td class="p-1 text-end">{{ number_format((float) $item->Q2_Target, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->Q2_Sales, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $userAchieveQ2, 2, '.', ',') }}%</td>
                            <td class="p-1 text-end">{{ number_format((float) $userGapQ2, 2, '.', ',') }}</td>

                            <td class="p-1 text-end">{{ number_format((float) $item->Q3_Target, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->Q3_Sales, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $userAchieveQ3, 2, '.', ',') }}%</td>
                            <td class="p-1 text-end">{{ number_format((float) $userGapQ3, 2, '.', ',') }}</td>

                            <td class="p-1 text-end">{{ number_format((float) $item->Q4_Target, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->Q4_Sales, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $userAchieveQ4, 2, '.', ',') }}%</td>
                            <td class="p-1 text-end">{{ number_format((float) $userGapQ4, 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #F4DFDF">
                        <td colspan="2" class="p-1 text-center fw-bold">Grand Total</td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) $grandTotalQ1Target, 2, '.', ',') }}</td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) $grandTotalQ1Sales, 2, '.', ',') }}
                        </td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) ($grandTotalQ1Sales / $grandTotalQ1Target) * 100, 2, '.', ',') }}%
                        </td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) ($grandTotalQ1Target - $grandTotalQ1Sales), 2, '.', ',') }}</td>

                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) $grandTotalQ2Target, 2, '.', ',') }}</td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) $grandTotalQ2Sales, 2, '.', ',') }}
                        </td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) ($grandTotalQ2Sales / $grandTotalQ2Target) * 100, 2, '.', ',') }}%
                        </td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) ($grandTotalQ2Target - $grandTotalQ2Sales), 2, '.', ',') }}</td>

                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) $grandTotalQ3Target, 2, '.', ',') }}</td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) $grandTotalQ3Sales, 2, '.', ',') }}
                        </td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) ($grandTotalQ3Sales / $grandTotalQ3Target) * 100, 2, '.', ',') }}%
                        </td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) ($grandTotalQ3Target - $grandTotalQ3Sales), 2, '.', ',') }}</td>

                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) $grandTotalQ4Target, 2, '.', ',') }}</td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) $grandTotalQ4Sales, 2, '.', ',') }}
                        </td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) ($grandTotalQ4Sales / $grandTotalQ4Target) * 100, 2, '.', ',') }}%
                        </td>
                        <td class="p-1 text-end fw-bold">
                            {{ number_format((float) ($grandTotalQ4Target - $grandTotalQ4Sales), 2, '.', ',') }}</td>
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

    // const myInput = document.querySelector(".flatpickr");
    // const fp = flatpickr(myInput, {
    //     mode: "range",
    //     dateFormat: "d-M-Y",
    //     defaultDate: [new Date(), new Date()]
    // }); // flatpickr

    function printDiscountReport() {
        window.print();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/table2excel@1.0.4/dist/table2excel.min.js"></script>
<script>
    function exportExcel() {
        let table2excel = new Table2Excel();
        let fileName = 'Target vs Sales Report.xlsx';
        table2excel.export(document.querySelector("#targetSalesReportTable table"), fileName);
    }
</script>
