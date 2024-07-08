@include('layouts.navbar')
<div class="container mt-4" style="margin-bottom: 15%">
    <div class="row">
        <div class="col-md-6">
            <center>
                <h6 class="text-center"><kbd>Lead Information</kbd></h6>
            </center>
            <div class="fs-09rem">
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
            </div>
        </div>
        <div class="col-md-6">
            <center>
                <h6 class="text-center"><kbd>Lead Lost Form</kbd></h6>
            </center>
            <form action="{{ route('lostEntry') }}" method="POST">
                @csrf
                <div>
                    <label class="form-label m-0">Lost Reason</label>
                    <select name="lostReason" class="form-control fs-08rem" required>
                        <option value="" selected disabled>Select A Reason</option>
                        <option value="High Price">High Price</option>
                        <option value="Not Appealing">Not Appealing</option>
                        <option value="Not Interested">Not Interested</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div>
                    <label class="form-label m-0">Lost Description</label>
                    <textarea name="lostDescription" class="form-control fs-08rem" cols="30" rows="5" required></textarea>
                </div>
                <div class="text-center">
                    <input name="lostLead" value="{{ $leadId }}" hidden required>
                    <button class="btn btn-sm btn-danger w-50 mt-4">Lost</button>
                </div>
            </form>
        </div>
    </div>
</div>
