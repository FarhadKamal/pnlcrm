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
        <h4 class="mt-3">Discount Set Form</h4>
    </center>
    <hr>
    <div class="row container-fluid">
        <div class="col-md-5 col-sm-5">
            <h6 class="text-center"><kbd>Lead Information</kbd></h6>
            <div class="container fs-09rem">
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
            </div>
        </div>
        <div class="col-md-7 col-sm-7">
            <h6 class="text-center"><kbd>SAP Discount Information</kbd></h6>
            <form action="{{ route('discountSetInsertion') }}" method="POST" id="discountSetInsertionForm">
                @csrf
                <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
                <label for="" class="fs-08rem">SAP Discount Remarks</label><br>
                <textarea name="discountRemark" class="form-control fs-08rem p-1" rows="3" required></textarea>
                <br>
                <center><button class="btn btn-sm btn-darkblue">Submit Discount</button></center>
            </form>
            <br>
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
</div>

<script>
    $('#discountSetInsertionForm').submit(function(e, params) {
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
            confirmButtonText: 'Confirm Submission',
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
</script>
