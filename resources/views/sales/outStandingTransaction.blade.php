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
    <center>
        <h4 class="mt-3">Outstanding Transaction Form</h4>
    </center>
    <div class="bg-darkblue">
        <h5 class="text-center text-white fs-5 p-3 m-0">Payment Mood: {{ $leadInfo->payment_type }}</h5>
    </div>
    <span class="badge badge-success fs-1rem float-end">Advance Payment: <span
            id="remainingAdvance">{{ $leadInfo->clientInfo->advance_amount }}</span></span>
    <hr>
    <div class="row">
        @if (!empty($leadInfo->clientInfo->assign_to) && !empty(Auth()->user()->assign_to))
            @if ((string) strpos($leadInfo->clientInfo->assign_to, (string) Auth()->user()->assign_to) !== false)
                <div class="col-md-3">
                    <h6 class="text-center"><kbd>Insert Transaction Form</kbd></h6>
                    <form action="{{ route('insertTransaction') }}" method="POST" enctype="multipart/form-data"
                        class="m-4" id="outTransForm">
                        @csrf
                        <div class="mb-1">
                            <label class="form-label m-0 fs-08rem">Deposit Date <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="transactionDate" id="transactionDate"
                                class="form-control fs-08rem p-1" required>
                        </div>

                        <div class="mb-1">
                            <label class="form-label m-0 fs-08rem">Transaction Type (Base Amount) <span
                                    class="text-danger">*</span></label>
                            <select name="transactionType" id="transactionType" class="form-select fs-08rem p-1"
                                onchange="checkTransType()" required>
                                <option value="" selected disabled>--Select One--</option>
                                <option value="cash">Cash Amount</option>
                                <option value="bank">Bank Amount</option>
                                <option value="advanceAdjust">Advance Adjustment</option>
                                <option value="fractionAdjust">Fraction Adjustment</option>
                                <option value="tax">TAX Amount</option>
                                <option value="vat">VAT Amount</option>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label class="form-label m-0  fs-08rem">Deposit Amount (BDT) <span
                                    class="text-danger">*</span></label>
                            <input name="transactionAmount" id="transactionAmount" type="number" step=".001"
                                class="form-control lh-sm fs-08rem p-1" required>
                        </div>
                        <div class="mb-1">
                            <label class="form-label m-0 fs-08rem">Advance Amount (Paid - Base) (BDT) </label>
                            <input name="advanceAmount" id="advanceAmount" type="number" step=".001"
                                class="form-control lh-sm fs-08rem p-1">
                        </div>
                        <div class="mb-1">
                            <label class="form-label m-0 fs-08rem">Excess Amount (BDT) </label>
                            <input name="excessAmount" id="excessAmount" type="number" step=".001"
                                class="form-control lh-sm fs-08rem p-1">
                        </div>
                        <div class="mb-1">
                            <label class="form-label m-0 fs-07rem">Attachment</label>
                            <input name="transactionFile" id="transactionFile" type="file"
                                accept="image/png, image/jpeg, image/jpg, .pdf" class="form-control lh-sm fs-08rem p-1"
                                required>
                        </div>
                        <div class="mb-1">
                            <label class="form-label m-0 fs-07rem">Remarks</label>
                            <textarea name="transactionRemarks" id="transactionRemarks" cols="30" rows="3"
                                class="form-control fs-08rem p-1"></textarea>
                        </div>
                        <div>
                            <input name="transactionLead" value="{{ $leadId }}" hidden>
                            <input name="transactionQuotation" value="{{ $quotationInfo[0]->id }}" hidden>
                            <button type="submit" class="btn btn-darkblue btn-sm w-100 mt-2">Save Transaction</button>
                        </div>
                    </form>
                </div>
            @endif
        @endif

        <div class="col-md-8">
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
        </div>
    </div>

    <div class="row mt-5 mb-3 container m-auto">
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
                        if ($item->is_verified == 1 && ($item->transaction_type == 'base' || $item->transaction_type == 'vat' || $item->transaction_type == 'tax')) {
                            $totalPaid = $totalPaid + $item->pay_amount;
                        }
                        ?>
                    @endforeach
                    <td class="p-1">{{ number_format((float) $totalPaid, 2, '.', ',') }}</td>
                    <td class="p-1">{{ number_format((float) $totalNetPrice - $totalPaid, 2, '.', ',') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row mt-5 mb-3 container m-auto">
        <h6 class="text-center"><kbd>Booking Transaction List</kbd></h6>
        <table class="table table-bordered fs-08rem log-table text-center">
            <thead>
                <tr>
                    <th class="p-1">SL.</th>
                    <th class="p-1">Deposit Date</th>
                    <th class="p-1">Taka</th>
                    <th class="p-1">Type</th>
                    <th class="p-1">By</th>
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
                        } elseif ($item->transaction_type == 'tax') {
                            $type = 'TAX Amount';
                        } elseif ($item->transaction_type == 'vat') {
                            $type = 'VAT Amount';
                        } else {
                            $type = $item->transaction_type;
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
                        <td class="p-1">{{ $item->transaction_by }}</td>
                        <td class="p-1">{{ $item->transaction_remarks }}</td>

                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'verifyTransaction') && $item->is_verified == 0)
                            <form action="{{ route('verifiedTransaction') }}" method="POST"
                                id="verifyTransactionForm" class="verifyTransactionForm">
                                @csrf
                                <input type="hidden" name="transactionId" value="{{ $item->id }}">
                                <input type="hidden" name="transactionAmount" value="{{ $item->pay_amount }}">
                                <input type="hidden" name="transactionType" value="{{ $item->transaction_type }}">
                                <td>
                                    <input type="text" class="flatpickr form-control p-1 fs-07rem"
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
                        @endif
                    </tr>
                    <?php $sl++; ?>
                @endforeach
            </tbody>
        </table>
    </div>


    <div>
        @if ($leadInfo->is_outstanding == 1)
            <center>
                <h5 class="badge badge-danger">Waiting For Outstanding Clearance</h5>
            </center>
        @endif
    </div>
    @if ($leadInfo->is_outstanding == 1 && App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'accountsClearance'))
        @if (number_format((float) $totalNetPrice - $totalPaid, 2, '.', ',') > 0)
            <center>
                <h5 class="badge badge-danger">Balance Need To Zero For Outstanding Clearance</h5>
            </center>
        @else
            <div class="container">
                <form action="{{ route('outstandingsClearance') }}" method="POST" id="OutstandingsClearanceForm">
                    @csrf
                    <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}">
                    <label for="" class="fs-08rem">Clearance Remarks</label>
                    <textarea name="clearRemark" id="clearRemark" class="form-control fs-08rem p-1" rows="3"></textarea>
                    <br>
                    <center><button class="btn btn-sm btn-darkblue">Proceed Outstanding Clearance</button></center>
                </form>
            </div>
        @endif
    @endif



