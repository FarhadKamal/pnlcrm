@include('layouts.navbar')
<div class="container-fluid mt-3 mb-5" style="position: static;">
    <style>
        .stageLabel {
            /* position: fixed; */
            /* width: max-content; */
            /* width: 11rem; */

            position: sticky;
            top: 5rem;
            left: 0;
            width: 100%;
        }

        .stageCardListDiv {
            /* position: static; */
            /* padding-top: 2rem; */

            position: static;
            top: 0;
            /* padding-top: 2rem; */
        }

        .stageLabelMobileSelection {
            position: sticky;
            top: 5rem;
            left: 0;
            width: 100%;
        }
    </style>

    @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'salesPerson'))
        <div class="row m-1">
            <div class="col-md-3 p-1">
                <div class="card bg-darkblue">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-bullseye text-white fa-2x"></i>
                                <p class="mb-0 fs-06rem text-white">{{ $targetLabel }}</p>
                            </div>
                            <div class="text-end text-white">
                                @if (isset($currentTarget))
                                    <h5>{{ number_format((float) $currentTarget, 2, '.', ',') }}</h5>
                                @else
                                    <h5>N/A</h5>
                                @endif
                                <p class="mb-0 fs-08rem">Target (BDT)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="card bg-info">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-bangladeshi-taka-sign text-white fa-2x"></i>
                                <p class="mb-0 fs-06rem text-white">{{ $targetLabel }}</p>
                            </div>
                            <div class="text-end text-white">
                                @if (isset($currentSales))
                                    <h5>{{ number_format((float) $currentSales, 2, '.', ',') }}</h5>
                                @else
                                    <h5>N/A</h5>
                                @endif
                                <p class="mb-0 fs-08rem">Sales (BDT)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="card bg-dark">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-award text-white fa-2x"></i>
                                <p class="mb-0 fs-06rem text-white">{{ $targetLabel }}</p>
                            </div>
                            <div class="text-end text-white">
                                @if (isset($currentSales) && isset($currentTarget))
                                    @php
                                        $achievement = ($currentSales / $currentTarget) * 100;
                                    @endphp
                                @else
                                    @php
                                        $achievement = 0;
                                    @endphp
                                @endif

                                <h5>{{ number_format((float) $achievement, 2, '.', ',') }}%</h5>
                                <p class="mb-0 fs-08rem">Achievement</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 p-1">
                <div class="card bg-danger">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-rotate-left text-white fa-2x"></i>
                            </div>
                            <div class="text-end text-white">
                                <h5>{{ number_format((float) $currentNetDue, 2, '.', ',') }}</h5>
                                <p class="mb-0 fs-08rem">Total Due (BDT)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!---------------------------- DropDown for Mobile view ---------------- -->

    <div class="col mb-3 d-block d-sm-none stageLabelMobileSelection">
        {{-- <form method="POST" action="{{route('changeMobileViewStage')}}" id="category-form">
            @csrf --}}
        <select class="form-select" name="mobileSalesSatges" id="mobileSalesSatges" onchange="changeMobileStage()">
            <option value="lead">Lead</option>
            <option value="deal">Deal</option>
            <option value="quotation">Quotation</option>
            <option value="booking">Booking</option>
            <option value="delivery">Ready To Deliver</option>
            <option value="won">Won</option>
            <option value="lost">Lost</option>
        </select>
        {{-- </form> --}}

    </div>

    <div class="d-flex  flex-row salesStageFlex" id="salesStageFlex">
        <!----------------------------Lead Column---------------- -->
        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'leadStage') ||
                App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'leadStageAll'))
            <div class="col-sm p-1 stageColumn" id="leadColumn">
                <h6 class=" rounded  p-1 bg-secondary text-white text-center mb-3 stageLabel">New Customer
                    {{-- @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'salesPerson'))
                        <a href="{{ route('newLeadForm') }}">
                            <badge class="badge badge-info p-1 rounded-pill  fs-07rem blink">+Add New</badge>
                        </a>
                    @endif --}}
                </h6>
                <select id="filterLead" onchange="filterTaskDash('lead')" class="form-select p-1 fs-08rem">
                    <option value="all">View All Lead</option>
                    <option value="task">View Pending Task</option>
                </select>
                <div class="stageCardListDiv">
                    @if (count($leadStage) <= 0)
                        <p class="text-danger">No Lead Found</p>
                    @endif
                    @foreach ($leadStage as $item)
                        <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;"
                            id="lead{{ $item->id }}">
                            <div class="card-body">
                                @if ($item->current_subStage == 'EDIT')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for Update &
                                        Re-Submission</small>
                                @endif
                                @if ($item->current_subStage == 'CHECKCUSDOC')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for Document
                                        Check</small>
                                @endif
                                @if ($item->current_subStage == 'ASSIGN')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for Approval</small>
                                @endif
                                @if ($item->current_subStage == 'SAPIDSET')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for SAP ID creation</small>
                                @endif
                                <div class="row">
                                    <div class="col-10">
                                        <small class="badge badge-success">Lead ID: {{ $item->id }}</small>
                                        <h6 class="card-title fs-09rem">
                                            {{ $item['clientInfo']->customer_name }}
                                        </h6>
                                    </div>
                                </div>
                                <small class="card-text mb-1"><b>Group:</b>
                                    {{ $item['clientInfo']->group_name }}</small><br>
                                <small class="card-text mb-1"><b>District:</b>
                                    {{ $item['clientInfo']->district }}</small><br>
                                <small class="card-text mb-1"><b>Contact:</b>
                                    {{ $item->lead_person }}</small><br>
                                <small class="card-text mb-1"><b>Phone:</b>
                                    {{ $item->lead_phone }}</small><br>
                                <small class="card-text mb-1"><b>Source:</b>
                                    {{ $item['source']->source_name }}</small><br>
                                <small class="card-text mb-1"><b>Created By:</b>
                                    {{ $item['createdBy']->user_name }}</small>
                                <div class="d-none">
                                    <?php $encoded = json_encode($item); ?>
                                    <button type="button" data-mdb-toggle="modal" data-mdb-target="#newLeadModal"
                                        class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100"
                                        onclick='dataShowModal(<?= $encoded ?>)'>{{ $leadButtonLabel }}</button>
                                </div>
                                <div>
                                    @if ($item->current_subStage == 'EDIT')
                                        @if ($item->clientInfo->assign_to == Auth()->user()->assign_to)
                                            <a href="{{ route('customerInfo', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Update
                                                    Customer</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                            </a>
                                        @endif
                                    @endif
                                    @if ($item->current_subStage == 'CHECKCUSDOC')
                                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'customerDocCheck'))
                                            <a href="{{ route('customerDocCheck', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Document
                                                    Check</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                            </a>
                                        @endif
                                    @endif
                                    @if ($item->current_subStage == 'ASSIGN')
                                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'leadAssign'))
                                            <?php $encoded = json_encode($item); ?>
                                            <button type="button" data-mdb-toggle="modal"
                                                data-mdb-target="#newLeadModal"
                                                class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100"
                                                onclick='dataShowModal(<?= $encoded ?>)'>Approve Customer</button>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                            </a>
                                        @endif
                                    @endif
                                    @if ($item->current_subStage == 'SAPIDSET')
                                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapIDCreation'))
                                            <a href="{{ route('newSapForm', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">SAP
                                                    ID
                                                    SET</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @include('sales.modals.newLeadModal')
                </div>
            </div>
        @endif

        <!----------------------------Deal Column---------------- -->
        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'dealStage') ||
                App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'dealStageAll'))
            <div class="col-sm p-1 stageColumn" id="dealColumn">
                <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 stageLabel">Deal
                    @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'salesPerson'))
                        <a href="{{ route('newLeadForm') }}">
                            <badge class="badge badge-info p-1 rounded-pill  fs-07rem blink">+Add New</badge>
                        </a>
                    @endif
                </h6>
                <div class="stageCardListDiv">
                    @if (count($dealStage) <= 0)
                        <p class="text-danger">No Deal Found</p>
                    @endif
                    @foreach ($dealStage as $item)
                        <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;">
                            <div class="card-body">
                                @if ($item->is_return == 1)
                                    <small class="badge badge-danger blink p-1 m-0 ">Return. Resubmit Deal</small>
                                @endif

                                <div class="row">
                                    <div class="col-10">
                                        <small class="badge badge-success">Lead ID: {{ $item->id }}</small>
                                        <h6 class="card-title fs-09rem">
                                            {{ $item['clientInfo']->customer_name }}
                                        </h6>
                                    </div>
                                </div>
                                <small class="card-text mb-1"><b>Group:</b>
                                    {{ $item['clientInfo']->group_name }}</small><br>
                                <small class="card-text mb-1"><b>District:</b>
                                    {{ $item['clientInfo']->district }}</small><br>
                                <small class="card-text mb-1"><b>Contact:</b>
                                    {{ $item->lead_person }}</small><br>
                                <small class="card-text mb-1"><b>Phone:</b>
                                    {{ $item->lead_phone }}</small><br>
                                <small class="card-text mb-1"><b>Source:</b>
                                    {{ $item['source']->source_name }}</small><br>
                                <small class="card-text mb-1"><b>Created By:</b>
                                    {{ $item['createdBy']->user_name }}</small>

                                <div>
                                    @if (Auth()->user()->assign_to && $item->clientInfo->assign_to == Auth()->user()->assign_to)
                                        <a href="{{ route('dealPage', ['leadId' => $item->id]) }}">
                                            <button type="button"
                                                class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Requirement
                                                &
                                                Choice</button>
                                        </a>
                                    @else
                                        <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                            <button type="button"
                                                class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                        </a>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <!----------------------------Quotation Column---------------- -->
        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'quotationStage') ||
                App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'quotationStageAll'))
            <div class="col-sm p-1 stageColumn" id="quotationColumn">
                <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 stageLabel">Quotation</h6>
                <select id="filterQuotation" onchange="filterTaskDash('quotation')" class="form-select p-1 fs-08rem">
                    <option value="all">View All Lead</option>
                    <option value="task">View Pending Task</option>
                </select>
                <div class="stageCardListDiv">
                    @if (count($quotationStage) <= 0)
                        <p class="text-danger">No Quotation Stage Found</p>
                    @endif
                    @foreach ($quotationStage as $item)
                        <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;"
                            id="quotation{{ $item->id }}">
                            <div class="card-body">
                                @if ($item->current_subStage == 'APPROVE')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for Approval</small>
                                @endif
                                @if ($item->current_subStage == 'MANAGEMENT')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for Managing
                                        Director</small>
                                @endif
                                @if ($item->current_subStage == 'SUBMIT')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for Submit</small>
                                @endif
                                @if ($item->current_subStage == 'FEEDBACK')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for Feddback</small>
                                @endif
                                <div class="row">
                                    <div class="col-10">
                                        <small class="badge badge-success">Lead ID: {{ $item->id }}</small>
                                        <h6 class="card-title fs-09rem">
                                            {{ $item['clientInfo']->customer_name }}
                                        </h6>
                                    </div>
                                </div>
                                <small class="card-text mb-1"><b>Group:</b>
                                    {{ $item['clientInfo']->group_name }}</small><br>
                                <small class="card-text mb-1"><b>District:</b>
                                    {{ $item['clientInfo']->district }}</small><br>
                                <small class="card-text mb-1"><b>Contact:</b>
                                    {{ $item->lead_person }}</small><br>
                                <small class="card-text mb-1"><b>Phone:</b>
                                    {{ $item->lead_phone }}</small><br>
                                <small class="card-text mb-1"><b>Source:</b>
                                    {{ $item['source']->source_name }}</small><br>
                                <small class="card-text mb-1"><b>Created By:</b>
                                    {{ $item['createdBy']->user_name }}</small>
                                <div>
                                    @if ($item->current_subStage == 'APPROVE')
                                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'dealApprove'))
                                            <a href="{{ route('quotationCheck', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Approve</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                            </a>
                                        @endif
                                    @endif
                                    @if ($item->current_subStage == 'MANAGEMENT')
                                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'dealTopApprove'))
                                            <a href="{{ route('quotationCheck', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-danger  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Managmement</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                            </a>
                                        @endif
                                    @endif
                                    @if ($item->current_subStage == 'SUBMIT')
                                        @if ($item->clientInfo->assign_to == Auth()->user()->assign_to)
                                            <a href="{{ route('quotationCheck', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Submit</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                            </a>
                                        @endif
                                    @endif
                                    @if ($item->current_subStage == 'FEEDBACK')
                                        @if ($item->clientInfo->assign_to == Auth()->user()->assign_to)
                                            <?php $encoded = json_encode($item); ?>
                                            {{-- <button type="button" data-mdb-toggle="modal"
                                                data-mdb-target="#quotationFeedbackModal"
                                                class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100"
                                                onclick='quotationFeedbackShowModal(<?= $encoded ?>)'>Quotation
                                                Feedback
                                            </button> --}}
                                            <a href="{{ route('quotationFeedback', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Quotation
                                                    Feedback</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
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
            </div>
        @endif

        <!----------------------------Booking Column---------------- -->
        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'bookingStage') ||
                App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'bookingStageAll') ||
                App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'bookingStageTask'))
            <div class="col-sm p-1 stageColumn" id="bookingColumn">
                <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 stageLabel">Booking</h6>
                <select id="filterBook" onchange="filterTaskDash('booking')" class="form-select p-1 fs-08rem">
                    <option value="all">View All Lead</option>
                    <option value="task">View Pending Task</option>
                </select>
                <div class="stageCardListDiv">
                    @if (count($bookingStage) <= 0)
                        <p class="text-danger">No Booking Found</p>
                    @endif
                    @foreach ($bookingStage as $item)
                        <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;"
                            id="booking{{ $item->id }}">
                            <div class="card-body">
                                @if ($item->current_subStage == 'CHECKCUSDOC')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for Document
                                        Check</small>
                                @endif
                                @if ($item->current_subStage == 'SAPIDSET')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for SAP ID creation</small>
                                @endif
                                @if ($item->current_subStage == 'DISCOUNTSET')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for discount set</small>
                                @endif
                                @if ($item->current_subStage == 'CREDITSET')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for credit set</small>
                                @endif
                                @if ($item->current_subStage == 'CREDITHOLD')
                                    <small class="badge badge-danger blink p-1 m-0 ">Credit Set Hold</small>
                                @endif
                                <div class="row">
                                    <div class="col-10">
                                        <small class="badge badge-success">Lead ID: {{ $item->id }}</small>
                                        <h6 class="card-title fs-09rem">
                                            {{ $item['clientInfo']->customer_name }}
                                        </h6>
                                    </div>
                                </div>
                                <small class="card-text mb-1"><b>Group:</b>
                                    {{ $item['clientInfo']->group_name }}</small><br>
                                <small class="card-text mb-1"><b>District:</b>
                                    {{ $item['clientInfo']->district }}</small><br>
                                <small class="card-text mb-1"><b>Contact:</b>
                                    {{ $item->lead_person }}</small><br>
                                <small class="card-text mb-1"><b>Phone:</b>
                                    {{ $item->lead_phone }}</small><br>
                                <small class="card-text mb-1"><b>Source:</b>
                                    {{ $item['source']->source_name }}</small><br>
                                <small class="card-text mb-1"><b>Created By:</b>
                                    {{ $item['createdBy']->user_name }}</small>
                                <div>
                                    @if ($item->current_subStage == 'CHECKCUSDOC')
                                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'customerDocCheck'))
                                            <a href="{{ route('customerDocCheck', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Document
                                                    Check</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                            </a>
                                        @endif
                                    @endif
                                    @if ($item->current_subStage == 'SAPIDSET')
                                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapIDCreation'))
                                            <a href="{{ route('newSapForm', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">SAP
                                                    ID
                                                    SET</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                            </a>
                                        @endif
                                    @endif
                                    @if ($item->current_subStage == 'DISCOUNTSET')
                                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapDiscountSet'))
                                            <a href="{{ route('discountSetForm', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">SAP
                                                    Discount Set</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
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
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
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
                                                <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                    <button type="button"
                                                        class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                                </a>
                                            @endif
                                        @endif
                                    @endif

                                    @if ($item->current_subStage == 'CREDITHOLD')
                                        <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                            <button type="button"
                                                class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Details</button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!----------------------------Ready To Deliver Column---------------- -->
        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'deliveryStage') ||
                App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'deliveryStageAll'))
            <div class="col-sm p-1 stageColumn" id="deliveryColumn">
                <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 stageLabel">Ready To Deliver</h6>
                <select id="filterDelivery" onchange="filterTaskDash('delivery')" class="form-select p-1 fs-08rem">
                    <option value="all">View All Lead</option>
                    <option value="task">View Pending Task</option>
                </select>
                <div class="stageCardListDiv">
                    @if (count($deliveryStage) <= 0)
                        <p class="text-danger">No Delivery Found</p>
                    @endif
                    @foreach ($deliveryStage as $item)
                        <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;"
                            id="delivery{{ $item->id }}">
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
                                        <small class="badge badge-success">Lead ID: {{ $item->id }}</small>
                                        <h6 class="card-title fs-09rem">
                                            {{ $item['clientInfo']->customer_name }}
                                        </h6>
                                    </div>
                                </div>
                                <small class="card-text mb-1"><b>Group:</b>
                                    {{ $item['clientInfo']->group_name }}</small><br>
                                <small class="card-text mb-1"><b>District:</b>
                                    {{ $item['clientInfo']->district }}</small><br>
                                <small class="card-text mb-1"><b>Contact:</b>
                                    {{ $item->lead_person }}</small><br>
                                <small class="card-text mb-1"><b>Phone:</b>
                                    {{ $item->lead_phone }}</small><br>
                                <small class="card-text mb-1"><b>Source:</b>
                                    {{ $item['source']->source_name }}</small><br>
                                <small class="card-text mb-1"><b>Created By:</b>
                                    {{ $item['createdBy']->user_name }}</small>
                                <div>
                                    @if ($item->current_subStage == 'DISCOUNTSET')
                                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapDiscountSet'))
                                            <a href="{{ route('discountSetForm', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">SAP
                                                    Discount Set</button>
                                            </a>
                                        @else
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
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
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
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
                                            <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
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
            </div>
        @endif

        <!----------------------------WON Column---------------- -->
        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'wonStage') ||
                App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'wonStageAll'))
            <div class="col-sm p-1 stageColumn" id="wonColumn">
                <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 stageLabel">Won</h6>
                <div class="stageCardListDiv">
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
                                        <small class="badge badge-success">Lead ID: {{ $item->id }}</small>
                                        <h6 class="card-title fs-09rem">
                                            {{ $item['clientInfo']->customer_name }}
                                        </h6>
                                    </div>
                                </div>
                                <small class="card-text mb-1"><b>Group:</b>
                                    {{ $item['clientInfo']->group_name }}</small><br>
                                <small class="card-text mb-1"><b>District:</b>
                                    {{ $item['clientInfo']->district }}</small><br>
                                <small class="card-text mb-1"><b>Contact:</b>
                                    {{ $item->lead_person }}</small><br>
                                <small class="card-text mb-1"><b>Phone:</b>
                                    {{ $item->lead_phone }}</small><br>
                                <small class="card-text mb-1"><b>Source:</b>
                                    {{ $item['source']->source_name }}</small><br>
                                <small class="card-text mb-1"><b>Created By:</b>
                                    {{ $item['createdBy']->user_name }}</small>
                                <div>
                                    <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                        <button type="button"
                                            class="btn btn-sm btn-success  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Win
                                            Log</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!----------------------------Lost Column---------------- -->
        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'lostStage') ||
                App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'lostStageAll'))
            <div class="col-sm p-1 stageColumn" id="lostColumn">
                <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 stageLabel">Lost</h6>
                <div class="stageCardListDiv">
                    @if (count($lostStage) <= 0)
                        <p class="text-danger">No Lost Found</p>
                    @endif
                    @foreach ($lostStage as $item)
                        <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;">
                            <div class="card-body">
                                @if ($item->current_subStage == 'RETURNCASH')
                                    <small class="badge badge-info blink p-1 m-0 ">Waiting for Cash Return</small>
                                @endif
                                <div class="row">
                                    <div class="col-10">
                                        <small class="badge badge-success">Lead ID: {{ $item->id }}</small>
                                        <h6 class="card-title fs-09rem">
                                            {{ $item['clientInfo']->customer_name }}
                                        </h6>
                                    </div>
                                </div>
                                <small class="card-text mb-1"><b>Group:</b>
                                    {{ $item['clientInfo']->group_name }}</small><br>
                                <small class="card-text mb-1"><b>District:</b>
                                    {{ $item['clientInfo']->district }}</small><br>
                                <small class="card-text mb-1"><b>Contact:</b>
                                    {{ $item->lead_person }}</small><br>
                                <small class="card-text mb-1"><b>Phone:</b>
                                    {{ $item->lead_phone }}</small><br>
                                <small class="card-text mb-1"><b>Source:</b>
                                    {{ $item['source']->source_name }}</small><br>
                                <small class="card-text mb-1"><b>Created By:</b>
                                    {{ $item['createdBy']->user_name }}</small>
                                <div>
                                    @if ($item->current_subStage == 'RETURNCASH')
                                        @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'verifyTransaction'))
                                            <a href="{{ route('returnTransaction', ['leadId' => $item->id]) }}">
                                                <button type="button"
                                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Return
                                                    Transaction</button>
                                            </a>
                                        @else
                                            @if ($item->clientInfo->assign_to == Auth()->user()->assign_to)
                                                <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Lost
                                                        Log</button>
                                                </a>
                                            @else
                                                <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Lost
                                                        Log</button>
                                                </a>
                                            @endif
                                        @endif
                                    @else
                                        <a href="{{ route('detailsLog', ['leadId' => $item->id]) }}">
                                            <button type="button"
                                                class="btn btn-sm btn-danger  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">Lost
                                                Log</button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<?php
