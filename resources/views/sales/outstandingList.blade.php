@include('layouts.navbar')
<div class="container p-2 mt-3">

    @if (count($outstandings) < 1)
        <center>
            <h4><kbd class="bg-danger">No Outstanding Found!</kbd></h4>
        </center>
    @endif

    @foreach ($outstandings as $item)
        <?php
        $depositedAmount = DB::select("SELECT SUM(pay_amount) AS totalPaid FROM transactions WHERE lead_id = $item->id AND is_verified = 1");
        
        if (!$depositedAmount[0]->totalPaid) {
            $depositedAmount = 0;
        } else {
            $depositedAmount = $depositedAmount[0]->totalPaid;
        }
        ?>
        <div class="row">
            <div class="col-md-3 col-6 fs-08rem">
                <kbd>Customer Info</kbd> <small class="badge badge-success">Lead ID: {{ $item->id }}</small>
                <br>
                <p class="p-0 m-0"><strong>Name: {{ $item->clientInfo->customer_name }}</strong></p>
                <p class="p-0 m-0">Phone: {{ $item->lead_phone }}</p>
                <p class="p-0 m-0">Emai: {{ $item->lead_email }}</p>
            </div>
            <?php
            $totalNet = DB::select("SELECT SUM(unit_price*qty) AS totalPrice, SUM(net_price) AS totalNet, SUM(discount_price) AS totalDiscount FROM pump_choices WHERE lead_id = $item->id");
            $totalAmt = $totalNet[0]->totalPrice;
            $totalNetAmt = $totalNet[0]->totalNet;
            $totalNetDiscount = $totalNet[0]->totalDiscount;
            ?>
            <div class="col-md-3 col-6 fs-08rem">
                <kbd>Deal Info</kbd>
                <br>
                <p class="p-0 m-0">Total Price: {{ $totalAmt }}</p>
                <p class="p-0 m-0">Total Discount: {{ $totalNetDiscount }}</p>
                <p class="p-0 m-0"><strong>Total Net Price: {{ $totalNetAmt }}</strong></p>
            </div>
            <div class="col-md-3 col-6 fs-08rem">
                <kbd>Payment Info</kbd>
                <br>
                <p class="p-0 m-0">Mood: {{ $item->payment_type }}</p>
                <p class="p-0 m-0">Paid: {{ $depositedAmount }}</p>
                <p class="p-0 m-0">Balance: {{ $totalNetAmt - $depositedAmount }}</p>
            </div>
            <div class="col-md-3 col-6 fs-08rem">
                <a href="{{ route('outStandingTransaction', ['leadId' => $item->id]) }}"><button
                        class="btn btn-sm btn-darkblue badge m-1">Transaction Details</button></a>
            </div>
        </div>
        <hr>
    @endforeach
</div>
