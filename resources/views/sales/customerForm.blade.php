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
        <h4 class="mt-3">New Client Insertion Form</h4>
    </center>
    <hr>
    <form action="{{ route('customerForm') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">
                    Company Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control fs-08rem" name="clientName" id="clientName"
                    @if (session('errorsData')) value="{{ session('errorsData')['clientName'] }}" @endif required>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    Group Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control fs-08rem" name="groupName" id="groupName"
                    @if (session('errorsData')) value="{{ session('errorsData')['groupName'] }}" @endif required>
            </div>
            <div class="col-md-6">
                <label class="form-label">
                    Address <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control fs-08rem" name="clientAddress" id="clientAddress"
                    @if (session('errorsData')) value="{{ session('errorsData')['clientAddress'] }}" @endif
                    required>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    Division <span class="text-danger">*</span>
                </label>
                <select class="form-control fs-08rem" aria-label="Client Zone" name="clientDivision" id="clientDivision"
                    required>
                    <option selected disabled value="">Select One</option>
                    @foreach ($divisionList as $item)
                        @if (session('errorsData') && session('errorsData')['clientDivision'] == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->div_name }}</option>
                        @else
                            <option value="{{ $item->id }}">{{ $item->div_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    District <span class="text-danger">*</span>
                </label>
                <select class="form-control fs-08rem" aria-label="Client Zone" name="clientDistrict" id="clientDistrict"
                    required>
                    <option selected disabled value="">Select One</option>
                    @foreach ($districtList as $item)
                        @if (session('errorsData') && session('errorsData')['clientDistrict'] == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->dist_name }}</option>
                        @else
                            <option value="{{ $item->id }}">{{ $item->dist_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                {{-- <label class="form-label">
                    Zone <span class="text-danger">*</span>
                </label>
                <select class="form-control fs-08rem" aria-label="Client Zone" name="clientZone" id="clientZone"
                    required>
                    <option selected disabled value="">Select One</option>
                    @foreach ($zoneList as $item)
                        @if (session('errorsData') && session('errorsData')['clientZone'] == $item->id)
                            <option value="{{ $item->id }}" selected>{{ $item->zone_name }}</option>
                        @else
                            <option value="{{ $item->id }}">{{ $item->zone_name }}</option>
                        @endif
                    @endforeach
                </select> --}}
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <label class="form-label">
                    TIN
                </label>
                <input name="customerTIN" id="customerTIN" type="file"
                    accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx" class="form-control lh-sm fs-08rem">
                {{-- <input type="text" class="form-control fs-08rem" name="clientTIN" id="clientTIN"
                    @if (session('errorsData')) value="{{ session('errorsData')['clientTIN'] }}" @endif> --}}
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    BIN <span class="text-danger">*</span>
                </label>
                <input name="customerBIN" id="customerBIN" type="file"
                    accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx" class="form-control lh-sm fs-08rem"
                    required>
                {{-- <input type="text" class="form-control fs-08rem" name="clientBIN" id="clientBIN"
                    @if (session('errorsData')) value="{{ session('errorsData')['clientBIN'] }}" @endif> --}}
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    Trade License <span class="text-danger">*</span>
                </label>
                <input name="customerTL" id="customerTL" type="file"
                    accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx" class="form-control lh-sm fs-08rem"
                    required>
                {{-- <input type="text" class="form-control fs-08rem" name="clientTL" id="clientTL"
                    @if (session('errorsData')) value="{{ session('errorsData')['clientTL'] }}" @endif> --}}
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
                    Contact Email <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control fs-08rem" name="contactEmail" id="contactEmail"
                    @if (session('errorsData')) value="{{ session('errorsData')['contactEmail'] }}" @endif
                    required>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <label class="form-label">
                    Lead Source <span class="text-danger">*</span>
                </label>
                <select class="form-control fs-08rem" aria-label="Client Zone" name="leadSource" id="leadSource"
                    required>
                    <option selected disabled value="">Select One</option>
                    @foreach ($leadSource as $item)
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
        <center><button type="submit" class="btn btn-sm btn-darkblue fs-09rem mt-3">Insert New Client</button>
        </center>
    </form>
</div>
