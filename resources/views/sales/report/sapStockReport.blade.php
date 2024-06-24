@include('layouts.navbar')
<div class="container p-2 mt-3">
    <center>
        <h4><kbd>SAP Stock Report</kbd></h4>
    </center>

    <div>
        <table class="table table-bordered fs-08rem">
            <thead>
                <tr>
                    <th class="p-1 text-center">Item Name</th>
                    <th class="p-1 text-center">WHS Code</th>
                    <th class="p-1 text-center">WHS Name</th>
                    <th class="p-1 text-center">Available QTY</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($responseData as $item)
                    <tr>
                        <td class="p-1">{{ $item->ItemName }}</td>
                        <td class="p-1 text-center">{{ $item->WhsCode }}</td>
                        <td class="p-1">{{ $item->WhsName }}</td>
                        <td class="p-1 text-center">{{ $item->Available_QTY }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
