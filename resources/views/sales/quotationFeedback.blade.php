@include('layouts.navbar')
<div class="">
    <div class="m-2 float-end">
        <a href="{{ route('detailsLog', ['leadId' => $leadInfo->id]) }}" target="_blank"><button
                class="btn btn-darkblue btm-sm fs-07rem p-1">Details Log</button></a>
        <a href="{{ route('lost', ['leadId' => $leadInfo->id]) }}"><button type='button'
                class='btn btn-sm btn-danger'>Lost</button></a>
    </div>
    <div class="text-center">
        <h5>Quotation Customer Feedback</h5>
        <p class="text-danger">Quotation Ref: {{ $quotationRef }}</p>
    </div>
    <div class="bg-darkblue">
        <h5 class="text-center text-white fs-5 p-3 m-0">Payment Mood: {{ $leadInfo->payment_type }}</h5>
    </div>
    <div class="row m-3">
        <div class="col-md-4 col-sm-4 mb-3">
            <h6 class="text-center"><kbd>Lead Information</kbd></h6>
            <div class="fs-09rem">
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Client SAP ID</p>
                    @if ($leadInfo->clientInfo->sap_id)
                        <small class="col-md-8">{{ $leadInfo->clientInfo->sap_id }}</small>
                    @else
                        <small class="col-md-8">N/A</small>
                    @endif
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Name</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->customer_name }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Phone</p>
                    <small class="col-md-8">{{ $leadInfo->lead_phone }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Email</p>
                    <small class="col-md-8">{{ $leadInfo->lead_email }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Address</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->address }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Lead Source</p>
                    <small class="col-md-8">{{ $leadInfo->source->source_name }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Requirement</p>
                    <small class="col-md-8">{{ $leadInfo->product_requirement }}</small>
                </div>

                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Created By</p>
                    <small class="col-md-8">{{ $leadInfo->createdBy->user_name }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 m-0 text-info">Assign To</p>
                    <small class="col-md-8 text-info">{{ $leadInfo->clientInfo->assignTo->user_name }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 bg-offwhite">
            <div class="text-center">
                <h6><kbd>Quotation Accept Form</kbd></h6>
            </div>
            <form action="{{ route('quotationAccept') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-1">
                    <label class="form-label m-0">Acceptence Attachment <small class="text-danger">*
                            (PO)</small></label>
                    <input name="quotationAcceptFile" id="quotationAcceptFile" type="file"
                        accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx" class="form-control lh-sm fs-08rem"
                        required>
                </div>
                <div class="mb-1">
                    <label class="form-label m-0">Purchase Order No <small class="text-danger">*</small></label>
                    <input type="text" name="quotationPO" class="form-control lh-sm fs-08rem" required>
                </div>
                <div class="mb-1">
                    <label class="form-label m-0">Purchase Order Date <small class="text-danger">*</small></label>
                    <input type="text" name="quotationPODate" class="form-control lh-sm flatpickr fs-08rem" required>
                </div>
                <div class="mb-1">
                    <label class="form-label m-0">Customer Feedback <small class="text-danger">*</small></label>
                    <textarea name="quotationAcceptFeedback" id="quotationAcceptFeedback" class="form-control lh-sm fs-08rem" rows="2"
                        required></textarea>
                </div>
                <div class="mb-1">
                    <label class="form-label m-0">AIT <small class="text-danger">(Mention the
                            amount)</small></label>
                    <input type="number" name="quotationAIT" min="0" value="0"
                        class="form-control lh-sm fs-08rem">
                </div>
                <div class="mb-1">
                    <label class="form-label m-0">VAT <small class="text-danger">(Mention the
                            amount)</small></label>
                    <input type="number" name="quotationVAT" min="0" value="0"
                        class="form-control lh-sm fs-08rem">
                </div>
                @if (!$leadInfo->clientInfo->sap_id)
                    <br>
                    <div class="text-center">
                        <h6><kbd>New Customer Document Form</kbd></h6>
                    </div>
                    <div class="mb-1">
                        <label class="form-label m-0">TIN</label>
                        <input name="customerTIN" id="customerTIN" type="file"
                            accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx"
                            class="form-control lh-sm fs-08rem">
                    </div>
                    <div class="mb-1">
                        <label class="form-label m-0">BIN <small class="text-danger">*
                            </small></label>
                        <input name="customerBIN" id="customerBIN" type="file"
                            accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx"
                            class="form-control lh-sm fs-08rem" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label m-0">Trade License <small class="text-danger">*
                            </small></label>
                        <input name="customerTL" id="customerTL" type="file"
                            accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx"
                            class="form-control lh-sm fs-08rem" required>
                    </div>
                @endif
                <input type="text" name="quotationFeedbackModal_leadId" id="quotationFeedbackModal_leadId"
                    value="{{ $leadInfo->id }}" hidden>
                <input type="text" name="quotationFeedbackModal_QuotationId"
                    id="quotationFeedbackModal_QuotationId" value="{{ $quotationId }}" hidden>
                <center><button class="btn btn-sm btn-darkblue">Accepted</button></center>
            </form>
        </div>
        <div class="col-md-4 p-3 h-50 d-inline-block">
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
                    <textarea name="quotationNotAcceptFeedback" id="quotationNotAcceptFeedback" class="form-control lh-sm"
                        rows="5" required></textarea>
                </div>
                <input type="text" name="quotationNotFeedbackModal_leadId" id="quotationNotFeedbackModal_leadId"
                    value="{{ $leadInfo->id }}" hidden>
                <center><button class="btn btn-sm btn-darkblue">Re-Deal Lead</button></center>
            </form>
        </div>
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
