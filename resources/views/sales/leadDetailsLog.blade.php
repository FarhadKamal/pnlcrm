@include('layouts.navbar')

@if (session('errors'))
    <div class="alert alert-danger">
        <ul>
            @foreach (session('errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($leadInfo->is_won == 1)
    <div class="m-2 float-end">
        <button class="btn btn-sm btn-darkblue fs-07rem p-1" onclick="printInvoice()">Print Invoice</button>
    </div>
    @include('sales.invoicePage')
    <script>
        function printInvoice() {
            var printWindow = window.open('', '_blank');

            // Check if the window opened successfully
            if (printWindow) {
                var printContents = document.getElementById("invoicePrint").innerHTML;
                printWindow.document.write('<html><body>');
                printWindow.document.write(printContents);
                printWindow.document.write('</body></html>');

                // Make sure to close the print window after printing
                printWindow.document.close();
                printWindow.print();
            } else {
                alert('Please allow pop-ups for this site to print');
            }
        }
    </script>
@endif


@if ($leadInfo->is_won != 1)
    @if ($leadInfo->is_lost != 1 && $leadInfo->clientInfo->assign_to == Auth()->user()->assign_to)
        <div class="m-2 float-end">
            <a href="{{ route('lost', ['leadId' => $leadInfo->id]) }}"><button type='button'
                    class='btn btn-sm btn-danger'>Lost</button></a>
        </div>
    @endif
@endif
<div class="mt-5">
    @if ($leadInfo->is_lost == 1)
        <div class="bg-danger mb-3">
            <h6 class="text-white text-center p-1">Lost Category: {{ $leadInfo->lost_reason }}</h6>
            <p class="text-center text-white p-1">Lost Reason: {{ $leadInfo->lost_description }}</p>
        </div>
    @endif

    <div class="row container-fluid">
        <!-- Lead Information Table -->
        <div class="col-md-5 col-sm-5 mb-3">
            <h6 class="text-center"><kbd>Lead Information</kbd></h6>
            <div class="container fs-09rem">
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Client SAP ID</p>
                    @if ($leadInfo->clientInfo->sap_id)
                        <small class="col-md-8">{{ $leadInfo->clientInfo->sap_id }}</small>
                    @else
                        <small class="col-md-8">N/A</small>
                    @endif
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Name</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->customer_name }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Phone</p>
                    <small class="col-md-8">{{ $leadInfo->lead_phone }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Email</p>
                    <small class="col-md-8">{{ $leadInfo->lead_email }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Address</p>
                    <small class="col-md-8">{{ $leadInfo->clientInfo->address }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Lead Source</p>
                    <small class="col-md-8">{{ $leadInfo->source->source_name }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Requirement</p>
                    <small class="col-md-8">{{ $leadInfo->product_requirement }}</small>
                </div>

                <div class="row border-bottom p-1">
                    <p class="col-md-4 text-muted m-0">Created By</p>
                    <small class="col-md-8">{{ $leadInfo->createdBy->user_name }}</small>
                </div>
                <div class="row border-bottom p-1">
                    <p class="col-md-4 m-0 text-info">Assign To</p>
                    <small class="col-md-8 text-info">{{ $leadInfo->clientInfo->assignTo->user_name }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            @if (count($pumpInfo) > 0)
                <table class="table table-bordered fs-08rem">
                    <thead>
                        <tr>
                            <th class="p-1 text-center">Brand</th>
                            <th class="p-1 text-center">Model</th>
                            <th class="p-1 text-center">Unit Price (TK)</th>
                            <th class="p-1 text-center">Qty.</th>
                            <th class="p-1 text-center">Discount %</th>
                            <th class="p-1 text-center">Discount Amount (TK)</th>
                            <th class="p-1 text-center">Net Price (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalDiscountAmt = 0;
                        $totalNetPrice = 0; ?>
                        @foreach ($pumpInfo as $pumps)
                            <?php
                            $totalDiscountAmt = $totalDiscountAmt + $pumps->discount_price;
                            $totalNetPrice = $totalNetPrice + $pumps->net_price;
                            if ($pumps->spare_parts == 0) {
                                $brandName = $pumps->productInfo->brand_name;
                                $matName = $pumps->productInfo->mat_name;
                            } else {
                                $brandName = $pumps->spareInfo->brand_name;
                                $matName = $pumps->spareInfo->mat_name;
                            }
                            ?>
                            <tr>
                                <td class="p-1">{{ $brandName }}</td>
                                <td class="p-1">{{ $matName }}</td>
                                <td class="p-1 text-end">{{ number_format((float) $pumps->unit_price, 2, '.', ',') }}
                                </td>
                                <td class="p-1 text-center">{{ $pumps->qty }}</td>
                                <td class="p-1 text-center">{{ $pumps->discount_percentage }}</td>
                                <td class="p-1 text-end">
                                    {{ number_format((float) $pumps->discount_price, 2, '.', ',') }}
                                </td>
                                <td class="p-1 text-end">{{ number_format((float) $pumps->net_price, 2, '.', ',') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="p-1 text-center fw-bold">Total</th>
                            <th class="p-1 text-end fw-bold">
                                {{ number_format((float) $totalDiscountAmt, 2, '.', ',') }}
                            </th>
                            <th class="p-1 text-end fw-bold">{{ number_format((float) $totalNetPrice, 2, '.', ',') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            @else
                <h6>No Item Selection Found</h6>
            @endif

            @if (
                $leadInfo->current_stage == 'BOOKING' &&
                    $leadInfo->current_subStage == 'CREDITHOLD' &&
                    $leadInfo->clientInfo->assign_to == Auth()->user()->assign_to)
                <div class="bg-offwhite  p-2">
                    <h6><kbd class="bg-danger">CREDIT HOLD</kbd></h6>

                    <h6 class="fs-08rem">{{ $salesLog[0]->log_task }}</h6>

                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('reSubmitToCredit') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="" class="fs-08rem">PO Attachment <small
                                                class="text-danger">(Submit If
                                                Required)</small></label>
                                        <input name="poFileUpdate" id="poFileUpdate" type="file"
                                            accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx"
                                            class="form-control lh-sm fs-08rem">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-darkblue fs-07rem p-1 mt-4">Re Submit Credit Set</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            @endif

            @if ($leadInfo->is_won != 1)
                @if (
                    $leadInfo->is_lost != 1 &&
                        $leadInfo->clientInfo->assign_to == Auth()->user()->assign_to &&
                        $leadInfo->sap_invoice == 0 && $leadInfo->current_stage != 'LEAD')
                    <form action=" {{ route('reDealStage') }}" method="POST">
                        @csrf
                        <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
                        <div class="row p-2 bg-offwhite mt-2">
                            <div class="col-md-7">
                                <label for="" class="fs-08rem">Re Deal Remarks</label>
                                <textarea name="reDealRemark" class="form-control fs-08rem p-1" cols="30" rows="3" required></textarea>
                            </div>
                            <div class="col-md-4" style="align-content: space-evenly;">
                                <button class="btn btn-darkblue fs-07rem p-1">Back to Re Deal Stage</button>
                            </div>
                        </div>
                    </form>

                    {{-- <form action="">
                        @csrf
                        <input type="hidden" name="leadId" value="{{ $leadInfo->id }}">
                        <div style="background-color: #D8E3F4" class="row p-2 mt-2">
                            <center>
                                <kbd>Store Any Important Document For The Lead</kbd>
                            </center>
                            <div class="col-md-4">
                                <label for="" class="fs-08rem">Upload Attachment</label>
                                <input type="file" class="form-control fs-07rem p-1">
                            </div>
                            <div class="col-md-4">
                                <label for="" class="fs-08rem">Attachment Name</label>
                                <input type="text" class="form-control fs-07rem p-1">
                            </div>
                            <div class="col-md-4" style="align-content: space-evenly;">
                                <button class="btn btn-darkblue fs-07rem p-1">Store File</button>
                            </div>
                        </div>
                    </form> --}}
                @endif
            @endif
        </div>
    </div>


    <!-- Log table-->

    <div class="mt-3 table-responsive">
        <h6 class="text-center"><kbd>Details Log Table</kbd></h6>
        <table class="table table-bordered fs-08rem lh-1 log-table">
            <thead class="table-primary text-center">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Log Date Time</th>
                    <th scope="col">Log Stage</th>
                    <th scope="col">Task</th>
                    <th scope="col">Log By</th>
                    <th scope="col">Waiting For</th>
                </tr>
            </thead>
            <tbody>
                <?php $sl = count($salesLog); ?>
                @foreach ($salesLog as $item)
                    <tr>
                        <th scope="row" class="text-center">{{ $sl }}</th>
                        <td class="text-center">{{ date('d-M-Y h:i a', strtotime($item->created_at)) }}</td>
                        <td class="text-center">{{ $item->log_stage }}</td>
                        <?php
                        $baseDatetime = new DateTime($item->created_at);
                        $startDatetime = $baseDatetime->modify('-1 minutes')->format('Y-m-d H:i:s');
                        $endDatetime = $baseDatetime->modify('+2 minutes')->format('Y-m-d H:i:s');
                        if ($item->log_next == 'Quotation feedback') {
                            $checkQuotationFile = DB::select("SELECT quotation_file,is_accept,quotation_po,accept_file FROM quotations WHERE quotations.lead_id = '$leadInfo->id' AND DATE_FORMAT(quotations.created_at, '%Y-%m-%d %H:%i:%s') BETWEEN ('$startDatetime') AND ('$endDatetime')");
                            ?>
                        @if (isset($checkQuotationFile[0]->quotation_file))
                            <td class="text-center">{{ $item->log_task }} <a
                                    href="{{ asset('quotations') . '/' . $checkQuotationFile[0]->quotation_file }}"
                                    target="_blank"><small class="badge badge-info">VIEW QUOTATION</small></a>
                                @if ($checkQuotationFile[0]->is_accept == 1 && isset($checkQuotationFile[0]->accept_file))
                                    <a href="{{ asset('leadQuotationAcceptAttachment') . '/' . $checkQuotationFile[0]->accept_file }}"
                                        target="_blank"><small class="badge badge-info">VIEW PO</small></a>
                                @endif
                            </td>
                        @else
                            <td class="text-center">{{ $item->log_task }} </td>
                        @endif
                        <?php 
                        }else {
                           ?>
                        <td class="text-center">{{ $item->log_task }}</td>
                        <?php 
                        }
                        ?>
                        <td class="text-center">{{ $item->logBy->user_name }}</td>
                        <td class="text-center">{{ $item->log_next }}</td>
                    </tr>
                    <?php $sl--; ?>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
