@include('layouts.navbar')
<div class="container-fluid mt-3 mb-5">
    <div class="d-flex  flex-row salesStageFlex" id="salesStageFlex">
        
        <!----------------------------Lead Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="leadColumn">
            <h6 class=" rounded  p-1 bg-secondary text-white text-center mb-3 ">Lead <a href="#"><badge class="badge badge-info p-1 rounded-pill  fs-07rem blink">+Add New</badge></a></h6>
            <div class="shadow p-1 mb-3 bg-white rounded fs-08rem" style="width: 7 rem;">
                <div class="card-body">
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
                    <div>
                        <button type="button" data-mdb-toggle="modal" data-mdb-target="#newLeadModal"
                            class="btn btn-sm btn-darkblue  pt-1 pb-1 ps-2 pe-2 fs-06rem w-100"
                            onclick='dataShowModal()'>Assign</button>
                    </div>
                </div>
            </div>
            @include('sales.modals.newLeadModal')
        </div>

        <!----------------------------Deal Column---------------- -->

        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Deal</h6>
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

        <!----------------------------Quotation Column---------------- -->
        <div class="col-sm p-1 stageColumn" id="dealColumn">
            <h6 class="rounded  p-1 bg-secondary text-white text-center mb-3 ">Quotation</h6>
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