if (isset($_COOKIE['MobileStage'])) {
    $sessionMobileStage = $_COOKIE['MobileStage'];
    // echo $sessionMobileStage;
} else {
    $sessionMobileStage = null;
}
?>

<script>
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        $(document).ready(function() {
            if ({{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'lostStageAll') }} ||
                {{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'lostStage') }}) {
                const $select = document.querySelector('#mobileSalesSatges');
                $select.value = 'lost';
            }
            if ({{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'wonStage') }} ||
                {{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'wonStageAll') }}) {
                const $select = document.querySelector('#mobileSalesSatges');
                $select.value = 'won';
            }
            if ({{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'deliveryStage') }} ||
                {{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'deliveryStageAll') }}) {
                const $select = document.querySelector('#mobileSalesSatges');
                $select.value = 'delivery';
            }
            if ({{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'bookingStage') }} ||
                {{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'bookingStageAll') }}) {
                const $select = document.querySelector('#mobileSalesSatges');
                $select.value = 'booking';
            }
            if ({{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'quotationStage') }} ||
                {{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'quotationStageAll') }}) {
                const $select = document.querySelector('#mobileSalesSatges');
                $select.value = 'quotation';
            }
            if ({{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'dealStage') }} ||
                {{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'dealStageAll') }}) {
                const $select = document.querySelector('#mobileSalesSatges');
                $select.value = 'deal';
            }
            if ({{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'leadStage') }} ||
                {{ App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'leadStageAll') }}) {
                const $select = document.querySelector('#mobileSalesSatges');
                $select.value = 'lead';
            }

            if ("<?= $sessionMobileStage ?>") {
                const $select = document.querySelector('#mobileSalesSatges');
                $select.value = "<?= $sessionMobileStage ?>";
            }

            changeMobileStage();
        });
    }

    function changeMobileStage() {
        let stage = $('#mobileSalesSatges').val();
        let columns = document.getElementsByClassName('stageColumn');
        for (let i = 0; i < columns.length; i++) {
            columns[i].style.display = "none";
        }

        switch (stage) {
            case 'lead':
                document.getElementById("leadColumn").style.display = "block";
                break;
            case 'deal':
                document.getElementById("dealColumn").style.display = "block";
                break;
            case 'quotation':
                document.getElementById("quotationColumn").style.display = "block";
                break;
            case 'booking':
                document.getElementById("bookingColumn").style.display = "block";
                break;
            case 'delivery':
                document.getElementById("deliveryColumn").style.display = "block";
                break;
            case 'won':
                document.getElementById("wonColumn").style.display = "block";
                break;
            case 'lost':
                document.getElementById("lostColumn").style.display = "block";
        }
        // console.log($('#mobileSalesSatges').val());

        document.cookie = escape('MobileStage') + "=" + escape(stage);
    }
