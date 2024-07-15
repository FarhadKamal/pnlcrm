@include('layouts.navbar')
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
                        if ($item->log_next == 'Quotation feedback') {
                            $checkQuotationFile = DB::select("SELECT quotation_file FROM quotations WHERE quotations.lead_id = '$leadInfo->id' AND DATE(quotations.created_at) BETWEEN DATE('$item->created_at') AND DATE('$item->created_at')");
                            ?>
                        @if (isset($checkQuotationFile[0]->quotation_file))
                            <td class="text-center">{{ $item->log_task }} <a
                                    href="{{ asset('quotations') . '/' . $checkQuotationFile[0]->quotation_file }}"
                                    target="_blank"><small class="badge badge-info">VIEW QUOTATION</small></a></td>
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
