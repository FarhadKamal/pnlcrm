@include('layouts.navbar')

<style>
    .reportCard{
        margin-top: 5%;
        padding: 5%;
        height: 50px;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
    }
</style>

<div class="container mt-2 mb-3">
    <h6 class="text-center">All Report List</h6>
    <div class="row">
        <div class="col-md-3">
            <div class="container reportCard">
                <a href="{{ route('discountReport') }}" target="_blank" class="text-dark">
                    <h6>Discount Check Report</h6>
                </a>
            </div>
        </div>
    </div>
</div>
