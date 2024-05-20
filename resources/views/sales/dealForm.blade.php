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

<div class="container-fluid mb-3 mt-2">
    <center>
        <h4 class="mt-3">Requirement & Pump Selection Form</h4>
    </center>
    <hr>
    <div class="row justify-content-evenly">
        <div class="col-md-4 bg-white rounded shadow">
            <center>
                <h6 class="text-primary fw-bold">Client Requirement</h6>
            </center>
            <div class="row fs-08rem p-1">
                <div class="col-md-6">
                    <label for="">Type Of Use</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">Domestic</option>
                        <option value="">Industry</option>
                        <option value="">Others</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Suction Type</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">Positive</option>
                        <option value="">Negative</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Delivery Head</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Suction Pipe Dia (MM)</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Delivery Pipe Dia (MM)</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Horizontal Pipe Length (MT)</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Source Of Water</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">Reservoir</option>
                        <option value="">Wasa</option>
                        <option value="">River</option>
                        <option value="">Deep Tube Well</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Water Consumption (m3/Day)</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                        <option value="">4</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Liquid Type</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">Clean Water</option>
                        <option value="">Mud Water</option>
                        <option value="">Salt Water</option>
                        <option value="">Furnace</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="">Pump Running Hour/Day</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                        <option value="">4</option>
                    </select>
                </div>
            </div>
            <center><button class="btn btn-sm btn-darkblue fs-07rem p-1 m-2">Save Requirement</button></center>
        </div>
        <div class="col-md-7 bg-white rounded shadow">
            <center>
                <h6 class="text-primary fw-bold">Pump Selection</h6>
            </center>
            <div class="row fs-08rem">
                <div class="col-md-2">
                    <label for="">Brand</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">Pedrollo</option>
                        <option value="">BGFlow</option>
                        <option value="">HCP</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">HP</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                        <option value="">4</option>
                        <option value="">5</option>
                        <option value="">6</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Model</label>
                    <select name="" id="" class="form-select fs-08rem p-1">
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                        <option value="">4</option>
                        <option value="">5</option>
                        <option value="">6</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
