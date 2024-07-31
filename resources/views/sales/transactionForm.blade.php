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
    <div class="row">
        @if ($leadInfo->payment_type == 'Cash')
            <div class="col-md-3">
                <h6 class="text-center"><kbd>Insert Transaction Form</kbd></h6>
                <form action="{{ route('insertTransaction') }}" method="POST" enctype="multipart/form-data"
                    class="m-4">
                    @csrf
                    <div class="mb-1">
                        <label class="form-label m-0">Deposit Date</label>
                        <input type="text" name="transactionDate" id="transactionDate" class="form-control fs-08rem"
                            required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label m-0">Amount</label>
                        <input name="transactionAmount" type="number" class="form-control lh-sm" required>
                    </div>
                    {{-- <div class="mb-1">
                        <label class="form-label m-0">Attachment</label>
                        <input name="transactionFile" type="file" accept="image/png, image/jpeg, image/jpg, .pdf"
                            class="form-control lh-sm" required>
                    </div> --}}
                    <div>
                        <input name="transactionLead" value="{{ $leadId }}" hidden>
                        <input name="transactionQuotation" value="{{ $quotationInfo[0]->id }}" hidden>
                        <button type="submit" class="btn btn-darkblue btn-sm w-100 mt-2">Save Transaction</button>
                    </div>
                </form>
            </div>
        @endif

        @if ($leadInfo->payment_type == 'Cash')
            <?php $rowClass = 'col-md-8'; ?>
        @else
            <?php $rowClass = 'col-md-12'; ?>
        @endif
        <div class="{{ $rowClass }}">
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

            <div class="row">
                <div class="col-md-6">AIT Amount: <span class="text-white p-1 rounded fw-bold bg-darkblue">{{ $leadInfo->aitAmt }}</span></div>
                <div class="col-md-6">VAT Amount: <span class="text-white p-1 rounded fw-bold bg-darkblue">{{ $leadInfo->vatAmt }}</span></div>
            </div>
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
                            @if ($item->deposited_date)
                                <td class="p-1">{{ date('d-M-Y', strtotime($item->deposited_date)) }}</td>
                            @else
                                <td class="p-1"></td>
                            @endif
                            <td class="p-1">{{ $item->deposited_remarks }}</td>
                            @if ($item->is_verified == 0)
                                <td class="p-1"><button class="btn btn-sm btn-danger badge">unverified</button></td>
                            @else
                                <td class="p-1"><button class="btn btn-sm btn-success badge">verified</button></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


    <div>
        <form action="" method="POST">
            @csrf
            <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}">
            @if ($leadInfo->accounts_clearance == 0)
                <center>
                    <h5 class="badge badge-danger">Waiting For Accounts Clearance</h5>
                </center>
            @else
                <center><button class="btn btn-sm btn-darkblue">Proceed For Delivery</button></center>
            @endif
        </form>
    </div>


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
