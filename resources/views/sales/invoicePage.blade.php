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
                @foreach ($pumpInfo as $itemPump)
                    <?php
                    $totalPrice = $itemPump->qty * $itemPump->unit_price;
                    $totalDiscount = $totalDiscount + $itemPump->discount_price;
                    $totalNetPrice = $totalNetPrice + $itemPump->net_price;
                    if ($itemPump->spare_parts == 0) {
                        if ($itemPump->productInfo->pump_type != 'ITAP' && $itemPump->productInfo->pump_type != 'MAXWELL') {
                            $country = $itemPump->productInfo->country_name;
                            $productDesc = '<b>' . $itemPump->productInfo->brand_name . ' ' . $itemPump->productInfo->pump_type . ' pump</b> (' . $country . '). <b>Model:</b> ' . $itemPump->productInfo->mat_name . '(' . $itemPump->productInfo->phase . ').  <br><b>Power:</b> ' . $itemPump->productInfo->kw . 'KW/' . $itemPump->productInfo->hp . 'HP. <b>Head(M):</b> ' . $itemPump->productInfo->max_head . '-' . $itemPump->productInfo->min_head . '. <b>Suction Dia:</b> ' . $itemPump->productInfo->suction_dia . ' Inch. ' . '<b>Delivery Dia:</b> ' . $itemPump->productInfo->delivery_dia . ' Inch.';
                        } else {
                            $country = $itemPump->productInfo->country_name;
                            $productDesc = '<b>' . $itemPump->productInfo->brand_name . ' </b> (' . $country . ') ' . $itemPump->productInfo->mat_name;
                        }
                    } else {
                        $country = $itemPump->spareInfo->country_name;
                        $productDesc = '<b>' . $itemPump->spareInfo->brand_name . ' </b> (' . $country . ') ' . $itemPump->spareInfo->mat_name;
                    }
                    
                    ?>
                    <tr class="fs-07rem">
                        <td class="p-1 text-center" style="align-content: space-evenly; text-align:center">
                            {{ $sl }}</td>
                        <td class="p-1">{!! $productDesc !!}</td>
                        <td class="p-1 text-end" style="align-content: space-evenly;text-align:right">
                            {{ number_format((float) $itemPump->unit_price, 2, '.', ',') }}</td>
                        <td class="p-1 text-center" style="align-content: space-evenly;text-align:center">
                            {{ $itemPump->qty }}</td>
                        <td class="p-1 text-end" style="align-content: space-evenly;text-align:right">
                            {{ number_format((float) $itemPump->discount_price, 2, '.', ',') }}</td>
                        <td class="p-1 text-end" style="align-content: space-evenly;text-align:right">
                            {{ number_format((float) $itemPump->net_price, 2, '.', ',') }}</td>
                    </tr>
                    <?php $sl++; ?>
                @endforeach
                <tr>
                    <td class="p-1 fw-bold text-center" colspan="5"><b>Total Net Pay</b></td>
                    <td class="p-1 text-end fw-bold" style="align-content: space-evenly;text-align:right">
                        <b>{{ number_format((float) $totalNetPrice, 2, '.', ',') }}</b>
                    </td>
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
