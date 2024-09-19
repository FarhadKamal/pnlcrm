@include('layouts.navbar')

<div class="container mt-2 mb-3">
    <h6 class="text-center">Discount Report</h6>
    <form action="{{ route('discountReport') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="" class="form-label">Invoice Date Range</label>
                <input type="text" name="invoiceDateFilter" class="flatpickr form-control" required>
            </div>
            <div class="col-md-6">
                {{-- <label for="" class="form-label">Invoice Date Start</label> --}}
                <button class="btn btn-darkblue mt-4 w-100">Pull Report</button>
            </div>
        </div>
    </form>
</div>

@if (isset($reportData) && count($reportData) > 0)
    <div class="m-2">
        <table class="table table-bordered fs-07rem">
            <thead>
                <tr>
                    <td class="p-1 text-center">Invoice Number</td>
                    <td class="p-1 text-center">Invoice Date</td>
                    <td class="p-1 text-center">Employee</td>
                    <td class="p-1 text-center">Customer Code</td>
                    <td class="p-1 text-center">Customer Name</td>
                    <td class="p-1 text-center">Brand</td>
                    <td class="p-1 text-center">Type</td>
                    <td class="p-1 text-center">Item Code</td>
                    <td class="p-1 text-center">Item Name</td>
                    <td class="p-1 text-center">MRP</td>
                    <td class="p-1 text-center">Qty.</td>
                    <td class="p-1 text-center">Total Price</td>
                    <td class="p-1 text-center">Total Discount</td>
                    <td class="p-1 text-center">Total Discount (%)</td>
                    <td class="p-1 text-center">Trade Discount</td>
                    <td class="p-1 text-center">Trade Discount (%)</td>
                    <td class="p-1 text-center">Special Discount</td>
                    <td class="p-1 text-center">Special Discount (%)</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($reportData as $item)
                    @php
                        $totalPrice = $item->unit_price * $item->qty;
                        $totalTradeDiscount = $totalPrice * $totalPrice;
                        $specialDiscount = $item->discount_price - $totalTradeDiscount;
                        $specialDiscountPer = $item->discount_percentage - $item->trade_discount;
                        if ($item->spare_parts == 0) {
                            $type = 'Spare Parts';
                        } else {
                            $type = 'Items';
                        }
                        
                    @endphp
                    <tr>
                        <td class="p-1 text-center">{{ $item->sap_invoice }}</td>
                        <td class="p-1">{{ date('d-M-Y', strtotime($item->invoice_date)) }}</td>
                        <td class="p-1">{{ $item->user_name }}</td>
                        <td class="p-1">{{ $item->sap_id }}</td>
                        <td class="p-1">{{ $item->customer_name }}</td>
                        <td class="p-1">{{ $item->brand_name }}</td>
                        <td class="p-1">{{ $type }}</td>
                        <td class="p-1">{{ $item->product_code }}</td>
                        <td class="p-1">{{ $item->mat_name }}</td>
                        <td class="p-1">{{ $item->unit_price }}</td>
                        <td class="p-1 text-center">{{ $item->qty }}</td>
                        <td class="p-1 text-end">{{ $totalPrice }}</td>
                        <td class="p-1 text-end">{{ $item->discount_price }}</td>
                        <td class="p-1 text-center">{{ $item->discount_percentage }}</td>
                        <td class="p-1 text-end">{{ $totalTradeDiscount }}</td>
                        <td class="p-1 text-center">{{ $item->trade_discount }}</td>
                        <td class="p-1 text-end">{{ $specialDiscount }}</td>
                        <td class="p-1 text-center">{{ $specialDiscountPer }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<script>
    const myInput = document.querySelector(".flatpickr");
    const fp = flatpickr(myInput, {
        mode: "range",
        dateFormat: "d-M-Y",
        defaultDate: [new Date(), new Date()]
    }); // flatpickr
</script>
