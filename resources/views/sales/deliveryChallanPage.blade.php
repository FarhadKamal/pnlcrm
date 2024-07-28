<div id="deliveryChallanPrint" class="d-none">
    <style>
        @media print {
            @page {
                size: auto;
                size: A4;
                margin: 0in;
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
        }
    </style>
    {{-- <div>
        <img style="padding:0;margin:10px" src="{{ asset('images/system/logo.png') }}" alt="" height="50">
    </div> --}}
    <div style="margin-top: 120px"></div>
    <div style="font-size: 20px; margin:0px; padding:0px;">
        <center>
            <p>Delivery Challan</p>
        </center>
    </div>

    <div
        style="display: flex; flex-direction:row; justify-content:space-between; margin-left: 20px;margin-right: 20px;margin-top: 15px;">
        <p class="m-0 p-0">Delivery Challan No: {{ $leadInfo->delivery_challan }}</p>
        <p>Date : <?= date('jS F Y') ?></p>
    </div>

    <div class="leadInfo">
        <div style="margin-left: 20px">
            <p>Customer: {{ $leadInfo->clientInfo->customer_name }}
                <br>Delivery Address: {{ $leadInfo->delivery_address }}
            </p>
        </div>
    </div>

    <div class="leadInfo">
        <div style="display:flex; flex-direction:row; justify-content:space-evenly">
            <p>Client PO Ref. No: {{ $quotationInfo[0]->quotation_po }}
                <br>PO. Date: {{ date('d-M-Y', strtotime($quotationInfo[0]->quotation_po_date)) }}
            </p>
            <p>Contact Person: {{ $leadInfo->delivery_person }}
                <br>Contact No: {{ $leadInfo->delivery_mobile }}
            </p>
        </div>
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
                        <td class="p-1" style="font-size: 12px; line-height:initial">{!! $productDesc !!}</td>
                        <td class="p-1 text-center" style="align-content: space-evenly;text-align:center">
                            {{ $itemPump->qty }}</td>
                    </tr>
                    <?php $sl++; ?>
                @endforeach
                <tr>
                    <td class="p-1 fw-bold text-center" colspan="2"><b>Total Quantity</b></td>
                    <td class="p-1 text-end fw-bold" style="align-content: space-evenly;text-align:center"><b>
                            {{ $totalQuantity }}</b></td>
                </tr>
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
</div>
