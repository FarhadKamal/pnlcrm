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
                width: 690px;
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
    <div style="margin-top: 140px"></div>
    <div style="font-size: 20px; margin:0px; padding:0px; font-weight:600;">
        <center>
            <p>INVOICE</p>
        </center>
    </div>

    {{-- <div>
        <table>
            <tr>
                <td>
                    <p style="margin-left: 1rem">CUSTOMER SEGMENT</p>
                </td>
                <td>
                    <p style="margin-left: 1rem">CORPORATE</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="margin-left: 1rem">INVOICE GENERATED FROM</p>
                </td>
                <td>
                    <p style="margin-left: 1rem"></p>
                </td>
            </tr>
        </table>
    </div>
    <br> --}}
    <div>
        <table>
            <tr>
                <td>
                    <p style="margin-left: 1rem; font-size:13px;line-height:initial">
                        CUSTOMER NAME: {{ $leadInfo->clientInfo->customer_name }}
                        <br>CUSTOMER ID: {{ $leadInfo->clientInfo->sap_id }}
                        <br>CONTACT PERSON: {{ $leadInfo->lead_person }}
                        <br>CUSTOMER ADDRESS: {{ $leadInfo->clientInfo->address }}
                    </p>
                </td>
                <td>
                    <p style="margin-left: 1rem; font-size:13px;line-height:initial">
                        INVOICE NO: {{ $leadInfo->sap_invoice }} <br>
                        INVOICE DATE: {{ date('d-M-Y', strtotime($leadInfo->invoice_date)) }} <br>
                        PO/PR REF NO: {{ $quotationInfo[0]->quotation_po }} <br>
                        PO/PR REF DATE: {{ date('d-M-Y', strtotime($quotationInfo[0]->quotation_po_date)) }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- <div class="leadInfo">
        <div style="display:flex; flex-direction:row; justify-content:space-evenly">
            <p>
                Customer: {{ $leadInfo->clientInfo->customer_name }}
                <br>Address: {{ $leadInfo->clientInfo->address }}, {{ $leadInfo->clientInfo->district }}
            </p>
            <p>
                Invoice No: {{ $leadInfo->sap_invoice }} <br>
                Invoice Date: {{ date('d-M-Y', strtotime($leadInfo->invoice_date)) }} <br>
                PO/PR Ref No: {{ $quotationInfo[0]->quotation_po }} <br>
                PO/PR Ref Date: {{ date('d-M-Y', strtotime($quotationInfo[0]->quotation_po_date)) }}
            </p>
        </div>
    </div> --}}
    <br>
    <div>
        <table class="table table-bordered" style="font-size: 12px">
            <thead>
                <tr>
                    <td>
                        <center>SL No.</center>
                    </td>
                    <td>
                        <center>Item Code</center>
                    </td>
                    <td>
                        <center>Material Description</center>
                    </td>
                    <td>
                        <center>Unit</center>
                    </td>
                    <td>
                        <center>Qty.</center>
                    </td>
                    <td>
                        <center>Unit Price (BDT)</center>
                    </td>

                    <td>
                        <center>Total Amount (BDT)</center>
                    </td>

                </tr>
            </thead>
            <tbody>
                <?php $sl = 1;
                $totalInvoiceAmount = 0;
                $totalPrice = 0;
                $totalDiscount = 0;
                $totalNetPrice = 0; ?>
                @foreach ($pumpInfo as $itemPump)
                    <?php
                    $totalPrice = $itemPump->qty * $itemPump->unit_price;
                    $totalInvoiceAmount = $totalInvoiceAmount + $totalPrice;
                    $totalDiscount = $totalDiscount + $itemPump->discount_price;
                    $totalNetPrice = $totalNetPrice + $itemPump->net_price;
                    if ($itemPump->spare_parts == 0) {
                        if ($itemPump->productInfo->pump_type != 'ITAP' && $itemPump->productInfo->pump_type != 'MAXWELL') {
                            $country = $itemPump->productInfo->country_name;
                            $productDesc = '<b>' . $itemPump->productInfo->brand_name . ' ' . $itemPump->productInfo->pump_type . ' pump</b> (' . $country . '). <b>Model:</b> ' . $itemPump->productInfo->mat_name . '(' . $itemPump->productInfo->phase . ').  <br><b>Power:</b> ' . $itemPump->productInfo->kw . 'KW/' . $itemPump->productInfo->hp . 'HP. <b>Head(M):</b> ' . $itemPump->productInfo->max_head . '-' . $itemPump->productInfo->min_head . '. <b>Suction Dia:</b> ' . $itemPump->productInfo->suction_dia . ' Inch. ' . '<b>Delivery Dia:</b> ' . $itemPump->productInfo->delivery_dia . ' Inch.';
                            $unitName = $itemPump->productInfo->unit_name;
                            $itemCode = $itemPump->productInfo->new_code;
                        } else {
                            $country = $itemPump->productInfo->country_name;
                            $productDesc = '<b>' . $itemPump->productInfo->brand_name . ' </b> (' . $country . ') ' . $itemPump->productInfo->mat_name;
                            $unitName = $itemPump->productInfo->unit_name;
                            $itemCode = $itemPump->productInfo->new_code;
                        }
                    } else {
                        $country = $itemPump->spareInfo->country_name;
                        $productDesc = '<b>' . $itemPump->spareInfo->brand_name . ' </b> ' . $itemPump->spareInfo->mat_name;
                        $unitName = $itemPump->spareInfo->unit_name;
                        $itemCode = $itemPump->spareInfo->new_code;
                    }
                    
                    ?>
                    <tr class="fs-07rem">
                        <td class="p-1 text-center" style="align-content: space-evenly; text-align:center">
                            {{ $sl }}</td>
                        <td class="p-1 text-center" style="align-content: space-evenly; text-align:center">
                            {{ $itemCode }}</td>

                        <td class="p-1" style="font-size: 12px; line-height:initial">{!! $productDesc !!}</td>
                        <td class="p-1 text-center" style="align-content: space-evenly; text-align:center">
                            {{ $unitName }}</td>
                        <td class="p-1 text-center" style="align-content: space-evenly;text-align:center">
                            {{ $itemPump->qty }}</td>
                        <td class="p-1 text-end" style="align-content: space-evenly;text-align:right">
                            {{ number_format((float) $itemPump->unit_price, 2, '.', ',') }}&nbsp;</td>

                        {{-- <td class="p-1 text-end" style="align-content: space-evenly;text-align:right">
                            {{ number_format((float) $itemPump->discount_price, 2, '.', ',') }}</td> --}}
                        <td class="p-1 text-end" style="align-content: space-evenly;text-align:right">
                            {{ number_format((float) ($itemPump->unit_price * $itemPump->qty), 2, '.', ',') }}&nbsp;
                        </td>
                    </tr>
                    <?php $sl++; ?>
                @endforeach
                <tr>
                    <td class="p-1 fw-bold" style="text-align:right" colspan="6"><b>Total Invoice Amount: </b></td>
                    <td class="p-1 text-end fw-bold" style="align-content: space-evenly;text-align:right">
                        <b>{{ number_format((float) $totalInvoiceAmount, 2, '.', ',') }}</b>&nbsp;
                    </td>
                </tr>
                @if ($totalDiscount > 0)
                    <tr>
                        <td class="p-1 fw-bold text-center" style="text-align:right" colspan="6"><b>Less Discount:
                            </b>
                        </td>
                        <td class="p-1 text-end fw-bold" style="align-content: space-evenly;text-align:right">
                            <b>{{ number_format((float) $totalDiscount, 2, '.', ',') }}</b>&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1 fw-bold text-center" style="text-align:right" colspan="6"><b>Net Pay: </b>
                        </td>
                        <td class="p-1 text-end fw-bold" style="align-content: space-evenly;text-align:right">
                            <b>{{ number_format((float) $totalNetPrice, 2, '.', ',') }}</b>&nbsp;
                        </td>
                    </tr>
                @endif
                <tr>
                    <?php
                    $priceWord = new NumberFormatter('bd', NumberFormatter::SPELLOUT);
                    $priceWord = ucwords($priceWord->format($totalNetPrice));
                    ?>
                    <td class="p-1" colspan="7" style="padding-left: 10px;font-weight:600">In Words in BDT:
                        {{ $priceWord }} Taka Only</td>
                </tr>
            </tbody>
        </table>
        <?php
        if ($leadInfo->invoiceBy) {
            $preparedBy = $leadInfo->invoiceBy->user_name . ' | ' . $leadInfo->invoiceBy->designation->desg_name;
        } else {
            $preparedBy = '';
        }
        ?>

    </div>
    {{-- 
    <div>
        <p>The Cheque/ PO/EFT will be issued in favor of <span class="fw-bold">“REL Motors Limited”</span></p>
    </div> --}}

    <div style="display:flex; flex-direction:row; justify-content:space-evenly; margin-top:5rem">
        <p style="text-align: -webkit-center;"><span style="border-bottom: 1px solid #111;">{{ $preparedBy }}</span>
            <br>
            <span>Prepared
                By</span>
        </p>
        <p><span></span> <br> <span style="border-top: 1px solid #111; text-align: -webkit-center;">Authorized By</span>
        </p>
        <p><span></span> <br> <span style="border-top: 1px solid #111; text-align: -webkit-center;">Acknowledged By
                (Customer)</span></p>
        {{-- <p style="border-top: 1px solid #111; width:20%; text-align: -webkit-center;">Authorized By</p> --}}
        {{-- <p style="border-top: 1px solid #111; width:30%; text-align: -webkit-center;">Acknowledged By (Customer)</p> --}}
    </div>
    {{-- <div>
        <center><p>This is a system generated invoice</p></center>
    </div> --}}
</div>
