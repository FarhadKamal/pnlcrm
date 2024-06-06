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
    <div class="row">
        @if ($leadInfo->payment_type == 'Cash')
            <div class="col-md-3">
                <h6 class="text-center"><kbd>Insert Transaction Form</kbd></h6>
                <form action="" method="POST" enctype="multipart/form-data" class="m-4">
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
                    <div class="mb-1">
                        <label class="form-label m-0">Attachment</label>
                        <input name="transactionFile" type="file" accept="image/png, image/jpeg, image/jpg, .pdf"
                            class="form-control lh-sm" required>
                    </div>
                    <div>
                        <input name="transactionLead" value="{{ $leadId }}" hidden>
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
                    @foreach ($pumpInfo as $pumps)
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
            </table>
        </div>
    </div>

    @if ($leadInfo->payment_type == 'Cash')
        <div class="row mt-5 mb-3 container m-auto">
            <h6 class="text-center"><kbd>Booking Transaction List</kbd></h6>
            <table class="table table-bordered fs-08rem log-table text-center">
                <thead>
                    <tr>
                        <th>SL.</th>
                        <th>Deposit Date</th>
                        <th>Taka</th>
                        <th>Attachment</th>
                        <th>Statement Date</th>
                        <th>Statement Remarks</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    @endif


    <div>
        <form action="" method="POST">
            @csrf
            <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}">
            <center><button class="btn btn-sm btn-darkblue">Proceed For Delivery</button></center>
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
