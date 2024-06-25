<div id="invoicePrint" class="d-none">
    <style>
        @media print {
            @page {
                size: auto;
                size: A4;
                margin: 0in;
            }

            #invoicePrint {
                visibility: visible;
            }

            table {
                font-size: 0.9rem;
                width: 600px;
                border-collapse: collapse;
                margin: auto;
                /* border: 1px solid black !important; */
            }

            tr {
                page-break-inside: avoid;
                line-height: 20px;
                height: 20px;
            }

            td {
                border: 1px solid black !important;
                font-size: 13px;
            }

            .signFooter {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                /* position: fixed; */
                margin-left: 20px;
                margin-right: 20px;
                margin-top: 50px;
            }

            .leadInfo {
                font-size: 13px;
            }
        }
    </style>
    <div style="margin-top: 120px"></div>
    <div style="font-size: 20px; margin:0px; padding:0px;">
        <center>
            <p>INVOICE</p>
        </center>
    </div>

    <div class="leadInfo">
        <div style="display:flex; flex-direction:row; justify-content:space-evenly">
            <p>
                Customer: {{ $leadInfo->clientInfo->customer_name }}
                <br>Address: {{ $leadInfo->clientInfo->address }}, {{ $leadInfo->clientInfo->district }}
            </p>
            <p>
                Invoice No: {{ $leadInfo->sap_invoice }} <br>
                Invoice Date: {{ date('d-M-Y', strtotime($leadInfo->invoice_date)) }} <br>
                PO/PR Ref.: {{ $quotationInfo[0]->quotation_po }} <br>
                PO/PR Date: {{ date('d-M-Y', strtotime($quotationInfo[0]->quotation_po_date)) }}
            </p>
        </div>
    </div>

    <div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>
                        <center>SL No.
                    </td>
                    </center>
                    <td>
                        <center>Description
                    </td>
                    </center>
                    <td>
                        <center>Unit Price
                    </td>
                    </center>
                    <td>
                        <center>Qty
                    </td>
                    </center>
                    <td>
                        <center>Discount (TK)
                    </td>
                    </center>
                    <td>
                        <center>Amount (TK)
                    </td>
                    </center>
                </tr>
            </thead>
            <tbody>
                <?php $sl = 1;
                $totalPrice = 0;
                $totalDiscount = 0;
                $totalNetPrice = 0; ?>
                @foreach ($pumpInfo as $item)
                    <?php
                    $totalPrice = $item->qty * $item->unit_price;
                    $totalDiscount = $totalDiscount + $item->discount_price;
                    $totalNetPrice = $totalNetPrice + $item->net_price; ?>
                    <tr class="fs-07rem">
                        <td class="p-1 text-center" style="align-content: space-evenly; text-align:center">
                            {{ $sl }}</td>
                        <td class="p-1"><b>Brand:</b> {{ $item->productInfo->brand_name }} <br> <b>Type:</b>
                            {{ $item->productInfo->itm_group }} <br> <b>Model:</b> {{ $item->productInfo->mat_name }}
                            <br> <b>Specification:</b> HP: {{ $item->productInfo->hp }}, KW:
                            {{ $item->productInfo->kw }}
                        </td>
                        <td class="p-1 text-end" style="align-content: space-evenly;text-align:right">
                            {{ number_format((float) $item->unit_price, 2, '.', ',') }}</td>
                        <td class="p-1 text-center" style="align-content: space-evenly;text-align:center">
                            {{ $item->qty }}</td>
                        <td class="p-1 text-end" style="align-content: space-evenly;text-align:right">
                            {{ number_format((float) $item->discount_price, 2, '.', ',') }}</td>
                        <td class="p-1 text-end" style="align-content: space-evenly;text-align:right">
                            {{ number_format((float) $item->net_price, 2, '.', ',') }}</td>
                    </tr>
                    <?php $sl++; ?>
                @endforeach
                <tr>
                    <td class="p-1 fw-bold text-center" colspan="5"><b>Total Net Pay</b></td>
                    <td class="p-1 text-end fw-bold" style="align-content: space-evenly;text-align:right">
                        <b>{{ number_format((float) $totalNetPrice, 2, '.', ',') }}</b></td>
                </tr>
                <tr>
                    <?php
                    $priceWord = new NumberFormatter('bd', NumberFormatter::SPELLOUT);
                    $priceWord = ucwords($priceWord->format($totalNetPrice));
                    ?>
                    <td class="p-1" colspan="6" style="padding-left: 10px;font-weight:600">In Words in BDT:
                        {{ $priceWord }} TAKA
                        ONLY</td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- 
    <div>
        <p>The Cheque/ PO/EFT will be issued in favor of <span class="fw-bold">“REL Motors Limited”</span></p>
    </div> --}}

    <div style="display:flex; flex-direction:row; justify-content: space-evenly; margin-top:5rem">
        <p style="border-top: 1px solid #111; width:20%; text-align: -webkit-center;">Customer Signature</p>
        <p style="border-top: 1px solid #111; width:20%; text-align: -webkit-center;">Checked By</p>
        <p style="border-top: 1px solid #111; width:20%; text-align: -webkit-center;">Authorized By</p>
    </div>
</div>
