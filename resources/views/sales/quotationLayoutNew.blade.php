<div class="quotDiv" id="section-to-print">
    <style>
        /* body {
            font-family: 'Helvetica';
        } */

        #section-to-print {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 794px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .quotDiv {
            margin: 0 20px;
            padding: 0;
            text-indent: 0;
        }

        .quotDiv h2 {
            color: black;
            font-weight: bold;
            text-decoration: none;
            font-size: 10pt;
        }

        .quotDiv h1 {
            color: black;
            font-weight: bold;
            text-decoration: none;
            font-size: 11pt;
        }

        .quotDiv a {
            color: #1154cc;
            font-weight: bold;
            text-decoration: underline;
            font-size: 10pt;
        }

        .quotDiv p {
            color: black;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
            margin: 0pt;
        }

        .quotDiv li {
            display: block;
        }

        .quotDiv #l1 {
            padding-left: 0pt;
        }

        .quotDiv #l1>li>*:first-child:before {
            content: "▪ ";
            color: black;
            /* font-family: Verdana, sans-serif; */
            /* font-style: normal; */
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }

        .quotDiv .quotContainer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* margin: 0 ; */
            /* padding: 0; */
            margin-bottom: 10px;
        }

        .quotDiv .navItemsContainer {
            display: flex;
            flex-direction: column;
            border-left: 2px solid rgb(49, 81, 161);
        }

        .quotDiv .container1 {
            /* display: flex; */
            /* justify-content: space-between; */
            align-items: center;
            margin: 0;
            margin-top: -5.5vh;
            padding: 0;
            /* border: 1px solid; */
        }

        .quotDiv .container2 {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            margin-top: 5%;
        }

        .quotDiv .container3 {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
        }

        .quotDiv .startText {
            display: inline-block;
            width: 40px;
        }

        .quotDiv .boldText {
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        .quotDiv .verticalGap {
            margin-top: 5%;
            margin-bottom: 2%;
        }

        .quotDiv .table-row {
            border: 1px solid black;
        }

        .quotDiv .border-cell {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        .quotDiv .table1RowCol1 {
            width: 300px;
            border-top-style: solid;
            border-top-width: 1pt;
            border-left-style: solid;
            border-left-width: 1pt;
            border-bottom-style: solid;
            border-bottom-width: 1pt;
            border-right-style: solid;
            border-right-width: 1pt;
            line-height: 16px;
            word-spacing: 0px;
        }

        .quotDiv .table1RowCol2 {
            width: 600px;
            border-top-style: solid;
            border-top-width: 1.4pt;
            border-left-style: solid;
            border-left-width: 1.4pt;
            border-bottom-style: solid;
            border-bottom-width: 1.4pt;
            border-right-style: solid;
            border-right-width: 1.4pt;
            line-height: 19px;
            word-spacing: 0px;
            font-size: 16px;
        }

        .quotDiv .colText {
            /* margin-left: 10px; */
            font-size: 12px;
        }

        .quotDiv .tableRow {
            line-height: 20px;
            height: 20px;

        }

        .quotDiv .footerContainer {
            /* font-family: Arial, sans-serif; */
            font-size: .7rem;
            /* margin-top: 15px; */
            /* width: 100%; */
        }

        .headerContainer {
            /* width: 100%; */
            text-align: right;
            margin-top: 2vh;
        }

        @page {
            /* size: auto; */
            size: A4;
            margin-left: 15mm;
            margin-right: 10mm;
            margin-bottom: 10mm;
            margin-top: 10mm;
        }

        @media print {

            .quotDiv {
                /* margin-left: 4rem; */
            }

            .pagebreak {
                /* page-break-before: always; */
                /* clear: both; */
            }

            .headerContainer {
                position: fixed;
                top: 0;
                right: 0;
                /* width: 100%; */
                text-align: right;
                margin-top: 0;
            }

            .quotDiv .container1 {
                margin-top: -7vh;
            }

            .quotDiv .colText {
                /* width: max-content; */
                width: fit-content;
                font-size: 13px;
                margin-left: 5px;
            }

            .quotDiv .pagebreakAvoid {
                page-break-inside: avoid;
            }

            .footerContainer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }

            /* body * {
                visibility: hidden;
            } */

            #section-to-print,
            #section-to-print * {
                /* visibility: visible; */
            }

            /* #quotationLayoutTable * {
                visibility: visible;
            } */

            #section-to-print .quotContainer {
                margin-top: -90px;
            }

            .text-end {
                float: right;
            }
        }
    </style>

    <div class="headerContainer">
        <img style="padding:0;margin:0;" src="{{ asset('images/system/logo3.png') }}" alt="" height="85">
        {{-- <h6 style="font-weight: 600; margin-top:1%">PNL HOLDINGS LIMITED</h6> --}}
    </div>

    <table style="width: 100%;" id="quotationLayoutTable">
        <thead>
            <tr>
                <td>
                    {{-- <div style="height: 10px"></div> --}}
                </td>
            </tr>
        </thead>
        <tbody class="quotContainer" style="margin-top: -4.5vh">
            <tr>
                <td>
                    @if ($leadInfo->current_stage == 'QUOTATION' && $leadInfo->current_subStage == 'SUBMIT')
                        <div class="container1">
                            <p class="colText"><span id="quotationRef"></span></p>
                            <p class="colText">Date: <?= date('jS F Y') ?></p>
                        </div>
                    @endif
                    <br>
                    <div>
                        {{-- <p class="colText"> To</p> --}}
                        <p class=" colText">{{ $leadInfo['clientInfo']->customer_name }}</p>
                        <p class="colText">{{ $leadInfo['clientInfo']->address }},
                            {{ $leadInfo['clientInfo']->district }}</p>
                        <p class=" colText">Attention</span></p>
                        <p class="colText"><span>Name: </span>{{ $leadInfo->lead_person }}</p>
                        <p class="colText"><span>Phone: </span>{{ $leadInfo->lead_phone }}</p>
                        @if ($leadInfo->lead_email)
                            <p class="colText"><span>Email: </span>{{ $leadInfo->lead_email }}</p>
                        @endif
                        <br>
                        {{-- <p class="colText" style="font-size:13px">Subject: <span
                                class="boldText">{{ $subjectText }}.</span>
                        </p> --}}
                        <p class="colText">Subject: <span class="">Price Quotation</span>
                        </p>

                        {{-- <p class="boldText colText" style="margin-top: 2%;font-size:12px">Greetings,</p> --}}
                        <p class=" colText" style="margin-top: 2%;">Dear Concern,</p>
                        <p class="colText">Thank you for your inquiry and interest to purchase product from our company.
                            We are pleased
                            to submit
                            our price quotation below along with other required details:</p>
                    </div>

                    <div>
                        <?php
                        $allSurfaceTermFlag = 0;
                        $allSubmersibleTermFlag = 0;
                        $pedHCPDrainageTermFlag = 0;
                        $bgFlowDrainageTermFlag = 0;
                        ?>
                        @foreach ($reqInfo as $itemReq)
                            <div class="pagebreakAvoid colText">
                                <table cellspacing="0" style="margin-top: 2%;">
                                    <thead>
                                        <tr class="tableRow">
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Sl.</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class=" boldText text-center">Product Description</p>
                                            </td>
                                            {{-- <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Unit
                                                    Price (BDT)</p>
                                            </td> --}}
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">MRP (Unit)</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Offer Price (Unit)</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Qty.</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Total Price</p>
                                            </td>
                                            {{-- <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Qty.
                                                </p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Less
                                                    Discount (BDT)</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Net
                                                    Payable (BDT)</p>
                                            </td> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sl = 1;
                                        $totalNetPay = 0; ?>
                                        @foreach ($pumpInfo as $itemPump)
                                            @if ($itemPump->req_id == $itemReq->id)
                                                <?php
                                                if ($itemPump->spare_parts == 0 && $itemPump->productInfo->pump_type == 'Surface') {
                                                    $allSurfaceTermFlag = 1;
                                                }
                                                if ($itemPump->spare_parts == 0 && $itemPump->productInfo->pump_type == 'Submersible') {
                                                    $allSubmersibleTermFlag = 1;
                                                }
                                                if ($itemPump->spare_parts == 0 && $itemPump->productInfo->pump_type == 'Drainage' && ($itemPump->productInfo->brand_name == 'PEDROLLO' || $itemPump->productInfo->brand_name == 'HCP')) {
                                                    $pedHCPDrainageTermFlag = 1;
                                                }
                                                if ($itemPump->spare_parts == 0 && $itemPump->productInfo->pump_type == 'Drainage' && $itemPump->productInfo->brand_name == 'BGFlow') {
                                                    $bgFlowDrainageTermFlag = 1;
                                                }
                                                if ($itemPump->productInfo->brand_name == 'PEDROLLO') {
                                                    $itemPump->productInfo->brand_name = 'Pedrollo';
                                                }
                                                if ($itemPump->productInfo->brand_name == 'MAXWELL') {
                                                    $itemPump->productInfo->brand_name = 'Maxwell';
                                                }
                                                if ($itemPump->spare_parts == 0) {
                                                    if ($itemPump->productInfo->pump_type != 'ITAP' && $itemPump->productInfo->pump_type != 'MAXWELL') {
                                                        $country = $itemPump->productInfo->country_name;
                                                        $productDesc = '<b>' . $itemPump->productInfo->brand_name . ' ' . $itemPump->productInfo->pump_type . ' Pump</b> (' . $country . '). <b>Model:</b> ' . $itemPump->productInfo->mat_name . '(' . $itemPump->productInfo->phase . ').  <br><b>Power:</b> ' . $itemPump->productInfo->kw . 'KW/' . $itemPump->productInfo->hp . 'HP. <b>Head(M):</b> ' . $itemPump->productInfo->max_head . '-' . $itemPump->productInfo->min_head . '. <b>Suction Dia:</b> ' . $itemPump->productInfo->suction_dia . ' Inch. ' . '<b>Delivery Dia:</b> ' . $itemPump->productInfo->delivery_dia . ' Inch.';
                                                    } else {
                                                        $country = $itemPump->productInfo->country_name;
                                                        $productDesc = '<b>' . $itemPump->productInfo->brand_name . ' </b> (' . $country . ') ' . $itemPump->productInfo->mat_name;
                                                    }
                                                } else {
                                                    $country = $itemPump->spareInfo->country_name;
                                                    $productDesc = '<b>' . $itemPump->spareInfo->brand_name . ' </b> ' . $itemPump->spareInfo->mat_name;
                                                }
                                                
                                                $totalNetPay = $totalNetPay + $itemPump->net_price;
                                                ?>
                                                <tr class="tableRow">
                                                    <td class="table1RowCol1">
                                                        <p class="colText text-center">{{ $sl }}
                                                        </p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="colText"
                                                            style="width: max-content; letter-spacing:0.7px;">
                                                            {!! $productDesc !!}
                                                        </p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="text-end colText me-2" style="text-align: right;">
                                                            {{ number_format((float) $itemPump->unit_price, 2, '.', ',') }}
                                                        </p>
                                                    </td>
                                                    <?php
                                                    $offerSinglePrice = $itemPump->unit_price - $itemPump->unit_price * ($itemPump->discount_percentage / 100);
                                                    ?>
                                                    <td class="table1RowCol1">
                                                        <p class="text-end colText me-2" style="text-align: right;">
                                                            {{ number_format((float) $offerSinglePrice, 2, '.', ',') }}
                                                        </p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <center>
                                                            <p class="colText">{{ $itemPump->qty }}</p>
                                                        </center>
                                                    </td>
                                                    <?php
                                                    $totalSinglePrice = $offerSinglePrice * $itemPump->qty;
                                                    ?>
                                                    <td class="table1RowCol1">
                                                        <p class="text-end colText me-2" style="text-align: right;">
                                                            {{ number_format((float) $totalSinglePrice, 2, '.', ',') }}
                                                        </p>
                                                    </td>
                                                    {{-- <td class="table1RowCol1">
                                                        <center>{{ $itemPump->qty }}</center>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="colText text-end me-2" style="text-align: right;">
                                                            {{ number_format((float) $itemPump->discount_price, 2, '.', ',') }}
                                                        </p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="colText text-end me-2" style="text-align: right;">
                                                            {{ number_format((float) $itemPump->net_price, 2, '.', ',') }}
                                                        </p>
                                                    </td> --}}
                                                </tr>
                                                <?php $sl++; ?>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        {{-- <tr class="tableRow">
                                            <td class="table1RowCol1" colspan="5">
                                                <p class="colText boldText">Total Payable</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-end me-2">
                                                    {{ number_format((float) $totalNetPay, 2, '.', ',') }}
                                                </p>
                                            </td>
                                        </tr> --}}
                                        <?php
                                        function numberToWordsBD($number)
                                        {
                                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                            $units = [
                                                1 => 'Thousand',
                                                10 => 'Lakh',
                                                100 => 'Crore',
                                            ];
                                        
                                            $words = '';
                                            $unit = 1;
                                        
                                            if ($number < 1000) {
                                                $words = $number . ' ';
                                            } else {
                                                // For crore
                                                if ($number >= 10000000) {
                                                    $crore = floor($number / 10000000);
                                                    $words .= $crore . ' Crore ';
                                                    $words .= ucwords($f->format($crore)) . ' Crore ';
                                                    $number %= 10000000;
                                                }
                                                // For lakh
                                                if ($number >= 100000) {
                                                    $lakh = floor($number / 100000);
                                                    // $words .= $lakh . ' Lakh ';
                                                    $words .= ucwords($f->format($lakh)) . ' Lakh ';
                                                    $number %= 100000;
                                                }
                                                // For thousand
                                                if ($number >= 1000) {
                                                    $thousand = floor($number / 1000);
                                                    // $words .= $thousand . ' Thousand ';
                                                    $words .= ucwords($f->format($thousand)) . ' Thousand ';
                                                    $number %= 1000;
                                                }
                                                // For the remaining number (less than 1000)
                                                if ($number > 0) {
                                                    // $words .= $number . ' ';
                                                    $words .= ucwords($f->format($number)) . ' ';
                                                }
                                            }
                                        
                                            return trim($words);
                                        }
                                        ?>
                                        <tr class="tableRow">
                                            <td class="table1RowCol1" colspan="5">
                                                {{-- <p class="colText boldText" style="letter-spacing: 1px;">In Words:
                                                    {{ numberToWordsBD($totalNetPay) }} Taka Only</p> --}}
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText text-end me-2">
                                                    {{ number_format((float) $totalNetPay, 2, '.', ',') }}
                                                </p>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <p class="colText" style="letter-spacing: 1px;">In Words:
                                    {{ numberToWordsBD($totalNetPay) }} Taka Only</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="pagebreakAvoid">
                        {{-- <div class="colText" style="margin-top: 1%;font-size:12px">
                            <p>Please send the purchase order to: <b>sales@pnlholdings.com</b></p>
                        </div> --}}
                        {{-- <div class="colText" style="margin-top: 1%;">
                            <p>Please send the purchase order to: <b>sales@pnlholdings.com</b></p>
                        </div> --}}
                        {{-- <p class="boldText colText" style="margin-top: 1%;font-size:12px">Terms & Conditions:</p> --}}
                        <p style="margin-top: 1%;"></p>
                        <p class="colText"><b>Purchase Order</b> <br>&nbsp;&nbsp;
                            <img src="{{ asset('images/system/square.png') }}" alt="" style="width: 8px">
                            Purchase Order to be issued in
                            favor of <b>PNL Holdings Limited</b> and sent to sales@pnlholdings.com.
                        </p>
                        <p class="colText"><b>Delivery</b>
                            <br>
                            &nbsp;&nbsp;<img src="{{ asset('images/system/square.png') }}" alt=""
                                style="width: 8px"> Delivery
                            after 5 days from the date of your confirmed
                            Purchase Order / Payment subject to stock availability.
                            <br>
                            &nbsp;&nbsp;<img src="{{ asset('images/system/square.png') }}" alt=""
                                style="width: 8px"> Delivery
                            from {{ $leadInfo->delivery_from }}.
                        </p>
                        <p class="colText"><b>Payment Mode</b> <br>&nbsp;&nbsp;<img
                                src="{{ asset('images/system/square.png') }}" alt="" style="width: 8px"> A/C
                            Payee
                            Cheque / Pay Order in favor of <b>PNL Holdings Limited</b>.</p>
                        <p class="colText"><b>VAT & TAX</b> <br>&nbsp;&nbsp;<img
                                src="{{ asset('images/system/square.png') }}" alt="" style="width: 8px">
                            Included in our offer
                            price.</p>
                        <p class="colText"><b>Warranty</b>
                            @if ($allSurfaceTermFlag == 1)
                                <br>&nbsp;&nbsp;<img src="{{ asset('images/system/square.png') }}" alt=""
                                    style="width: 8px">
                                03 (Three) Years’ Surface Pump Service Warranty as
                                per our company policy.
                            @endif
                            @if ($allSubmersibleTermFlag == 1)
                                <br>&nbsp;&nbsp;<img src="{{ asset('images/system/square.png') }}" alt=""
                                    style="width: 8px">
                                02 (Two) Years’ Submersible Pump Service Warranty
                                as per our company policy.
                            @endif
                            @if ($pedHCPDrainageTermFlag == 1)
                                <br>&nbsp;&nbsp;<img src="{{ asset('images/system/square.png') }}" alt=""
                                    style="width: 8px"> 2
                                (Two) Years’ Drainage Pump (Pedrollo/HCP)
                                Service Warranty as per our company policy.
                            @endif
                            @if ($bgFlowDrainageTermFlag == 1)
                                <br>&nbsp;&nbsp;<img src="{{ asset('images/system/square.png') }}" alt=""
                                    style="width: 8px"> 1
                                (One) Years’ Drainage Pump (BGFlow) Service
                                Warranty as per our company policy.
                            @endif
                        </p>
                        <p class="colText"><b>Price Validity</b> <br>&nbsp;&nbsp;<img
                                src="{{ asset('images/system/square.png') }}" alt="" style="width: 8px">
                            Price offer
                            validity 7 days from the date hereof.</p>
                        <br>
                        <p class="colText"><b>Note:</b> All electrical works will be done by purchaser. <b>PNL Holdings
                                Limited</b> will not be liable for any electrical works or power line.</p>
                        {{-- @if ($leadInfo->payment_type == 'Cash')
                            <p class="colText">1. Payment
                                shall be
                                made through
                                <b>Cash Advance/Pay Order</b> and Purchase Order in favor of <b>PNL Holdings
                                    Limited</b>.
                            </p>
                        @else
                            <p class="colText">1. Payment
                                shall be
                                made
                                through crossed cheque and Purchase Order in favor of <b>PNL
                                    Holdings Limited</b>.</p>
                        @endif

                        <p class="colText">2. VAT/TAX as per government rules and BIN number should be provided.
                        </p>
                        <p class="colText">3. Delivery
                            after
                            5 days
                            from
                            the date of your
                            confirmed Purchase Order subject to available in our
                            stock.</p>
                        <p class="colText">4. Delivery
                            from
                            {{ $leadInfo->delivery_from }}.</p>
                        <p class="colText">5. Price offer
                            validity 7
                            days
                            from the date
                            hereof.</p>

                        @if ($allSurfaceTermFlag == 1)
                            <p class="colText">6. 03
                                (Three)
                                Years’ Surface Pump
                                Service
                                Warranty as per our company policy.
                            </p>
                        @endif
                        @if ($allSubmersibleTermFlag == 1)
                           // <?php
                           // if ($allSurfaceTermFlag == 1) {
                           //   $ts = 7;
                           //} else {
                           //  $ts = 6;
                           // }
                           ?>
                            <p class="colText">{{ $ts }}. 02 (Two)
                                Years’ Submersible Pump
                                Service
                                Warranty as per our company policy.
                            </p>
                        @endif
                        @php
                            $ts = isset($ts) ? $ts++ : 6;
                        @endphp
                        @if ($pedHCPDrainageTermFlag == 1)
                            <p class="colText">{{ $ts }}. 2 (Two)
                                Years’ Drainage Pump (Pedrollo/HCP)
                                Service
                                Warranty as per our company policy.
                            </p>
                            @php
                                $ts++;
                            @endphp
                        @endif
                        @if ($bgFlowDrainageTermFlag == 1)
                            <p class="">{{ $ts }}. 1 (One)
                                Years’ Drainage Pump (BGFlow)
                                Service
                                Warranty as per our company policy.
                            </p>
                        @endif --}}


                    </div>
                    @if ($leadInfo->current_stage == 'QUOTATION' && $leadInfo->current_subStage == 'SUBMIT')
                        <div style="margin-top: 1%" class="pagebreakAvoid colText">
                            <p style="font-size: 11px"><span class="boldText">Contact Person:</span>
                                {{ Auth()->user()->user_name }},
                                {{ $desgName->desg_name }}</p>

                            <p style="font-size:11px">Thanking You,</p>
                            <img src="{{ asset('images/system/quotationSign.png') }}" width="120" alt="">
                            <p class="">Md. Afzal Hamid</p>
                            <p style="font-size:11px">Chief Operating Officer</p>
                            <p style="font-size:11px">PNL Holdings Limited</p>
                            <p style="font-size:11px">E-mail: afzal@pnlholdings.com</p>
                        </div>
                    @endif
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <div style="height: 10px"></div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="footerContainer" style="font-family: Arial, sans-serif;width: 794px;text-indent:0;margin: 0 20px;">
    <p class="text-primary fw-bold" style="font-size:11px">Head Office: Pedrollo Plaza, 05
        Jubilee Road,
        Chattogram-4000. Dhaka Office: Pedrollo House, 12 Topkhana Road,
        Segunbagicha, Dhaka-1000.</p>
    <table style="width: 100%;margin-top:-2%">
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

    <div style="display: flex; flex-direction:row; justify-content: space-between;width: 100%;margin-top:2%;align-items: center;">
        <img src="{{ asset('images/system/com/pedrollo.png') }}" alt="" style="width: auto; height:15px">
        <img src="{{ asset('images/system/com/BGFlow.jpg') }}" alt="" style="width: auto; height:15px">
        <img src="{{ asset('images/system/com/panelli.jpg') }}" alt="" style="width: auto; height:15px">
        <img src="{{ asset('images/system/com/hcp.png') }}" alt="" style="width: auto; height:25px">
        <img src="{{ asset('images/system/com/maxwell.jpg') }}" alt="" style="width: auto; height:40px">
        <img src="{{ asset('images/system/com/itap.png') }}" alt="" style="width: auto; height:25px">
        <img src="{{ asset('images/system/com/firenza.png') }}" alt="" style="width: auto; height:25px">
    </div>

    <table style="width: 100%;margin-top:1%;display:none">
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


<script>
    function printWithLogo() {
        showHeaderFooter();
        document.querySelector('#quotationPageTop').style.visibility = "hidden";
        document.querySelector('#quotationPageBottom').style.visibility = "hidden";
        document.querySelector('#navbarButtonsSidebar').style.visibility = "hidden";
        document.querySelector('#mainNavbar').style.visibility = "hidden";
        window.print();
    }

    function printWithoutLogo() {
        hideHeaderFooter();
        document.querySelector('#quotationPageTop').style.visibility = "hidden";
        document.querySelector('#quotationPageBottom').style.visibility = "hidden";
        document.querySelector('#navbarButtonsSidebar').style.visibility = "hidden";
        document.querySelector('#mainNavbar').style.visibility = "hidden";
        window.print();
    }

    function showHeaderFooter() {
        let header = document.querySelector('.headerContainer');
        let footer = document.querySelector('.footerContainer');
        header.style.visibility = "visible";
        footer.style.visibility = "visible";
    }

    function hideHeaderFooter() {
        let header = document.querySelector('.headerContainer');
        let footer = document.querySelector('.footerContainer');
        header.style.visibility = "hidden";
        footer.style.visibility = "hidden";
    }

    // Ensure header and footer are always visible after print dialog
    window.onafterprint = function() {
        showHeaderFooter();
        document.querySelector('#quotationPageTop').style.visibility = "visible";
        document.querySelector('#quotationPageBottom').style.visibility = "visible";
        document.querySelector('#navbarButtonsSidebar').style.visibility = "visible";
        document.querySelector('#mainNavbar').style.visibility = "visible";
    };
</script>
