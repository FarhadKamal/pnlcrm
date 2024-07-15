<div class="quotDiv" id="section-to-print">
    <style>
        /* body {
            font-family: 'Helvetica';
        } */

        #section-to-print {
            font-family: Arial, sans-serif;
            font-size: 12px;
            /* width: 100%; */
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0;
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
            font-size: 14px;
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
            line-height: 19px;
            word-spacing: 0px;
            font-size: 12px;
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
            margin-left: 10px;
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

        @page {
            size: auto;
            size: A4;
            margin: 5mm;
        }

        @media print {

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
            }

            .quotDiv .colText {
                width: max-content;
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
        }
    </style>
    <div class="headerContainer float-end">
        <img style="padding:0;margin:0;" src="{{ asset('images/system/logo.png') }}" alt="" height="50">
    </div>

    <table style="width: 100%" id="quotationLayoutTable">
        <thead>
            <tr>
                <td>
                    <div style="height: 10px"></div>
                </td>
            </tr>
        </thead>
        <tbody class="quotContainer">
            <tr>
                <td>
                    @if ($leadInfo->current_stage == 'QUOTATION' && $leadInfo->current_subStage == 'SUBMIT')
                        <div class="container1">
                            <h2><b id="quotationRef"></b></h2>
                            <h2>Date: <?= date('jS F Y') ?></h2>
                        </div>
                    @endif

                    <div>
                        <p> To</p>
                        <p class="boldText">{{ $leadInfo['clientInfo']->customer_name }}</p>
                        <p>{{ $leadInfo['clientInfo']->address }}, {{ $leadInfo['clientInfo']->district }}</p>
                        <p class="boldText">Attention</span></p>
                        <p><span>Name: </span>{{ $leadInfo->lead_person }}</p>
                        <p><span>Phone: </span>{{ $leadInfo->lead_phone }}</p>
                        @if ($leadInfo->lead_email)
                            <p><span>Email: </span>{{ $leadInfo->lead_email }}</p>
                        @endif

                        <p>Subject :<span class="boldText"><u>Price Quotation for the supply of electric water
                                    pump.</u></span>
                        </p>

                        <p class="boldText" style="margin-top: 2%">Greetings,</p>
                        <p>Thank you for your enquiry and interest to purchase product from us. We are pleased to submit
                            our price offer below along with other required details:</p>
                    </div>
                    <div>
                        <p>Please send the purchase order to: <b>sales@pnlholdings.com</b></p>
                    </div>

                    <div>
                        <?php
                        $allSurfaceTermFlag = 0;
                        $allSubmersibleTermFlag = 0;
                        $pedDrainageTermFlag = 0;
                        ?>
                        @foreach ($reqInfo as $itemReq)
                            <div class="pagebreakAvoid">
                                <table cellspacing="0" style="margin-top: 2%;">
                                    <thead>
                                        <tr class="tableRow">
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Sl
                                                    No</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Product Description</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Unit
                                                    Price (Taka)</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Qty.
                                                </p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Less
                                                    Discount (Taka)</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-center">Net
                                                    Payable (Taka)</p>
                                            </td>
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
                                                if ($itemPump->spare_parts == 0 && $itemPump->productInfo->pump_type == 'Drainage' && $itemPump->brand_name == 'Pedrollo') {
                                                    $pedDrainageTermFlag = 1;
                                                }
                                                if ($itemPump->spare_parts == 0) {
                                                    if ($itemPump->productInfo->pump_type != 'ITAP' && $itemPump->productInfo->pump_type != 'MAXWELL') {
                                                        $productDesc = '<b>' . $itemPump->productInfo->brand_name . ' ' . $itemPump->productInfo->pump_type . ' pump.</b> <b>Model:</b> ' . $itemPump->productInfo->mat_name . '(' . $itemPump->productInfo->phase . ').  <b>Power:</b> ' . $itemPump->productInfo->kw . 'KW/' . $itemPump->productInfo->hp . 'HP. <b>Head Range:</b> ' . $itemPump->productInfo->max_head . '-' . $itemPump->productInfo->min_head;
                                                    } else {
                                                        $productDesc = '<b>' . $itemPump->productInfo->brand_name . ' </b>' . $itemPump->productInfo->mat_name;
                                                    }
                                                } else {
                                                    $productDesc = '<b>' . $itemPump->spareInfo->brand_name . ' </b>' . $itemPump->spareInfo->mat_name;
                                                }
                                                
                                                $totalNetPay = $totalNetPay + $itemPump->net_price;
                                                ?>
                                                <tr class="tableRow">
                                                    <td class="table1RowCol1">
                                                        <p class="colText text-center">{{ $sl }}
                                                        </p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="colText" style="width: max-content;">
                                                            {!! $productDesc !!}
                                                        </p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="text-end colText">
                                                            {{ number_format((float) $itemPump->unit_price, 2, '.', ',') }}
                                                        </p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="colText text-center">{{ $itemPump->qty }}</p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="colText text-end">
                                                            {{ number_format((float) $itemPump->discount_price, 2, '.', ',') }}
                                                        </p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="colText text-end">
                                                            {{ number_format((float) $itemPump->net_price, 2, '.', ',') }}
                                                        </p>
                                                    </td>
                                                </tr>
                                                <?php $sl++; ?>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="tableRow">
                                            <td class="table1RowCol1" colspan="5">
                                                <p class="colText boldText text-center">Total Payable</p>
                                            </td>
                                            <td class="table1RowCol1">
                                                <p class="colText boldText text-end">
                                                    {{ number_format((float) $totalNetPay, 2, '.', ',') }}
                                                </p>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endforeach
                    </div>

                    <div class="pagebreakAvoid">
                        <p class="boldText" style="margin-top: 2%;">Terms & Condition:</p>
                        @if ($leadInfo->payment_type == 'Cash')
                            <p class="">1. Payment
                                shall be
                                made
                                <b>Cash Advance/Pay Order</b> and Purchase Order in favor of <b>PNL Holdings
                                    Limited</b>.
                            </p>
                        @else
                            <p class="">1. Payment
                                shall be
                                made
                                through crossed cheque
                                within <b>30 days</b> and Purchase Order in favor of <b>PNL
                                    Holdings Limited</b>.</p>
                        @endif

                        <p class="">2. VAT/TAX as per government rules and BIN number should be provided.
                        </p>
                        <p class="">3. Delivery
                            after
                            5 days
                            from
                            the date of your
                            confirmed Purchase Order subject to available in our
                            stock.</p>
                        <p class="">4. Delivery
                            from
                            Pedrollo
                            Plaza, 5, Jubilee road,
                            Chittagong.</p>
                        <p class="">5. Price offer
                            validity 7
                            days
                            from the date
                            hereof.</p>

                        @if ($allSurfaceTermFlag == 1)
                            <p class="">6. 3
                                (Three)
                                Years’
                                Service
                                Warranty as per our company policy.
                            </p>
                        @endif
                        @if ($allSubmersibleTermFlag == 1 || $pedDrainageTermFlag == 1)
                            <?php if ($allSurfaceTermFlag == 1) {
                                $ts = 7;
                            } else {
                                $ts = 6;
                            } ?>
                            <p class="">{{ $ts }}. 2 (Two)
                                Years’
                                Service
                                Warranty as per our company policy.
                            </p>
                        @endif
                        {{-- @if ($pedDrainageTermFlag == 1)
                            <p class=""> 2 (Two)
                                Years’
                                Service
                                Warranty as per our company policy.
                            </p>
                        @endif --}}


                    </div>
                    @if ($leadInfo->current_stage == 'QUOTATION' && $leadInfo->current_subStage == 'SUBMIT')
                        <div style="margin-top: 2%" class="pagebreakAvoid">
                            <p class="colText boldText">Contact Person: {{ Auth()->user()->user_name }}</p>
                            <p class="colText boldText">{{ $desgName->desg_name }}, {{ $deptName->dept_name }}, Cell
                                No:
                                {{ Auth()->user()->user_phone }}, E-mail: {{ Auth()->user()->user_email }}</p>
                            <br>
                            <p>Thanking You,</p>
                            <img src="{{ asset('images/system/quotationSign.png') }}" width="120" alt="">
                            <p>Md. Afzal Hamid</p>
                            <p>Chief Operating Officer</p>
                            <p>PNL Holdings Limited</p>
                            <p>E-mail: afzal@pnlholdings.com</p>
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
    <div class="footerContainer">
        <p class="text-primary fw-bold" style="font-size:11px">PNL HOLDINGS LIMITED, Head Office: Pedrollo Plaza, 5 Jubilee Road,
            Chattogram-400. Phone: +88 031 621531-35</p>
        <p class="text-primary fw-bold" style="font-size:11px">Dhaka Office: Pedrollo House, 12 Topkhana Road, Segunbagicha, Dhaka-1000.
            Phone: ++88 02 9571210</p>
        <p class="text-primary fw-bold" style="display:inline; font-size:11px">
            <img src="{{asset('images/system/email.png')}}" alt="" height="10">&nbsp;sales@pnlholdings.com&nbsp;<img src="{{asset('images/system/web.png')}}" alt="" height="12">&nbsp;www.pnlholdings.com&nbsp;<img src="{{asset('images/system/facebook.png')}}" alt="" height="12">&nbsp;facebook.com/thinkPNL&nbsp;<img src="{{asset('images/system/linkedin.png')}}" alt="" height="12">&nbsp;pnl-holdings-limited&nbsp;<img src="{{asset('images/system/call.png')}}" alt="" height="12">&nbsp;16308 (9:00 AM - 9:00 PM)
        </p>

        <div style="display: flex; justify-content:space-evenly; align-items: center; margin-top:1px; padding-top:0px;">
            <div class="col-md-1">
                <img src="{{ asset('images/system/pedrollo.svg') }}" alt="" width="100">
            </div>
            <div class="col-md-1">
                <img src="{{ asset('images/system/BGFlow.svg') }}" alt="" width="100">
            </div>
            <div class="col-md-1">
                <img src="{{ asset('images/system/panelli.svg') }}" alt=""width="120">
            </div>
            <div class="col-md-1">
                <img src="{{ asset('images/system/hcp.svg') }}" alt=""width="60">
            </div>
            <div class="col-md-1">
                <img src="{{ asset('images/system/maxwell.svg') }}" alt="" width="60">
            </div>
            <div class="col-md-1">
                <img src="{{ asset('images/system/itap.svg') }}" alt="" width="80">
            </div>
            <div class="col-md-1">
                <img src="{{ asset('images/system/firenza.svg') }}" alt="" width="80">
            </div>
        </div>
    </div>
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
