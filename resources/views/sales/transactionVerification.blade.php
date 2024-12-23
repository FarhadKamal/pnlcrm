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

<div class="container-fluid mb-3 mt-2">
    <div class="m-2 float-end">
        <a href="{{ route('detailsLog', ['leadId' => $leadInfo->id]) }}" target="_blank"><button
                class="btn btn-darkblue btm-sm fs-07rem p-1">Details Log</button></a>
    </div>
    <center>
        <h4 class="mt-3">Booking Transaction Form</h4>
    </center>
    <div class="bg-darkblue">
        <h5 class="text-center text-white fs-5 p-3 m-0">Payment Mood: {{ $leadInfo->payment_type }}</h5>
    </div>
    <hr>
    <div class="row container-fluid">
        <div class="col-md-5 col-sm-5">
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
                    <small class="col-md-8">{{ $leadInfo->clientInfo->tin }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">BIN</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->bin }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Trade License</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->trade_license }}</small>
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
        </div>
        <div class="col-md-7 col-sm-7">
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
            <h6 class="text-center"><kbd>Transaction Summary</kbd></h6>
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
                <div class="col-md-6">AIT Amount: <span
                        class="text-white p-1 rounded fw-bold bg-darkblue">{{ $leadInfo->aitAmt }}</span></div>
                <div class="col-md-6">VAT Amount: <span
                        class="text-white p-1 rounded fw-bold bg-darkblue">{{ $leadInfo->vatAmt }}</span></div>
            </div>
        </div>
    </div>

    @if ($leadInfo->payment_type == 'Cash')
        <div class="row mt-5 mb-3 container m-auto">
            <h6 class="text-center"><kbd>Booking Transaction List</kbd></h6>
            <table class="table table-bordered fs-08rem log-table text-center">
                <thead>
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
                    <?php $sl = 1;
                    $tranVerifyFlag = 0; ?>
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
                            @if ($item->is_verified == 0)
                                @php
                                    $tranVerifyFlag = 1;
                                @endphp
                                <form action="{{ route('verifiedTransaction') }}" method="POST"
                                    id="verifyTransactionForm">
                                    @csrf
                                    <input type="hidden" name="transactionId" value="{{ $item->id }}">
                                    <input type="hidden" name="transactionAmount" value="{{ $item->pay_amount }}">
                                    <td>
                                        <input type="text" class="flatpickr form-control p-1 fs-07rem mb-2"
                                            name="depositedDate" id="depositedDate" required>

                                        <a href="{{ route('deleteTransaction', ['transactionId' => $item->id]) }}"
                                            class="btn btn-danger fs-06rem p-1">Delete</a>
                                    </td>
                                    <td>
                                        <textarea name="depositedRemarks" id="depositedRemarks" cols="30" rows="2"></textarea>
                                    </td>
                                    <td><button class="btn btn-darkblue btn-sm fs-06rem mt-2">Click Here To
                                            Verify</button>
                                    </td>
                                </form>
                            @else
                                <td>{{ date('d-M-Y', strtotime($item->deposited_date)) }}</td>
                                <td>{{ $item->deposited_remarks }}</td>
                                <td><button class="btn btn-sm btn-success badge">verified</button></td>
                            @endif
                        </tr>
                        @php
                            $sl++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


    @if ($tranVerifyFlag == 1)
        <center>
            <h5 class="badge badge-danger">You have unverified transaction. First verify it / if duplicate input delete
                it.</h5>
        </center>
    @else
        <div class="container">
            <form action="{{ route('accountsClearance') }}" method="POST" id="AccountsClearanceForm">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}">
                <input type="hidden" name="quotation_id" value="{{ $leadInfo->id }}">
                <label for="" class="fs-08rem">Clearance Remarks</label>
                <textarea name="clearRemark" id="clearRemark" class="form-control fs-08rem p-1" rows="3"></textarea>
                @if ($leadInfo->accounts_clearance == 0)
                    <br>
                    <center><button class="btn btn-sm btn-darkblue">Proceed Accounts Clearance</button></center>
                @endif
            </form>
        </div>
    @endif


</div>

<script>
    $(function() {
        $(".flatpickr").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-M-yyyy',
            minDate: 0,
            defaultDate: "+1w",
        }).datepicker('update', new Date());
    });
</script>

<script>
    $('#verifyTransactionForm').submit(function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }

        let proceedFlag = 1;
        let totalNetPrice = '<?php echo $totalNetPrice; ?>';
        let totalPaid = '<?php echo $totalPaid; ?>';
        let balance = Number(totalNetPrice) - Number(totalPaid);
        
        var form = e;
        Swal.fire({
            title: 'Are you sure?',
            text: "Once verified, you will not be able to undo it!",
            icon: "warning",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Confirm transaction',
            // denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                // form.delegateTarget.submit()
            } else {
                Swal.fire('Transaction is not verified', '', 'info')
            }
        })
    });
</script>

<script>
    $('#AccountsClearanceForm').submit(function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }
        var form = e;
        Swal.fire({
            title: 'Are you sure?',
            text: "Once cleared, you will not be able to undo it!",
            icon: "warning",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Confirm clearance',
            // denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                form.delegateTarget.submit()
            } else {
                Swal.fire('Clearance is not succeed', '', 'info')
            }
        })
    });
</script>
