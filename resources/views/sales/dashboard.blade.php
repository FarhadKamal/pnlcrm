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
                            <?php $buttonLabel = 'Approve'; ?>
                        @endif
                        @if ($item->current_subStage == 'SUBMIT')
                            <small class="badge badge-info blink p-1 m-0 ">Waiting for Submit</small>
                            <?php $buttonLabel = 'Submit'; ?>
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
                            <a href="{{ route('quotationCheck', ['leadId' => $item->id]) }}">
                                <button type="button"
                                    class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100">{{ $buttonLabel }}</button>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <!----------------------------Booking Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Booking</h6>
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
                            <h6 class="card-title">
                                MM Trade
                            </h6>
                        </div>
                    </div>
                    <small class="card-text mb-1"><b>Group:</b> MM Group</small><br>
                    {{-- <small class="card-text mb-1"><b>Intersted In:</b> {{ $interested }}</small><br> --}}
                    <small class="card-text mb-1"><b>District:</b>
                        Chattogram</small><br>
                    <small class="card-text mb-1"><b>Contact:</b> Mr. Jitu</small><br>
                    <small class="card-text mb-1"><b>Phone:</b> 01844556655</small><br>
                    <small class="card-text mb-1"><b>Source:</b> Mr. Jitu</small><br>
                    <small class="card-text mb-1"><b>Created By:</b> Noushad</small>
                </div>
            </div>
        </div>


        <!----------------------------Ready To Deliver Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Ready To Deliver</h6>
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
                            <h6 class="card-title">
                                MM Trade
                            </h6>
                        </div>
                    </div>
                    <small class="card-text mb-1"><b>Group:</b> MM Group</small><br>
                    {{-- <small class="card-text mb-1"><b>Intersted In:</b> {{ $interested }}</small><br> --}}
                    <small class="card-text mb-1"><b>District:</b>
                        Chattogram</small><br>
                    <small class="card-text mb-1"><b>Contact:</b> Mr. Jitu</small><br>
                    <small class="card-text mb-1"><b>Phone:</b> 01844556655</small><br>
                    <small class="card-text mb-1"><b>Source:</b> Mr. Jitu</small><br>
                    <small class="card-text mb-1"><b>Created By:</b> Noushad</small>
                </div>
            </div>
        </div>


        <!----------------------------WON Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Won</h6>
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
                            <h6 class="card-title">
                                MM Trade
                            </h6>
                        </div>
                    </div>
                    <small class="card-text mb-1"><b>Group:</b> MM Group</small><br>
                    {{-- <small class="card-text mb-1"><b>Intersted In:</b> {{ $interested }}</small><br> --}}
                    <small class="card-text mb-1"><b>District:</b>
                        Chattogram</small><br>
                    <small class="card-text mb-1"><b>Contact:</b> Mr. Jitu</small><br>
                    <small class="card-text mb-1"><b>Phone:</b> 01844556655</small><br>
                    <small class="card-text mb-1"><b>Source:</b> Mr. Jitu</small><br>
                    <small class="card-text mb-1"><b>Created By:</b> Noushad</small>
                </div>
            </div>
        </div>


        <!----------------------------Lost Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Lost</h6>
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
                            <h6 class="card-title">
                                MM Trade
                            </h6>
                        </div>
                    </div>
                    <small class="card-text mb-1"><b>Group:</b> MM Group</small><br>
                    {{-- <small class="card-text mb-1"><b>Intersted In:</b> {{ $interested }}</small><br> --}}
                    <small class="card-text mb-1"><b>District:</b>
                        Chattogram</small><br>
                    <small class="card-text mb-1"><b>Contact:</b> Mr. Jitu</small><br>
                    <small class="card-text mb-1"><b>Phone:</b> 01844556655</small><br>
                    <small class="card-text mb-1"><b>Source:</b> Mr. Jitu</small><br>
                    <small class="card-text mb-1"><b>Created By:</b> Noushad</small>
                </div>
            </div>
        </div>
    </div>
</div>
