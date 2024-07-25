@include('layouts.navbar')
<div>
    @if (session('success'))
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: "<?= session('success') ?>",
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    @endif
</div>

<div class="container mb-3 mt-2">
    <center>
        <h4 class="mt-3">Lead Form</h4>
    </center>
    <hr>
    <form action="{{ route('newLeadForm') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">
                    Company Name <span class="text-danger">*</span>
                </label>
                <select class="form-select fs-08rem" aria-label="clientId" name="clientId" id="clientId"
                    onchange="fetchClientInfo()" required>
                    <option selected disabled value="">Select One</option>
                    @foreach ($companyList as $item)
                        @if (session('errorsData') && session('errorsData')['clientId'] == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->customer_name }}-{{ $item->sap_id }}
                            </option>
                        @else
                            <option value="{{ $item->id }}">{{ $item->customer_name }}-{{ $item->sap_id }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    Group Name <span class="text-danger">*</span>
                </label>
                <p id="groupName"></p>
            </div>
            <div class="col-md-6">
                <label class="form-label">
                    Address <span class="text-danger">*</span>
                </label>
                <p id="address"></p>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    Division <span class="text-danger">*</span>
                </label>
                <p id="division"></p>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    District <span class="text-danger">*</span>
                </label>
                <p id="district"></p>
            </div>
            <div class="col-md-3">
                {{-- <label class="form-label">
                    Zone <span class="text-danger">*</span>
                </label>
                <p id="zone"></p> --}}
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <label class="form-label">
                    TIN
                </label>
                <p id="tin"></p>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    BIN
                </label>
                <p id="bin"></p>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    Trade License
                </label>
                <p id="trade"></p>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <label class="form-label">
                    Contact Person <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control fs-08rem" name="contactPerson" id="contactPerson"
                    @if (session('errorsData')) value="{{ session('errorsData')['contactPerson'] }}" @endif
                    required>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    Contact Mobile <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control fs-08rem" name="contactMobile" id="contactMobile"
                    @if (session('errorsData')) value="{{ session('errorsData')['contactMobile'] }}" @endif
                    required>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    Contact Email
                </label>
                <input type="text" class="form-control fs-08rem" name="contactEmail" id="contactEmail"
                    @if (session('errorsData')) value="{{ session('errorsData')['contactEmail'] }}" @endif>
            </div>
            <div class="col-md-3 fs-07rem mt-3"><input type="checkbox" name="infoPermChange" id="infoPermChange">&nbsp; Permanent Change Contact Info</div>
            <div class="col-md-3">
                <label class="form-label">
                    Lead Source <span class="text-danger">*</span>
                </label>
                <select class="form-control fs-08rem" aria-label="Lead Source" name="leadSource" id="leadSource"
                    required>
                    <option selected disabled value="">Select One</option>
                    @foreach ($sourceList as $item)
                        @if (session('errorsData') && session('errorsData')['leadSource'] == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->source_name }}</option>
                        @else
                            <option value="{{ $item->id }}">{{ $item->source_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-9">
                <label class="form-label">
                    Product Requirement <span class="text-danger">*</span>
                </label>
                <textarea name="clientReq" id="clientReq" cols="30" rows="3" class="form-control" required> @if (session('errorsData'))
{{ session('errorsData')['clientReq'] }}
@endif
</textarea>
            </div>
        </div>
        <center><button type="submit" class="btn btn-sm btn-darkblue fs-09rem mt-3">Create New Lead</button>
        </center>
    </form>
</div>

<script>
    $("#clientId").select2({
        allowClear: false
    });
</script>

<script>
    function fetchClientInfo() {
        let clientId = $('#clientId').val();

        fetch('/getSingleClientInfo/'+clientId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(json => {
                console.log(json);
                let groupName = json.group_name;
                let address = json.address;
                let division = json.division;
                let district = json.district;
                let zone = json.zone;
                let tin = json.tin;
                let bin = json.bin;
                let trade = json.trade_license;
                let contactPerson = json.contact_person;
                let contactMobile = json.contact_mobile;
                let contactEmail = json.contact_email;
                
                if(division == ''){
                    division = 'N/A';
                }
                if(zone == 'none'){
                    zone = 'N/A';
                }

                $('#groupName').text(groupName);
                $('#address').text(address);
                $('#division').text(division);
                $('#district').text(district);
                $('#zone').text(zone);
                $('#tin').text(tin);
                $('#bin').text(bin);
                $('#trade').text(trade);
                $('#contactPerson').val(contactPerson);
                $('#contactMobile').val(contactMobile);
                $('#contactEmail').val(contactEmail);

            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }
</script>
