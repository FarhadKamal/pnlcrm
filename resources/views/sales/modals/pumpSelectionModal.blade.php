<div class="modal fade" id="pumpSelectionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    style="overflow: scroll">
    <div class="modal-dialog modal-xl">
        <div class="modal-content " ShowTab="1" id="121" style="overflow: scroll;">
            <div class="modal-header border p-2">
                <h5 class="modal-title p-0 m-0" id="exampleModalLabel">
                    <center>
                        <h6>Pump Selection Table</h6>
                    </center>
                </h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fs-09rem">
                <div class="border p-2 mt-2">
                    <div class="row fs-07rem ">
                        <div class="col-md-2">
                            <label for="">Item Type <small class="text-danger">*</small></label>
                            <select name="filterType" id="filterType" class="form-select fs-07rem p-1"
                                onchange="changeFilterLabel()" required>
                                <option value="" selected disabled>--Select One--</option>
                                <option value="Surface">Surface Pump</option>
                                <option value="Submersible">Submersible Pump</option>
                                <option value="Drainage">Drainage Pump</option>
                                <option value="Itap">Itap</option>
                                <option value="Maxwell">Maxwell</option>
                                <option value="Spare Parts">Spare Parts</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">Brand</label>
                            <select name="filterBrand" id="filterBrand" class="form-select fs-07rem p-1">
                                <option value="all">All Brand</option>
                                <option value="Pedrollo">Pedrollo</option>
                                <option value="BGFlow">BGFlow</option>
                                <option value="HCP">HCP</option>
                                <option value="Panelli">Panelli</option>
                                <option value="Itap">Itap</option>
                                <option value="Maxwell">Maxwell</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-none">
                            <label for="">Spare Parts List</label>
                            <select name="filterSpare" id="filterSpare" class="form-select fs-07rem p-1">
                                <option value="all">All Spare Parts</option>
                                @foreach ($allSpareParts as $item)
                                    <option value="{{ $item->mat_name }}">{{ $item->mat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label for="">HP</label>
                            <select name="filterHP" id="filterHP" class="form-select fs-07rem p-1">
                                <option value="all">All HP</option>
                                @foreach ($allPumpHP as $item)
                                    <option value="{{ $item->hp }}">{{ $item->hp }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">Model</label>
                            <select name="filterModel" id="filterModel" class="form-select fs-07rem p-1">
                                <option value="all">All Model</option>
                                @foreach ($allPumpModel as $item)
                                    <option value="{{ $item->mat_name }}">{{ $item->mat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label for="">Head</label>
                            <input type="number" class="form-control fs-07rem p-1" name="filterHead" id="filterHead">
                            {{-- <select name="filterHead" id="filterHead" class="form-select fs-07rem p-1">
                                <option value="all">All Head</option>
                                @foreach ($allPumpHead as $item)
                                    <option value="{{ $item->head }}">{{ $item->head }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                        <div class="col-md-2">
                            <label for="">Phase</label>
                            <select name="filterPhase" id="filterPhase" class="form-select fs-07rem p-1">
                                <option value="all">All Phase</option>
                                @foreach ($allPumpPhase as $item)
                                    <option value="{{ $item->phase }}">{{ $item->phase }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-darkblue fs-06rem pt-1 pb-1 ps-1 pe-1 mt-3"
                                onclick="filterItem()">Search</button>
                        </div>
                    </div>
                    {{-- Search List  --}}
                    <div class="mt-2">
                        <table class="table fs-07rem table-bordered">
                            <thead>
                                <tr>
                                    <th class="p-1 text-center">Product</th>
                                    <th class="p-1 text-center">Brand</th>
                                    <th class="p-1 text-center">HP</th>
                                    <th class="p-1 text-center">Head (M)</th>
                                    <th class="p-1 text-center">Unit Price</th>
                                    <th class="p-1 text-center">Stock</th>
                                    <th class="p-1 text-center">Qty.</th>
                                    <th class="p-1 text-center">Discount (%)</th>
                                    <th class="p-1 text-center">Net Price</th>
                                    <th class="p-1 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="filterPumpList">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script>
    $("#filterBrand").select2({
        allowClear: false
    });
    $("#filterHP").select2({
        allowClear: false
    });
</script> --}}

<script>
    function changeFilterLabel() {
        let filterType = $('#filterType').val();
        if (filterType == 'Spare Parts') {
            let hpDiv = document.getElementById('filterHP').parentNode;
            hpDiv.classList.add('d-none');
            let modelDiv = document.getElementById('filterModel').parentNode;
            modelDiv.classList.add('d-none');
            let headDiv = document.getElementById('filterHead').parentNode;
            headDiv.classList.add('d-none');
            let phaseDiv = document.getElementById('filterPhase').parentNode;
            phaseDiv.classList.add('d-none');
            let spareDiv = document.getElementById('filterSpare').parentNode;
            spareDiv.classList.remove('d-none');
        } else {
            let hpDiv = document.getElementById('filterHP').parentNode;
            hpDiv.classList.remove('d-none');
            let modelDiv = document.getElementById('filterModel').parentNode;
            modelDiv.classList.remove('d-none');
            let headDiv = document.getElementById('filterHead').parentNode;
            headDiv.classList.remove('d-none');
            let phaseDiv = document.getElementById('filterPhase').parentNode;
            phaseDiv.classList.remove('d-none');
            let spareDiv = document.getElementById('filterSpare').parentNode;
            spareDiv.classList.add('d-none');
        }
    }
</script>

<script>
    function filterItem() {
        $('#filterPumpList').empty();
        let filterType = $('#filterType').val();
        let filterBrand = $('#filterBrand').val();
        let filterHP = $('#filterHP').val();
        let filterModel = $('#filterModel').val();
        let filterHead = $('#filterHead').val();
        let filterPhase = $('#filterPhase').val();
        let filterSpare = $('#filterSpare').val();
        if (filterType == '' || filterType == null) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Please select a item type first",
                showConfirmButton: false,
                timer: 2000
            });
        } else {
            document.getElementById("loadingGif").style.display = "block";
            document.getElementById("loadingText").style.display = "block";
            let filterData = {
                filterType: filterType,
                filterBrand: filterBrand,
                filterHP: filterHP,
                filterModel: filterModel,
                filterHead: filterHead,
                filterPhase: filterPhase,
                filterSpare: filterSpare
            };
            const csrfToken = '<?php echo csrf_token(); ?>';

            fetch('/getSelectionPumpInfo/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(filterData)
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("loadingGif").style.display = "none";
                    document.getElementById("loadingText").style.display = "none";
                    //  console.log(data);
                    if (data.status === 'success') {
                        let fetchData = data.data;
                        if (fetchData.length < 1) {
                            let html = '<tr><td colspan="9" class="text-danger">No Data Found</td></tr>';
                            $('#filterPumpList').append(html);
                        }

                        fetchData.forEach(element => {
                            let spare = 0;
                            if (filterType == 'Spare Parts') {
                                spare = 1;
                            }
                            let itemCode = element.new_code;
                            let html = '<tr>';
                            html += '<td class="d-none">' + element.id + '</td>';
                            html += '<td class="p-1">' + element.mat_name + '</td>';
                            html += '<td class="p-1">' + element.brand + '</td>';
                            html += '<td class="p-1">' + element.hp + '</td>';
                            html += '<td class="p-1">' + element.head + '</td>';
                            html += '<td class="p-1">' + element.price + '</td>';
                            if (element.stock == null) {
                                html += '<td class="p-1 text-end totalPrice">0</td>';
                            } else {
                                let stockURL = '<?= url('') ?>/SAPstockDetails/' + itemCode;

                                html +=
                                    '<td class="p-1 text-end totalPrice"><a href="' + stockURL +
                                    '" target="_blank">' +
                                    element.stock + '</a></td>';
                            }
                            html +=
                                '<td class="p-1 text-center"><input type="number" class="text-center" style="width:5rem" value="1" min="1" onchange="updatePrice(this)" onkeyup="updatePrice(this)"></td>';
                            html +=
                                '<td class="p-1 text-center"><input type="number" class="text-center" style="width:5rem" value="0" onchange="updatePrice(this)" onkeyup="updatePrice(this)"></td>';
                            html += '<td class="p-1 text-end totalPrice">' + element.price + '</td>';
                            html +=
                                '<td class="p-1 text-center" onclick="addCart(this)"><badge class="bg-darkblue text-white pt-1 pb-1 ps-2 pe-2 rounded-pill" style="cursor:pointer">Add</badge></td>';
                            html += '<td class="d-none">' + spare + '</td>';
                            html += '</tr>';
                            $('#filterPumpList').append(html);
                        });

                    } else if (data.status === 'null') {
                        let html = '<tr><td colspan="9" class="text-danger">No Data Found</td></tr>';
                        $('#filterPumpList').append(html);
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: "Something Is Wrong",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    }
</script>
