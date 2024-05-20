@include('layouts.navbar')
<div class="container mt-3 mb-4">
    @if (session('successUserInfo'))
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'User Info Updated',
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    @endif

    @if (session('error'))
        <?php 
            for($i=0; $i<count(session('error')); $i++){
                $totalErrorMsg = session('error')[$i]; 
                ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                text: '<?php echo $totalErrorMsg; ?>',
                customClass: 'swal-wide',
                showConfirmButton: false,
                timer: 2000
            })
        </script>
        <?php 
        }
        ?>
    @endif
    <div>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td class="p-2">Name</td>
                    <td class="p-2">{{ $userInfo->user_name }}</td>
                </tr>
                <tr>
                    <td class="p-2">Email</td>
                    <td class="p-2">{{ $userInfo->user_email }}</td>
                </tr>
                <tr>
                    <td class="p-2">Phone</td>
                    <td class="p-2">{{ $userInfo->user_phone }}</td>
                </tr>
                <tr>
                    <td class="p-2">Designation</td>
                    <td class="p-2">{{ $userInfo['designation']->desg_name }}</td>
                </tr>
                <tr>
                    <td class="p-2">Department</td>
                    <td class="p-2">{{ $userInfo['department']->dept_name }}</td>
                </tr>
                <tr>
                    <td class="p-2">Location</td>
                    <td class="p-2">{{ $userInfo['location']->loc_name }}</td>
                </tr>
                <tr>
                    <td class="p-2">Signature</td>
                    @empty(!$userInfo->user_signature)
                        <td class="p-2"><img src="{{ asset('images/userSignature/') . '/' . $userInfo->user_signature }}"
                                alt="User Signature" width="150px"></td>
                    @endempty
                </tr>
            </tbody>
        </table>
    </div>
    @if (isset($userEdit))
        <div>
            <center>
                <h6><kbd>User Edit Form</kbd></h6>
            </center>
            <form action="{{ route('editUsers') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input name="userId" value="{{ $userInfo->id }}" hidden>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control fs-08rem" name="userName" id="userName"
                            value="{{ $userInfo->user_name }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control fs-08rem" name="userEmail" id="userEmail"
                            value="{{ $userInfo->user_email }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <input type="number" class="form-control fs-08rem" name="userPhone" id="userPhone"
                            value="{{ $userInfo->user_phone }}">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                        <select name="userDesg" id="userDesg" class="form-control  fs-08rem">
                            {{-- <option value="" selected disabled>--Select One--</option> --}}
                            @foreach ($designations as $item)
                                @if ($userInfo->user_desg == $item->id)
                                    <option value="{{ $item->id }}" selected>{{ $item->desg_name }}</option>
                                @else
                                    <option value="{{ $item->id }}">{{ $item->desg_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="userDept" id="userDept" class="form-control  fs-08rem">
                            <option value="" selected disabled>--Select One--</option>
                            @foreach ($departments as $item)
                                @if ($userInfo->user_dept == $item->id)
                                    <option value="{{ $item->id }}" selected>{{ $item->dept_name }}</option>
                                @else
                                    <option value="{{ $item->id }}">{{ $item->dept_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="form-label">Location <span class="text-danger">*</span></label>
                        <select name="userLoc" id="userLoc" class="form-control  fs-08rem">
                            <option value="" selected disabled>--Select One--</option>
                            @foreach ($locations as $item)
                                @if ($userInfo->user_location == $item->id)
                                    <option value="{{ $item->id }}" selected>{{ $item->loc_name }}</option>
                                @else
                                    <option value="{{ $item->id }}">{{ $item->loc_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="form-label">User Password </label>
                        <input type="password" class="form-control fs-08rem" name="userPassword" id="userPassword">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-2">
                        <label class="form-label">Your Signature</label>
                    @empty(!$userInfo->user_signature)
                        <img src="{{ asset('images/userSignature/') . '/' . $userInfo->user_signature }}"
                            alt="User Signature" width="150px">
                    @endempty

                </div>
                <div class="col-md-3 mt-2">
                    <label class="form-label">User Signature <span class="text-danger">[Max 5 mb]</span></label>
                    <input type="file" accept=".png,.jpg,.jpeg" class="form-control fs-08rem"
                        name="userSignature" id="userSignature">
                </div>
            </div>
            <br>
            <div>
                <button type="submit" class="btn btn-darkblue btn-sm w-100">Save Information</button>
            </div>
        </form>
    </div>
@endif
</div>
