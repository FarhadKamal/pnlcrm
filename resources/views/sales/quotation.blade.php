@include('layouts.navbar')
<div class="float-end m-2">
    <a href="{{ route('detailsLog', ['leadId'=>$leadInfo->id]) }}" target="_blank"><button class="btn btn-darkblue btm-sm fs-07rem p-1">Details Log</button></a>
</div>
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
                                <td class="p-1 text-center"><input type="number" min="0" step="any"
                                        name="set_discount[]" onkeyup="updatePrice(this)"
                                        value="{{ $proposed_discount }}" required /></td>
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
                                <td class="p-1 text-center"><input type="number" min="0" step="any"
                                        name="set_discount[]" onkeyup="updatePrice(this)"
                                        value="{{ $proposed_discount }}" required /></td>
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
    <input type="hidden" name="QleadId" id="QleadId" value="{{ $leadInfo->id }}" required>
    @if ($leadInfo->lead_email)
        <center><button class="btn btn-sm btn-darkblue m-3" onclick="sentQuotation()">Submit to Client</button></center>
    @else
        <div class="bg-danger text-white p-2 text-center">
            <h5 class="">No Email Found For The Client. Please Add An Email First.</h5>
            <form action="{{ route('updateLeadEmail') }}" class="m-3">
                @csrf
                <input type="hidden" name="QleadId" id="QleadId" value="{{ $leadInfo->id }}" required>
                <label for="">Email</label>
                <input type="email" name="lead_email" id="lead_email" class="border-0 rounded fs-08rem p-1" required>
                <button class="btn btn-darkblue btn-sm">Add Email</button>
            </form>
        </div>
    @endif
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


<script>
    setInterval(function() {
        fetch('/quotationReferenceCheck')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(json => {
                let refFullDate = new Date(json.currentDate);
                let refYear = refFullDate.getFullYear();
                let refMonth = Number(refFullDate.getMonth() + 1).toString().padStart(2, '0');
                let refDate = Number(refFullDate.getDate()).toString().padStart(2, '0');

                let serialNo = Number(json.checkQuotationSerial[0]['sl'] + 1).toString().padStart(3,
                    '0');

                let refPreText = 'REF: PNL/P/QUT/' + refYear + '/' + refMonth + refDate + serialNo;
                // console.log(refPreText);
                document.getElementById('quotationRef').innerText = refPreText;
                $('#quotationRef').val(refPreText);

            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });

    }, 1000 * 60 * 0.01);
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


<script>
    function sentQuotation() {
        var blob;
        window.jsPDF = window.jspdf.jsPDF;
        var docPDF = new jsPDF();
        docPDF.setFont("Helvetica", "normal");

        var elementHTML = document.querySelector("#section-to-print");
        docPDF.html(elementHTML, {
            callback: function(docPDF) {
                // docPDF.save();
                blob = docPDF.output('blob');
                let leadId = $('#QleadId').val();
                let quotationRef = $('#quotationRef').val();
                let _token = '<?php echo csrf_token(); ?>';
                var formData = new FormData();
                formData.append('leadId', leadId);
                formData.append('quotationRef', quotationRef);
                formData.append('doc', blob);
                formData.append('_token', _token);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content'),
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/submitQuotation',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        document.getElementById("loadingGif").style.display = "block";
                    },
                    complete: function() {
                        document.getElementById("loadingGif").style.display = "none";
                    },
                    success: function(data) {
                        // console.log(data);
                        window.location.href = "/home";
                    },
                    error: function(data) {
                        // console.log(data)
                    }
                });

            },
            margin: [10, 10, 10, 10],
            autoPaging: true,
            x: 5,
            y: 2,
            width: 190, //target width in the PDF document
            windowWidth: 675 //window width in CSS pixels
        });
    }
</script>
