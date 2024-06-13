@include('layouts.navbar')
<div class="container-fluid mt-3 mb-5">
    <div class="d-flex  flex-row salesStageFlex" id="salesStageFlex">

        <!----------------------------Lead Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="leadColumn">
            <h6 class=" rounded  p-1 bg-secondary text-white text-center mb-3 ">Lead
                @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'leadForm'))
                    <a href="{{ route('newLeadForm') }}">
                        <badge class="badge badge-info p-1 rounded-pill  fs-07rem blink">+Add New</badge>
                    </a>
                @endif
            </h6>
            @if (count($leadStage) <= 0)
                <p class="text-danger">No Lead Found</p>
            @endif
            @foreach ($leadStage as $item)
                <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                                <h6 class="card-title fs-09rem">
                                    {{ $item['clientInfo']->customer_name }}
                                </h6>
                            </div>
                        </div>
                        <small class="card-text mb-1"><b>Group:</b> {{ $item['clientInfo']->group_name }}</small><br>
                        <small class="card-text mb-1"><b>District:</b>
                            {{ $item['clientInfo']->district }}</small><br>
                        <small class="card-text mb-1"><b>Contact:</b>
                            {{ $item['clientInfo']->contact_person }}</small><br>
                        <small class="card-text mb-1"><b>Phone:</b>
                            {{ $item['clientInfo']->contact_mobile }}</small><br>
                        <small class="card-text mb-1"><b>Source:</b> {{ $item['source']->source_name }}</small><br>
                        <small class="card-text mb-1"><b>Created By:</b> {{ $item['createdBy']->user_name }}</small>
                        <div>
                            <?php $encoded = json_encode($item); ?>
                            <button type="button" data-mdb-toggle="modal" data-mdb-target="#newLeadModal"
                                class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100"
                                onclick='dataShowModal(<?= $encoded ?>)'>{{ $leadButtonLabel }}</button>
                        </div>
                    </div>
                </div>
            @endforeach
            @include('sales.modals.newLeadModal')
        </div>

        <!----------------------------Deal Column---------------- -->

        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Deal</h6>
            @if (count($dealStage) <= 0)
                <p class="text-danger">No Deal Found</p>
            @endif
            @foreach ($dealStage as $item)
                <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;">
                    <div class="card-body">
                        {{-- @if ($item->current_subStage == 'APPROVE')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for Approval</small>
                        @endif
                        @if ($item->current_subStage == 'CHECK')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for Checking</small>
                        @endif
                        @if ($item->current_subStage == 'FORM')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for Dealing</small>
                        @endif --}}
                        <div class="row">
                            <div class="col-10">
                                <h6 class="card-title fs-09rem">
                                    {{ $item['clientInfo']->customer_name }}
                                </h6>
                            </div>
                        </div>
                        <small class="card-text mb-1"><b>Group:</b> {{ $item['clientInfo']->group_name }}</small><br>
                        <small class="card-text mb-1"><b>District:</b>
                            {{ $item['clientInfo']->district }}</small><br>
                        <small class="card-text mb-1"><b>Contact:</b>
                            {{ $item['clientInfo']->contact_person }}</small><br>
                        <small class="card-text mb-1"><b>Phone:</b>
                            {{ $item['clientInfo']->contact_mobile }}</small><br>
                        <small class="card-text mb-1"><b>Source:</b> {{ $item['source']->source_name }}</small><br>
                        <small class="card-text mb-1"><b>Created By:</b> {{ $item['createdBy']->user_name }}</small>
                        <div>
                            <a href="{{ route('dealPage', ['leadId' => $item->id]) }}">
                                <button type="button"
                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Requirement &
                                    Choice</button>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!----------------------------Quotation Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Quotation</h6>
            @if (count($quotationStage) <= 0)
                <p class="text-danger">No Quotation Stage Found</p>
            @endif
            @foreach ($quotationStage as $item)
                <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;">
                    <div class="card-body">
                        @if ($item->current_subStage == 'APPROVE')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for Approval</small>
                        @endif
                        @if ($item->current_subStage == 'MANAGEMENT')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for Top Management</small>
                        @endif
                        @if ($item->current_subStage == 'SUBMIT')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for Submit</small>
                        @endif
                        @if ($item->current_subStage == 'FEEDBACK')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for Feddback</small>
                        @endif
                        <div class="row">
                            <div class="col-10">
                                <h6 class="card-title fs-09rem">
                                    {{ $item['clientInfo']->customer_name }}
                                </h6>
                            </div>
                        </div>
                        <small class="card-text mb-1"><b>Group:</b> {{ $item['clientInfo']->group_name }}</small><br>
                        <small class="card-text mb-1"><b>District:</b>
                            {{ $item['clientInfo']->district }}</small><br>
                        <small class="card-text mb-1"><b>Contact:</b>
                            {{ $item['clientInfo']->contact_person }}</small><br>
                        <small class="card-text mb-1"><b>Phone:</b>
                            {{ $item['clientInfo']->contact_mobile }}</small><br>
                        <small class="card-text mb-1"><b>Source:</b> {{ $item['source']->source_name }}</small><br>
                        <small class="card-text mb-1"><b>Created By:</b> {{ $item['createdBy']->user_name }}</small>
                        <div>
                            @if ($item->current_subStage == 'APPROVE')
                                @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'dealApprove'))
                                    <a href="{{ route('quotationCheck', ['leadId' => $item->id]) }}">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Approve</button>
                                    </a>
                                @else
                                    <a href="">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                    </a>
                                @endif
                            @endif
                            @if ($item->current_subStage == 'MANAGEMENT')
                                @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'dealTopApprove'))
                                    <a href="{{ route('quotationCheck', ['leadId' => $item->id]) }}">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Managmement</button>
                                    </a>
                                @else
                                    <a href="">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                    </a>
                                @endif
                            @endif
                            @if ($item->current_subStage == 'SUBMIT')
                                @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'salesPerson'))
                                    <a href="{{ route('quotationCheck', ['leadId' => $item->id]) }}">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Submit</button>
                                    </a>
                                @else
                                    <a href="">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                    </a>
                                @endif
                            @endif
                            @if ($item->current_subStage == 'FEEDBACK')
                                @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'salesPerson'))
                                    <?php $encoded = json_encode($item); ?>
                                    <button type="button" data-mdb-toggle="modal"
                                        data-mdb-target="#quotationFeedbackModal"
                                        class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100"
                                        onclick='quotationFeedbackShowModal(<?= $encoded ?>)'>Quotation
                                        Feedback
                                    </button>
                                @else
                                    <a href="">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            @include('sales.modals.quotationFeedbackModal')
        </div>


        <!----------------------------Booking Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Booking</h6>
            @if (count($bookingStage) <= 0)
                <p class="text-danger">No Lead Found</p>
            @endif
            @foreach ($bookingStage as $item)
                <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;">
                    <div class="card-body">
                        @if ($item->current_subStage == 'SAPIDSET')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for SAP ID creation</small>
                        @endif
                        @if ($item->current_subStage == 'CREDITSET')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for credit set</small>
                        @endif
                        <div class="row">
                            <div class="col-10">
                                <h6 class="card-title fs-09rem">
                                    {{ $item['clientInfo']->customer_name }}
                                </h6>
                            </div>
                        </div>
                        <small class="card-text mb-1"><b>Group:</b> {{ $item['clientInfo']->group_name }}</small><br>
                        <small class="card-text mb-1"><b>District:</b>
                            {{ $item['clientInfo']->district }}</small><br>
                        <small class="card-text mb-1"><b>Contact:</b>
                            {{ $item['clientInfo']->contact_person }}</small><br>
                        <small class="card-text mb-1"><b>Phone:</b>
                            {{ $item['clientInfo']->contact_mobile }}</small><br>
                        <small class="card-text mb-1"><b>Source:</b> {{ $item['source']->source_name }}</small><br>
                        <small class="card-text mb-1"><b>Created By:</b> {{ $item['createdBy']->user_name }}</small>
                        <div>
                            @if ($item->current_subStage == 'SAPIDSET')
                                @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapIDCreation'))
                                    <a href="{{ route('newSapForm', ['leadId' => $item->id]) }}">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">SAP ID
                                            SET</button>
                                    </a>
                                @else
                                    <a href="">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                    </a>
                                @endif
                            @endif
                            @if ($item->current_subStage == 'CREDITSET')
                                @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapCreditSet'))
                                    <a href="{{ route('creditSetForm', ['leadId' => $item->id]) }}">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">SAP
                                            Credit Set</button>
                                    </a>
                                @else
                                    <a href="">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                    </a>
                                @endif
                            @endif
                            @if ($item->current_subStage == 'TRANSACTION')
                                @if ($item->clientInfo->assign_to == Auth()->user()->assign_to)
                                    <a href="{{ route('transaction', ['leadId' => $item->id]) }}">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Transaction</button>
                                    </a>
                                @else
                                    @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'verifyTransaction'))
                                        <a href="{{ route('verifyTransaction', ['leadId' => $item->id]) }}">
                                            <button type="button"
                                                class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Verify
                                                Transaction</button>
                                        </a>
                                    @else
                                        <a href="">
                                            <button type="button"
                                                class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                        </a>
                                    @endif
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <!----------------------------Ready To Deliver Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Ready To Deliver</h6>
            @if (count($deliveryStage) <= 0)
                <p class="text-danger">No Delivery Found</p>
            @endif
            @foreach ($deliveryStage as $item)
                <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;">
                    <div class="card-body">
                        @if ($item->current_subStage == 'DISCOUNTSET')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for Discount Set</small>
                        @endif
                        @if ($item->current_subStage == 'INVOICE')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for SAP Invoice</small>
                        @endif
                        @if ($item->current_subStage == 'READY')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for Delivery</small>
                        @endif
                        <div class="row">
                            <div class="col-10">
                                <h6 class="card-title fs-09rem">
                                    {{ $item['clientInfo']->customer_name }}
                                </h6>
                            </div>
                        </div>
                        <small class="card-text mb-1"><b>Group:</b> {{ $item['clientInfo']->group_name }}</small><br>
                        <small class="card-text mb-1"><b>District:</b>
                            {{ $item['clientInfo']->district }}</small><br>
                        <small class="card-text mb-1"><b>Contact:</b>
                            {{ $item['clientInfo']->contact_person }}</small><br>
                        <small class="card-text mb-1"><b>Phone:</b>
                            {{ $item['clientInfo']->contact_mobile }}</small><br>
                        <small class="card-text mb-1"><b>Source:</b> {{ $item['source']->source_name }}</small><br>
                        <small class="card-text mb-1"><b>Created By:</b> {{ $item['createdBy']->user_name }}</small>
                        <div>
                            @if ($item->current_subStage == 'DISCOUNTSET')
                                @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapDiscountSet'))
                                    <a href="{{ route('discountSetForm', ['leadId' => $item->id]) }}">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">SAP
                                            Discount Set</button>
                                    </a>
                                @else
                                    <a href="">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                    </a>
                                @endif
                            @endif
                            @if ($item->current_subStage == 'INVOICE')
                                @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapInvoiceSet'))
                                    <a href="{{ route('invoiceSetForm', ['leadId' => $item->id]) }}">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">SAP
                                            Invoice Set</button>
                                    </a>
                                @else
                                    <a href="">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                    </a>
                                @endif
                            @endif
                            @if ($item->current_subStage == 'READY')
                                @if ($item->clientInfo->assign_to == Auth()->user()->assign_to)
                                    <a href="{{ route('deliveryPage', ['leadId' => $item->id]) }}">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Delivery</button>
                                    </a>
                                @else
                                    <a href="">
                                        <button type="button"
                                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <!----------------------------WON Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Won</h6>
            @if (count($wonStage) <= 0)
                <p class="text-danger">No Won Found</p>
            @endif
            @foreach ($wonStage as $item)
                <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;">
                    <div class="card-body">
                        @if ($item->is_outstanding == 1)
                            <small class="badge badge-danger blink p-1 m-0 ">Outstanding</small>
                        @endif
                        <div class="row">
                            <div class="col-10">
                                <h6 class="card-title fs-09rem">
                                    {{ $item['clientInfo']->customer_name }}
                                </h6>
                            </div>
                        </div>
                        <small class="card-text mb-1"><b>Group:</b> {{ $item['clientInfo']->group_name }}</small><br>
                        <small class="card-text mb-1"><b>District:</b>
                            {{ $item['clientInfo']->district }}</small><br>
                        <small class="card-text mb-1"><b>Contact:</b>
                            {{ $item['clientInfo']->contact_person }}</small><br>
                        <small class="card-text mb-1"><b>Phone:</b>
                            {{ $item['clientInfo']->contact_mobile }}</small><br>
                        <small class="card-text mb-1"><b>Source:</b> {{ $item['source']->source_name }}</small><br>
                        <small class="card-text mb-1"><b>Created By:</b> {{ $item['createdBy']->user_name }}</small>
                        <div>
                            <a href="">
                                <button type="button"
                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <!----------------------------Lost Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Lost</h6>
            @if (count($lostStage) <= 0)
                <p class="text-danger">No Lost Found</p>
            @endif
            @foreach ($lostStage as $item)
                <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                                <h6 class="card-title fs-09rem">
                                    {{ $item['clientInfo']->customer_name }}
                                </h6>
                            </div>
                        </div>
                        <small class="card-text mb-1"><b>Group:</b> {{ $item['clientInfo']->group_name }}</small><br>
                        <small class="card-text mb-1"><b>District:</b>
                            {{ $item['clientInfo']->district }}</small><br>
                        <small class="card-text mb-1"><b>Contact:</b>
                            {{ $item['clientInfo']->contact_person }}</small><br>
                        <small class="card-text mb-1"><b>Phone:</b>
                            {{ $item['clientInfo']->contact_mobile }}</small><br>
                        <small class="card-text mb-1"><b>Source:</b> {{ $item['source']->source_name }}</small><br>
                        <small class="card-text mb-1"><b>Created By:</b> {{ $item['createdBy']->user_name }}</small>
                        <div>
                            <a href="">
                                <button type="button"
                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
