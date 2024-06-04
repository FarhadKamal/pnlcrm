@include('layouts.navbar')
<br>
<button onclick="window.print()" class="btn btn-sm btn-darkblue printBtn float-end mt-2">Print Quotation</button>
<div class="quotDiv" id="section-to-print">
    <style>
        body {
            font-family: 'Helvetica';
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
        }

        .quotDiv .tableRow {
            line-height: 20px;
            height: 20px;

        }

        .quotDiv .footerContainer {
            /* font-family: Arial, sans-serif; */
            font-size: .7rem;
            margin-top: 15px;
            width: 100%;
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
                width: 100%;
            }

            .quotDiv .pagebreakAvoid {
                page-break-inside: avoid;
            }

            .footerContainer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }

            body * {
                visibility: hidden;
            }

            #section-to-print,
            #section-to-print * {
                visibility: visible;
            }

            #section-to-print .quotContainer {
                margin-top: -80px;
            }
        }
    </style>
    <div class="headerContainer">
        <img style="padding:0;margin:0;" src="{{ asset('images/system/logo.png') }}" alt="" height="50">
    </div>

    <table style="width: 100%">
        <thead>
            <tr>
                <td>
                    <div style="height: 80px"></div>
                </td>
            </tr>
        </thead>
        <tbody class="quotContainer">
            <tr>
                <td>
                    @if ($leadInfo->current_stage == 'QUOTATION' && $leadInfo->current_subStage == 'APPROVE')
                        <div class="container1">
                            <h2><b id="quotationRef"></b></h2>
                            <h2>Date: <?= date('jS F Y') ?></h2>
                        </div>
                    @endif

                    <div>
                        <p> To</p>
                        <p class="boldText">{{ $leadInfo['clientInfo']->customer_name }}</p>
                        <p>{{ $leadInfo['clientInfo']->address }}, {{ $leadInfo['clientInfo']->district }}</p>
                        <p class="boldText">Attention: <span>{{ $leadInfo['clientInfo']->contact_person }}</span></p>
                        <p><span>Phone: </span>{{ $leadInfo->lead_phone }}</p>
                        @if ($leadInfo->lead_email)
                            <p><span>Email: </span>{{ $leadInfo->lead_email }}</p>
                        @endif

                        <p>Subject :<span class="boldText"><u>Price Quotation for the supply of
                                    pump
                                    motor</u></span>
                        </p>

                        <p class="boldText" style="margin-top: 2%">Greetings,</p>
                        <p>Thank you for your enquiry and reference is made to your requirement of pump. We are pleased
                            to submit
                            our Quotation price for pump motor which details are as under:</p>
                    </div>

                    <div>
                        @foreach ($reqInfo as $itemReq)
                            <div class="pagebreakAvoid">
                                <p class="boldText" style="margin-top: 2%;">Your Requirement:</p>
                                <p>Type: {{ $itemReq->type_of_use }}</p>
                                <table cellspacing="0" style="margin-top: 2%;">
                                    <tr class="tableRow">
                                        <td class="table1RowCol1">
                                            <p class="colText boldText text-center">Brand</p>
                                        </td>
                                        <td class="table1RowCol1">
                                            <p class="colText boldText text-center">Model</p>
                                        </td>
                                        {{-- <td class="table1RowCol1">
                                    <p class="colText boldText text-center">Phase</p>
                                </td> --}}
                                        <td class="table1RowCol1">
                                            <p class="colText boldText text-center">Power</p>
                                        </td>
                                        <td class="table1RowCol1">
                                            <p class="colText boldText text-center">Head</p>
                                        </td>
                                        <td class="table1RowCol1">
                                            <p class="colText boldText text-center">Unit Price <br> (Taka) </p>
                                        </td>
                                        <td class="table1RowCol1">
                                            <p class="colText boldText text-center">Qty.</p>
                                        </td>
                                        <td class="table1RowCol1">
                                            <p class="colText boldText text-center">Less Discount <br> (Taka) </p>
                                        </td>
                                        <td class="table1RowCol1">
                                            <p class="colText boldText text-center">Total Price <br> (Taka) </p>
                                        </td>
                                    </tr>
                                    <tbody>
                                        @foreach ($pumpInfo as $itemPump)
                                            @if ($itemPump->req_id == $itemReq->id)
                                                <tr class="tableRow">
                                                    <td class="table1RowCol1">
                                                        <p class="colText">{{ $itemPump['productInfo']->brand_name }}
                                                        </p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="">{{ $itemPump['productInfo']->mat_name }}

                                                        </p>
                                                    </td>
                                                    {{-- <td class="table1RowCol1">
                                                <p class="colText text-center">{{ $itemPump['productInfo']->phase }}
                                                </p>
                                            </td> --}}
                                                    <td class="table1RowCol1">
                                                        <p class="text-center colText">HP:
                                                            {{ $itemPump['productInfo']->hp }}, KW:
                                                            {{ $itemPump['productInfo']->kw }}</p>
                                                    </td>
                                                    <td class="table1RowCol1">
                                                        <p class="text-center colText">
                                                            {{ $itemPump['productInfo']->head }}</p>
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
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>

                    <div class="pagebreakAvoid">
                        <p class="boldText" style="margin-top: 2%;">Terms & Condition:</p>
                        <p class=""><i class="fa-regular fa-circle-dot" style="font-size:8px"></i></i> Payment
                            shall be
                            made
                            through crossed cheque
                            within 30 days and Purchase Order in favor of PNL
                            Holdings Limited.</p>
                        <p class=""><i class="fa-regular fa-circle-dot" style="font-size:8px"></i> Provide your
                            concern
                            Factory address & BIN
                            number for issue the Vat Challan (Mushak-6.3).</p>
                        <p class=""><i class="fa-regular fa-circle-dot" style="font-size:8px"></i> Delivery after
                            5 days
                            from
                            the date of your
                            confirmed Purchase Order subject to available in our
                            stock.</p>
                        <p class=""><i class="fa-regular fa-circle-dot" style="font-size:8px"></i> Delivery from
                            Pedrollo
                            Plaza, 5, Jubilee road,
                            Chittagong.</p>

                        <p class=""><i class="fa-regular fa-circle-dot" style="font-size:8px"></i> 2 (Two) Years’
                            Service
                            Warranty for Pump-moto.
                        </p>

                        <p class=""><i class="fa-regular fa-circle-dot" style="font-size:8px"></i> Price offer
                            validity 7
                            days
                            from the date
                            hereof.</p>
                        <br>
                        <p class="colText boldText">Contact Person: {{ Auth()->user()->user_name }}</p>
                        <p class="colText boldText">{{ $desgName->desg_name }}, {{ $deptName->dept_name }}, Cell No:
                            {{ Auth()->user()->user_phone }}, E-mail: {{ Auth()->user()->user_email }}</p>
                    </div>
                    <div style="margin-top: 2%" class="pagebreakAvoid">
                        <p>Thanking You,</p>
                        <img src="{{ asset('images/system/quotationSign.png') }}" width="120" alt="">
                        <p>Md. Afzal Hamid</p>
                        <p>Chief Operating Officer</p>
                        <p>PNL Holdings Limited</p>
                        <p>E-mail: afzal@pnlholdings.com</p>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <div style="height: 100px"></div>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="footerContainer">
        <p class="text-primary fw-bold">PNL HOLDINGS LIMITED, Head Office: Pedrollo Plaza, 5 Jubilee Road,
            Chattogram-400. Phone: +88 031 621531-35</p>
        <p class="text-primary fw-bold">Dhaka Office 1: Pedrollo House, 12 Topkhana Road, Segunbagicha, Dhaka-1000.
            Phone: ++88 02 9571210</p>
        <p class="text-primary fw-bold" style="display:inline; font-size:11px">
            <i class="fas fa-envelope"></i>&nbsp;info@pnlholdings.com&nbsp;<i
                class="fas fa-globe"></i>&nbsp;www.pnlholdings.com&nbsp;<i
                class="fa-brands fa-facebook"></i>&nbsp;facebook.com/thinkPNL&nbsp;<i
                class="fa-brands fa-linkedin"></i>&nbsp;pnl-holdings-limited&nbsp;<i
                class="fa-solid fa-headset"></i>&nbsp;16308 (9:00 AM - 9:00 PM)
        </p>

        <div style="display: flex; justify-content:space-evenly; align-items: center; margin-top:1px; padding-top:0px;">
            <div class="col-md-2">
                <img width="100" src="{{ asset('images/system/pedrollo.png') }}" alt="">
            </div>
            <div class="col-md-2">
                <img width="100" src="{{ asset('images/system/BGFlow.jpg') }}" alt="">
            </div>
            <div class="col-md-2">
                <img width="120" src="{{ asset('images/system/panelli.jpg') }}" alt="">
            </div>
            <div class="col-md-2">
                <img width="60" src="{{ asset('images/system/hcp.png') }}" alt="">
            </div>
            <div class="col-md-2">
                <img width="60" src="{{ asset('images/system/maxwell.jpg') }}" alt="">
            </div>
            <div class="col-md-2">
                <img width="80" src="{{ asset('images/system/itap.png') }}" alt="">
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
    integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    setInterval(function() {
        $.ajax({
            type: "GET",
            url: "/quotationReferenceCheck",
            cache: false,
            success: function(data) {
                let refFullDate = new Date(data['currentDate']);
                let refYear = refFullDate.getFullYear();
                let refMonth = Number(refFullDate.getMonth() + 1).toString().padStart(2, '0');
                let refDate = Number(refFullDate.getDate()).toString().padStart(2, '0');

                let serialNo = Number(data['checkQuotationSerial'][0]['sl'] + 1).toString()
                    .padStart(2, '0');

                let refPreText = 'REF: REL/V/QUT/' + refYear + '/' + refMonth + refDate + serialNo;
                // console.log(refPreText);
                // console.log(data['checkQuotationSerial'][0]['sl']);
                document.getElementById('quotationRef').innerText = refPreText;
                $('#quotationRef').val(refPreText);
            }
        });

    }, 1000 * 60 * 0.01);
</script>