</div>

<script>
    $(function() {
        $("#transactionDate").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-M-yyyy',
            // startDate: new Date(),
            defaultDate: "+1w",
        }).datepicker('update', new Date());
    });
</script>

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
    // $('#verifyTransactionForm').submit(function(e, params) {
    $(document).on('submit', '.verifyTransactionForm', function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }
        let proceedFlag = 1;
        let totalNetPrice = '<?php echo $totalNetPrice; ?>';
        let totalPaid = '<?php echo $totalPaid; ?>';
        let balance = Number(totalNetPrice) - Number(totalPaid);
        balance = (Math.round(balance * 100) / 100).toFixed(2);
        // var form = e;
        var form = e.target;
        let transactionAmount = Number(form.transactionAmount.value);
        let transactionType = form.transactionType.value;
        
        if (transactionType == 'base' || transactionType == 'vat' || transactionType == 'tax') {
            if (transactionAmount > balance) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "Invalid Amount",
                    text: "Verification amount is greater than remaining balance amount",
                    showConfirmButton: false,
                    timer: 3000
                });
                proceedFlag = 0;
            }
        }

        if (proceedFlag == 1) {
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
                    form.submit();
                } else {
                    Swal.fire('Transaction is not verified', '', 'info')
                }
            })
        }
    });
</script>

<script>
    $('#OutstandingsClearanceForm').submit(function(e, params) {
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


<script>
    $('#outTransForm').submit(function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }
        let transType = $('#transactionType').val();
        let proceedFlag = 1;
        // Validate Deposit Amount and Net Amount 
        let transAmt = $('#transactionAmount').val();
        let totalNetPrice = '<?php echo $totalNetPrice; ?>';
        let totalPaid = '<?php echo $totalPaid; ?>';
        let balance = Number(totalNetPrice) - Number(totalPaid);
        balance = (Math.round(balance * 100) / 100).toFixed(2);

        if (transAmt <= 0) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Invalid Deposite Amount",
                showConfirmButton: false,
                timer: 3000
            });
            proceedFlag = 0;
        }

        if (Number(transAmt) > Number(totalNetPrice)) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Invalid Deposite Amount",
                text: "Deposited amount is greater than net price",
                showConfirmButton: false,
                timer: 3000
            });
            proceedFlag = 0;
        }
        if (Number(transAmt) > Number(balance)) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Invalid Deposite Amount",
                text: "Deposited amount " + transAmt + " is greater than remaining balance " + balance +
                    " amount",
                showConfirmButton: false,
                timer: 3000
            });
            proceedFlag = 0;
        }

        if (transType == 'fractionAdjust' && Number(transAmt) > 200) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Invalid Amount",
                text: "Fraction adjustment not more than 200",
                showConfirmButton: false,
                timer: 3000
            });
            proceedFlag = 0;
        }


        if (proceedFlag == 1) {
            var form = e;
            Swal.fire({
                title: 'Are you sure?',
                text: "Once stored, you will not be able to undo it!",
                icon: "warning",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Confirm Transaction',
                // denyButtonText: `Don't save`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    form.delegateTarget.submit()
                } else {
                    Swal.fire('Transaction is not succeed', '', 'info')
                }
            })
        }
    });
</script>

<script>
    function checkTransType() {
        var transType = $('#transactionType').val();
        document.getElementById('transactionAmount').removeAttribute('disabled');

        if (transType == 'advanceAdjust') {
            let remainingAdvance = Number($('#remainingAdvance')[0].innerText);
            if (remainingAdvance < 1) {
                document.getElementById('transactionAmount').setAttribute('disabled', true);
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: "Customer don't have advance balance",
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }

        if (transType == 'vat' || transType == 'tax') {
            document.getElementById('advanceAmount').parentNode.classList.add('d-none');
        } else {
            document.getElementById('advanceAmount').parentNode.classList.remove('d-none');
        }

        if (transType == 'advanceAdjust' || transType == 'fractionAdjust') {
            document.getElementById('transactionFile').removeAttribute('required');
            document.getElementById('advanceAmount').parentNode.classList.add('d-none');
            document.getElementById('excessAmount').parentNode.classList.add('d-none');
        } else {
            document.getElementById('transactionFile').setAttribute('required', true);
            document.getElementById('advanceAmount').parentNode.classList.remove('d-none');
            document.getElementById('excessAmount').parentNode.classList.remove('d-none');
        }
    }
</script>
