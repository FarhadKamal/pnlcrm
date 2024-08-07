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

            .termDiv {
                margin: 1rem;
            }

            .termDiv ul {
                padding-top: 0px !important;
                padding-bottom: 0px !important;
                margin-top: 0px !important;
                margin-bottom: 0px !important;
            }

            .termDiv p {
                font-size: 0.6rem;
                padding: 0px !important;
                margin: 0px !important;
            }

            small {
                font-size: 0.6rem;
                padding: 0;
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
    <div style="margin-top: 50px"></div>
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
                        <td class="p-1 text-center" style="align-content: space-evenly;text-align:center">
                            {{ $unitName }}</td>
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

    <div style="display:flex; flex-direction:row; justify-content: space-evenly; margin-top:3rem">
        <p style="border-top: 1px solid #111; width:20%; text-align: -webkit-center;">Received By</p>
        <p style="border-top: 1px solid #111; width:30%; text-align: -webkit-center;">For PNL Holdings Limited</p>
    </div>
    <div class="termDiv">
        <p>পাম্প নষ্ট হওয়ার প্রধান কারণসমূহ</p>
        <ul>
            <li><small>সঠিক মানের ভোল্টেজ না থাকলে। যেমনঃ সিঙ্গেল ফেইজের ক্ষেত্রে 220 Volt থেকে 230 Volt এবং থ্রি ফেইজের
                    ক্ষেত্রে (380 Volt-415 Volt) ত্রুটিপূর্ণ ভাবে পাম্প ও মোটর স্থাপন করলে।</small></li>
            <li><small>পাম্পকৃত পানিতে বালি, কাদা, আয়রণ ও নূড়ি পাথর থাকলে।</small></li>
            <li><small>পাম্প পানি ছাড়া দীর্ঘক্ষণ চললে।</small></li>
        </ul>
        <p>পাম্প ও মটর রক্ষণাবেক্ষণের উপায়</p>
        <ul>
            <li><small>সঠিক মাত্রায় ভোল্টেজ আছে কিনা ভোল্টমিটার দ্বারা নিশ্চিত হয়ে পাম্প চালানো।</small></li>
            <li><small>পাম্পের মোটর অংশে যাতে কোনভাবে পানি প্রবেশ না করে তা নিশ্চিত করা।</small></li>
            <li><small>থ্রি ফেইজ পাম্প স্থাপন এর সময় ফেইজ ফেইলিউর প্রোটেকশন এবং ওভার লোড রিলে ব্যবহার করা।</small></li>
            <li><small>দীর্ঘদিন অব্যবহৃত পাম্প চালানোর সময় এর ফ্যান ও শ্যাফ্ট ঘুরিয়ে দেখে চালু করা।</small></li>
            <li><small>সকল বৈদ্যুতিক কানেকশন টার্মিনালের তারগুলো হোল্ডারের ক্রুর সাথে মজবুতভাবে লাগানো, যাতে লুজ
                    কানেকশন দ্বারা স্পার্ক সৃষ্টি না হয়।</small></li>
            <li><small>সাবমার্সিবল পাম্প-এর ক্ষেত্রে পানিতে বালি, কাদা ও আয়রনের মাত্রা অধিক না হয়।</small></li>
        </ul>
        <p>ওয়ারেন্টির শর্তাবলী</p>
        <ul>
            <li><small>সারফেস পাম্পের ক্ষেত্রে ৩ বছর এবং সাবমার্সিবল পাম্পের ক্ষেত্রে ২ বছর ফ্রী সার্ভিস।</small></li>
            <ul>
                <li><small>১ বছরের স্পেয়ার পার্টস Replacement গ্যারেন্টি।</small></li>
                <li><small>১ বছরের মোটর কয়েলের ফ্রী ওয়াইন্ডিং। পাম্প ও মোটরের সকল উৎপাদনজনিত ত্রুটি প্রদত্ত ওয়ারেন্টির
                        আওতাভুক্ত হবে। কোন অবস্থাতেই ওয়ারেন্টি দ্বারা অন্যান্য ক্ষতিপূরণের জন্য সম্ভাব্য অনুরোধ বুঝাবে
                        না।</small></li>
            </ul>
            <li><small>পাম্প বিক্রয়ের চালান/ বিল/ ওয়ারেন্টি কার্ড (যথাযথতথ্য সম্বলিত) বিক্রয় দলিল হিসাবে বিবেচিত হবে।
                    ভুল বিদ্যুৎ সংযোগ, ত্রুটিপূর্ণ স্থাপন এবং উপযুক্ত সতর্কতা অবলম্বনে বার্থতার ফলে পানিও মোটর নষ্ট হলে
                    প্রদত্ত ওয়ারেন্টি কার্যকর হবে না খুচরা যন্ত্রাংশের অপ্রাপ্যতার জন্য ওয়ারেন্টি সেবা বিলম্বিত হলে এর
                    জন্য কর্তৃপক্ষ দায়ী থাকবে না।</small></li>
        </ul>
        <p>নিম্নোক্ত ক্ষেত্রে ওয়ারেন্টির আওতা স্বীকৃত নয়</p>
        <ul>
            <li><small>পাম্পকৃত পানিতে বালি, কাদা, আয়রন ও ক্ষতিকর রাসায়নিক পদার্থ দ্বারা ক্ষয় ও ঘর্ষণের ফলে পাম্প ও মোটর
                    নষ্ট হলে- ড্রেনেজ পাম্পের ক্ষেত্রে নির্দিষ্ট কিছু মডেলের পাম্প ব্যতীত।</small></li>
            <li><small>থ্রি ফেইজ পাম্পের ক্ষেত্রে ফেইজ ফেইলিউর ও সঠিক মানের ওভার লোড রিলে ব্যবহার না করলে।</small></li>
            <li><small>হর্স পাওয়ার ও বিদ্যুৎ উৎস হতে দূরত্ব অনুযায়ী সঠিক মানের পাওয়ার ক্যাবল ব্যবহার না করলে।</small>
            </li>
            <li><small>সারফেস পাম্পের মোটরে পানি ঢুকে কয়েল পুড়ে গেলে।</small></li>
            <li><small>সাবমার্সিবল পাম্প পানির লেভেল থেকে ৪০-৫০ ফুট এর বেশী নিচে স্থাপন করলে।</small></li>
            <li><small>সাবমার্সিবল পাম্প এর বোরিং পাম্পের ডায়ার দ্বিগুণ এর বেশী হলে, পাম্পের মোটরের গায়ে কুলিং কেসিং
                    ব্যবহার না করলে।</small></li>
            <li><small>পাম্প ক্রয়ের সময় ওয়ারেন্টি কার্ড সঠিক ভাবে পুরন করে নিতে হবে এবং ওয়ারেন্টির আওতায় সেবা নেওয়ার
                    ক্ষেত্রে অবশ্যই ওয়ারেন্টি কার্ড প্রদর্শন করতে হবে।</small></li>
        </ul>
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
