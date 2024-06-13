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
                font-size: 18px;
                margin-left: 20px;
            }
        }
    </style>
    <div>
        <img style="padding:0;margin:10px" src="{{ asset('images/system/logo.png') }}" alt="" height="50">
    </div>
    <div
        style="display: flex; flex-direction:row; justify-content:space-between; margin-left: 20px;margin-right: 20px;margin-top: 15px;">
        <p class="m-0 p-0">Delivery Challan No: {{ $quotationInfo[0]->quotation_ref }}<br>Invoice No:
            {{ $leadInfo->sap_invoice }}</p>
        <p>Date : <?= date('jS F Y') ?></p>
    </div>

    <div style="font-size: 30px; margin:0px; padding:0px;">
        <center>
            <h6>Delivery Challan</h6>
        </center>
    </div>

    <div class="leadInfo">
        <div style="display:flex; flex-direction:row; justify-content:space-evenly">
            <p>Customer: {{ $leadInfo->clientInfo->customer_name }}
                <br>Delivery Address: {{ $leadInfo->clientInfo->address }},
                {{ $leadInfo->clientInfo->district }}
            </p>
            <p>Contact Person: {{ $leadInfo->lead_person }}
                <br>Phone: {{ $leadInfo->lead_phone }}
                <br>Email: {{ $leadInfo->lead_email }}
            </p>
        </div>
    </div>

    <div>
        <table class="table table-bordered">
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
                @foreach ($pumpInfo as $item)
                    <?php
                    $totalQuantity = $totalQuantity + $item->qty;
                    ?>
                    <tr class="fs-07rem">
                        <td class="p-1 text-center" style="align-content: space-evenly; text-align:center">
                            {{ $sl }}</td>
                        <td class="p-1"><b>Brand:</b> {{ $item->productInfo->brand_name }} <br> <b>Type:</b>
                            {{ $item->productInfo->itm_group }} <br> <b>Model:</b> {{ $item->productInfo->mat_name }}
                            <br> <b>Specification:</b> HP: {{ $item->productInfo->hp }}, KW:
                            {{ $item->productInfo->kw }}
                        </td>
                        <td class="p-1 text-center" style="align-content: space-evenly;text-align:center">
                            {{ $item->qty }}</td>
                    </tr>
                    <?php $sl++; ?>
                @endforeach
                <tr>
                    <td class="p-1 fw-bold text-center" colspan="2">Total Quantity</td>
                    <td class="p-1 text-end fw-bold" style="align-content: space-evenly;text-align:center">
                        {{ $totalQuantity }}</td>
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
