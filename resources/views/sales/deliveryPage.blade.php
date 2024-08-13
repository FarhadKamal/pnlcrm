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
        <h4 class="mt-3">Delivery Form</h4>
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
                        id="deliveryInfoCheckbox" onchange="changeDeliveryInfo()">
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
                        <div class="col-md-4">
                            <label for="" class="fs-07rem">Delivery Challan No</label>
                            <input type="text" name="challanNo" id="challanNo" class="form-control p-1 fs-07rem"
                                min="0" value="{{ $challanNo }}" required readonly>
                        </div>
                        <div class="col-md-8">
                            <label for="" class="fs-07rem">Delivery Address</label>
                            <input type="text" name="address" id="address" class="form-control p-1 fs-07rem"
                                value=" {{ $delAddress }} " required>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="fs-07rem">Delivery Contact Person</label>
                            <input type="text" name="contactPerson" id="contactPerson"
                                class="form-control p-1 fs-07rem" min="0" value="{{ $delPerson }}"
                                required>
                        </div>
                        <div class="col-md-4">
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
                    <button class="col-md-2 me-1 btn btn-sm btn-darkblue fs-07rem p-1"
                        onclick="printWarrantyInfo()">Warranty Info</button>
                </div>
            </div>

        </div>
    </div>
    <hr>
    <div>
        <form action="{{ route('delivered') }}" method="POST" class="row container" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
            <div class="col-md-4">
                <label for="" class="fs-07rem">Delivery Acknowledgement</label>
                <input type="file" name="deliveryAttachment" accept="application/pdf, image/*"
                    class="form-control fs-07rem p-1" required>
            </div>
            <div class="col-md-8 mt-3">
                <center><button class="btn btn-sm btn-darkblue fs-08rem">Delivered Item</button></center>
            </div>
        </form>
    </div>
</div>

@include('sales.invoicePage')
@include('sales.deliveryChallanPage')
@include('sales.deliveryChallanPage2')

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

    function printWarrantyInfo() {
        var printWindow = window.open('', '_blank');

        // Check if the window opened successfully
        if (printWindow) {
            var printContents = document.getElementById("warrantyInfoPrint").innerHTML;
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
</script>

<script>
    setInterval(function() {
        let checkChallanNo = '<?= $leadInfo->delivery_challan ?>';
        if (checkChallanNo == '' || checkChallanNo == null) {
            fetch('/deliveryReferenceCheck')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(json => {
                    let refFullDate = new Date(json.currentDate);
                    let refYear = refFullDate.getFullYear();
                    let refMonth = Number(refFullDate.getMonth() + 1).toString().padStart(2, '0');
                    let refDate = Number(refFullDate.getDate()).toString().padStart(2, '0');

                    let serialNo = Number(json.checkDeliverySerial[0]['sl'] + 1).toString().padStart(3,
                        '0');
                    let sellerZone = '<?= Auth()->user()->assign_to ?>';
                    let refPreText = 'DC/PNL/' + sellerZone + '/' + refYear + '/' + refMonth + refDate +
                        serialNo;
                    // console.log(refPreText);
                    // document.getElementById('challanNo').value = refPreText;
                    $('#challanNo').val(refPreText);

                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    }, 1000 * 60 * 0.01);
</script>
