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
    <div id="fullDealForm">
        <div class="row justify-content-evenly requirementSlectionDiv mb-2 mt-2">
            <div class="col-md-4 bg-white rounded shadow p-1">
                <center>
                    <h6 class="text-primary fw-bold">Client Requirement</h6>
                </center>

                <div class="row fs-07rem p-1">
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
            <div class="col-md-7 bg-white rounded shadow p-1">
                <center>
                    <h6 class="text-primary fw-bold">Pump Selection</h6>
                    <button data-mdb-toggle="modal" data-mdb-target="#pumpSelectionModal"
                        class="float-end btn btn-sm btn-primary fs-07rem p-1 m-1 modalBtn" onclick="setModalNumber(0)">Select Pump</button>
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
                    <center><button class="btn btn-sm btn-darkblue fs-06rem p-1">Save Selected Pump</button></center>
                </div>
                
            </div>
        </div>
    </div>
    <center><button class="btn btn-sm btn-darkblue p-1 mt-3" onclick="addNewRequirement()">Add Another
            Requirement</button></center>
</div>

<script>
    var activeModal = 0;
    function setModalNumber(e){
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
        let newModalBtn = clone.querySelector('.modalBtn');
        newModalBtn.setAttribute("onclick", "setModalNumber("+(totalRequirementSection)+")");
        $('#fullDealForm').append(clone);
    }
</script>
