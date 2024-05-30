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
        <h6>Total Requirement <span class="bg-darkblue text-white p-2 rounded blink">{{ count($reqList) }}</span> And
            Total Pump Selection <span><span class="bg-darkblue text-white p-2 rounded blink">0</span></span></h6>
    </center>
    <hr>
    <div id="fullDealForm">
        @if (count($reqList) > 0)
            <?php $modalNo = 0; ?>
            @foreach ($reqList as $item)
                <div class="row justify-content-evenly requirementSlectionDiv mb-2 mt-2">
                    <div class="col-md-4 bg-white rounded shadow p-1">
                        <center>
                            <h6 class="text-primary fw-bold">Client Requirement <i
                                    class="fas fa-check text-success blink"></i></h6>
                        </center>
                        <form action="{{ route('requirement') }}" method="POST">
                            @csrf
                            <input type="hidden" name="lead_id" value="{{ $leadId }}">
                            <div class="row fs-07rem p-1">
                                <div class="col-md-6">
                                    <label for="">Type Of Use <small class="text-danger">*</small></label>
                                    <select name="type_of_use" id="type_of_use" class="form-select fs-08rem p-1"
                                        required>
                                        <option value="" selected disabled>--Select One--</option>

                                        <option value="Domestic"
                                            <?= $item->type_of_use == 'Domestic' ? 'selected' : '' ?>>Domestic</option>
                                        <option value="Industrial"
                                            <?= $item->type_of_use == 'Industrial' ? 'selected' : '' ?>>Industrial
                                        </option>
                                        <option value="Agricultural"
                                            <?= $item->type_of_use == 'Agricultural' ? 'selected' : '' ?>>Agricultural
                                        </option>
                                        <option value="Bore-hole"
                                            <?= $item->type_of_use == 'Bore-hole' ? 'selected' : '' ?>>Tube Well
                                        </option>
                                        <option value="Any" <?= $item->type_of_use == 'Any' ? 'selected' : '' ?>>Any
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Suction Type</label>
                                    <select name="suction_type" id="suction_type" class="form-select fs-08rem p-1">
                                        <option value="" selected disabled>--Select One--</option>
                                        <option value="Postive"
                                            <?= $item->suction_type == 'Postive' ? 'selected' : '' ?>>Positive</option>
                                        <option value="Negative"
                                            <?= $item->suction_type == 'Negative' ? 'selected' : '' ?>>Negative</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Delivery Head</label>
                                    <input type="number" class="form-control fs-08rem p-1" name="delivery_head"
                                        id="delivery_head">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Suction Pipe Dia (Inch)</label>
                                    <select name="suction_pipe_dia" id="suction_pipe_dia"
                                        class="form-select fs-08rem p-1">
                                        <option value="" selected disabled>--Select One--</option>
                                        <option value="0.25"
                                            <?= $item->suction_pipe_dia == '0.25' ? 'selected' : '' ?>>
                                            0.25</option>
                                        <option value="0.75"
                                            <?= $item->suction_pipe_dia == '0.75' ? 'selected' : '' ?>>
                                            0.75</option>
                                        <option value="1.00"
                                            <?= $item->suction_pipe_dia == '1.00' ? 'selected' : '' ?>>
                                            1.00</option>
                                        <option value="1.50"
                                            <?= $item->suction_pipe_dia == '1.50' ? 'selected' : '' ?>>
                                            1.50</option>
                                        <option value="2.00"
                                            <?= $item->suction_pipe_dia == '2.00' ? 'selected' : '' ?>>
                                            2.00</option>
                                        <option value="1.25"
                                            <?= $item->suction_pipe_dia == '1.25' ? 'selected' : '' ?>>
                                            1.25</option>
                                        <option value="3.00"
                                            <?= $item->suction_pipe_dia == '3.00' ? 'selected' : '' ?>>
                                            3.00</option>
                                        <option value="2.50"
                                            <?= $item->suction_pipe_dia == '2.50' ? 'selected' : '' ?>>
                                            2.50</option>
                                        <option value="4.00"
                                            <?= $item->suction_pipe_dia == '4.00' ? 'selected' : '' ?>>
                                            4.00</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Delivery Pipe Dia (Inch)</label>
                                    <select name="delivery_pipe_dia" id="delivery_pipe_dia"
                                        class="form-select fs-08rem p-1">
                                        <option value="" selected disabled>--Select One--</option>
                                        <option value="0.25"
                                            <?= $item->delivery_pipe_dia == '0.25' ? 'selected' : '' ?>>0.25</option>
                                        <option value="0.75"
                                            <?= $item->delivery_pipe_dia == '0.75' ? 'selected' : '' ?>>0.75</option>
                                        <option value="1.00"
                                            <?= $item->delivery_pipe_dia == '1.00' ? 'selected' : '' ?>>1.00</option>
                                        <option value="1.50"
                                            <?= $item->delivery_pipe_dia == '1.50' ? 'selected' : '' ?>>1.50</option>
                                        <option value="2.00"
                                            <?= $item->delivery_pipe_dia == '2.00' ? 'selected' : '' ?>>2.00</option>
                                        <option value="1.25"
                                            <?= $item->delivery_pipe_dia == '1.25' ? 'selected' : '' ?>>1.25</option>
                                        <option value="3.00"
                                            <?= $item->delivery_pipe_dia == '3.00' ? 'selected' : '' ?>>3.00</option>
                                        <option value="2.50"
                                            <?= $item->delivery_pipe_dia == '2.50' ? 'selected' : '' ?>>2.50</option>
                                        <option value="4.00"
                                            <?= $item->delivery_pipe_dia == '4.00' ? 'selected' : '' ?>>4.00</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Horizontal Pipe Length (MT)</label>
                                    <select name="horizontal_pipe_length" id="horizontal_pipe_length"
                                        class="form-select fs-08rem p-1">
                                        <option value="" selected disabled>--Select One--</option>
                                        <option value="1"
                                            <?= $item->horizontal_pipe_length == '1' ? 'selected' : '' ?>>1</option>
                                        <option value="2"
                                            <?= $item->horizontal_pipe_length == '2' ? 'selected' : '' ?>>2</option>
                                        <option value="3"
                                            <?= $item->horizontal_pipe_length == '3' ? 'selected' : '' ?>>3</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Source Of Water</label>
                                    <select name="source_of_water" id="source_of_water"
                                        class="form-select fs-08rem p-1">
                                        <option value="" selected disabled>--Select One--</option>
                                        <option value="Reservoir"
                                            <?= $item->source_of_water == 'Reservoir' ? 'selected' : '' ?>>Reservoir
                                        </option>
                                        <option value="Wasa"
                                            <?= $item->source_of_water == 'Wasa' ? 'selected' : '' ?>>Wasa</option>
                                        <option value="River"
                                            <?= $item->source_of_water == 'River' ? 'selected' : '' ?>>River</option>
                                        <option value="Deep Tube Well"
                                            <?= $item->source_of_water == 'Deep Tube Well' ? 'selected' : '' ?>>Deep
                                            Tube Well</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Water Consumption (m3/Day)</label>
                                    <input type="number" class="form-control fs-08rem p-1" name="water_consumption"
                                        id="water_consumption">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Liquid Type</label>
                                    <select name="liquid_type" id="liquid_type" class="form-select fs-08rem p-1">
                                        <option value="" selected disabled>--Select One--</option>
                                        <option value="Clean Water"
                                            <?= $item->liquid_type == 'Clean Water' ? 'selected' : '' ?>>Clean Water
                                        </option>
                                        <option value="Mud Water"
                                            <?= $item->liquid_type == 'Mud Water' ? 'selected' : '' ?>>Mud Water
                                        </option>
                                        <option value="Salt Water"
                                            <?= $item->liquid_type == 'Salt Water' ? 'selected' : '' ?>>Salt Water
                                        </option>
                                        <option value="Furnace"
                                            <?= $item->liquid_type == 'Furnace' ? 'selected' : '' ?>>Furnace</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Pump Running Hour/Day</label>
                                    <select name="pump_running_hour" id="pump_running_hour"
                                        class="form-select fs-08rem p-1">
                                        <option value="" selected disabled>--Select One--</option>
                                        <option value="2"
                                            <?= $item->pump_running_hour == '2' ? 'selected' : '' ?>>2</option>
                                        <option value="4"
                                            <?= $item->pump_running_hour == '4' ? 'selected' : '' ?>>4</option>
                                        <option value="6"
                                            <?= $item->pump_running_hour == '6' ? 'selected' : '' ?>>6</option>
                                        <option value="8"
                                            <?= $item->pump_running_hour == '8' ? 'selected' : '' ?>>8</option>
                                        <option value="10"
                                            <?= $item->pump_running_hour == '10' ? 'selected' : '' ?>>10</option>
                                        <option value="12"
                                            <?= $item->pump_running_hour == '12' ? 'selected' : '' ?>>12</option>
                                        <option value="14"
                                            <?= $item->pump_running_hour == '14' ? 'selected' : '' ?>>14</option>
                                        <option value="16"
                                            <?= $item->pump_running_hour == '16' ? 'selected' : '' ?>>16</option>
                                        <option value="18"
                                            <?= $item->pump_running_hour == '18' ? 'selected' : '' ?>>18</option>
                                        <option value="20"
                                            <?= $item->pump_running_hour == '20' ? 'selected' : '' ?>>20</option>
                                        <option value="22"
                                            <?= $item->pump_running_hour == '22' ? 'selected' : '' ?>>22</option>
                                        <option value="24"
                                            <?= $item->pump_running_hour == '24' ? 'selected' : '' ?>>24</option>
                                    </select>
                                </div>
                            </div>
                            <center><button type="submit" class="btn btn-sm btn-darkblue fs-07rem p-1 m-2">Save
                                    Requirement</button></center>
                        </form>
                    </div>
                    <div class="col-md-7 bg-white rounded shadow p-1">
                        <center>
                            <h6 class="text-primary fw-bold">Pump Selection</h6>
                            <button data-mdb-toggle="modal" data-mdb-target="#pumpSelectionModal"
                                class="float-end btn btn-sm btn-primary fs-07rem p-1 m-1 modalBtn"
                                onclick="setModalNumber({{ $modalNo }})">Select Pump</button>
                        </center>
                        @include('sales.modals.pumpSelectionModal')
                        <div class="selectedPumps border p-2">
                            <p class="badge blink noPumpSelectedText bg-danger text-center">No Pump Selected Yet</p>
                            <table class="table fs-07rem table-bordered">
                                <thead>
                                    <tr>
                                        <th class="p-1 text-center">Product</th>
                                        <th class="p-1 text-center">Brand</th>
                                        <th class="p-1 text-center">HP</th>
                                        <th class="p-1 text-center">Head (M)</th>
                                        <th class="p-1 text-center">Unit Price</th>
                                        <th class="p-1 text-center">Qty.</th>
                                        <th class="p-1 text-center">Discount</th>
                                        <th class="p-1 text-center">Net Price</th>
                                        <th class="p-1 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="selectedPumpsTbody">

                                </tbody>
                            </table>
                            <center><button class="btn btn-sm btn-darkblue fs-06rem p-1">Save Selected Pump</button>
                            </center>
                        </div>

                    </div>
                </div>
                <?php  $modalNo++ ?>
            @endforeach
        @else
            <div class="row justify-content-evenly requirementSlectionDiv mb-2 mt-2">
                <div class="col-md-4 bg-white rounded shadow p-1">
                    <center>
                        <h6 class="text-primary fw-bold">Client Requirement</h6>
                    </center>
                    <form action="{{ route('requirement') }}" method="POST">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ $leadId }}">
                        <div class="row fs-07rem p-1">
                            <div class="col-md-6">
                                <label for="">Type Of Use <small class="text-danger">*</small></label>
                                <select name="type_of_use" id="type_of_use" class="form-select fs-08rem p-1"
                                    required>
                                    <option value="" selected disabled>--Select One--</option>
                                    <option value="Domestic">Domestic</option>
                                    <option value="Industrial">Industrial</option>
                                    <option value="Agricultural">Agricultural</option>
                                    <option value="Bore-hole">Tube Well</option>
                                    <option value="Any">Any</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Suction Type</label>
                                <select name="suction_type" id="suction_type" class="form-select fs-08rem p-1">
                                    <option value="" selected disabled>--Select One--</option>
                                    <option value="Postive">Positive</option>
                                    <option value="Negative">Negative</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Delivery Head</label>
                                <input type="number" class="form-control fs-08rem p-1" name="delivery_head"
                                    id="delivery_head">
                            </div>
                            <div class="col-md-6">
                                <label for="">Suction Pipe Dia (Inch)</label>
                                <select name="suction_pipe_dia" id="suction_pipe_dia"
                                    class="form-select fs-08rem p-1">
                                    <option value="" selected disabled>--Select One--</option>
                                    <option value="0.25">0.25</option>
                                    <option value="0.75">0.75</option>
                                    <option value="1.00">1.00</option>
                                    <option value="1.50">1.50</option>
                                    <option value="2.00">2.00</option>
                                    <option value="1.25">1.25</option>
                                    <option value="3.00">3.00</option>
                                    <option value="2.50">2.50</option>
                                    <option value="4.00">4.00</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Delivery Pipe Dia (Inch)</label>
                                <select name="delivery_pipe_dia" id="delivery_pipe_dia"
                                    class="form-select fs-08rem p-1">
                                    <option value="" selected disabled>--Select One--</option>
                                    <option value="0.25">0.25</option>
                                    <option value="0.75">0.75</option>
                                    <option value="1.00">1.00</option>
                                    <option value="1.50">1.50</option>
                                    <option value="2.00">2.00</option>
                                    <option value="1.25">1.25</option>
                                    <option value="3.00">3.00</option>
                                    <option value="2.50">2.50</option>
                                    <option value="4.00">4.00</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Horizontal Pipe Length (MT)</label>
                                <select name="horizontal_pipe_length" id="horizontal_pipe_length"
                                    class="form-select fs-08rem p-1">
                                    <option value="" selected disabled>--Select One--</option>
                                    <option value="">1</option>
                                    <option value="">2</option>
                                    <option value="">3</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Source Of Water</label>
                                <select name="source_of_water" id="source_of_water" class="form-select fs-08rem p-1">
                                    <option value="" selected disabled>--Select One--</option>
                                    <option value="Reservoir">Reservoir</option>
                                    <option value="Wasa">Wasa</option>
                                    <option value="River">River</option>
                                    <option value="Deep Tube Well">Deep Tube Well</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Water Consumption (m3/Day)</label>
                                <input type="number" class="form-control fs-08rem p-1" name="water_consumption"
                                    id="water_consumption">
                            </div>
                            <div class="col-md-6">
                                <label for="">Liquid Type</label>
                                <select name="liquid_type" id="liquid_type" class="form-select fs-08rem p-1">
                                    <option value="" selected disabled>--Select One--</option>
                                    <option value="Clean Water">Clean Water</option>
                                    <option value="Mud Water">Mud Water</option>
                                    <option value="Salt Water">Salt Water</option>
                                    <option value="Furnace">Furnace</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Pump Running Hour/Day</label>
                                <select name="pump_running_hour" id="pump_running_hour"
                                    class="form-select fs-08rem p-1">
                                    <option value="" selected disabled>--Select One--</option>
                                    <option value="2">2</option>
                                    <option value="4">4</option>
                                    <option value="6">6</option>
                                    <option value="8">8</option>
                                    <option value="10">10</option>
                                    <option value="12">12</option>
                                    <option value="14">14</option>
                                    <option value="16">16</option>
                                    <option value="18">18</option>
                                    <option value="20">20</option>
                                    <option value="22">22</option>
                                    <option value="24">24</option>
                                </select>
                            </div>
                        </div>
                        <center><button type="submit" class="btn btn-sm btn-darkblue fs-07rem p-1 m-2">Save
                                Requirement</button></center>
                    </form>
                </div>
                <div class="col-md-7 bg-white rounded shadow p-1">
                    <center>
                        <h6 class="text-primary fw-bold">Pump Selection</h6>
                        <button data-mdb-toggle="modal" data-mdb-target="#pumpSelectionModal"
                            class="float-end btn btn-sm btn-primary fs-07rem p-1 m-1 modalBtn"
                            onclick="setModalNumber(0)">Select Pump</button>
                    </center>
                    @include('sales.modals.pumpSelectionModal')
                    <div class="selectedPumps border p-2">
                        <p class="badge blink noPumpSelectedText bg-danger text-center">No Pump Selected Yet</p>
                        <table class="table fs-07rem table-bordered">
                            <thead>
                                <tr>
                                    <th class="p-1 text-center">Product</th>
                                    <th class="p-1 text-center">Brand</th>
                                    <th class="p-1 text-center">HP</th>
                                    <th class="p-1 text-center">Head (M)</th>
                                    <th class="p-1 text-center">Unit Price</th>
                                    <th class="p-1 text-center">Qty.</th>
                                    <th class="p-1 text-center">Discount</th>
                                    <th class="p-1 text-center">Net Price</th>
                                    <th class="p-1 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="selectedPumpsTbody">

                            </tbody>
                        </table>
                        <center><button class="btn btn-sm btn-darkblue fs-06rem p-1">Save Selected Pump</button>
                        </center>
                    </div>

                </div>
            </div>
        @endif


    </div>
    <center><button class="btn btn-sm btn-darkblue p-1 mt-3" onclick="addNewRequirement()">Add Another
            Requirement</button></center>
