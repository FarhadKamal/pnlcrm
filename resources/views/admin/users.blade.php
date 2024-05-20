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

<div class="container-fluid mt-3 mb-5">
    <div class="d-flex flex-row justify-content-between">
        <h5>User Management</h5>
        <button type="button" class="btn btn-darkblue btn-sm btn-rounded" data-mdb-toggle="modal"
            data-mdb-target="#userCreateleModal">Add User +</button>
    </div>
    <div>
        <!-- Modal -->
        <div class="modal top fade" id="userCreateleModal" tabindex="-1" aria-labelledby="userCreateleModalLabel"
            aria-hidden="true" data-mdb-backdrop="static" data-mdb-keyboard="true">
            <div class="modal-dialog modal-xl ">
                <form action="{{ route('users') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userCreateleModalLabel">New User Creation</h5>
                            <button type="button" class="btn-close" data-mdb-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body bg-offwhite">

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control fs-08rem" name="userName" id="userName"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control fs-08rem" name="userEmail" id="userEmail"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Phone</label>
                                    <input type="number" class="form-control fs-08rem" name="userPhone" id="userPhone">
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label class="form-label">Designation <span class="text-danger">*</span></label>
                                    <select name="userDesg" id="userDesg" class="form-control  fs-08rem">
                                        <option value="" selected disabled>--Select One--</option>
                                        {{-- @foreach ($designations as $item)
                                            <option value="{{ $item->id }}">{{ $item->desg_name }}</option>
                                        @endforeach --}}
                                        <option value="1">Assistant Manager</option>
                                        <option value="2">Senior Sales Executive</option>
                                        <option value="3">Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label class="form-label">Department <span class="text-danger">*</span></label>
                                    <select name="userDept" id="userDept" class="form-control  fs-08rem">
                                        <option value="" selected disabled>--Select One--</option>
                                        {{-- @foreach ($departments as $item)
                                            <option value="{{ $item->id }}">{{ $item->dept_name }}</option>
                                        @endforeach --}}
                                        <option value="1">Business Developement</option>
                                        <option value="2">Marketing</option>
                                        <option value="3">IT</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label class="form-label">Location <span class="text-danger">*</span></label>
                                    <select name="userLoc" id="userLoc" class="form-control  fs-08rem">
                                        <option value="" selected disabled>--Select One--</option>
                                        {{-- @foreach ($locations as $item)
                                            <option value="{{ $item->id }}">{{ $item->location_name }}</option>
                                        @endforeach --}}
                                        <option value="1">Chattogram</option>
                                        <option value="2">Dhaka</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label class="form-label">User Signature <span class="text-danger"> [Max 5
                                            mb]</span></label>
                                    <input type="file" accept=".png,.jpg,.jpeg" class="form-control fs-08rem"
                                        name="userSignature" id="userSignature">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-darkblue">Create User</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (isset($users) && count($users) > 0)
        <section class="row">
            @foreach ($users as $item)
                <div class="col-md-3 ms-1 mt-3 row shadow-4">
                    <div class="col-md-10 col-10">
                        <div class="d-flex flex-row">
                            <div>
                                <img src="{{ asset('images/system/avatar.png') }}" alt="" class="rounded-circle"
                                    width="50">
                                @if ($item->is_active != 1)
                                    <center><small class="badge badge-danger fs-06rem blink">In-Active</small></center>
                                @endif
                            </div>
                            <div class="ms-3">
                                <h6 class="m-0 fs-09rem"><strong>{{ $item->user_name }}</strong></h6>
                                <p class="m-0 fs-08rem">{{ $item->designation['desg_name'] }}</p>
                                <p class="m-0 fs-08rem">{{ $item->department['dept_name'] }}</p>
                                <p class="m-0 fs-08rem">{{ $item->location['loc_name'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-2">
                        <a href="{{ route('userInfo', ['userId' => $item->id]) }}"><button
                                class="btn btn-sm btn-success ps-2 pe-2 pt-1 pb-1 mb-1 fs-06rem"><i
                                    class="fas fa-eye"></i></button></a><br>
                        <a href="{{ route('userEdit', ['userId' => $item->id]) }}"><button
                                class="btn btn-sm btn-info ps-2 pe-2 pt-1 pb-1 mb-1 fs-06rem"><i
                                    class="fas fa-pencil"></i></button></a><br>
                                    {{-- route('userPermission', ['userId' => $item->id]) --}}
                        <a href="#"><button
                                class="btn btn-sm btn-dark ps-2 pe-2 pt-1 pb-1 mb-1 fs-06rem"><i
                                    class="fas fa-key"></i></button></a><br>
                        @if ($item->is_active != 1)
                            <a href="{{ route('activeUser', ['userId' => $item->id]) }}"><button
                                    class="btn btn-sm btn-success ps-2 pe-2 pt-1 pb-1 mb-1 fs-06rem"><i
                                        class="fas fa-user-check"></i></button></a>
                        @else
                            <a href="{{ route('inactiveUser', ['userId' => $item->id]) }}"><button
                                    class="btn btn-sm btn-danger ps-2 pe-2 pt-1 pb-1 mb-1 fs-06rem"><i
                                        class="fas fa-trash"></i></button></a>
                        @endif
                    </div>
                </div>
            @endforeach

        </section>
    @endif
</div>
