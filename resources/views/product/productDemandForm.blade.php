@include('layouts.navbar')
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

<div class="container mt-2 mb-3">
    <h6 class="text-center">New Product Demand Form</h6>
    <div class="bg-offwhite p-2">
        <form action="{{ route('productDemand') }}" method="POST" id="productInsertionForm">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Product Type <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control fs-08rem" name="prType" id="prType"
                        placeholder="i.e. Surface Pump"
                        @if (session('errorsData')) value="{{ session('errorsData')['prType'] }}" @endif required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Product Brand <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control fs-08rem" name="prBrand" id="prBrand"
                        @if (session('errorsData')) value="{{ session('errorsData')['prBrand'] }}" @endif
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Product Name/Model <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control fs-08rem" name="prName" id="prName"
                        @if (session('errorsData')) value="{{ session('errorsData')['prName'] }}" @endif required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Quantity <span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control fs-08rem" name="prQuantity" id="prQuantity"
                        pattern="[0-9]+([\.,][0-9]+)?" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-08rem">
                        Product Description
                    </label>
                    <textarea name="prDescription" id="prDescription" class="form-control fs-08rem" cols="30" rows="3"></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Customer Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control fs-08rem" name="customerName" id="customerName"
                        @if (session('errorsData')) value="{{ session('errorsData')['customerName'] }}" @endif required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Customer Phone <span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control fs-08rem" name="customerPhone" id="customerPhone"
                        pattern="[0-9]+([\.,][0-9]+)?" required>
                </div>
            </div>
            <center><button class="btn btn-darkblue fs-08rem p-1 mt-4 mb-2">Save For Future</button></center>
        </form>
    </div>
</div>