</div>

<script>
    var activeModal = 0;

    function setModalNumber(e) {
        activeModal = e;
    }

    function updatePrice(e) {
        var row = e.parentElement.parentElement;
        row = row.querySelectorAll("td");
        let productUP = row[5].innerText;
        let productQty = row[6].querySelector('input');
        productQty = productQty.value;
        let productDiscountPercentage = row[7].querySelector('input');
        productDiscountPercentage = productDiscountPercentage.value;
        let totalPrice = (Number(productUP) * Number(productQty));
        let discountAmount = totalPrice * (Number(productDiscountPercentage) / 100);
        let productTotalPrice = totalPrice - discountAmount;
        row[8].innerText = productTotalPrice;
    }

    function addCart(e) {
        var row = e.parentElement;
        row = row.querySelectorAll("td");
        let productId = row[0].innerText;
        let productName = row[1].innerText;
        let productBrand = row[2].innerText;
        let productHP = row[3].innerText;
        let productHead = row[4].innerText;
        let productUP = row[5].innerText;
        let productQty = row[6].querySelector('input');
        productQty = productQty.value;
        let productDiscountPercentage = row[7].querySelector('input');
        productDiscountPercentage = productDiscountPercentage.value;
        let totalPrice = (Number(productUP) * Number(productQty));
        let discountAmount = totalPrice * (Number(productDiscountPercentage) / 100);
        let productTotalPrice = totalPrice - discountAmount;

        if (productQty < 1) {
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                text: "Minimum product quantity is 1",
                showConfirmButton: false,
                timer: 2000
            });
        }

        let html = "<tr>";
        html += "<td class='d-none'><input name='productId[]' value='" + productId + "'></td>";
        html += "<td class='p-1'>" + productName + "</td>";
        html += "<td class='p-1'>" + productBrand + "</td>";
        html += "<td class='p-1 text-center'>" + productHP + "</td>";
        html += "<td class='p-1 text-center'>" + productHead + "</td>";
        html += "<td class='p-1 text-end'>" + productUP + "</td>";
        html += "<td class='p-1 text-center'>" + productQty + "</td>";
        html += "<td class='p-1 text-end'>" + discountAmount + "</td>";
        html += "<td class='p-1 text-end'>" + productTotalPrice + "</td>";
        html +=
            "<td class='p-1 text-center' style='cursor: pointer' onclick='deleteSelectedRow(this)'><i class='fas fa-trash text-danger' ></i></td>";
        html += "</tr>";

        let allSelctedPumpTobody = document.querySelectorAll('#selectedPumpsTbody');
        allSelctedPumpTobody[activeModal].innerHTML += html;
        // $('#selectedPumpsTbody').append(html);


        // console.log(e.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode);
        let allNoPumpBlink = document.querySelectorAll('.noPumpSelectedText');
        allNoPumpBlink[activeModal].classList.add("d-none");
        // document.querySelector(".noPumpSelectedText").classList.add("d-none");
    }

    function deleteSelectedRow(e) {
        e.parentElement.remove();
        let totalTr = $('#selectedPumpsTbody')[0].children.length;
        if (totalTr < 1) {
            document.querySelector(".noPumpSelectedText").classList.remove("d-none");
        }
    }

    function addNewRequirement() {
        const clonedDiv = document.querySelectorAll('.requirementSlectionDiv');
        const clone = clonedDiv[0].cloneNode(true);
        let newSelectedTbody = clone.querySelector('#selectedPumpsTbody');
        let totalRequirementSection = clonedDiv.length;
        newSelectedTbody.innerHTML = '';

        let checkSign = clone.querySelector('.fa-check');
        checkSign.remove();
        // checkSign.classList.remove('fa-check');
        // checkSign.classList.remove('text-success');
        // checkSign.classList.add('fa-trash');
        // checkSign.classList.add('text-danger');
        // console.log(checkSign);

        let newModalBtn = clone.querySelector('.modalBtn');
        newModalBtn.setAttribute("onclick", "setModalNumber(" + (totalRequirementSection) + ")");
        $('#fullDealForm').append(clone);
    }
</script>
