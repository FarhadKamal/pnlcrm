@include('layouts.navbar')
<div id="quotationPageTop">
    <div class=" m-2">
        <a href="{{ route('detailsLog', ['leadId' => $leadInfo->id]) }}" target="_blank"><button
                class="btn btn-darkblue btm-sm fs-07rem p-1">Details Log</button></a>
    </div>
    <button onclick="printWithLogo()" class=" m-2 btn btn-sm btn-darkblue printBtn  mt-2 me-2">Print Quotation</button>
    <button onclick="printWithoutLogo()" class=" m-2 btn btn-sm btn-darkblue printBtn  mt-2 me-2">Pad Print
        Quotation</button>
</div>
<div class="">
    @include('sales.quotationLayoutNew')
</div>
<div id="quotationPageBottom">
    @if ($leadInfo->current_subStage == 'APPROVE')
        {{-- COO Aproval  --}}
        <div class="container mt-5 mb-5 p-4 shadow-4 border border-3" style="background-color: rgba(0,84,166, 0.2)">
            <?php
            $discountRemarksArr = explode(':', $discountRemarks->log_task);
            ?>
            @if ($discountRemarksArr[1] != ' ')
                <p class="mt-3 mb-3"><span class="">&#8226;</span> {{ $discountRemarks->log_task }}</p>
            @endif
            <form action="{{ route('preQuotationApprove') }}" method="POST">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}" required>
                @if ($leadInfo->need_credit_approval)
                    <label for="">Credit Approval</label><br>
                    <select name="credit_approved" id="" required>
                        <option value="1" selected>Approved</option>
                        <option value="0">Not Approved</option>
                    </select>
                    <br>
                @endif
                @if ($leadInfo->need_discount_approval)
                    <label for="">Discount Approval</label>
                    <table class="table table-bordered border-dark">
                        <thead>
                            <tr>
                                <th class="p-1 text-center">Brand</th>
                                <th class="p-1 text-center">Model</th>
                                <th class="p-1 text-center">Unit Price (TK)</th>
                                <th class="p-1 text-center">Trade Discount (%)</th>
                                <th class="p-1 text-center">Qty.</th>
                                <th class="p-1 text-center">Deal Discount (%)</th>
                                <th class="p-1 text-center">Net Price (TK)</th>
                                <th class="p-1 text-center">Set New Discount (%)</th>
                                <th class="p-1 text-center">New Net Price (TK)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pumpInfo as $item)
                                <?php
                                $proposed_discount = $item->discount_percentage;
                                if ($item->spare_parts == 0) {
                                    $trade_discount = $item->productInfo->TradDiscontInfo->trade_discount;
                                    $brandName = $item->productInfo->brand_name;
                                    $matName = $item->productInfo->mat_name;
                                } else {
                                    $trade_discount = 'N/A';
                                    $brandName = $item->spareInfo->brand_name;
                                    $matName = $item->spareInfo->mat_name;
                                }
                                // if ($proposed_discount > $trade_discount) {
                                // }
                                ?>
                                <tr>
                                    <td class="d-none"><input name="approvePumpChoice[]" value="{{ $item->id }}">
                                    </td>
                                    <td class="p-1 text-center">{{ $brandName }}</td>
                                    <td class="p-1 text-center">{{ $matName }}</td>
                                    <td class="p-1 text-end">{{ $item->unit_price }}</td>
                                    <td class="p-1 text-center">{{ $trade_discount }}</td>
                                    <td class="p-1 text-center">{{ $item->qty }}</td>
                                    <td class="p-1 text-center">{{ $proposed_discount }}</td>
                                    <td class="p-1 text-end">{{ $item->net_price }}</td>
                                    <td class="p-1 text-center"><input type="number" min="0" step="any"
                                            name="set_discount[]" onkeyup="updatePrice(this)"
                                            value="{{ $proposed_discount }}" required /></td>
                                    <td class="p-1 text-end">{{ $item->net_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <center><button class="btn btn-sm btn-darkblue">Submit Approval</button></center>
            </form>
        </div>

        <div class="container mt-5 mb-5 p-4 shadow-4 border border-3" style="background-color: rgba(166, 0, 0, 0.2)">
            <form action="{{ route('preQuotationReturn') }}" method="POST">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}" required>
                <label for="">Return Remarks</label>
                <input type="text" name="preReturnRemarks" id="preReturnRemarks" class="form-control" required>
                <center><button class="btn btn-sm btn-danger mt-3">Return</button></center>
            </form>
        </div>
    @endif

    @if ($leadInfo->current_subStage == 'MANAGEMENT')
        {{-- Top Management Approval  --}}
        <div class="container mt-5 mb-5 p-4 shadow-4 border border-3" style="background-color: rgba(0,84,166, 0.2)">
            <?php
            $discountRemarksArr = explode(':', $discountRemarks->log_task);
            ?>
            @if ($discountRemarksArr[1] != ' ')
                <p class="mt-3 mb-3"><span class="">&#8226;</span> {{ $discountRemarks->log_task }}</p>
            @endif
            <form action="{{ route('topQuotationApprove') }}" method="POST">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}" required>
                @if ($leadInfo->need_discount_approval)
                    <label for="">Discount Approval</label>
                    <table class="table table-bordered border-dark">
                        <thead>
                            <tr>
                                <th class="p-1 text-center">Brand</th>
                                <th class="p-1 text-center">Model</th>
                                <th class="p-1 text-center">Unit Price (TK)</th>
                                <th class="p-1 text-center">Trade Discount (%)</th>
                                <th class="p-1 text-center">Qty.</th>
                                <th class="p-1 text-center">Deal Discount (%)</th>
                                <th class="p-1 text-center">Net Price (TK)</th>
                                <th class="p-1 text-center">Set New Discount (%)</th>
                                <th class="p-1 text-center">New Net Price (TK)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pumpInfo as $item)
                                <?php
                                $proposed_discount = $item->discount_percentage;
                                if ($item->spare_parts == 0) {
                                    $trade_discount = $item->productInfo->TradDiscontInfo->trade_discount;
                                    $brandName = $item->productInfo->brand_name;
                                    $matName = $item->productInfo->mat_name;
                                } else {
                                    $trade_discount = 'N/A';
                                    $brandName = $item->spareInfo->brand_name;
                                    $matName = $item->spareInfo->mat_name;
                                }
                                // if ($proposed_discount > $trade_discount) {
                                // }
                                ?>
                                <tr>
                                    <td class="d-none"><input name="approvePumpChoice[]" value="{{ $item->id }}">
                                    </td>
                                    <td class="p-1 text-center">{{ $brandName }}</td>
                                    <td class="p-1 text-center">{{ $matName }}</td>
                                    <td class="p-1 text-end">{{ $item->unit_price }}</td>
                                    <td class="p-1 text-center">{{ $trade_discount }}</td>
                                    <td class="p-1 text-center">{{ $item->qty }}</td>
                                    <td class="p-1 text-center">{{ $proposed_discount }}</td>
                                    <td class="p-1 text-end">{{ $item->net_price }}</td>
                                    <td class="p-1 text-center"><input type="number" min="0" step="any"
                                            name="set_discount[]" onkeyup="updatePrice(this)"
                                            value="{{ $proposed_discount }}" required /></td>
                                    <td class="p-1 text-end">{{ $item->net_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <center><button class="btn btn-sm btn-darkblue">Submit Approval</button></center>
            </form>
        </div>

        <div class="container mt-5 mb-5 p-4 shadow-4 border border-3" style="background-color: rgba(166, 0, 0, 0.2)">
            <form action="{{ route('topQuotationReturn') }}" method="POST">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $leadInfo->id }}" required>
                <label for="">Return Remarks</label>
                <input type="text" name="topReturnRemarks" id="topReturnRemarks" class="form-control" required>
                <center><button class="btn btn-sm btn-danger mt-3">Return</button></center>
            </form>
        </div>
    @endif

    @if ($leadInfo->current_subStage == 'SUBMIT')
        {{-- Submit To Customer  --}}
        <input type="hidden" name="QleadId" id="QleadId" value="{{ $leadInfo->id }}" required>
        @if ($leadInfo->lead_email)
            <div class="container">
                <div class="row" id="attachmentDiv">
                    <div class="col-md-3 mt-1"><input type="file" name="attachmentFiles[]"
                            class="form-control fs-07rem p-1 attachmentFiles"></div>
                </div>
                <center>
                    <p class="btn btn-primary btn-sm fs-06rem p-1 mt-2" onclick="addAttachment()">Add Attchment</p>
                </center>
            </div>
            <div class="container">
                <label for="" class="fs-08rem">Add CC Email <small class="text-success">Use comma (,) for
                        multiple
                        email</small></label>
                <input type="text" class="form-control fs-08rem" name="ccEmails" id="ccEmails">
            </div>
            <div class="container">
                @if ($reEmail)
                    <div class="row">
                        <div class="col-md-8">
                            <label for="" class="fs-08rem">Remarks <small class="text-success">(show on mail
                                    body)</small></label>
                            <input type="text" class="form-control fs-08rem" name="emailRemarks"
                                id="emailRemarks">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="fs-08rem">Re Email To Customer</label>
                            <select name="emailFlag" id="emailFlag" class="form-control" required>
                                <option value="Yes" selected>Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                @else
                    <label for="" class="fs-08rem">Remarks <small class="text-success">(show on mail
                            body)</small></label>
                    <input type="text" class="form-control fs-08rem" name="emailRemarks" id="emailRemarks">
                @endif
            </div>
            <center><button class="btn btn-sm btn-darkblue m-3" onclick="sentQuotation()">Submit to Client</button>
            </center>
        @else
            <div class="bg-danger text-white p-2 text-center">
                <h5 class="">No Email Found For The Client. Please Add An Email First.</h5>
                <form action="{{ route('updateLeadEmail') }}" class="m-3">
                    @csrf
                    <input type="hidden" name="QleadId" id="QleadId" value="{{ $leadInfo->id }}" required>
                    <label for="">Email</label>
                    <input type="email" name="lead_email" id="lead_email" class="border-0 rounded fs-08rem p-1"
                        required>
                    <button class="btn btn-darkblue btn-sm">Add Email</button>
                </form>
            </div>
        @endif
    @endif
