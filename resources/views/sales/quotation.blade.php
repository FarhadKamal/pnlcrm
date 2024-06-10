@include('layouts.navbar')
<div class="">
    @include('sales.quotationLayout')
</div>

@if ($leadInfo->current_subStage == 'APPROVE')
    {{-- COO Aproval  --}}
    <div class="container mt-5 mb-5 p-4 shadow-4 border border-3" style="background-color: rgba(0,84,166, 0.2)">
        <form action="{{ route('preQuotationApprove') }}" method="POST">
            @csrf
            <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}" required>
            @if ($leadInfo->need_credit_approval)
                <label for="">Credit Approval</label><br>
                <select name="credit_approved" id="" required>
                    <option value="1" selected>Approved</option>
                    <option value="0">Not Approved</option>
                </select>
                <br>
            @endif
            @if ($leadInfo->need_discount_approval)
                <label for="">Discount Approval</label>
                <table class="table table-bordered border-dark">
                    <thead>
                        <tr>
                            <th class="p-1 text-center">Brand</th>
                            <th class="p-1 text-center">Model</th>
                            <th class="p-1 text-center">Unit Price (TK)</th>
                            <th class="p-1 text-center">Trade Discount (%)</th>
                            <th class="p-1 text-center">Qty.</th>
                            <th class="p-1 text-center">Deal Discount (%)</th>
                            <th class="p-1 text-center">Net Price (TK)</th>
                            <th class="p-1 text-center">Set New Discount (%)</th>
                            <th class="p-1 text-center">New Net Price (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pumpInfo as $item)
                            <?php
                            $proposed_discount = $item->discount_percentage;
                            $trade_discount = $item->productInfo->TradDiscontInfo->trade_discount;
                            if ($proposed_discount > $trade_discount) {
                            }
                            ?>
                            <tr>
                                <td class="d-none"><input name="approvePumpChoice[]" value="{{ $item->id }}"></td>
                                <td class="p-1 text-center">{{ $item->productInfo->brand_name }}</td>
                                <td class="p-1 text-center">{{ $item->productInfo->mat_name }}</td>
                                <td class="p-1 text-end">{{ $item->unit_price }}</td>
                                <td class="p-1 text-center">{{ $trade_discount }}</td>
                                <td class="p-1 text-center">{{ $item->qty }}</td>
                                <td class="p-1 text-center">{{ $proposed_discount }}</td>
                                <td class="p-1 text-end">{{ $item->net_price }}</td>
                                <td class="p-1 text-center"><input type="number" min="0" name="set_discount[]"
                                        onkeyup="updatePrice(this)" value="{{ $proposed_discount }}" required /></td>
                                <td class="p-1 text-end">{{ $item->net_price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <center><button class="btn btn-sm btn-darkblue">Submit Approval</button></center>
        </form>
    </div>
@endif

@if ($leadInfo->current_subStage == 'MANAGEMENT')
    {{-- Top Management Approval  --}}
    <div class="container mt-5 mb-5 p-4 shadow-4 border border-3" style="background-color: rgba(0,84,166, 0.2)">
        <form action="{{ route('topQuotationApprove') }}" method="POST">
            @csrf
            <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}" required>
            @if ($leadInfo->need_discount_approval)
                <label for="">Discount Approval</label>
                <table class="table table-bordered border-dark">
                    <thead>
                        <tr>
                            <th class="p-1 text-center">Brand</th>
                            <th class="p-1 text-center">Model</th>
                            <th class="p-1 text-center">Unit Price (TK)</th>
                            <th class="p-1 text-center">Trade Discount (%)</th>
                            <th class="p-1 text-center">Qty.</th>
                            <th class="p-1 text-center">Deal Discount (%)</th>
                            <th class="p-1 text-center">Net Price (TK)</th>
                            <th class="p-1 text-center">Set New Discount (%)</th>
                            <th class="p-1 text-center">New Net Price (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pumpInfo as $item)
                            <?php
                            $proposed_discount = $item->discount_percentage;
                            $trade_discount = $item->productInfo->TradDiscontInfo->trade_discount;
                            if ($proposed_discount > $trade_discount) {
                            }
                            ?>
                            <tr>
                                <td class="d-none"><input name="approvePumpChoice[]" value="{{ $item->id }}"></td>
                                <td class="p-1 text-center">{{ $item->productInfo->brand_name }}</td>
                                <td class="p-1 text-center">{{ $item->productInfo->mat_name }}</td>
                                <td class="p-1 text-end">{{ $item->unit_price }}</td>
                                <td class="p-1 text-center">{{ $trade_discount }}</td>
                                <td class="p-1 text-center">{{ $item->qty }}</td>
                                <td class="p-1 text-center">{{ $proposed_discount }}</td>
                                <td class="p-1 text-end">{{ $item->net_price }}</td>
                                <td class="p-1 text-center"><input type="number" min="0" name="set_discount[]"
                                        onkeyup="updatePrice(this)" value="{{ $proposed_discount }}" required /></td>
                                <td class="p-1 text-end">{{ $item->net_price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <center><button class="btn btn-sm btn-darkblue">Submit Approval</button></center>
        </form>
    </div>
@endif

@if ($leadInfo->current_subStage == 'SUBMIT')
    {{-- Submit To Customer  --}}
    <form action="">
        @csrf
        <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}" required>
    </form>
@endif


<script>
    function updatePrice(e) {
        var row = e.parentElement.parentElement;
        row = row.querySelectorAll("td");
        let productUP = row[3].innerText;
        let productQty = row[5].innerText;
        let productNewDiscountPercentage = row[8].querySelector('input');
        productDiscountPercentage = productNewDiscountPercentage.value;
        let totalPrice = (Number(productUP) * Number(productQty));
        let discountAmount = totalPrice * (Number(productDiscountPercentage) / 100);
        let productTotalPrice = totalPrice - discountAmount;
        row[9].innerText = productTotalPrice;
    }
</script>
