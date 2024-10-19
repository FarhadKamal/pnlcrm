@include('layouts.navbar')

<style>
    .reportCard {
        margin-top: 5%;
        padding: 5%;
        height: 50px;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
        background: #3F4A60;
        text-align: center;
        border-radius: 12px;
    }

    .reportCard h6 {
        color: #FFFFFF;
    }
</style>

<div class="container mt-2 mb-3">
    <h5 class="text-center">All Report List</h5>
    <div class="row">
        <div class="col-md-3">
            <div class="container reportCard">
                <a href="{{ route('discountReport') }}" target="_blank">
                    <h6>Discount Check Report</h6>
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container reportCard">
                <a href="{{ route('outstandingReport') }}" target="_blank">
                    <h6>Outstanding Report</h6>
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container reportCard">
                <a href="{{ route('targetSalesReport') }}" target="_blank">
                    <h6>Target vs Sales Report</h6>
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container reportCard">
                <a href="{{ route('transactionReport') }}" target="_blank">
                    <h6>Transaction Report</h6>
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container reportCard">
                <a href="{{ route('graphReport') }}" target="_blank">
                    <h6>Graph Report</h6>
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="container reportCard">
                <a href="{{ route('leadDetailReport') }}" target="_blank">
                    <h6>Lead Detail Info</h6>
                </a>
            </div>
        </div>
    </div>
</div>
