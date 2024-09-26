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
        <h4 class="mt-3">New Client/SAP ID Creation</h4>
    </center>
    <hr>
    <div class="row">
        <div class="col-md-6 col-sm-6">
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
                    @if ($leadInfo->clientInfo->tin)
                        <small class="col-md-8"> <a
                                href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->tin }}"
                                target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                        class="fas fa-eye"></i></button></a>
                            <a href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->tin }}" target="_blank"
                                download><button class="btn btn-primary btn-sm p-1"><i
                                        class="fas fa-download"></i></button></a></small>
                    @else
                        <small class="col-md-8">N/A</small>
                    @endif
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">BIN</p>
                    @if ($leadInfo->clientInfo->bin)
                        <small class="col-md-8"> <a
                                href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->bin }}"
                                target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                        class="fas fa-eye"></i></button></a>
                            <a href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->bin }}" target="_blank"
                                download><button class="btn btn-primary btn-sm p-1"><i
                                        class="fas fa-download"></i></button></a></small>
                    @else
                        <small class="col-md-8">N/A</small>
                    @endif
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Trade License</p>
                    @if ($leadInfo->clientInfo->trade_license)
                        <small class="col-md-8"> <a
                                href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->trade_license }}"
                                target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                        class="fas fa-eye"></i></button></a>
                            <a href="{{ asset('customerDocument') . '/' . $leadInfo->clientInfo->trade_license }}"
                                target="_blank" download><button class="btn btn-primary btn-sm p-1"><i
                                        class="fas fa-download"></i></button></a></small>
                    @else
                        <small class="col-md-8">N/A</small>
                    @endif
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
        <div class="col-md-4 col-sm-4">
            <h6 class="text-center"><kbd>SAP Information</kbd></h6>
            <form action="{{ route('newSapInsertion') }}" method="POST" id="sapCreationForm">
                @csrf
                <input type="hidden" name="customerId" value="{{ $leadInfo->clientInfo->id }}">
                <label for="" class="fs-08rem">SAP ID</label>
                <input type="number" class="form-control fs-08rem p-1" name="newSAP" required>
                <br>
                <center><button class="btn btn-sm btn-darkblue">Save SAP ID</button></center>
            </form>
        </div>
    </div>
</div>

<script>
    $('#sapCreationForm').submit(function(e, params) {
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
            confirmButtonText: 'Confirm transaction',
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
