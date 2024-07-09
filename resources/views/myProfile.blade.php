@include('layouts.navbar')

<div class="container mt-3 mb-5 d-flex justify-content-between">
    {{-- <div class="row">
        <div class="col-md-4">
            <p>Name</p>
            <p>Email</p>
            <p>Phone</p>
            <p>Designation</p>
            <p>Department</p>
            <p>Location</p>
        </div>
        <div class="col-md-8">
            <p>{{$userInfo->user_name}}</p>
            <p>{{$userInfo->user_email}}</p>
            <p>{{$userInfo->user_phone}}</p>
            <p>{{$userInfo['designation']->desg_name}}</p>
            <p>{{$userInfo['department']->dept_name}}</p>
            <p>{{$userInfo['location']->location_name}}</p>
        </div>
    </div> --}}
    <div class="w-90">
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
                    <td class="p-2">{{ $userInfo['location']->location_name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <a href="{{ route('myProfileEdit') }}"><button class="btn btn-darkblue btn-sm">Edit</button></a>
    </div>
</div>
<div class="container mt-3 mb-5">
    @if (isset($userEdit))
        <div>
            @if (session('error'))
                <div class="alert alert-danger">
                    <ul>
                        @foreach (session('error') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <center>
                <h6><kbd>Profile Edit Form</kbd></h6>
            </center>
            <form action="{{ route('updateMyProfile') }}" method="POST" enctype="multipart/form-data">
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
                        <label class="form-label">User Password </label>
                        <input type="password" class="form-control fs-08rem" name="userPassword" id="userPassword">
                    </div>

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
