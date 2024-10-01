@include('layouts.navbar')

<style>
    .select2-selection__rendered {
        font-size: small;
    }
</style>

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


<div class="container-fluid mt-2 mb-3">
    <h6 class="text-center">New Target Entry Form</h6>
    <div class="p-2">
        <form action="{{ route('targetEntry') }}" method="POST" id="taregtInsertionForm">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Salesperson Selection <span class="text-danger">*</span>
                    </label>
                    <select name="userId" id="userId" class="form-select fs-08rem p-1" required>
                        <option value="" disabled selected>--Select One--</option>
                        @foreach ($salesPersons as $item)
                            @if ($item->assign_to)
                                <option value="{{ $item->id }}">{{ $item->assign_to }} - {{ $item->user_name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Financial Year <span class="text-danger">*</span>
                    </label>
                    <select class="form-control fs-08rem p-1" name="financialYear" id="financialYear" required>
                        <option value="" selected disabled>--Select One--</option>
                        <option value="{{ $previousYear }}">{{ $previousYear }} To {{ $currentYear }}</option>
                        <option value="{{ $currentYear }}">{{ $currentYear }} To {{ $futureYear }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-08rem">
                        Total Annual Target (BDT) <span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control fs-08rem p-1" min="1" name="totalTarget"
                        id="totalTarget" onkeyup="calculateAll()" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label bg-offwhite fs-08rem p-2 mt-1 rounded w-100">
                        Quarter Target (%) <span class="text-danger">*</span>
                        <small class="text-danger">Insert % In Each Quarter</small>
                    </label>
                    <table class="table table-bordered p-1 fs-08rem">
                        <tr>
                            <td class="p-1 text-center" width="10%">Q1</td>
                            <td class="p-1" width="30%"><input type="number"
                                    class="form-control fs-08rem p-1 text-end" min="1" max="100"
                                    name="q1Per" id="q1Per" onkeyup="calculateAll()" placeholder="%">
                            </td>
                            <td class="p-1 fw-bold" width="60%">
                                <p id="q1Amount">0.00</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1 text-center" width="10%">Q2</td>
                            <td class="p-1" width="30%"><input type="number"
                                    class="form-control fs-08rem p-1 text-end" min="1" max="100"
                                    name="q2Per" id="q2Per" onkeyup="calculateAll()" placeholder="%"></td>
                            <td class="p-1 fw-bold" width="60%">
                                <p id="q2Amount">0.00</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1 text-center" width="10%">Q3</td>
                            <td class="p-1" width="30%"><input type="number"
                                    class="form-control fs-08rem p-1 text-end" name="q3Per" min="1"
                                    max="100" id="q3Per" onkeyup="calculateAll()" placeholder="%"></td>
                            <td class="p-1 fw-bold" width="60%">
                                <p id="q3Amount">0.00</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1 text-center" width="10%">Q4</td>
                            <td class="p-1" width="30%"><input type="number"
                                    class="form-control fs-08rem p-1 text-end" name="q4Per" min="1"
                                    max="100" id="q4Per" onkeyup="calculateAll()" placeholder="%"></td>
                            <td class="p-1 fw-bold" width="60%">
                                <p id="q4Amount">0.00</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1" width="10%">Total</td>
                            <td class="p-1 text-end" width="30%" id="toalQuarterPer">0%</td>
                            <td class="p-1" width="60%"><small id="toalQuarterPerText"
                                    class="text-danger fw-bold"></small></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-8">
                    <label class="form-label fs-08rem bg-offwhite p-2 mt-1 rounded w-100">
                        Brand Target <span class="text-danger">(Assume each quarter as 100% than divide into the below
                            table)</span>
                    </label>
                    <div class="row">
                        <table class="table table-bordered fs-07rem">
                            <thead>
                                <tr>
                                    <td class="p-1">Brand</td>
                                    <td class="p-1">Q1</td>
                                    <td class="p-1">Q2</td>
                                    <td class="p-1">Q3</td>
                                    <td class="p-1">Q4</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $item)
                                    <tr>
                                        <td class="p-1">{{ $item->brand_name }}</td>
                                        <td class="p-1"><input type="number"
                                                class="form-control fs-08rem p-1 text-end w-30 d-inline"
                                                name="{{ $item->brand_name }}PerQ1"
                                                id="{{ $item->brand_name }}PerQ1" min="1" max="100"
                                                onkeyup="calculateBrand()" placeholder="%">
                                            <span class="fw-bold" id="{{ $item->brand_name }}Q1">0.00</span>
                                        </td>
                                        <td class="p-1"><input type="number"
                                                class="form-control fs-08rem p-1 text-end w-30 d-inline"
                                                name="{{ $item->brand_name }}PerQ2"
                                                id="{{ $item->brand_name }}PerQ2" min="1" max="100"
                                                onkeyup="calculateBrand()" placeholder="%">
                                            <span class="fw-bold" id="{{ $item->brand_name }}Q2">0.00</span>
                                        </td>
                                        <td class="p-1"><input type="number"
                                                class="form-control fs-08rem p-1 text-end w-30 d-inline"
                                                name="{{ $item->brand_name }}PerQ3"
                                                id="{{ $item->brand_name }}PerQ3" min="1" max="100"
                                                onkeyup="calculateBrand()" placeholder="%">
                                            <span class="fw-bold" id="{{ $item->brand_name }}Q3">0.00</span>
                                        </td>
                                        <td class="p-1"><input type="number"
                                                class="form-control fs-08rem p-1 text-end w-30 d-inline"
                                                name="{{ $item->brand_name }}PerQ4"
                                                id="{{ $item->brand_name }}PerQ4" min="1" max="100"
                                                onkeyup="calculateBrand()" placeholder="%">
                                            <span class="fw-bold" id="{{ $item->brand_name }}Q4">0.00</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <center><small class="text-danger" id="BrandErrorText"></small></center>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label fs-08rem bg-offwhite p-2 mt-1 rounded w-100">
                        Maonthly Target (%) <span class="text-danger">*</span>
                    </label>
                    <div class="row">
                        <div class="col-md-3 col-12">
                            <table class="table table-bordered p-1 fs-08rem">
                                <tr>
                                    <td colspan="3" class="p-1">Quarter One <span id="monthQ1Label"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">July</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="julPer" id="julPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="julAmount">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">August</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="augPer" id="augPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="augAmount">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">September</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="sepPer" id="sepPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="sepAmount">0.00</span></td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-3 col-12">
                            <table class="table table-bordered p-1 fs-08rem">
                                <tr>
                                    <td colspan="3" class="p-1">Quarter Two <span id="monthQ2Label"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">October</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="octPer" id="octPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="octAmount">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">November</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="novPer" id="novPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="novAmount">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">December</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="decPer" id="decPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="decAmount">0.00</span></td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-3 col-12">
                            <table class="table table-bordered p-1 fs-08rem">
                                <tr>
                                    <td colspan="3" class="p-1">Quarter Three <span id="monthQ3Label"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">January</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="janPer" id="janPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="janAmount">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">February</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="febPer" id="febPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="febAmount">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">March</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="marPer" id="marPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="marAmount">0.00</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-3 col-12">
                            <table class="table table-bordered p-1 fs-08rem">
                                <tr>
                                    <td colspan="3" class="p-1">Quarter Four <span id="monthQ4Label"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">April</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="aprPer" id="aprPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="aprAmount">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">May</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="mayPer" id="mayPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="mayAmount">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class="p-1 text-center" width="20%">June</td>
                                    <td class="p-1" width="20%"><input type="number"
                                            class="form-control fs-08rem p-1 text-end" name="junPer" id="junPer"
                                            min="1" max="100" onkeyup="calculateMonth()"
                                            placeholder="%">
                                    </td>
                                    <td class="p-1 fw-bold" width="60%"><span id="junAmount">0.00</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <center><small class="text-danger" id="monthlyErrorText"></small></center>
            </div>
            <center><button class="btn btn-darkblue fs-08rem p-1 mt-4 mb-2" id="insertBTN">Insert Target</button>
            </center>
        </form>
    </div>
</div>

<script>
    $("#userId").select2({
        allowClear: false
    });


    function calculateAll() {
        let totalTarget = $('#totalTarget').val();
        if (totalTarget < 1) {
            document.getElementById('insertBTN').disabled = true;
            Swal.fire("Unvalid Target Amount");
        } else {
            document.getElementById('insertBTN').disabled = false;
        }

        // Quarter One Calulcation 
        let q1Per = Number($('#q1Per').val());
        let q1Amount = Number(totalTarget) * (Number(q1Per) / 100);
        document.getElementById('q1Amount').innerText = q1Amount.toFixed(3);
        let q1EachMonthPer = q1Per / 3;
        $('#julPer').val(Math.floor(q1EachMonthPer));
        $('#augPer').val(Math.floor(q1EachMonthPer));
        if (q1EachMonthPer % 1 == 0) {
            $('#sepPer').val(Math.floor(q1EachMonthPer));
        } else {
            let upperTwoMonth = Math.floor(q1EachMonthPer) * 2;
            $('#sepPer').val((q1Per - Number(upperTwoMonth)));
        }
        document.getElementById('monthQ1Label').innerText = q1Per + '% BDT: ' + q1Amount.toFixed(3);
        calculateMonth();

        // Quarter Two Calulcation 
        let q2Per = Number($('#q2Per').val());
        let q2Amount = Number(totalTarget) * (Number(q2Per) / 100);
        document.getElementById('q2Amount').innerText = q2Amount.toFixed(3);
        let q2EachMonthPer = q2Per / 3;
        $('#octPer').val(Math.floor(q2EachMonthPer));
        $('#novPer').val(Math.floor(q2EachMonthPer));
        if (q2EachMonthPer % 1 == 0) {
            $('#decPer').val(Math.floor(q2EachMonthPer));
        } else {
            let upperTwoMonth = Math.floor(q2EachMonthPer) * 2;
            $('#decPer').val((q2Per - Number(upperTwoMonth)));
        }
        document.getElementById('monthQ2Label').innerText = q2Per + '% BDT: ' + q2Amount.toFixed(3);
        calculateMonth();

        // Quarter Three Calulcation  
        let q3Per = Number($('#q3Per').val());
        let q3Amount = Number(totalTarget) * (Number(q3Per) / 100);
        document.getElementById('q3Amount').innerText = q3Amount.toFixed(3);
        let q3EachMonthPer = q3Per / 3;
        $('#janPer').val(Math.floor(q3EachMonthPer));
        $('#febPer').val(Math.floor(q3EachMonthPer));
        if (q3EachMonthPer % 1 == 0) {
            $('#marPer').val(Math.floor(q3EachMonthPer));
        } else {
            let upperTwoMonth = Math.floor(q3EachMonthPer) * 2;
            $('#marPer').val((q3Per - Number(upperTwoMonth)));
        }
        document.getElementById('monthQ3Label').innerText = q3Per + '% BDT: ' + q3Amount.toFixed(3);
        calculateMonth();

        // Quarter Four Calulcation 
        let q4Per = Number($('#q4Per').val());
        let q4Amount = Number(totalTarget) * (Number(q4Per) / 100);
        document.getElementById('q4Amount').innerText = q4Amount.toFixed(3);
        let q4EachMonthPer = q4Per / 3;
        $('#aprPer').val(Math.floor(q4EachMonthPer));
        $('#mayPer').val(Math.floor(q4EachMonthPer));
        if (q4EachMonthPer % 1 == 0) {
            $('#junPer').val(Math.floor(q4EachMonthPer));
        } else {
            let upperTwoMonth = Math.floor(q4EachMonthPer) * 2;
            $('#junPer').val((q4Per - Number(upperTwoMonth)));
        }
        document.getElementById('monthQ4Label').innerText = q4Per + '% BDT: ' + q4Amount.toFixed(3);
        calculateMonth();

        calculateBrand();
        let totalQuarterPer = q1Per + q2Per + q3Per + q4Per;
        document.getElementById('toalQuarterPer').innerText = totalQuarterPer + '%';
        if (totalQuarterPer > 100) {
            // Swal.fire("Total percentage of quarter is exceed 100%");
            document.getElementById('toalQuarterPerText').innerText = 'Total percentage of quarter is exceed 100%';
            document.getElementById('insertBTN').disabled = true;
        } else if (totalQuarterPer < 100) {
            document.getElementById('toalQuarterPerText').innerText = 'Total percentage of quarter is below 100%';
            document.getElementById('insertBTN').disabled = true;
        } else {
            document.getElementById('toalQuarterPerText').innerText = '';
            document.getElementById('insertBTN').disabled = false;
        }


    }

    function calculateMonth() {
        let totalTarget = Number($('#totalTarget').val());

        // Quarter One Month Calulcation 
        let julPer = Number($('#julPer').val());
        let julAmount = totalTarget * julPer / 100;
        document.getElementById('julAmount').innerText = julAmount.toFixed(3);
        let augPer = Number($('#augPer').val());
        let augAmount = totalTarget * augPer / 100;
        document.getElementById('augAmount').innerText = augAmount.toFixed(3);
        let sepPer = Number($('#sepPer').val());
        let sepAmount = totalTarget * sepPer / 100;
        document.getElementById('sepAmount').innerText = sepAmount.toFixed(3);
        let totalQuarterOnePer = julPer + augPer + sepPer;

        // Quarter Two Month Calulcation 
        let octPer = Number($('#octPer').val());
        let octAmount = totalTarget * octPer / 100;
        document.getElementById('octAmount').innerText = octAmount.toFixed(3);
        let novPer = Number($('#novPer').val());
        let novAmount = totalTarget * novPer / 100;
        document.getElementById('novAmount').innerText = novAmount.toFixed(3);
        let decPer = Number($('#decPer').val());
        let decAmount = totalTarget * decPer / 100;
        document.getElementById('decAmount').innerText = decAmount.toFixed(3);
        let totalQuarterTwoPer = octPer + novPer + decPer;

        // Quarter Three Month Calulcation 
        let janPer = Number($('#janPer').val());
        let janAmount = totalTarget * janPer / 100;
        document.getElementById('janAmount').innerText = janAmount.toFixed(3);
        let febPer = Number($('#febPer').val());
        let febAmount = totalTarget * febPer / 100;
        document.getElementById('febAmount').innerText = febAmount.toFixed(3);
        let marPer = Number($('#marPer').val());
        let marAmount = totalTarget * marPer / 100;
        document.getElementById('marAmount').innerText = marAmount.toFixed(3);
        let totalQuarterThreePer = janPer + febPer + marPer;

        // Quarter Four Month Calulcation 
        let aprPer = Number($('#aprPer').val());
        let aprAmount = totalTarget * aprPer / 100;
        document.getElementById('aprAmount').innerText = aprAmount.toFixed(3);
        let mayPer = Number($('#mayPer').val());
        let mayAmount = totalTarget * mayPer / 100;
        document.getElementById('mayAmount').innerText = mayAmount.toFixed(3);
        let junPer = Number($('#junPer').val());
        let junAmount = totalTarget * junPer / 100;
        document.getElementById('junAmount').innerText = junAmount.toFixed(3);
        let totalQuarterFourPer = aprPer + mayPer + junPer;

        let q1Per = Number($('#q1Per').val());
        let q2Per = Number($('#q2Per').val());
        let q3Per = Number($('#q3Per').val());
        let q4Per = Number($('#q4Per').val());
        let showError = false;
        let errQ = '';
        if (totalQuarterOnePer > q1Per) {
            showError = true;
            errQ = 'Total percentage of quarter one is exceed ' + q1Per + '%';
        }
        if (totalQuarterOnePer < q1Per) {
            showError = true;
            errQ = 'Total percentage of quarter one is below  ' + q1Per + '%';
        }
        if (totalQuarterTwoPer > q2Per) {
            showError = true;
            errQ = 'Total percentage of quarter two is exceed ' + q2Per + '%';
        }
        if (totalQuarterTwoPer < q2Per) {
            showError = true;
            errQ = 'Total percentage of quarter two is below ' + q2Per + '%';
        }

        if (totalQuarterThreePer > q3Per) {
            showError = true;
            errQ = 'Total percentage of quarter three is exceed ' + q3Per + '%';
        }
        if (totalQuarterThreePer < q3Per) {
            showError = true;
            errQ = 'Total percentage of quarter three is below ' + q3Per + '%';
        }
        if (totalQuarterFourPer > q4Per) {
            showError = true;
            errQ = 'Total percentage of quarter four is exceed ' + q4Per + '%';
        }
        if (totalQuarterFourPer < q4Per) {
            showError = true;
            errQ = 'Total percentage of quarter four is below ' + q4Per + '%';
        }

        if (showError) {
            document.getElementById('monthlyErrorText').innerText = errQ;
            document.getElementById('insertBTN').disabled = true;
        } else {
            document.getElementById('monthlyErrorText').innerText = '';
            document.getElementById('insertBTN').disabled = false;
        }
    }

    function calculateBrand() {
        let totalTarget = Number($('#totalTarget').val());
        let allBrands = JSON.parse('<?php echo $brands; ?>');

        let q1Per = Number($('#q1Per').val());
        let q1Amount = Number(totalTarget) * (Number(q1Per) / 100);

        let q2Per = Number($('#q2Per').val());
        let q2Amount = Number(totalTarget) * (Number(q2Per) / 100);

        let q3Per = Number($('#q3Per').val());
        let q3Amount = Number(totalTarget) * (Number(q3Per) / 100);

        let q4Per = Number($('#q4Per').val());
        let q4Amount = Number(totalTarget) * (Number(q4Per) / 100);

        let totalQuarterOneBrandPer = 0;
        let totalQuarterTwoBrandPer = 0;
        let totalQuarterThreeBrandPer = 0;
        let totalQuarterFourBrandPer = 0;
        allBrands.forEach(element => {
            let brandName = element.brand_name;
            let BPerQ1 = Number($('#' + brandName + 'PerQ1').val());
            let BPerQ2 = Number($('#' + brandName + 'PerQ2').val());
            let BPerQ3 = Number($('#' + brandName + 'PerQ3').val());
            let BPerQ4 = Number($('#' + brandName + 'PerQ4').val());

            let BAmountQ1 = q1Amount * (BPerQ1 / 100);
            let BAmountQ2 = q2Amount * (BPerQ2 / 100);
            let BAmountQ3 = q3Amount * (BPerQ3 / 100);
            let BAmountQ4 = q4Amount * (BPerQ4 / 100);

            document.getElementById(brandName + 'Q1').innerText = BAmountQ1.toFixed(3);
            document.getElementById(brandName + 'Q2').innerText = BAmountQ2.toFixed(3);
            document.getElementById(brandName + 'Q3').innerText = BAmountQ3.toFixed(3);
            document.getElementById(brandName + 'Q4').innerText = BAmountQ4.toFixed(3);

            totalQuarterOneBrandPer = totalQuarterOneBrandPer + BPerQ1;
            totalQuarterTwoBrandPer = totalQuarterTwoBrandPer + BPerQ2;
            totalQuarterThreeBrandPer = totalQuarterThreeBrandPer + BPerQ3;
            totalQuarterFourBrandPer = totalQuarterFourBrandPer + BPerQ4;
        });

        let showError = false;
        let errQ = '';
        if (totalQuarterOneBrandPer > 100) {
            showError = true;
            errQ = 'Total brand percentage of quarter one is exceed 100%';
        }
        if (totalQuarterTwoBrandPer > 100) {
            showError = true;
            errQ = 'Total brand percentage of quarter two is exceed 100%';
        }
        if (totalQuarterThreeBrandPer > 100) {
            showError = true;
            errQ = 'Total brand percentage of quarter three is exceed 100%';
        }
        if (totalQuarterFourBrandPer > 100) {
            showError = true;
            errQ = 'Total brand percentage of quarter four is exceed 100%';
        }

        if (showError) {
            document.getElementById('BrandErrorText').innerText = errQ;
            document.getElementById('insertBTN').disabled = true;
        } else {
            document.getElementById('BrandErrorText').innerText = '';
            document.getElementById('insertBTN').disabled = false;
        }

    }

    $('#taregtInsertionForm').submit(function(e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }
        var form = e;
        Swal.fire({
            title: 'Are you sure?',
            // text: "Once verified, you will not be able to undo it!",
            icon: "warning",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Confirm Target',
            // denyButtonText: `Don't save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                form.delegateTarget.submit()
            } else {
                Swal.fire('Target is not inserted', '', 'info')
            }
        })
    });
</script>
