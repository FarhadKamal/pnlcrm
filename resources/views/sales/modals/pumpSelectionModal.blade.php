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
                            <label for="">Brand</label>
                            <select name="" id="" class="form-select fs-07rem p-1">
                                <option value="">Pedrollo</option>
                                <option value="">BGFlow</option>
                                <option value="">HCP</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">HP</label>
                            <select name="" id="" class="form-select fs-07rem p-1">
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
                            <select name="" id="" class="form-select fs-07rem p-1">
                                <option value="">1</option>
                                <option value="">2</option>
                                <option value="">3</option>
                                <option value="">4</option>
                                <option value="">5</option>
                                <option value="">6</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">Head</label>
                            <select name="" id="" class="form-select fs-07rem p-1">
                                <option value="">1</option>
                                <option value="">2</option>
                                <option value="">3</option>
                                <option value="">4</option>
                                <option value="">5</option>
                                <option value="">6</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-darkblue fs-06rem pt-1 pb-1 ps-1 pe-1 mt-3">Search</button>
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
                                    <th class="p-1 text-center">Qty.</th>
                                    <th class="p-1 text-center">Discount (%)</th>
                                    <th class="p-1 text-center">Net Price</th>
                                    <th class="p-1 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="d-none">1</td>
                                    <td class="p-1">4SR 4m/15-F</td>
                                    <td class="p-1">Pedrollo</td>
                                    <td class="p-1 text-center">2</td>
                                    <td class="p-1 text-center">2</td>
                                    <td class="p-1 text-end">25000</td>
                                    <td class="p-1 text-center"><input type="number" class="text-center"
                                            style="width:5rem" value="1" min="1"
                                            onchange="updatePrice(this)" onkeyup="updatePrice(this)"></td>
                                    <td class="p-1 text-center"><input type="number" class="text-center"
                                            style="width:5rem" value="0" onchange="updatePrice(this)"
                                            onkeyup="updatePrice(this)"></td>
                                    <td class="p-1 text-end totalPrice">25000</td>
                                    <td class="p-1 text-center" style="cursor: pointer" onclick="addCart(this)">
                                        <badge class="bg-darkblue text-white pt-1 pb-1 ps-2 pe-2 rounded-pill">Add</badge>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="d-none">1</td>
                                    <td class="p-1">4SR 4m/15-F</td>
                                    <td class="p-1">Pedrollo</td>
                                    <td class="p-1 text-center">2</td>
                                    <td class="p-1 text-center">2</td>
                                    <td class="p-1 text-end">25000</td>
                                    <td class="p-1 text-center"><input type="number" class="text-center"
                                            style="width:5rem" value="1" min="1"
                                            onchange="updatePrice(this)" onkeyup="updatePrice(this)"></td>
                                    <td class="p-1 text-center"><input type="number" class="text-center"
                                            style="width:5rem" value="0" onchange="updatePrice(this)"
                                            onkeyup="updatePrice(this)"></td>
                                    <td class="p-1 text-end totalPrice">25000</td>
                                    <td class="p-1 text-center" style="cursor: pointer" onclick="addCart(this)">
                                        <badge class="bg-darkblue text-white pt-1 pb-1 ps-2 pe-2 rounded-pill">Add</badge>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="d-none">1</td>
                                    <td class="p-1">4SR 4m/15-F</td>
                                    <td class="p-1">Pedrollo</td>
                                    <td class="p-1 text-center">2</td>
                                    <td class="p-1 text-center">2</td>
                                    <td class="p-1 text-end">25000</td>
                                    <td class="p-1 text-center"><input type="number" class="text-center"
                                            style="width:5rem" value="1" min="1"
                                            onchange="updatePrice(this)" onkeyup="updatePrice(this)"></td>
                                    <td class="p-1 text-center"><input type="number" class="text-center"
                                            style="width:5rem" value="0" onchange="updatePrice(this)"
                                            onkeyup="updatePrice(this)"></td>
                                    <td class="p-1 text-end totalPrice">25000</td>
                                    <td class="p-1 text-center" style="cursor: pointer" onclick="addCart(this)">
                                        <badge class="bg-darkblue text-white pt-1 pb-1 ps-2 pe-2 rounded-pill">Add</badge>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="d-none">1</td>
                                    <td class="p-1">4SR 4m/15-F</td>
                                    <td class="p-1">Pedrollo</td>
                                    <td class="p-1 text-center">2</td>
                                    <td class="p-1 text-center">2</td>
                                    <td class="p-1 text-end">25000</td>
                                    <td class="p-1 text-center"><input type="number" class="text-center"
                                            style="width:5rem" value="1" min="1"
                                            onchange="updatePrice(this)" onkeyup="updatePrice(this)"></td>
                                    <td class="p-1 text-center"><input type="number" class="text-center"
                                            style="width:5rem" value="0" onchange="updatePrice(this)"
                                            onkeyup="updatePrice(this)"></td>
                                    <td class="p-1 text-end totalPrice">25000</td>
                                    <td class="p-1 text-center" style="cursor: pointer" onclick="addCart(this)">
                                        <badge class="bg-darkblue text-white pt-1 pb-1 ps-2 pe-2 rounded-pill">Add</badge>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="d-none">1</td>
                                    <td class="p-1">4SR 4m/15-F</td>
                                    <td class="p-1">Pedrollo</td>
                                    <td class="p-1 text-center">2</td>
                                    <td class="p-1 text-center">2</td>
                                    <td class="p-1 text-end">25000</td>
                                    <td class="p-1 text-center"><input type="number" class="text-center"
                                            style="width:5rem" value="1" min="1"
                                            onchange="updatePrice(this)" onkeyup="updatePrice(this)"></td>
                                    <td class="p-1 text-center"><input type="number" class="text-center"
                                            style="width:5rem" value="0" onchange="updatePrice(this)"
                                            onkeyup="updatePrice(this)"></td>
                                    <td class="p-1 text-end totalPrice">25000</td>
                                    <td class="p-1 text-center" style="cursor: pointer" onclick="addCart(this)">
                                        <badge class="bg-darkblue text-white pt-1 pb-1 ps-2 pe-2 rounded-pill">Add</badge>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

