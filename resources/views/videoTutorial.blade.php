@include('layouts.navbar')
<style>
    .videoCard{
        margin-top: 5%;
        padding: 5%;
        height: 80px;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
    }
    small{
        display: none !important;
    }
</style>
<div class="container">
    <center>
        <h5>Video Tutorial</h5>
    </center>
    <div class="row">
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to create new customer.mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to create new customer</h6>
                </a>
                <small class="badge badge-success">Lead Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to create new Lead (Exist Customer).mp4') }}" target="_blank"
                    class="text-dark">
                    <h6>CRM - How to create new Lead (Exist Customer)</h6>
                </a>
                <small class="badge badge-success">Lead Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to insert deal (Requirement & Item Choice).mp4') }}" target="_blank"
                    class="text-dark">
                    <h6>CRM - How to insert deal (Requirement & Item Choice)</h6>
                </a>
                <small class="badge badge-success">Deal Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to approve a deal.mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to approve a deal</h6>
                </a>
                <small class="badge badge-success">Deal Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to submit a quotation to customer.mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to submit a quotation to customer</h6>
                </a>
                <small class="badge badge-success">Quotation Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to submit quotation feedback (Accept).mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to submit quotation feedback (Accept)</h6>
                </a>
                <small class="badge badge-success">Quotation Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to submit quotation feedback (Reject & Re-Deal).mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to submit quotation feedback (Reject & Re-Deal)</h6>
                </a>
                <small class="badge badge-success">Quotation Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How To Insert Transaction Form (Booking Stage).mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to insert transaction form (Booking Stage)</h6>
                </a>
                <small class="badge badge-success">Booking Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to verify transaction (Pay Type Cash).mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to verify transaction</h6>
                </a>
                <small class="badge badge-success">Booking Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to set credit (Pay Type Credit).mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to set credit (Pay Type Credit)</h6>
                </a>
                <small class="badge badge-success">Booking Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to set AITVAT credit (Pay Type Cash).mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to set AITVAT credit (Pay Type Cash)</h6>
                </a>
                <small class="badge badge-success">Booking Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to hold a lead on credit set.mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to hold a lead on credit set</h6>
                </a>
                <small class="badge badge-success">Booking Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to set discount.mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to set discount</h6>
                </a>
                <small class="badge badge-success">Delivery Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to set Invoice ID.mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to set Invoice ID</h6>
                </a>
                <small class="badge badge-success">Delivery Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to deliver and won a lead.mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to deliver and won a lead</h6>
                </a>
                <small class="badge badge-success">Delivery Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to re-submit credit set when credit on hold.mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to re-submit credit set when credit on hold</h6>
                </a>
                <small class="badge badge-success">Booking Stage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to back in the deal stage (Re-Deal).mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to back in the deal stage (Re-Deal)</h6>
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/How to lost a lead.mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - How to lost a lead</h6>
                </a>
            </div>
        </div>
        {{-- <div class="col-md-3">
            <div class="container videoCard">
                <a href="{{ asset('tutorial/.mp4') }}" target="_blank" class="text-dark">
                    <h6>CRM - </h6>
                </a>
            </div>
        </div> --}}

    </div>
</div>
