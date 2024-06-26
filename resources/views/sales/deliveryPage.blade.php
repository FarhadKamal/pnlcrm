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
        <h4 class="mt-3">Invoice Set Form</h4>
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
            <h6 class="text-center"><kbd>Item Information</kbd></h6>
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

            <div>
                <h6 class="text-center"><kbd>Delivery Information</kbd></h6>
                {{-- <div class="fs-07rem">
                    Is same as lead information? <input type="checkbox" name="deliveryInfoCheckbox"
                        id="deliveryInfoCheckbox" onclick="changeDeliveryInfo()">
                </div> --}}
                <div>
                    <form action="{{ route('deliveryInformation') }}" method="POST" class="row mb-4">
                        @csrf
                        <input type="hidden" name="leadId" value="{{ $leadInfo->id }}" required>
                        <?php
                        if ($leadInfo->delivery_challan && $leadInfo->delivery_challan != '') {
                            $challanNo = $leadInfo->delivery_challan;
                        } else {
                            $challanNo = '';
                        }
                        if ($leadInfo->delivery_address && $leadInfo->delivery_address != '') {
                            $delAddress = $leadInfo->delivery_address;
                        } else {
                            $delAddress = '';
                        }
                        if ($leadInfo->delivery_person && $leadInfo->delivery_person != '') {
                            $delPerson = $leadInfo->delivery_person;
                        } else {
                            $delPerson = '';
                        }
                        if ($leadInfo->delivery_mobile && $leadInfo->delivery_mobile != '') {
                            $delMobile = $leadInfo->delivery_mobile;
                        } else {
                            $delMobile = '';
                        }
                        
                        ?>
                        <div class="col-md-3">
                            <label for="" class="fs-07rem">Delivery Challan No</label>
                            <input type="number" name="challanNo" class="form-control p-1 fs-07rem" min="0"
                                value="{{ $challanNo }}" required>
                        </div>
                        <div class="col-md-7">
                            <label for="" class="fs-07rem">Delivery Address</label>
                            <input type="text" name="address" id="address" class="form-control p-1 fs-07rem"
                                value=" {{ $delAddress }} " required>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="fs-07rem">Delivery Contact Person</label>
                            <input type="text" name="contactPerson" id="contactPerson"
                                class="form-control p-1 fs-07rem" min="0" value="{{ $delPerson }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="fs-07rem">Delivery Contact Mobile</label>
                            <input type="number" name="contactMobile" id="contactMobile"
                                class="form-control p-1 fs-07rem" min="0" value="{{ $delMobile }}"
                                required>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-darkblue fs07rem mt-3">Save</button>
                        </div>
                    </form>
                </div>
                <div class="row">
                    @if ($challanNo == '' || $delAddress == '')
                        <center><small class="text-danger">Please save the delivery challan no and delivery
                                address.</small></center>
                    @endif
                    <button class="col-md-2 me-1 btn btn-sm btn-darkblue fs-07rem p-1"
                        onclick="printInvoice()">Invoice</button>
                    @if ($challanNo == '' || $delAddress == '')
                        <button class="col-md-2 me-1 btn btn-sm btn-darkblue fs-07rem p-1" disabled>Delivery
                            Challan</button>
                    @else
                        <button class="col-md-2 me-1 btn btn-sm btn-darkblue fs-07rem p-1"
                            onclick="printDeliveryChallan()">Delivery Challan</button>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <hr>
    <div>
        <form action="{{ route('delivered') }}" method="POST">
            @csrf
            <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
            <center><button class="btn btn-sm btn-darkblue fs-08rem">Delivered Item</button></center>
        </form>
    </div>
</div>

@include('sales.invoicePage')
@include('sales.deliveryChallanPage')

<script>
    $('#invoiceSetInsertionForm').submit(function(e, params) {
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

    function printInvoice() {
        var printWindow = window.open('', '_blank');

        // Check if the window opened successfully
        if (printWindow) {
            var printContents = document.getElementById("invoicePrint").innerHTML;
            printWindow.document.write('<html><body>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');

            // Make sure to close the print window after printing
            printWindow.document.close();
            printWindow.print();
        } else {
            alert('Please allow pop-ups for this site to print');
        }
    }

    function printDeliveryChallan() {
        var printWindow = window.open('', '_blank');

        // Check if the window opened successfully
        if (printWindow) {
            var printContents = document.getElementById("deliveryChallanPrint").innerHTML;
            printWindow.document.write('<html><body>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');

            // Make sure to close the print window after printing
            printWindow.document.close();
            printWindow.print();
        } else {
            alert('Please allow pop-ups for this site to print');
        }
    }

    function changeDeliveryInfo() {
        if ($('#deliveryInfoCheckbox').is(":checked") == true) {
            let add = '<?= $leadInfo->clientInfo->address ?>';
            $('#address').val(add);
            $('#contactPerson').val('<?= $leadInfo->lead_person ?>');
            $('#contactMobile').val('<?= $leadInfo->lead_phone ?>');
        } else {
            $('#address').val('<?= $leadInfo->delivery_address ?>');
            $('#contactPerson').val('<?= $leadInfo->delivery_person ?>');
            $('#contactMobile').val('<?= $leadInfo->delivery_mobile ?>');
        }
    }
</script>
