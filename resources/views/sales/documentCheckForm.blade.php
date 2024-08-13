@include('layouts.navbar')
<div class="container">
    <div class="m-2 float-end">
        <a href="{{ route('detailsLog', ['leadId' => $leadInfo->id]) }}" target="_blank"><button
                class="btn btn-darkblue btm-sm fs-07rem p-1">Details Log</button></a>
    </div>
    <div class="row" style="width: inherit">
        <div class="col-md-6 mb-3">
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
        <div class="col-md-6">
            <h6 class="text-center"><kbd>Customer Document</kbd></h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td class="p-1 text-center">Document Type</td>
                        <td class="p-1 text-center">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-1 text-center">TIN</td>
                        @if ($leadInfo->clientInfo->tin)
                            <td class="p-1 text-center"><a
                                    href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->tin }}"
                                    target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                            class="fas fa-eye"></i></button></a>
                                <a href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->tin }}"
                                    target="_blank" download><button class="btn btn-primary btn-sm p-1"><i
                                            class="fas fa-download"></i></button></a>
                            </td>
                        @else
                            <td class="p-1 text-center">N/A</td>
                        @endif

                    </tr>
                    <tr>
                        <td class="p-1 text-center">BIN</td>
                        @if ($leadInfo->clientInfo->bin)
                            <td class="p-1 text-center"><a
                                    href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->bin }}"
                                    target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                            class="fas fa-eye"></i></button></a>
                                <a href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->bin }}"
                                    target="_blank" download><button class="btn btn-primary btn-sm p-1"><i
                                            class="fas fa-download"></i></button></a>
                            </td>
                        @else
                            <td class="p-1 text-center">N/A</td>
                        @endif

                    </tr>
                    <tr>
                        <td class="p-1 text-center">Trade License</td>
                        @if ($leadInfo->clientInfo->trade_license)
                            <td class="p-1 text-center"><a
                                    href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->trade_license }}"
                                    target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                            class="fas fa-eye"></i></button></a>
                                <a href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->trade_license }}"
                                    target="_blank" download><button class="btn btn-primary btn-sm p-1"><i
                                            class="fas fa-download"></i></button></a>
                            </td>
                        @else
                            <td class="p-1 text-center">N/A</td>
                        @endif

                    </tr>
                </tbody>
            </table>
            <form action="{{ route('customerDocClear') }}" method="POST" id="customerDocumentCheckForm">
                @csrf
                <label for="">Checked Remarks</label><br>
                <textarea name="docCheckRemark" cols="30" rows="3" class="fs-08rem form-control"></textarea><br>
                <input type="hidden" name="customerId" value="{{ $leadInfo->clientInfo->id }}">
                <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
                <center><button class="btn btn-sm btn-darkblue fs-07rem p-1">Document Checked</button></center>
            </form>
            <form action="{{ route('customerDocReturn') }}" method="POST" id="customerDocumentReturnForm">
                @csrf
                <label for="">Return Remarks</label><br>
                <textarea name="docReturnRemark" cols="30" rows="3" class="fs-08rem form-control" required></textarea><br>
                <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
                <center><button class="btn btn-sm btn-danger fs-07rem p-1">Return Previous Stage</button></center>
            </form>
        </div>
    </div>
</div>

<script>
    $('#customerDocumentCheckForm').submit(function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }
        var form = e;
        Swal.fire({
            title: 'Are you sure?',
            text: "Once checked, you will not be able to undo it!",
            icon: "warning",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Confirm Checked',
            // denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                form.delegateTarget.submit()
            } else {
                Swal.fire('Checking is not succeed', '', 'info')
            }
        })
    });

    $('#customerDocumentReturnForm').submit(function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }
        var form = e;
        Swal.fire({
            title: 'Are you sure to return?',
            // text: "Once checked, you will not be able to undo it!",
            icon: "warning",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Confirm Return',
            // denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                form.delegateTarget.submit()
            } else {
                Swal.fire('Return is not succeed', '', 'info')
            }
        })
    });
</script>
