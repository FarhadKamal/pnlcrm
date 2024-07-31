<div class=" modal fade" id="quotationFeedbackModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top modal-lg">
        <div class="modal-content ">
            <div class="modal-header p-2">
                {{-- <button class="btn btn-sm btn-darkblue fs-07rem p-1" onclick="printAck()">Print Acknowledgement</button> --}}
                <div><h6>Payment Type: <span class="bg-darkblue p-1 rounded text-white" id="quotationPayType"></span></h6></div>
                <div class="col d-flex justify-content-end" id="detailsBtn2">

                </div>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Forms -->
                <div class="row fs-08rem">

                    <!-- Quotation Feedback Form -->
                    <div class="col-md-6 p-3 h-50 d-inline-block bg-offwhite">
                        <div class="text-center">
                            <h6><kbd>Quotation Customer Feedback</kbd></h6>
                        </div>
                        <p class="text-danger">Quotation Ref: <span id="quotationAckRef2"></span></p>
                        <form action="{{ route('quotationAccept') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-1">
                                <label class="form-label m-0">Acceptence Attachment <small
                                        class="text-danger">*</small></label>
                                <input name="quotationAcceptFile" id="quotationAcceptFile" type="file"
                                    accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx"
                                    class="form-control lh-sm fs-08rem" required>
                            </div>
                            <div class="mb-1">
                                <label class="form-label m-0">Purchase Order No <small
                                        class="text-danger">*</small></label>
                                <input type="text" name="quotationPO" class="form-control lh-sm fs-08rem" required>
                            </div>
                            <div class="mb-1">
                                <label class="form-label m-0">Purchase Order Date <small
                                        class="text-danger">*</small></label>
                                <input type="text" name="quotationPODate"
                                    class="form-control lh-sm flatpickr fs-08rem" required>
                            </div>
                            <div class="mb-1">
                                <label class="form-label m-0">Customer Feedback <small
                                        class="text-danger">*</small></label>
                                <textarea name="quotationAcceptFeedback" id="quotationAcceptFeedback" class="form-control lh-sm fs-08rem" rows="2"
                                    required></textarea>
                            </div>
                            <div class="mb-1">
                                <label class="form-label m-0">AIT <small class="text-danger">(Only for cash payment)</small></label>
                                <input type="number" name="quotationAIT" min="0" value="0"
                                    class="form-control lh-sm fs-08rem">
                            </div>
                            <div class="mb-1">
                                <label class="form-label m-0">VAT <small class="text-danger">(Only for cash payment)</small></label>
                                <input type="number" name="quotationVAT" min="0" value="0"
                                    class="form-control lh-sm fs-08rem">
                            </div>
                            <input type="text" name="quotationFeedbackModal_leadId"
                                id="quotationFeedbackModal_leadId" hidden>
                            <input type="text" name="quotationFeedbackModal_QuotationId"
                                id="quotationFeedbackModal_QuotationId" hidden>
                            <center><button class="btn btn-sm btn-darkblue">Accepted</button></center>
                        </form>
                    </div>

                    <!-- Quotation Return Form -->
                    <div class="col-md-6 p-3 h-50 d-inline-block">
                        <div class="text-center">
                            <h6><kbd>Quotation Return Form</kbd></h6>
                        </div>
                        <form action="{{ route('quotationNotAccept') }}" method="POST">
                            @csrf
                            <div class="mb-1">
                                <label class="form-label m-0">Return Reason</label>
                                <select name="quotationNotAcceptReason" id="quotationNotAcceptReason"
                                    class="form-select fs-08rem lh-sm" aria-label="Deal Vehicle" required>
                                    <option value="" selected disabled>Select One</option>
                                    <option value="High Price">High Price</option>
                                    <option value="Need More Discount">Need More Discount</option>
                                    <option value="Change Item">Change Item</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div class="mb-1">
                                <label class="form-label m-0">Reason Description</label>
                                <textarea name="quotationNotAcceptFeedback" id="quotationNotAcceptFeedback" class="form-control lh-sm" rows="5"
                                    required></textarea>
                            </div>
                            <input type="text" name="quotationNotFeedbackModal_leadId"
                                id="quotationNotFeedbackModal_leadId" hidden>
                            <center><button class="btn btn-sm btn-darkblue">Re-Deal Lead</button></center>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-2" id="lostBtn2">
                {{-- <a href=""><button type="button" class="btn btn-sm btn-danger">Lost</button></a> --}}
            </div>
        </div>
    </div>