</script>

<script>
    function filterLead(filterSelection) {
        let allLead = <?php echo $encodedLeadStage; ?>;
        let userInfo = JSON.parse('<?php echo Auth()->user(); ?>');
        allLead.forEach(element => {
            let leadDivId = 'lead' + element.id;
            if (filterSelection == 'task') {
                if (element.current_subStage == 'EDIT') {
                    if (element.client_info.assign_to == userInfo.assign_to) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'CHECKCUSDOC') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'customerDocCheck'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'ASSIGN') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'leadAssign'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'SAPIDSET') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapIDCreation'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
            } else {
                document.getElementById(leadDivId).style.display = 'block';
            }
        });
    }

    function filterQuotation(filterSelection) {
        let allLead = <?php echo $encodedQuotationStage; ?>;
        let userInfo = JSON.parse('<?php echo Auth()->user(); ?>');
        allLead.forEach(element => {
            let leadDivId = 'quotation' + element.id;
            if (filterSelection == 'task') {
                if (element.current_subStage == 'APPROVE') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'dealApprove'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'MANAGEMENT') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'dealTopApprove'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'SUBMIT') {
                    if (element.client_info.assign_to == userInfo.assign_to) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'FEEDBACK') {
                    if (element.client_info.assign_to == userInfo.assign_to) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                // Final Check Owner
                if (element.client_info.assign_to == userInfo.assign_to) {
                    document.getElementById(leadDivId).style.display = 'block';
                }
            } else {
                document.getElementById(leadDivId).style.display = 'block';
            }
        });
    }

    function filterBooking(filterSelection) {
        let allLead = <?php echo $encodedBookingStage; ?>;
        let userInfo = JSON.parse('<?php echo Auth()->user(); ?>');
        allLead.forEach(element => {
            let leadDivId = 'booking' + element.id;
            if (filterSelection == 'task') {
                if (element.current_subStage == 'CHECKCUSDOC') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'customerDocCheck'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'SAPIDSET') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapIDCreation'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'CREDITSET' || element.current_subStage == 'CREDITHOLD') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapCreditSet'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'TRANSACTION') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'verifyTransaction'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                // Final Check Owner
                if (element.client_info.assign_to == userInfo.assign_to) {
                    document.getElementById(leadDivId).style.display = 'block';
                }
            } else {
                document.getElementById(leadDivId).style.display = 'block';
            }
        });
    }

    function filterDelivery(filterSelection) {
        let allLead = <?php echo $encodedDeliveryStage; ?>;
        let userInfo = JSON.parse('<?php echo Auth()->user(); ?>');
        allLead.forEach(element => {
            let leadDivId = 'delivery' + element.id;
            if (filterSelection == 'task') {
                if (element.current_subStage == 'DISCOUNTSET') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapDiscountSet'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'INVOICE') {
                    if (@json(App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'sapInvoiceSet'))) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                if (element.current_subStage == 'READY') {
                    if (element.client_info.assign_to == userInfo.assign_to) {
                        document.getElementById(leadDivId).style.display = 'block';
                    } else {
                        document.getElementById(leadDivId).style.display = 'none';
                    }
                }
                // Final Check Owner
                if (element.client_info.assign_to == userInfo.assign_to) {
                    document.getElementById(leadDivId).style.display = 'block';
                }
            } else {
                document.getElementById(leadDivId).style.display = 'block';
            }
        });
    }

    function filterTaskDash(stage) {
        let filterSelection;
        if (stage == 'lead') {
            filterSelection = $('#filterLead').val();
            filterLead(filterSelection);
        }
        if (stage == 'quotation') {
            filterSelection = $('#filterQuotation').val();
            filterQuotation(filterSelection);
        }
        if (stage == 'booking') {
            filterSelection = $('#filterBook').val();
            filterBooking(filterSelection);
        }
        if (stage == 'delivery') {
            filterSelection = $('#filterDelivery').val();
            filterDelivery(filterSelection);
        }
    }
</script>
