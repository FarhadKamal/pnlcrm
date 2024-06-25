@include('layouts.navbar')
<div class="container-fluid mt-3 mb-5">
    <div class="d-flex flex-row justify-content-between">
        <h5>Designation Management</h5>
    </div>
    <div class="row">
        <div class="col-md-4">
            <form action="{{ route('designations') }}" method="POST">
                @csrf
                <div>
                    <label class="form-label">Designation Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control fs-08rem" name="desgName" id="desgName" required>
                </div>
                <div>
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="desgStatus" id="desgStatus" class="form-control fs-08rem">
                        <option value="1" selected>Active</option>
                        <option value="0">In-Active</option>
                    </select>
                </div>
                <button class="btn btn-sm btn-darkblue w-100 mt-2">Save</button>
            </form>
        </div>
        <div class="col-md-8">
            <table class="table">
                <thead>
                    <tr>
                        <td>SL</td>
                        <td>Designation</td>
                        <td>Status</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $sl = 1; ?>
                    @foreach ($designations as $item)
                        <?php
                        $status = $item->is_active;
                        if ($status == 1) {
                            $status = 'checked';
                            $statusName = 'Active';
                            $statusClass = 'badge-success';
                        } else {
                            $status = '';
                            $statusName = 'In-Active';
                            $statusClass = 'badge-danger';
                        }
                        ?>
                        <tr>
                            <td>{{ $sl }}</td>
                            <td>{{ $item->desg_name }}</td>
                            <td><span class="badge {{ $statusClass }}">{{ $statusName }}</span></td>
                            <td><button class="btn btn-xs btn-info p-2"
                                    onclick="editDesignation({{ $item->id }})"><i
                                        class="fas fa-pencil"></i></button></td>
                        </tr>
                        <?php $sl++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal top fade" id="desgEditModal" tabindex="-1" aria-labelledby="desgEditModalLabel" aria-hidden="true"
    data-mdb-backdrop="true" data-mdb-keyboard="true">
    <div class="modal-dialog   modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="desgEditModalLabel">Edit Designation</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('updateDesignation') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div>
                        <input type="number" id="itemId" name="itemId" hidden>
                    </div>
                    <div>
                        <label class="form-label">Designation Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control fs-08rem" name="desgEditName" id="desgEditName"
                            required>
                    </div>
                    <div>
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="desgEditStatus" id="desgEditStatus" class="form-control fs-08rem">
                            <option value="1">Active</option>
                            <option value="0">In-Active</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editDesignation(itemId) {

        var getData = {
            itemId: itemId,
            _token: '<?php echo csrf_token(); ?>'
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/editDesignation',
            data: getData,
            dataType: 'json',
            success: function(data) {
                document.getElementById("desgEditName").value = data.desg_name;
                document.getElementById("itemId").value = data.id;
                if (data.is_active == 1) {
                    $("#desgEditStatus").val(1).selected;
                } else {
                    $("#desgEditStatus").val(0).selected;
                }
                $('#desgEditModal').modal('show');
                // console.log(data);
            }
        });
    }
</script>
