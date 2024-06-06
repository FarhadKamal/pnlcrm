@include('layouts.navbar')
<div class="container-fluid mt-3 mb-5">
    <div class="d-flex flex-row justify-content-between">
        <h5>Permission List</h5>
    </div>
    <div class="row">
        <div class="col-md-3">
            <form action="{{ route('permissions') }}" method="POST">
                @csrf
                <div>
                    <label class="form-label">Permission Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control fs-08rem" name="permName" id="permName" required>
                </div>
                <div>
                    <label class="form-label">Permission Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control fs-08rem" name="permCode" id="permCode" required>
                </div>
                <div>
                    <label class="form-label">Permission Description <span class="text-danger">*</span></label>
                    <textarea name="permDesc" id="permDesc" class="form-control fs-08rem" cols="30" rows="5" required></textarea>
                </div>
                {{-- <div>
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="locStatus" id="locStatus" class="form-control fs-08rem">
                        <option value="1" selected>Active</option>
                        <option value="0">In-Active</option>
                    </select>
                </div> --}}
                <button class="btn btn-sm btn-darkblue w-100 mt-2">Save</button>
            </form>
        </div>
        <div class="col-md-9">
            <table class="table">
                <thead>
                    <tr>
                        <td>SL</td>
                        <td>Permission Name</td>
                        <td>Permission Code</td>
                        <td>Description</td>
                        {{-- <td>Action</td> --}}
                    </tr>
                </thead>
                <tbody>
                    <?php $sl = 1; ?>
                    @foreach ($permissions as $item)
                        <tr>
                            <td>{{ $sl }}</td>
                            <td>{{ $item->permission_name }}</td>
                            <td>{{ $item->permission_code }}</td>
                            <td>{{ $item->permission_description }}</td>
                        </tr>
                        <?php $sl++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


