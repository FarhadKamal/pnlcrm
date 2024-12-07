@include('layouts.navbar')
<div class="container p-2 mt-3">

    @if (count($outstandings) < 1)
        <center>
            <h4><kbd class="bg-danger">No Outstanding Found!</kbd></h4>
        </center>
    @endif

    <div class="row p-2 mb-3 rounded-pill">
        <small class="text-danger">Please reset before searching</small>
        <p class="col-md-2">Search By Lead ID</p>
        <div class="col-md-2 col-4">
            <input type="number" class="form-control p-1 fs-08rem" id="searchBox">
        </div>
        <div class="col-md-2 col-4">
            <button class="btn btn-darkblue p-1 fs-07rem" onclick="filterData()">Search</button>
        </div>
        <div class="col-md-2 col-4">
            <button class="btn btn-primary p-1 fs-07rem" onclick="location.reload()">Reset</button>
        </div>
    </div>

    @foreach ($outstandings as $item)
        <?php
        $depositedAmount = DB::select("SELECT SUM(pay_amount) AS totalPaid FROM transactions WHERE lead_id = $item->id AND is_verified = 1");
        
        if (!$depositedAmount[0]->totalPaid) {
            $depositedAmount = 0;
        } else {
            $depositedAmount = $depositedAmount[0]->totalPaid;
        }
        ?>
        <div class="row allOutRow">
            <span class="d-none">{{ $item->id }}</span>
            <div class="col-md-3 col-6 fs-08rem">
                <kbd>Customer Info</kbd> <small class="badge badge-success">Lead ID: {{ $item->id }}</small>
                <br>
                <p class="p-0 m-0"><strong>Name: {{ $item->clientInfo->customer_name }}</strong></p>
                <p class="p-0 m-0">Customer ID: {{ $item->clientInfo->sap_id }}</p>
                <p class="p-0 m-0">Assign Person: {{ $item->clientInfo->assignTo->user_name }}</p>
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
                <p class="p-0 m-0">Invoice No: {{ $item->sap_invoice }}</p>
            </div>
            <div class="col-md-3 col-6 fs-08rem">
                <kbd>Payment Info</kbd>
                <br>
                <p class="p-0 m-0">Mood: {{ $item->payment_type }}</p>
                <p class="p-0 m-0">Paid: {{ $depositedAmount }}</p>
                <p class="p-0 m-0">Balance: {{ $totalNetAmt - $depositedAmount }}</p>
                <p class="p-0 m-0">Invoice Date: {{ date('d-M-Y', strtotime($item->invoice_date)) }}</p>
            </div>
            <div class="col-md-3 col-6 fs-08rem">
                <a href="{{ route('outStandingTransaction', ['leadId' => $item->id]) }}"><button
                        class="btn btn-sm btn-darkblue badge m-1">Transaction Details</button></a>
            </div>
            <hr>
        </div>
    @endforeach
</div>

<script>
    function filterData() {
        let searchBox = $('#searchBox').val();
        let allRow = document.querySelectorAll('.allOutRow');
        allRow.forEach(element => {
            let rowId = Number(element.childNodes[1].innerText);
            if (searchBox != rowId) {
                element.style.display = 'none';
            }
        });
    }
</script>
