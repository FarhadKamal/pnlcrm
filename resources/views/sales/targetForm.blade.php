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


<div class="container mt-2 mb-3">
    <h6 class="text-center">New Target Entry Form</h6>
    <div class="bg-offwhite p-2">
        <form action="{{ route('insertProduct') }}" method="POST" id="productInsertionForm">
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
                        Total Target (BDT) <span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control fs-08rem p-1" min="1" name="totalTarget"
                        id="totalTarget" onkeyup="calculateAll()">
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem">
                        Quarter Target (%) <span class="text-danger">*</span>
                        <br>
                        <small class="text-danger">Insert % In Each Quarter</small>
                    </label>
                    <div class="row d-none">
                        <div class="col-md-3 fs-07rem">
                            <input class="form-check-input m-0" type="checkbox" value="q1" id="flexCheckDefault" />
                            <label class="form-check-label" for="flexCheckDefault">Q1</label>
                        </div>
                        <div class="col-md-3 fs-07rem">
                            <input class="form-check-input m-0" type="checkbox" value="q2" id="flexCheckDefault" />
                            <label class="form-check-label" for="flexCheckDefault">Q2</label>
                        </div>
                        <div class="col-md-3 fs-07rem">
                            <input class="form-check-input m-0" type="checkbox" value="q3" id="flexCheckDefault" />
                            <label class="form-check-label" for="flexCheckDefault">Q3</label>
                        </div>
                        <div class="col-md-3 fs-07rem">
                            <input class="form-check-input m-0" type="checkbox" value="q4" id="flexCheckDefault" />
                            <label class="form-check-label" for="flexCheckDefault">Q4</label>
                        </div>

                    </div>
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
                    <label class="form-label fs-08rem">
                        Maonthly Target (%) <span class="text-danger">*</span>
                    </label>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered p-1 fs-08rem">
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
                        <div class="col-md-6">
                            <table class="table table-bordered p-1 fs-08rem">
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
        calculateMonth();

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
        if (totalQuarterTwoPer > q2Per) {
            showError = true;
            errQ = 'Total percentage of quarter two is exceed ' + q2Per + '%';
        }

        if (totalQuarterThreePer > q3Per) {
            showError = true;
            errQ = 'Total percentage of quarter three is exceed ' + q3Per + '%';
        }
        if (totalQuarterFourPer > q4Per) {
            showError = true;
            errQ = 'Total percentage of quarter four is exceed ' + q4Per + '%';
        }

        if (showError) {
            document.getElementById('monthlyErrorText').innerText = errQ;
            document.getElementById('insertBTN').disabled = true;
        } else {
            document.getElementById('monthlyErrorText').innerText = '';
            document.getElementById('insertBTN').disabled = false;
        }
    }
</script>
