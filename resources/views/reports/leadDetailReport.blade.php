@include('layouts.navbar')

@if (session('swError'))
    <div>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: '<?= session('swError') ?>',
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    </div>
@endif


<style>
    .select2-search__field {
        font-size: 0.7rem;
    }

    .select2-selection__rendered {
        font-size: 0.7rem;
    }
</style>

<div class="container mt-2 mb-3">
    <h6 class="text-center">Lead Detail Report</h6>
    <form action="{{ route('leadDetailReport') }}" method="POST">
        @csrf
        <div class="row">
            {{-- <small class="fs-07rem text-danger">Search By Lead Id or Invoice Number</small>
            <div class="col-md-2">
                <label for="" class="form-label fs-07rem">By Lead ID</label>
                <input type="number" min="0" class="form-control fs-07rem p-1" id="byLeadId"
                    onkeyup="checkActiveInput(this)">
            </div>
            <div class="col-md-2">
                <label for="" class="form-label fs-07rem">By Invoice Number</label>
                <input type="number" min="0" class="form-control fs-07rem p-1" id="byInvoiceId"
                    onkeyup="checkActiveInput(this)">
            </div> --}}
            <div class="col-md-6">
                <label for="" class="form-label fs-07rem">Select A Lead</label>
                <select name="leadId" id="leadId" class="form-select fs-07rem p-1">
                    @foreach ($ownLead as $item)
                        <option value="{{ $item->id }}">
                            Lead:{{ $item->id }}|{{ $item->clientInfo->customer_name }}|Invoice:{{ $item->sap_invoice }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-darkblue mt-4 w-100">Pull Report</button>
            </div>
        </div>
    </form>
    <hr>
    @if (isset($salesLog))
        <div class="row mt-5 border">
            <center>
                <h5><kbd>Lead ID: {{ $leadInfo->id }}</kbd></h5>
            </center>
            <br><br>
            <div class="col-md-5 col-sm-5 mb-3" style="background:rgb(240, 240, 240)">
                <h6 class="text-center"><kbd>Lead Information</kbd></h6>
                <div class="container fs-09rem">
                    <div class="row border-bottom p-1">
                        <p class="col-md-4 text-muted m-0">Client SAP ID</p>
                        @if ($leadInfo->clientInfo->sap_id)
                            <small class="col-md-8">{{ $leadInfo->clientInfo->sap_id }}</small>
                        @else
                            <small class="col-md-8">N/A</small>
                        @endif
                    </div>
                    <div class="row border-bottom p-1">
                        <p class="col-md-4 text-muted m-0">Name</p>
                        <small class="col-md-8">{{ $leadInfo->clientInfo->customer_name }}</small>
                    </div>
                    <div class="row border-bottom p-1">
                        <p class="col-md-4 text-muted m-0">Phone</p>
                        <small class="col-md-8">{{ $leadInfo->lead_phone }}</small>
                    </div>
                    <div class="row border-bottom p-1">
                        <p class="col-md-4 text-muted m-0">Email</p>
                        <small class="col-md-8">{{ $leadInfo->lead_email }}</small>
                    </div>
                    <div class="row border-bottom p-1">
                        <p class="col-md-4 text-muted m-0">Address</p>
                        <small class="col-md-8">{{ $leadInfo->clientInfo->address }}</small>
                    </div>
                    <div class="row border-bottom p-1">
                        <p class="col-md-4 text-muted m-0">Lead Source</p>
                        <small class="col-md-8">{{ $leadInfo->source->source_name }}</small>
                    </div>
                    <div class="row border-bottom p-1">
                        <p class="col-md-4 text-muted m-0">Requirement</p>
                        <small class="col-md-8">{{ $leadInfo->product_requirement }}</small>
                    </div>

                    <div class="row border-bottom p-1">
                        <p class="col-md-4 text-muted m-0">Created By</p>
                        <small class="col-md-8">{{ $leadInfo->createdBy->user_name }}</small>
                    </div>
                    <div class="row border-bottom p-1">
                        <p class="col-md-4 m-0 text-info">Assign To</p>
                        <small class="col-md-8 text-info">{{ $leadInfo->clientInfo->assignTo->user_name }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <h6 class="text-center"><kbd>Deal Information</kbd></h6>
                @if (count($pumpInfo) > 0)
                    <table class="table table-bordered fs-08rem">
                        <thead class="table-primary text-center">
                            <tr>
                                <th class="p-1 text-center">Brand</th>
                                <th class="p-1 text-center">Model</th>
                                <th class="p-1 text-center">Unit Price (TK)</th>
                                <th class="p-1 text-center">Qty.</th>
                                <th class="p-1 text-center">Discount %</th>
                                <th class="p-1 text-center">Discount Amount (TK)</th>
                                <th class="p-1 text-center">Net Price (TK)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $totalDiscountAmt = 0;
                            $totalNetPrice = 0; ?>
                            @foreach ($pumpInfo as $pumps)
                                <?php
                                $totalDiscountAmt = $totalDiscountAmt + $pumps->discount_price;
                                $totalNetPrice = $totalNetPrice + $pumps->net_price;
                                if ($pumps->spare_parts == 0) {
                                    $brandName = $pumps->productInfo->brand_name;
                                    $matName = $pumps->productInfo->mat_name;
                                } else {
                                    $brandName = $pumps->spareInfo->brand_name;
                                    $matName = $pumps->spareInfo->mat_name;
                                }
                                ?>
                                <tr>
                                    <td class="p-1">{{ $brandName }}</td>
                                    <td class="p-1">{{ $matName }}</td>
                                    <td class="p-1 text-end">
                                        {{ number_format((float) $pumps->unit_price, 2, '.', ',') }}
                                    </td>
                                    <td class="p-1 text-center">{{ $pumps->qty }}</td>
                                    <td class="p-1 text-center">{{ $pumps->discount_percentage }}</td>
                                    <td class="p-1 text-end">
                                        {{ number_format((float) $pumps->discount_price, 2, '.', ',') }}
                                    </td>
                                    <td class="p-1 text-end">
                                        {{ number_format((float) $pumps->net_price, 2, '.', ',') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="p-1 text-center fw-bold">Total</th>
                                <th class="p-1 text-end fw-bold">
                                    {{ number_format((float) $totalDiscountAmt, 2, '.', ',') }}
                                </th>
                                <th class="p-1 text-end fw-bold">
                                    {{ number_format((float) $totalNetPrice, 2, '.', ',') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <h6>No Item Selection Found</h6>
                @endif

                <h6 class="text-center"><kbd>Transaction Summary</kbd></h6>
                <table class="table table-bordered fs-08rem log-table text-center">
                    <thead class="table-primary text-center">
                        <tr>
                            <th class="p-1">Net Price</th>
                            <th class="p-1">Deposited</th>
                            <th class="p-1">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-1">{{ number_format((float) $totalNetPrice, 2, '.', ',') }}</td>
                            <?php $totalPaid = 0; ?>
                            @foreach ($transactionInfo as $item)
                                <?php
                                if ($item->is_verified == 1) {
                                    $totalPaid = $totalPaid + $item->pay_amount;
                                }
                                ?>
                            @endforeach
                            <td class="p-1">{{ number_format((float) $totalPaid, 2, '.', ',') }}</td>
                            @if ($totalNetPrice - $totalPaid > 0)
                                <td class="p-1 bg-danger text-white fw-bold">
                                    {{ number_format((float) $totalNetPrice - $totalPaid, 2, '.', ',') }}
                                </td>
                            @else
                                <td class="p-1 bg-success text-white fw-bold">
                                    {{ number_format((float) $totalNetPrice - $totalPaid, 2, '.', ',') }}
                                </td>
                            @endif

                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <h6 class="text-center"><kbd>Transaction List</kbd></h6>
                <table class="table table-bordered fs-08rem log-table text-center">
                    <thead class="table-primary text-center">
                        <tr>
                            <th class="p-1">SL.</th>
                            <th class="p-1">Deposit Date</th>
                            <th class="p-1">Taka</th>
                            <th class="p-1">Type</th>
                            <th class="p-1">Transaction Remarks</th>
                            <th class="p-1">Statement Date</th>
                            <th class="p-1">Statement Remarks</th>
                            <th class="p-1">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sl = 1; ?>
                        @foreach ($transactionInfo as $item)
                            @php
                                $type = '';
                                if ($item->transaction_type == 'base') {
                                    $type = 'Base Amount';
                                }
                                if ($item->transaction_type == 'tax') {
                                    $type = 'TAX Amount';
                                }
                                if ($item->transaction_type == 'vat') {
                                    $type = 'VAT Amount';
                                }
                                if ($item->transaction_file) {
                                    $type .=
                                        "<small><a href='" .
                                        asset('transactionAttachment/' . $item->transaction_file) .
                                        "' target='_blank'><small class='badge badge-info'>Attachment</small></a></small>";
                                }
                            @endphp
                            <tr>
                                <td class="p-1">{{ $sl }}</td>
                                <td class="p-1">{{ date('d-M-Y', strtotime($item->deposit_date)) }}</td>
                                <td class="p-1">{{ number_format((float) $item->pay_amount, 2, '.', ',') }}</td>
                                <td class="p-1">{!! $type !!}</td>
                                <td class="p-1">{{ $item->transaction_remarks }}</td>
                                @if ($item->deposited_date)
                                    <td class="p-1">{{ date('d-M-Y', strtotime($item->deposited_date)) }}</td>
                                @else
                                    <td class="p-1"></td>
                                @endif
                                <td class="p-1">{{ $item->deposited_remarks }}</td>
                                @if ($item->is_verified == 0)
                                    <td class="p-1"><button class="btn btn-sm btn-danger badge">unverified</button>
                                    </td>
                                @else
                                    <td class="p-1"><button class="btn btn-sm btn-success badge">verified</button>
                                    </td>
                                @endif
                            </tr>
                            @php
                                $sl++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <h6 class="text-center"><kbd>Details Log Table</kbd></h6>
                <table class="table table-bordered fs-08rem lh-1 log-table">
                    <thead class="table-primary text-center">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Log Date Time</th>
                            <th scope="col">Log Stage</th>
                            <th scope="col">Task</th>
                            <th scope="col">Log By</th>
                            <th scope="col">Waiting For</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sl = count($salesLog); ?>
                        @foreach ($salesLog as $item)
                            <tr>
                                <th scope="row" class="text-center">{{ $sl }}</th>
                                <td class="text-center">{{ date('d-M-Y h:i a', strtotime($item->created_at)) }}</td>
                                <td class="text-center">{{ $item->log_stage }}</td>
                                <?php
                        $baseDatetime = new DateTime($item->created_at);
                        $startDatetime = $baseDatetime->modify('-1 minutes')->format('Y-m-d H:i:s');
                        $endDatetime = $baseDatetime->modify('+2 minutes')->format('Y-m-d H:i:s');
                        if ($item->log_next == 'Quotation feedback') {
                            $checkQuotationFile = DB::select("SELECT quotation_file,is_accept,quotation_po,accept_file FROM quotations WHERE quotations.lead_id = '$leadInfo->id' AND DATE_FORMAT(quotations.created_at, '%Y-%m-%d %H:%i:%s') BETWEEN ('$startDatetime') AND ('$endDatetime')");
                            ?>
                                @if (isset($checkQuotationFile[0]->quotation_file))
                                    <td class="text-center">{{ $item->log_task }} <a
                                            href="{{ asset('quotations') . '/' . $checkQuotationFile[0]->quotation_file }}"
                                            target="_blank"><small class="badge badge-info">VIEW QUOTATION</small></a>
                                        @if ($checkQuotationFile[0]->is_accept == 1 && isset($checkQuotationFile[0]->accept_file))
                                            <a href="{{ asset('leadQuotationAcceptAttachment') . '/' . $checkQuotationFile[0]->accept_file }}"
                                                target="_blank"><small class="badge badge-info">VIEW PO</small></a>
                                        @endif
                                    </td>
                                @else
                                    <td class="text-center">{{ $item->log_task }} </td>
                                @endif
                                <?php 
                        }else {
                           ?>
                                <td class="text-center">{{ $item->log_task }}</td>
                                <?php 
                        }
                        ?>
                                <td class="text-center">{{ $item->logBy->user_name }}</td>
                                <td class="text-center">{{ $item->log_next }}</td>
                            </tr>
                            <?php $sl--; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

{{-- <script>
    function checkActiveInput(e) {
        let activeId = e.id;
        let allInput = document.querySelectorAll('.form-control');
        allInput.forEach(element => {
            if (element.id != activeId) {
                document.getElementById(element.id).value = '';
            }
        });
    }
</script> --}}

<script>
    function formatOption(option) {
        if (!option.id) {
            return option.text; // Return the default text for options without id
        }
        const parts = option.text.split('|');
        const leadId = parts[0].replace('Lead:', '').trim();
        const customerName = parts[1].trim();
        const invoice = parts[2].replace('Invoice:', '').trim();

        return $('<kbd><span>Lead:' + leadId + '</span></kbd>' +
            ' &nbsp;&nbsp; ' + customerName +
            ' &nbsp;&nbsp; ' +
            '<span class="badge badge-success fs-07rem">Invoice:' + invoice + '</span>');
    }
    $("#leadId").select2({
        allowClear: false,
        templateResult: formatOption,
        templateSelection: formatOption
    });
</script>
