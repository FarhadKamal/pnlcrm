@include('layouts.navbar')
<div>
    @if (session('success'))
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: "<?= session('success') ?>",
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    @endif
</div>
<div class="m-2 float-end">
    <a href="{{ route('detailsLog', ['leadId' => $leadInfo->id]) }}" target="_blank"><button
            class="btn btn-darkblue btm-sm fs-07rem p-1">Details Log</button></a>
</div>
<div class="container-fluid mb-3 mt-2">
    <center>
        <h4 class="mt-3">Credit Set Form</h4>
    </center>
    <hr>
    <div class="row container-fluid">
        <div class="col-md-6 col-sm-6">
            <h6 class="text-center"><kbd>Lead Information</kbd></h6>
            <div class="container fs-09rem">
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Client SAP ID</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->sap_id }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Client</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->customer_name }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Group Name</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->group_name }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Address</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->address }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">District</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->district }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Division</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->division }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Tin</p>
                    @if ($leadInfo->clientInfo->tin)
                        <small class="col-md-8"> <a
                                href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->tin }}"
                                target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                        class="fas fa-eye"></i></button></a>
                            <a href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->tin }}" target="_blank"
                                download><button class="btn btn-primary btn-sm p-1"><i
                                        class="fas fa-download"></i></button></a></small>
                    @else
                        <small class="col-md-8">N/A</small>
                    @endif
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">BIN</p>
                    @if ($leadInfo->clientInfo->tin)
                        <small class="col-md-8"> <a
                                href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->bin }}"
                                target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                        class="fas fa-eye"></i></button></a>
                            <a href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->bin }}"
                                target="_blank" download><button class="btn btn-primary btn-sm p-1"><i
                                        class="fas fa-download"></i></button></a></small>
                    @else
                        <small class="col-md-8">N/A</small>
                    @endif
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Trade License</p>
                    @if ($leadInfo->clientInfo->tin)
                        <small class="col-md-8"> <a
                                href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->trade_license }}"
                                target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                        class="fas fa-eye"></i></button></a>
                            <a href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->trade_license }}"
                                target="_blank" download><button class="btn btn-primary btn-sm p-1"><i
                                        class="fas fa-download"></i></button></a></small>
                    @else
                        <small class="col-md-8">N/A</small>
                    @endif
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Contact Person</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->contact_person }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Mobile</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->contact_mobile }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Email</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->contact_email }}</small>
                </div>

                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Quotation Ref</p>
                    <?php
                    $checkQuotationFile = DB::select("SELECT quotation_ref,quotation_file,accept_file,quotation_po FROM quotations WHERE quotations.lead_id = '$leadInfo->id' AND quotations.is_accept = 1 ORDER BY quotations.id DESC LIMIT 1");
                    if (isset($checkQuotationFile[0]->quotation_file)) {
                        ?>
                    <small class="col-md-8"><a
                            href="{{ asset('quotations') . '/' . $checkQuotationFile[0]->quotation_file }}"
                            target="_blank"><small
                                class="badge badge-info">{{ $checkQuotationFile[0]->quotation_ref }}</small></a></small>
                    <?php 
                    }else{
                        ?>
                    <small class="col-md-8">{{ $checkQuotationFile[0]->quotation_ref }}</small>
                    <?php 
                    }
                    ?>

                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Purchase Order</p>
                    <?php
                    if (isset($checkQuotationFile[0]->accept_file)){
                    ?>
                    <small class="col-md-8"><a
                            href="{{ asset('leadQuotationAcceptAttachment') . '/' . $checkQuotationFile[0]->accept_file }}"
                            target="_blank"><small
                                class="badge badge-info">{{ $checkQuotationFile[0]->quotation_po }}</small></a></small>
                    <?php 
                    }else{
                    ?>
                    <small class="col-md-8">{{ $checkQuotationFile[0]->quotation_po }}</small>
                    <?php 
                    }
                    ?>
                </div>
            </div>

            <div class="mt-3">
                <center>
                    <h6>Current Customer Position</h6>
                </center>
                <table class="table table-bordered fs-07rem">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="p-1 fw-bold text-center">Lead Creation</th>
                            <th class="p-1 fw-bold text-center">Pending Task</th>
                            <th class="p-1 fw-bold text-center">Pay Type</th>
                            <th class="p-1 fw-bold text-center">Credit Set</th>
                            <th class="p-1 fw-bold text-center">Invoice No</th>
                            <th class="p-1 fw-bold text-center">Invoice Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customerStageInfo as $item)
                            <tr>
                                <td class="p-1"><kbd>Lead Id: {{ $item->id }}</kbd></td>
                                <td class="p-1"> {{ date('d-M-Y', strtotime($item->created_at)) }}
                                </td>
                                <td class="p-1">{{ $item->salesLog[count($item->salesLog) - 1]->log_next }}</td>
                                @if ($item->payment_type)
                                    <td class="p-1">{{ $item->payment_type }}</td>
                                @else
                                    <td class="p-1">N/A</td>
                                @endif
                                <td class="p-1">{{ $item->creditAmt }}</td>
                                @if ($item->sap_invoice)
                                    <td class="p-1">{{ $item->sap_invoice }}</td>
                                @else
                                    <td class="p-1">Pending</td>
                                @endif
                                @if ($item->invoice_date)
                                    <td class="p-1">{{ date('d-M-Y', strtotime($item->invoice_date)) }}</td>
                                @else
                                    <td class="p-1">Pending</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">

            <h6 class="text-center"><kbd>Item & Transaction Summary</kbd></h6>
            <table class="table table-bordered fs-08rem">
                <thead>
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
                            $prName = $pumps->productInfo->mat_name;
                        } else {
                            $brandName = $pumps->spareInfo->brand_name;
                            $prName = $pumps->spareInfo->mat_name;
                        }
                        ?>
                        <tr>
                            <td class="p-1">{{ $brandName }}</td>
                            <td class="p-1">{{ $prName }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $pumps->unit_price, 2, '.', ',') }}</td>
                            <td class="p-1 text-center">{{ $pumps->qty }}</td>
                            <td class="p-1 text-center">{{ $pumps->discount_percentage }}</td>
                            <td class="p-1 text-end">{{ number_format((float) $pumps->discount_price, 2, '.', ',') }}
                            </td>
                            <td class="p-1 text-end">{{ number_format((float) $pumps->net_price, 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="p-1 text-center fw-bold">Total</th>
                        <th class="p-1 text-end fw-bold">{{ number_format((float) $totalDiscountAmt, 2, '.', ',') }}
                        </th>
                        <th class="p-1 text-end fw-bold">{{ number_format((float) $totalNetPrice, 2, '.', ',') }}</th>
                    </tr>
                </tfoot>
            </table>
            <table class="table table-bordered fs-08rem log-table text-center">
                <thead>
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
                        <td class="p-1">{{ number_format((float) $totalNetPrice - $totalPaid, 2, '.', ',') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-4">Payment Type: <span
                        class="text-white p-1 rounded fw-bold bg-darkblue">{{ $leadInfo->payment_type }}</span></div>
                <div class="col-md-4">AIT Amount: <span
                        class="text-white p-1 rounded fw-bold bg-darkblue">{{ $leadInfo->aitAmt }}</span></div>
                <div class="col-md-4">VAT Amount: <span
                        class="text-white p-1 rounded fw-bold bg-darkblue">{{ $leadInfo->vatAmt }}</span></div>
            </div>
            <br>
            <h6 class="text-center"><kbd>SAP Credit Information</kbd></h6>
            <center><small class="text-danger">Please cross check with purchase order</small></center>

            <form action="{{ route('creditSetInsertion') }}" method="POST" id="sapCreationForm">
                @csrf
                <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
                <label for="" class="fs-08rem">SAP Credit Limit</label>
                <input type="number" class="form-control fs-08rem p-1" name="creditLimit" required>
                <label for="" class="fs-08rem">SAP Credit Remarks</label><br>
                <textarea name="creditLimitRemark" class="form-control fs-08rem p-1" rows="3"></textarea>
                <br>
                <center><button class="btn btn-sm btn-darkblue">Submit Credit Form</button></center>
            </form>
            <br>

            <h6 class="text-center"><kbd>Hold SAP Credit Set Process</kbd></h6>
            <form action="{{ route('creditSetHold') }}" method="POST" id="sapCreationHoldForm">
                @csrf
                <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
                <label for="" class="fs-08rem">Hold Reason</label><br>
                <textarea name="creditHoldRemark" class="form-control fs-08rem p-1" rows="3" required></textarea>
                <br>
                <center><button class="btn btn-sm btn-danger">Hold Credit Set</button></center>
            </form>
            <br><br>
        </div>
    </div>
</div>

<script>
    $('#sapCreationForm').submit(function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }
        var form = e;
        Swal.fire({
            title: 'Are you sure?',
            text: "Once submitted, you will not be able to undo it!",
            icon: "warning",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Confirm transaction',
            // denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                form.delegateTarget.submit()
            } else {
                Swal.fire('Something is wrong', '', 'info')
            }
        })
    });

    $('#sapCreationHoldForm').submit(function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }
        var form = e;
        Swal.fire({
            title: 'Are you sure to hold credit set process?',
            // text: "Once submitted, you will not be able to undo it!",
            icon: "warning",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Confirm Hold',
            // denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                form.delegateTarget.submit()
            } else {
                Swal.fire('Hold is not done', '', 'info')
            }
        })
    });
</script>
