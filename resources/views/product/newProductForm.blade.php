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

<div class="container mt-2 mb-3">
    <h6 class="text-center">New Product Entry Form</h6>
    <div class="bg-offwhite p-2">
        <form action="{{ route('insertProduct') }}" method="POST" id="productInsertionForm">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Product Type <span class="text-danger">*</span>
                    </label>
                    <select class="form-control fs-08rem" name="prType" id="prType" onchange="checkFormFields()"
                        required>
                        <option value="" selected disabled>--Select One--</option>
                        <option value="Surface">Surface Pump</option>
                        <option value="Submersible">Submersible Pump</option>
                        <option value="Drainage">Drainage Pump</option>
                        <option value="Itap">Itap</option>
                        <option value="Maxwell">Maxwell</option>
                        <option value="Spare Parts">Spare Parts</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Product SAP ID <span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control fs-08rem" name="prSAPID" id="prSAPID"
                        @if (session('errorsData')) value="{{ session('errorsData')['prSAPID'] }}" @endif
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Product Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control fs-08rem" name="prName" id="prName"
                        @if (session('errorsData')) value="{{ session('errorsData')['prName'] }}" @endif required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Product Brand <span class="text-danger">*</span>
                    </label>
                    <select class="form-control fs-08rem" name="prBrand" id="prBrand" required>
                        <option value="" selected disabled value="">Select One</option>
                        @foreach ($brands as $item)
                            @if (session('errorsData') && session('errorsData')['prBrand'] == $item->id)
                                <option value="{{ $item->id }}" selected>{{ $item->brand_name }}</option>
                            @else
                                <option value="{{ $item->id }}">{{ $item->brand_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Item Group
                    </label>
                    <input type="text" class="form-control fs-08rem" name="prItemGrp" id="prItemGrp">
                </div>
                <div class="col-md-3 itemTab">
                    <label class="form-label fs-08rem">
                        Pump Phase <span class="text-danger">*</span>
                    </label>
                    <select class="form-control fs-08rem" name="phase" id="phase" required>
                        <option value="" selected disabled>--Select One--</option>
                        <option value="Single Phase">Single Phase</option>
                        <option value="Three Phase">Three Phase</option>
                    </select>
                </div>
                <div class="col-md-3 itemTab">
                    <label class="form-label fs-08rem">
                        Pump KW <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01"
                        class="form-control fs-08rem" name="kw" id="kw" required>
                </div>
                <div class="col-md-3 itemTab">
                    <label class="form-label fs-08rem">
                        Pump HP <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01"
                        class="form-control fs-08rem" name="hp" id="hp" required>
                </div>
                <div class="col-md-3 itemTab">
                    <label class="form-label fs-08rem">
                        Pump Suction Dia <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01"
                        class="form-control fs-08rem" name="suctionDia" id="suctionDia" required>
                </div>
                <div class="col-md-3 itemTab">
                    <label class="form-label fs-08rem">
                        Pump Delivery Dia <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01"
                        class="form-control fs-08rem" name="deliveryDia" id="deliveryDia" required>
                </div>
                <div class="col-md-3 itemTab">
                    <label class="form-label fs-08rem">
                        Pump Min Capacity <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="0" class="form-control fs-08rem" name="minCapacity"
                        id="minCapacity" required>
                </div>
                <div class="col-md-3 itemTab">
                    <label class="form-label fs-08rem">
                        Pump Max Capacity <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="0" class="form-control fs-08rem" name="maxCapacity"
                        id="maxCapacity" required>
                </div>
                <div class="col-md-3 itemTab">
                    <label class="form-label fs-08rem">
                        Pump Min Head <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="0" class="form-control fs-08rem" name="minHead"
                        id="minHead" required>
                </div>
                <div class="col-md-3 itemTab">
                    <label class="form-label fs-08rem">
                        Pump Max Head <span class="text-danger">*</span>
                    </label>
                    <input type="number" min="0" class="form-control fs-08rem" name="maxHead"
                        id="maxHead" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Unit Name <span class="text-danger">*</span>
                    </label>
                    <select class="form-control fs-08rem" name="unitName" id="unitName" required>
                        <option value="" selected disabled>--Select One--</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Mtr">Mtr</option>
                    </select>
                </div>
            </div>
            <center><button class="btn btn-darkblue fs-08rem p-1 mt-4 mb-2">Store Product</button></center>
        </form>
    </div>
</div>

@if (session('swError'))
    <div>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: '<?= session('swError') ?>',
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    </div>
@endif
@if (session('swSuccess'))
    <div>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: '<?= session('swSuccess') ?>',
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    </div>
@endif

<script>
    function checkFormFields() {
        let prType = $('#prType').val();
        if (prType == 'Spare Parts' || prType == 'Itap' || prType == 'Maxwell') {
            let hideFields = document.querySelectorAll('.itemTab');
            hideFields.forEach(element => {
                element.classList.add('d-none');
                element.querySelectorAll('input, select').forEach(field => {
                    field.removeAttribute('required');
                });
            });
        } else {
            let showFields = document.querySelectorAll('.itemTab');
            showFields.forEach(element => {
                element.classList.remove('d-none');
                element.querySelectorAll('input, select').forEach(field => {
                    field.setAttribute('required', '');
                });
            });
        }
    }

    $('#productInsertionForm').submit(async function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }

        let inputSAP = $('#prSAPID').val();
        let inputType = $('#prType').val();
        let filterData = {
            inputSAP: inputSAP,
            inputType: inputType
        };
        const csrfToken = '<?php echo csrf_token(); ?>';
        try {
            let response = await fetch('/checkSAPNewProduct', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(filterData)
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            let json = await response.json();
            let data = json.status;

            // Duplicacy Check in the CRM Start
            let isDuplicate = json.isDuplicate;
            if (isDuplicate && isDuplicate.length > 0) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    text: "The product sap id " + inputSAP + " is already exist in the CRM",
                    showConfirmButton: false,
                    timer: 3000
                });
                return false;
            }
            // Duplicacy Check in the CRM End

            if (data) {
                $('#productInsertionForm').off('submit').submit();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "Product SAP id not found in SAP",
                    showConfirmButton: false,
                    timer: 3000
                });
                return false;
            }

        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
            return false;
        }
    });
</script>
