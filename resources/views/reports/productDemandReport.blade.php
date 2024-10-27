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

        #productDemandReportForm {
            visibility: hidden;
        }

        #productDemandReportPrintBtn {
            visibility: hidden;
        }

        #navbarButtonsSidebar {
            visibility: hidden;
        }

        #mainNavbar {
            visibility: hidden;
        }

        #productDemandReportTable {
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

<div class="container mt-2 mb-3" id="productDemandReportForm">
    <h6 class="text-center">Product Demand List</h6>
    <form action="{{ route('productDemandReport') }}" method="POST">
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
                <label for="" class="form-label fs-07rem">Type Year <span class="text-danger">*</span></label>
                <input type="number" name="searchYear" class="form-control fs-07rem"
                    placeholder="Type your search year" required>
            </div>
            <div class="col-md-3">
                <button class="btn btn-darkblue mt-4 w-100">Pull Report</button>
            </div>
        </div>
    </form>
</div>

@if (isset($reportData))
    @if (count($reportData) > 0)
        <div class="m-2">
            <button id="productDemandReportPrintBtn" onclick="exportExcel()"
                class="btn btn-darkblue btm-sm fs-07rem p-1 float-end m-2">Excel Report</button>
            <button id="productDemandReportPrintBtn" onclick="printProductDemandReport()"
                class="btn btn-darkblue btm-sm fs-07rem p-1 float-end m-2">Print Report</button>

            <div id="productDemandReportTable">
                <table class="table table-bordered border-dark fs-07rem table-hover">
                    <thead class="thead">
                        <tr>
                            <td colspan="11" class="p-1 text-center">
                                <center>PNL Holdings Limited - Product Demand Report</center>
                            </td>
                        </tr>
                        <tr class="fixed-header">
                            <td class="p-1 text-center">Item Type</td>
                            <td class="p-1 text-center">Item Brand</td>
                            <td class="p-1 text-center">Item Name</td>
                            <td class="p-1 text-center">Item Quantity</td>
                            <td class="p-1 text-center">Item Description</td>
                            <td class="p-1 text-center">Customer Name</td>
                            <td class="p-1 text-center">Customer Phone</td>
                            <td class="p-1 text-center">Created By</td>
                            <td class="p-1 text-center">Created At</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportData as $key => $item)
                            <tr>
                                <td class="p-1">{{ $item->item_type }}</td>
                                <td class="p-1 text-center">{{ $item->item_brand }}</td>
                                <td class="p-1">{{ $item->item_name }}</td>
                                <td class="p-1 text-center">
                                    {{ number_format((float) $item->item_quantity, 2, '.', ',') }}
                                </td>
                                <td class="p-1">{{ $item->item_description }}</td>
                                <td class="p-1">{{ $item->customer_name }}</td>
                                <td class="p-1 text-center">{{ $item->customer_phone }}</td>
                                <td class="p-1">{{ $item->createdBy->user_name }}</td>
                                <td class="p-1 text-center">{{ date('d-M-Y', strtotime($item->created_at)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div>
            <center>
                <h4 class="bg-danger text-white p-2">No Record Found</h4>
            </center>
        </div>
    @endif
@endif

<script>
    $("#userId").select2({
        allowClear: false
    });

    function printProductDemandReport() {
        window.print();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/table2excel@1.0.4/dist/table2excel.min.js"></script>
<script>
    function exportExcel() {
        let table2excel = new Table2Excel();
        let fileName = 'Product Demand Report.xlsx';
        table2excel.export(document.querySelector("#productDemandReportTable table"), fileName);
    }
</script>
