<div id="deliveryChallanPrint" class="d-none">
    <style>
        @media print {
            @page {
                size: auto;
                size: A4;
                margin: 0in;
            }

            .headerContainer {
                position: fixed;
                top: 10;
                right: 10;
                text-align: right;
                margin: 1rem;
            }

            #deliveryChallanPrint {
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

            .footerContainer {
                position: fixed;
                bottom: 0;
                width: 100%;
                margin: 1rem;
            }

            .footerContainer tr {
                line-height: 0px !important;
                height: 0px !important;
            }

            .footerContainer td {
                border: none !important;
            }
        }
    </style>
    {{-- <div>
        <img style="padding:0;margin:10px" src="{{ asset('images/system/logo.png') }}" alt="" height="50">
    </div> --}}
    {{-- <div style="margin-top: 120px"></div> --}}
    <div class="headerContainer">
        <img style="padding:0;margin:0;" src="{{ asset('images/system/logo.png') }}" alt="" height="50">
        <h6 style="font-weight: 600; margin-top:1%;font-size:14px">PNL HOLDINGS LIMITED</h6>
    </div>
    <div style="margin-top: 130px"></div>
    <div style="font-size: 28px; margin:0px; padding:0px;">
        <center>
            <p style="border-bottom:2px solid #111; display:inline-block">Delivery Challan</p>
        </center>
    </div>


    <div>
        <table>
            <tr>
                <td style="border: none !important">
                    <p class="m-0 p-0">Delivery Challan No: {{ $leadInfo->delivery_challan }}</p>
                </td>
                <td style="border: none !important;float: right;">
                    <p>Date : <?= date('jS F Y') ?></p>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <table>
            <tr>
                <td style="border: none !important">
                    <p>Customer: {{ $leadInfo->clientInfo->customer_name }}
                        <br>Delivery Address: {{ $leadInfo->delivery_address }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table>
            <tr>
                <td style="border: none !important">
                    <p>Client PO Ref. No: {{ $quotationInfo[0]->quotation_po }}
                        <br>PO. Date: {{ date('d-M-Y', strtotime($quotationInfo[0]->quotation_po_date)) }}
                    </p>
                </td>
                <td style="border: none !important; float: right;">
                    <p>Contact Person: {{ $leadInfo->delivery_person }}
                        <br>Contact No: {{ $leadInfo->delivery_mobile }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table class="table table-bordered" style="font-size: 15px">
            <thead>
                <tr>
                    <td>
                        <center>SL No.</center>
                    </td>
                    <td>
                        <center>Description</center>
                    </td>
                    <td>
                        <center>Unit</center>
                    </td>
                    <td>
                        <center>Qty</center>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php $sl = 1;
                $totalQuantity = 0;
                ?>
                @foreach ($pumpInfo as $itemPump)
                    <?php
                    $totalQuantity = $totalQuantity + $itemPump->qty;
                    if ($itemPump->spare_parts == 0) {
                        if ($itemPump->productInfo->pump_type != 'ITAP' && $itemPump->productInfo->pump_type != 'MAXWELL') {
                            $country = $itemPump->productInfo->country_name;
                            $productDesc = '<b>' . $itemPump->productInfo->brand_name . ' ' . $itemPump->productInfo->pump_type . ' pump</b> (' . $country . '). <b>Model:</b> ' . $itemPump->productInfo->mat_name . '(' . $itemPump->productInfo->phase . ').  <br><b>Power:</b> ' . $itemPump->productInfo->kw . 'KW/' . $itemPump->productInfo->hp . 'HP. <b>Head(M):</b> ' . $itemPump->productInfo->max_head . '-' . $itemPump->productInfo->min_head . '. <b>Suction Dia:</b> ' . $itemPump->productInfo->suction_dia . ' Inch. ' . '<b>Delivery Dia:</b> ' . $itemPump->productInfo->delivery_dia . ' Inch.';
                            $unitName = $itemPump->productInfo->unit_name;
                        } else {
                            $country = $itemPump->productInfo->country_name;
                            $productDesc = '<b>' . $itemPump->productInfo->brand_name . ' </b> (' . $country . ') ' . $itemPump->productInfo->mat_name;
                            $unitName = $itemPump->productInfo->unit_name;
                        }
                    } else {
                        $country = $itemPump->spareInfo->country_name;
                        $productDesc = '<b>' . $itemPump->spareInfo->brand_name . ' </b>' . $itemPump->spareInfo->mat_name;
                        $unitName = $itemPump->spareInfo->unit_name;
                    }
                    ?>
                    <tr class="fs-07rem">
                        <td class="p-1 text-center" style="align-content: space-evenly; text-align:center">
                            {{ $sl }}</td>
                        <td class="p-1" style="font-size: 12px; line-height:initial">{!! $productDesc !!}</td>
                        <td class="p-1 text-center" style="align-content: space-evenly;text-align:center">{{ $unitName }}</td>
                        <td class="p-1 text-center" style="align-content: space-evenly;text-align:center">
                            {{ $itemPump->qty }}</td>
                    </tr>
                    <?php $sl++; ?>
                @endforeach
                {{-- <tr>
                    <td class="p-1 fw-bold text-center" colspan="2"><b>Total Quantity</b></td>
                    <td class="p-1 text-end fw-bold" style="align-content: space-evenly;text-align:center"><b>
                            {{ $totalQuantity }}</b></td>
                </tr> --}}
            </tbody>
        </table>
    </div>
    {{-- 
    <div>
        <p>The Cheque/ PO/EFT will be issued in favor of <span class="fw-bold">“REL Motors Limited”</span></p>
    </div> --}}

    <div style="display:flex; flex-direction:row; justify-content: space-evenly; margin-top:5rem">
        <p style="border-top: 1px solid #111; width:20%; text-align: -webkit-center;">Received By</p>
        <p style="border-top: 1px solid #111; width:30%; text-align: -webkit-center;">For PNL Holdings Limited</p>
    </div>

    <div class="footerContainer">
        <p class="text-primary fw-bold" style="font-size:11px">Head Office: Pedrollo Plaza, 05
            Jubilee Road,
            Chattogram-4000.</p>
        <p class="text-primary fw-bold" style="font-size:11px">Dhaka Office: Pedrollo House, 12 Topkhana Road,
            Segunbagicha, Dhaka-1000.</p>
        <table style="width: 100%;">
            <tr>
                <td style="color:#1154cc;font-size:10px"><img src="{{ asset('images/system/email.png') }}"
                        alt="" width="12">&nbsp;sales@pnlholdings.com
                    <img src="{{ asset('images/system/web.png') }}" alt=""
                        width="12">&nbsp;www.pnlholdings.com
                    <img src="{{ asset('images/system/facebook.png') }}" alt=""
                        width="12">&nbsp;facebook.com/thinkPNL
                    <img src="{{ asset('images/system/linkedin.png') }}" alt=""
                        width="12">&nbsp;pnl-holdings-limited
                    <img src="{{ asset('images/system/call.png') }}" alt="" width="12">&nbsp;16308 (9:00
                    AM - 9:00 PM)
                </td>
            </tr>
        </table>

        <table style="width: 100%;margin-top:1%">
            <tr>
                <td><img src="{{ asset('images/system/com/pedrollo.png') }}" alt="" width="80"></td>
                <td><img src="{{ asset('images/system/com/BGFlow.jpg') }}" alt="" width="80"></td>
                <td><img src="{{ asset('images/system/com/panelli.jpg') }}" alt="" width="100"></td>
                <td><img src="{{ asset('images/system/com/hcp.png') }}" alt="" width="50"></td>
                <td><img src="{{ asset('images/system/com/maxwell.jpg') }}" alt="" width="50"></td>
                <td><img src="{{ asset('images/system/com/itap.png') }}" alt="" width="70"></td>
                <td><img src="{{ asset('images/system/com/firenza.png') }}" alt="" width="80"></td>
            </tr>
        </table>
    </div>
</div>
