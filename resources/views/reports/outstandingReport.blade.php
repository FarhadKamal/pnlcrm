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

        #outstandingReportForm {
            visibility: hidden;
        }

        #outstandingReportPrintBtn {
            visibility: hidden;
        }

        #navbarButtonsSidebar {
            visibility: hidden;
        }

        #mainNavbar {
            visibility: hidden;
        }

        #outstandingReportTable {
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

<div class="container mt-2 mb-3" id="outstandingReportForm">
    <h6 class="text-center">Outstanding Report</h6>
    <form action="{{ route('outstandingReport') }}" method="POST">
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
                <label for="" class="form-label fs-07rem">Select Customer</label>
                <select name="customerId" id="customerId" class="form-select fs-07rem p-1">
                    <option value="all" selected>All Customer</option>
                    @foreach ($customerList as $item)
                        <option value="{{ $item->id }}">{{ $item->customer_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="" class="form-label fs-07rem">As On Date<span class="text-danger">*</span></label>
                <input type="text" name="filterDate" class="flatpickr form-control  fs-07rem p-1" required>
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
        <button id="outstandingReportPrintBtn" onclick="printOutstandingReport()"
            class="btn btn-darkblue btm-sm fs-07rem p-1 float-end m-2">Print Report</button>
        {{-- <center>
            <h7>Discount Report</h7><br>
            <small>Invoice Date From: {{ date('d-M-Y', strtotime($fromDate)) }} To:
                {{ date('d-M-Y', strtotime($toDate)) }} </small>
        </center> --}}
        <div id="outstandingReportTable">
            <table class="table table-bordered fs-06rem table-hover">
                <thead class="thead">
                    <tr>
                        <td colspan="11" class="p-1 text-center">PNL Holdings Limited - Outstanding Report</td>
                    </tr>
                    <tr>
                        <td colspan="11" class="p-1 text-center">As On Date:
                            {{ date('d-M-Y', strtotime($filterDate)) }}
                    </tr>
                    <tr class="fixed-header">
                        <td class="p-1 text-center">BD-Code</td>
                        <td class="p-1 text-center">Employee</td>
                        <td class="p-1 text-center">Customer Code</td>
                        <td class="p-1 text-center">Customer Name</td>
                        <td class="p-1 text-center">Net Due</td>
                        <td class="p-1 text-center">Within 30 Days</td>
                        <td class="p-1 text-center">31 to 60 Days</td>
                        <td class="p-1 text-center">61 to 90 Days</td>
                        <td class="p-1 text-center">91 to 180 Days</td>
                        <td class="p-1 text-center">180+ Days</td>
                        <td class="p-1 text-center">365+ Days</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalNetDue = 0;
                        $dueWithin30Total = 0;
                        $dueWithin31_60Total = 0;
                        $dueWithin61_90Total = 0;
                        $dueWithin91_180Total = 0;
                        $dueWithin180plusTotal = 0;
                        $dueWithin365plusTotal = 0;
                    @endphp
                    @foreach ($reportData as $key => $item)
                        @php
                            if (
                                isset($reportData[$key + 1]) &&
                                $reportData[$key]->assign_to == $reportData[$key + 1]->assign_to
                            ) {
                                $totalNetDue = $totalNetDue + $item->netDue;
                                $dueWithin30Total = $dueWithin30Total + $item->dueWithin30;
                                $dueWithin31_60Total = $dueWithin31_60Total + $item->dueWithin31_60;
                                $dueWithin61_90Total = $dueWithin61_90Total + $item->dueWithin61_90;
                                $dueWithin91_180Total = $dueWithin91_180Total + $item->dueWithin91_180;
                                $dueWithin180plusTotal = $dueWithin180plusTotal + $item->dueWithin180plus;
                                $dueWithin365plusTotal = $dueWithin365plusTotal + $item->dueWithin365plus;
                            } elseif (!isset($reportData[$key + 1])) {
                                $totalNetDue = $totalNetDue + $item->netDue;
                                $dueWithin30Total = $dueWithin30Total + $item->dueWithin30;
                                $dueWithin31_60Total = $dueWithin31_60Total + $item->dueWithin31_60;
                                $dueWithin61_90Total = $dueWithin61_90Total + $item->dueWithin61_90;
                                $dueWithin91_180Total = $dueWithin91_180Total + $item->dueWithin91_180;
                                $dueWithin180plusTotal = $dueWithin180plusTotal + $item->dueWithin180plus;
                                $dueWithin365plusTotal = $dueWithin365plusTotal + $item->dueWithin365plus;
                            }
                        @endphp
                        <tr>
                            <td class="p-1 text-center">{{ $item->assign_to }}</td>
                            <td class="p-1">{{ $item->user_name }}</td>
                            <td class="p-1">{{ $item->sap_id }}</td>
                            <td class="p-1">{{ $item->customer_name }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->netDue, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->dueWithin30, 2, '.', ',') }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $item->dueWithin31_60, 2, '.', ',') }}
                            </td>
                            <td class="p-1 text-end">{{ number_format((float) $item->dueWithin61_90, 2, '.', ',') }}
                            </td>
                            <td class="p-1 text-end">{{ number_format((float) $item->dueWithin91_180, 2, '.', ',') }}
                            </td>
                            <td class="p-1 text-end">{{ number_format((float) $item->dueWithin180plus, 2, '.', ',') }}
                            </td>
                            <td class="p-1 text-end">{{ number_format((float) $item->dueWithin365plus, 2, '.', ',') }}
                            </td>
                        </tr>
                        <?php 
                            if (isset($reportData[$key + 1]) && $reportData[$key]->assign_to == $reportData[$key + 1]->assign_to){
                                
                            }else{
                                ?>
                        <tr style="background-color: #F4DFDF">
                            <td class="p-1 text-center fw-bold" colspan="4">Total</td>
                            <td class="p-1 text-end fw-bold">{{ number_format((float) $totalNetDue, 2, '.', ',') }}
                            </td>
                            <td class="p-1 text-end fw-bold">
                                {{ number_format((float) $dueWithin30Total, 2, '.', ',') }}</td>
                            <td class="p-1 text-end fw-bold">
                                {{ number_format((float) $dueWithin31_60Total, 2, '.', ',') }}</td>
                            <td class="p-1 text-end fw-bold">
                                {{ number_format((float) $dueWithin61_90Total, 2, '.', ',') }}</td>
                            <td class="p-1 text-end fw-bold">
                                {{ number_format((float) $dueWithin91_180Total, 2, '.', ',') }}</td>
                            <td class="p-1 text-end fw-bold">
                                {{ number_format((float) $dueWithin180plusTotal, 2, '.', ',') }}</td>
                            <td class="p-1 text-end fw-bold">
                                {{ number_format((float) $dueWithin365plusTotal, 2, '.', ',') }}</td>
                        </tr>
                        <?php
                                $totalNetDue =0;
                                $dueWithin30Total =0;
                                $dueWithin31_60Total = 0;
                                $dueWithin61_90Total =0;
                                $dueWithin91_180Total = 0;
                                $dueWithin180plusTotal = 0;
                                $dueWithin365plusTotal = 0;
                            }
                        ?>
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
    $("#customerId").select2({
        allowClear: false
    });

    const myInput = document.querySelector(".flatpickr");
    const fp = flatpickr(myInput, {
        dateFormat: "d-M-Y",
        defaultDate: new Date()
    }); // flatpickr

    function printOutstandingReport() {
        window.print();
    }
</script>