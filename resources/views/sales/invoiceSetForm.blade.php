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
        <h4 class="mt-3">Invoice Set Form</h4>
    </center>
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
            <h6 class="text-center"><kbd>SAP Invoice Information</kbd></h6>
            <center><small class="text-danger">Please cross check with purchase order</small></center>
            <form action="{{ route('invoiceSetInsertion') }}" method="POST" id="invoiceSetInsertionForm">
                @csrf
                <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
                <input type="hidden" name="invoiceVatSum" id="invoiceVatSum">
                <label for="" class="fs-08rem">SAP Invoice ID</label>
                <input type="number" name="invoiceID" id="invoiceID" class="form-control fs-08rem p-1" min="0"
                    required>
                <label for="" class="fs-08rem">SAP Invoice Remarks</label><br>
                <textarea name="invoiceRemark" class="form-control fs-08rem p-1" rows="3"></textarea>
                <br>
                <center><button class="btn btn-sm btn-darkblue">Submit Invoice</button></center>
            </form>
            <br>
            <table class="table table-bordered fs-08rem">
                <thead>
                    <tr>
                        <th class="p-1 text-center">SAP Code</th>
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
                            $sapNewCode = $pumps->productInfo->new_code;
                        } else {
                            $brandName = $pumps->spareInfo->brand_name;
                            $prName = $pumps->spareInfo->mat_name;
                            $sapNewCode = $pumps->spareInfo->new_code;
                        }
                        ?>
                        <tr>
                            <td class="p-1">{{ $sapNewCode }}</td>
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
                        <th colspan="6" class="p-1 text-center fw-bold">Total</th>
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
    async function sapInvoiceCheck() {
        let inputSAP = $('#invoiceID').val();
        let totalNetPrice = '<?php echo $totalNetPrice; ?>';
        let pumpInfo = JSON.parse('<?php echo $pumpInfo; ?>');

        let filterData = {
            inputSAP: inputSAP,
        };
        const csrfToken = '<?php echo csrf_token(); ?>';
        try {
            let response = await fetch('/checkSAPInvoice', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(filterData)
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            let json = await response.json();
            let data = json.status;

            // Duplicacy Check in the CRM Start
            let isDuplicate = json.isDuplicate;
            if (isDuplicate && isDuplicate.length > 0) {

                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: "The invoice no " + inputSAP + " is already exist in the CRM",
                    showConfirmButton: false,
                    timer: 3000
                });
                return false;
            }
            // Duplicacy Check in the CRM End

            //Validate SAP Invoice
            if (data && data.length > 0) {
                let sapNetPrice = 0;
                let totalInvoiceVatSum = 0;

                // Item Validation Start 
                for (let i = 0; i < pumpInfo.length; i++) {
                    let itemFlag = false;
                    let itemErrorCode = '';
                    data.forEach(element => {
                        if ((pumpInfo[i].spare_parts == 0 && element.ItemCode == pumpInfo[i].product_info
                                .new_code) || (pumpInfo[i].spare_parts == 1 && element.ItemCode ==
                                pumpInfo[
                                    i].spare_info.new_code)) {
                            itemFlag = true;
                        } else {
                            if (pumpInfo[i].spare_parts == 0) {
                                itemErrorCode = pumpInfo[i].product_info.new_code;
                            } else {
                                itemErrorCode = pumpInfo[i].spare_info.new_code;
                            }
                        }
                    });

                    if (itemFlag == false) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            text: "The item code " + itemErrorCode + " not present in the SAP invoice",
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return false;
                    }
                }
                // Item Validation END 

                // Net Amount Validation Start
                // VatSum Process. This vat is predefined in SAP.
                data.forEach(element => {
                    sapNetPrice = Number(sapNetPrice) + Number(element.GTotal);
                    totalInvoiceVatSum = Number(totalInvoiceVatSum) + Number(element.VatSum);
                });
                totalInvoiceVatSum = Number(totalInvoiceVatSum).toFixed(2);
                $('#invoiceVatSum').val(totalInvoiceVatSum);
                
                totalNetPrice = Number(totalNetPrice).toFixed(2);
                sapNetPrice = Number(sapNetPrice).toFixed(2);
                let difference = Math.abs(totalNetPrice - sapNetPrice).toFixed(2);
                // console.log(difference);
                if (difference <= 0.01) {
                    return true;
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        text: "CRM net amount not match with invoice net amount",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return false;
                }
                // Net Amount Validation End
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "Invoice ID not found in SAP",
                    showConfirmButton: false,
                    timer: 3000
                });
                return false;
            }

            // if (json.status === 'gotSAP') {
            //     return true;
            // } else {
            //     Swal.fire({
            //         position: 'top-end',
            //         icon: 'error',
            //         title: "Invoice ID not found in SAP",
            //         showConfirmButton: false,
            //         timer: 2000
            //     });
            //     return false;
            // }
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
            return false;
        }
    }

    $('#invoiceSetInsertionForm').submit(async function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }

        let checkSAPInvoice = await sapInvoiceCheck();
        // console.log(checkSAPInvoice);

        if (checkSAPInvoice) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Once submitted, you will not be able to undo it!",
                icon: "warning",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Confirm Submission',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#invoiceSetInsertionForm').off('submit').submit();
                } else {
                    Swal.fire('Something is wrong', '', 'info');
                }
            });
        }
    });
</script>