</div>

<script>
    function updatePrice(e) {
        var row = e.parentElement.parentElement;
        row = row.querySelectorAll("td");
        let productUP = row[3].innerText;
        let productQty = row[5].innerText;
        let productNewDiscountPercentage = row[8].querySelector('input');
        productDiscountPercentage = productNewDiscountPercentage.value;
        let totalPrice = (Number(productUP) * Number(productQty));
        let discountAmount = totalPrice * (Number(productDiscountPercentage) / 100);
        let productTotalPrice = totalPrice - discountAmount;
        row[9].innerText = productTotalPrice;
    }
</script>


<script>
    setInterval(function() {
        fetch('/quotationReferenceCheck')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(json => {
                let refFullDate = new Date(json.currentDate);
                let refYear = refFullDate.getFullYear();
                let refMonth = Number(refFullDate.getMonth() + 1).toString().padStart(2, '0');
                let refDate = Number(refFullDate.getDate()).toString().padStart(2, '0');

                let serialNo = Number(json.checkQuotationSerial[0]['sl'] + 1).toString().padStart(3,
                    '0');
                let sellerZone = '<?= Auth()->user()->assign_to ?>';
                let refPreText = 'REF: PNL/' + sellerZone + '/QUOT/' + refYear + '/' + refMonth + refDate +
                    serialNo;
                // console.log(refPreText);
                document.getElementById('quotationRef').innerText = refPreText;
                $('#quotationRef').val(refPreText);

            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });

    }, 1000 * 60 * 0.01);
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    function sentQuotation() {
        var blob;
        window.jsPDF = window.jspdf.jsPDF;
        var docPDF = new jsPDF();
        // docPDF.setFont("helvetica");

        var elementHTML = document.querySelector("#section-to-print");
        
        var imageUrl = "https://i.imgur.com/g5UQypc.png";
        
        docPDF.html(elementHTML, {
            callback: function(docPDF) {

                // Add footer to every page
                const totalPages = docPDF.internal.getNumberOfPages();
                for (let i = 1; i <= totalPages; i++) {
                    docPDF.setPage(i); // Set to current page
                    const pageWidth = docPDF.internal.pageSize.width;
                    const pageHeight = docPDF.internal.pageSize.height;
                    const bottomMargin = 6;
                    const imageHeight = 25;
                    const yPosition = pageHeight - bottomMargin - imageHeight;
                    docPDF.addImage(imageUrl, 'png', 15, yPosition, (pageWidth-20), imageHeight);
                }

                // docPDF.save();
                blob = docPDF.output('blob');
                let leadId = $('#QleadId').val();
                let quotationRef = $('#quotationRef').val();
                let otherAttachment = document.getElementsByClassName('attachmentFiles');
                let emailRemarks = $('#emailRemarks').val();
                let emailFlag = $('#emailFlag').val();
                if (emailFlag && emailFlag == 'No') {
                    emailFlag = 0;
                } else {
                    emailFlag = 1;
                }
                let ccEmails = '';
                if ($('#ccEmails').val() != '') {
                    ccEmails = $('#ccEmails').val().split(',').map(email => email
                        .trim()); // Split and trim emails
                }
                // console.log($('#ccEmails').val() != '');
                // console.log(ccEmails);
                let _token = '<?php echo csrf_token(); ?>';
                var formData = new FormData();
                formData.append('leadId', leadId);
                formData.append('quotationRef', quotationRef);
                formData.append('doc', blob);
                formData.append('_token', _token);
                if (ccEmails.length > 0) {
                    ccEmails.forEach(email => {
                        formData.append('ccEmails[]', email);
                    });
                }
                formData.append('emailRemarks', emailRemarks);
                formData.append('emailFlag', emailFlag);
                // Append each file in the otherAttachment collection
                for (let i = 0; i < otherAttachment.length; i++) {
                    let fileInput = otherAttachment[i];
                    for (let j = 0; j < fileInput.files.length; j++) {
                        formData.append('otherAttachment[]', fileInput.files[j]);
                    }
                }
                console.log(formData);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content'),
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/submitQuotation',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        document.getElementById("loadingGif").style.display = "block";
                    },
                    complete: function() {
                        document.getElementById("loadingGif").style.display = "none";
                    },
                    success: function(data) {
                        // console.log(data);
                        window.location.href = "/home";
                    },
                    error: function(data) {
                        // console.log(data)
                    }
                });

            },
            margin: [1, 1, 1, 5],
            autoPaging: true,
            x: 5,
            y: 2,
            width: 160, //target width in the PDF document
            windowWidth: 675, //window width in CSS pixels
        });
    }
</script>

<script>
    function addAttachment() {
        let html =
            '<div class="col-md-3 mt-1"><input type="file" name="attachmentFiles[]" class="form-control fs-07rem p-1 attachmentFiles"></div>';
        $('#attachmentDiv').append(html);
    }
</script>
