<div class="modal fade" id="newLeadModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    style="overflow: scroll">
    <div class="modal-dialog modal-xl">
        <div class="modal-content " ShowTab="1" id="121" style="overflow: scroll;">
            <div class="modal-header border p-2">
                <h5 class="modal-title p-0 m-0" id="exampleModalLabel">
                    <center>
                        <h6>Lead Infomation</h6>
                    </center>
                </h5>
                <div id="leadEditLink" class="m-auto">

                </div>

                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fs-09rem">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Company</p>
                            <small class="col-8 col-md-8" id="leadModalCompany"></small>
                        </div>
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Group</p>
                            <small class="col-8 col-md-8" id="leadModalGroup"></small>
                        </div>
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Address</p>
                            <small class="col-8 col-4 col-md-8" id="leadModalAddress"></small>
                        </div>
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Contact Person</p>
                            <small class="col-8 col-md-8" id="leadModalPerson"></small>
                        </div>
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Contact Phone</p>
                            <small class="col-8 col-md-8" id="leadModalPhone"></small>
                        </div>
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Contact Email</p>
                            <small class="col-8 col-md-8" id="leadModalEmail"></small>
                        </div>

                    </div>
                    <div class="col-md-7">
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Zone</p>
                            <small class="col-8 col-md-8" id="leadModalZone"></small>
                        </div>
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">District</p>
                            <small class="col-8 col-md-8" id="leadModalDistrict"></small>
                        </div>
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Division</p>
                            <small class="col-8 col-md-8" id="leadModalDivision"></small>
                        </div>
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Lead Source</p>
                            <small class="col-8 col-md-8" id="leadModalSource"></small>
                        </div>
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Product Requirement</p>
                            <small class="col-8 col-md-8" id="leadModalPR"></small>
                        </div>
                        <div class="row border-bottom p-1">
                            <p class="col-4 col-md-4 text-muted m-0">Created By</p>
                            <small class="col-8 col-md-8" id="leadModalCreatedBy"></small>
                        </div>
                    </div>
                </div>

                {{-- @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'leadAssign')) --}}
                <div class="mt-3">
                    <center>
                        <h6>LEAD ASSIGN</h6>
                    </center>
                    <form action="#" method="POST">
                        @csrf
                        <input type="text" name="leadModal_leadId" id="leadModal_leadId" hidden>
                        <table class="table table-hover table-bordered fs-08rem p-0 m-0" id="workLoadTable">
                            <thead>
                                <tr>
                                    <th class="p-1 text-center">User</th>
                                    <th class="p-1 text-center">Designation</th>
                                    <th class="p-1 text-center">Location</th>
                                    <th class="p-1 text-center">Workload</th>
                                    <th class="p-1 text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody id="workLoadTableTbody">
                                {{-- DUMMY DATA  --}}
                                <tr>
                                    <td class="p-1">Jitu</td>
                                    <td class="p-1">Sr Sales</td>
                                    <td class="p-1">Dhaka</td>
                                    <td class="p-1"></td>
                                    <td class="p-1 text-center"><button
                                            class="btn btn-darkblue fs-06rem p-1">Assign</button></td>
                                </tr>
                                <tr>
                                    <td class="p-1">Nousahd</td>
                                    <td class="p-1">Sr Sales</td>
                                    <td class="p-1">Chattogram</td>
                                    <td class="p-1"></td>
                                    <td class="p-1 text-center"><button
                                            class="btn btn-darkblue fs-06rem p-1">Assign</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
</div>

<script>
    function dataShowModal(data) {
        console.log(data);
        $('#leadModal_leadId').val(data.id);
        $('#leadModalCompany').html(data.client_info['customer_name']);
        $('#leadModalGroup').html(data.client_info['group_name']);
        // $('#leadModalAddress').html('Pahartoli');
        $('#leadModalPerson').html(data.client_info['contact_person']);
        $('#leadModalPhone').html(data.client_info['contact_mobile']);
        // $('#leadModalZone').html('Pahartoli');
        $('#leadModalDistrict').html(data.client_info['district']);
        // $('#leadModalDivision').html('Chattogram');
        $('#leadModalSource').html(data.source['source_name']);
        $('#leadModalPR').html(data.product_requirement);
        $('#leadModalCreatedBy').html(data.created_by['user_name']);

        // $("#assign_to").selectedIndex = 0;

        $('#leadEditLink').empty();
        let leadId = data.id;
        let editDiv = document.getElementById('leadEditLink');
        let domain = window.location.origin;
        const aTag = document.createElement("a");
        aTag.href = domain + '/leadEdit/' + leadId;
        let btn = document.createElement('button');
        btn.innerText = "Edit";
        btn.classList = "btn btn-sm btn-darkblue fs-06rem p-1";
        aTag.appendChild(btn);
        editDiv.appendChild(aTag);

        // workLoadCheck();
    }
</script>
{{-- 
<script>
    function workLoadCheck() {
        $('#workLoadTableTbody').empty();
        $.ajax({
            type: "GET",
            url: "/workLoadCheck",
            success: function(data) {
                document.getElementById('workLoadTableTbody').innerHTML = data;

            }
        });
    }
</script> --}}