</div>

<div class="quotationAcknowledgement d-none" id="quotationAcknowledgement">
    <style>
        .quotContainer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0;
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
    <div class="quotContainer">
        <img style="padding:0;margin:0;" src="{{ asset('images/system/logo.png') }}" alt="" height="80">
        <div>
            <p style="margin-left: 5px;font-weight: bold;font-size: 1.2rem;">
                PNL Holdings Limited
            </p>

            <p style="margin-left: 5px;font-size: 1rem;">
                5 Jubilee Road, Chattogram.
                <br>
                Email: sales@pnlholdings.com
                <br>
                www.pnlholdings.com
            </p>
        </div>
    </div>
    <div class="quotContainer" style="font-size: 1.2rem;">
        <h6 style="font-size: 1.2rem;" id="quotationAckRef"></h6>
        <h6 style="font-size: 1.2rem;">Date: <?= date('jS F Y') ?></h6>
    </div>
    <div style="font-size: 1.2rem;">
        <p>To</p>
        <p><b>Head Of Sale</b><br>
            PNL Holdings Limited</p>
        <p style="margin-top: 2%;">
            Subject : <strong>Acceptence The Price Quotation</strong>
        </p>
        <p>Dear Sir,</p>
        <p>
            With reference to the price quotation <span id="quotationAckRef2"></span>, I acknowledge receipt and
            accepted the terms of the quotation.
        </p>
    </div>
    <div style="width:300px; font-size: 1.2rem;">
        <p>Your Sincerely</p>
        <br><br><br>
        <p style="border-top: 1px solid #111;padding-top:0px;">
            <center>Signature</center>
            <center><span id="ackLeadName"></span></center>
            <center><span id="ackLeadClient"></span></center>
        </p>
    </div>
</div>

<script>
    function quotationFeedbackShowModal(data) {
        //Initial Form Fields Start
        $('#quotationAcceptFile').val(null);
        $('#quotationAcceptFeedback').val(null);
        $('#quotationNotAcceptReason')[0].selectedIndex = 0;
        $('#quotationNotAcceptFeedback').val(null);
        $('#quotationFeedbackModal_leadId').val(data.id);
        $('#quotationFeedbackModal_QuotationId').val(data.quotationId);
        $('#quotationNotFeedbackModal_leadId').val(data.id);
        document.getElementById("quotationAckRef").innerText = data.quotationRef;
        let splitRef = data.quotationRef.split(":");
        document.getElementById("quotationAckRef2").innerText = splitRef[1];
        document.getElementById("ackLeadName").innerText = data.lead_person;
        document.getElementById("ackLeadClient").innerText = data.client_info.customer_name;
        document.getElementById("quotationPayType").innerText = data.payment_type;
        //Initial Form Fields End

        let leadId = data.id;
        $('#detailsBtn2').empty();
        let domain = window.location.origin;
        const aTag = document.createElement("a");
        aTag.id = 'detailsBtnId';
        aTag.href = domain + '/detailsLog/' + leadId;
        aTag.innerHTML = "<button type='button' class='btn btn-sm btn-primary'>Details Log</button>";
        $('#detailsBtn2').append(aTag);

        $('#lostBtn2').empty();
        const aTagLost = document.createElement("a");
        aTagLost.href = domain + '/lost/' + leadId;
        aTagLost.innerHTML = "<button type='button' class='btn btn-sm btn-danger'>Lost</button>";
        $('#lostBtn2').append(aTagLost);
    }
</script>

<script>
    function printAck() {
        var printWindow = window.open('', '_blank');
        if (printWindow) {
            var printContents = document.getElementById("quotationAcknowledgement").innerHTML;
            printWindow.document.write("<body>");
            printWindow.document.write(printContents);
            printWindow.document.write("</body>");
            printWindow.document.close();
            printWindow.print();
        } else {
            alert('Please allow pop-ups for this site to print');
        }
    }
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
