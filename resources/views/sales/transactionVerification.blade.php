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
        <h4 class="mt-3">Booking Transaction Form</h4>
    </center>
    <div class="bg-darkblue">
        <h5 class="text-center text-white fs-5 p-3 m-0">Payment Mood: {{ $leadInfo->payment_type }}</h5>
    </div>
    <hr>
    <div class="row d-none">
        <div class="col-md-12">
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
                        ?>
                        <tr>
                            <td class="p-1">{{ $pumps->productInfo->brand_name }}</td>
                            <td class="p-1">{{ $pumps->productInfo->mat_name }}</td>
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

    @if ($leadInfo->payment_type == 'Cash')
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
        </div>
        <div class="row mt-5 mb-3 container m-auto">
            <h6 class="text-center"><kbd>Booking Transaction List</kbd></h6>
            <table class="table table-bordered fs-08rem log-table text-center">
                <thead>
                    <tr>
                        <th class="p-1">SL.</th>
                        <th class="p-1">Deposit Date</th>
                        <th class="p-1">Taka</th>
                        <th class="p-1">Statement Date</th>
                        <th class="p-1">Statement Remarks</th>
                        <th class="p-1">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sl = 1; ?>
                    @foreach ($transactionInfo as $item)
                        <tr>
                            <td class="p-1">{{ $sl }}</td>
                            <td class="p-1">{{ date('d-M-Y', strtotime($item->deposit_date)) }}</td>
                            <td class="p-1">{{ number_format((float) $item->pay_amount, 2, '.', ',') }}</td>
                            @if ($item->is_verified == 0)
                                <form action="{{ route('verifiedTransaction') }}" method="POST"
                                    id="verifyTransactionForm">
                                    @csrf
                                    <input type="hidden" name="transactionId" value="{{ $item->id }}">
                                    <td>
                                        <input type="text" class="flatpickr form-control p-1 fs-07rem"
                                            name="depositedDate" id="depositedDate" required>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


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
                form.delegateTarget.submit()
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
