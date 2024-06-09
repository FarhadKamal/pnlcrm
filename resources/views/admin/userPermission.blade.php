@include('layouts.navbar')
<div class="container-fluid mt-3 mb-5">
    <div class="d-flex flex-row justify-content-between">
        <h5>User Permission Management</h5>
    </div>
    <div>
        <table class="table fs-08rem">
            <tr>
                <td class="p-2"><strong>Name: </strong></td>
                <td class="p-2">{{ $userInfo->user_name }}</td>
            </tr>
            <tr>
                <td class="p-2"><strong>Designation: </strong></td>
                <td class="p-2">{{ $userInfo->designation['desg_name'] }}</td>
            </tr>
            <tr>
                <td class="p-2"><strong>Department: </strong></td>
                <td class="p-2">{{ $userInfo->department['dept_name'] }}</td>
            </tr>
            <tr>
                <td class="p-2"><strong>Location: </strong></td>
                <td class="p-2">{{ $userInfo->location['loc_name'] }}</td>
            </tr>
        </table>
    </div>
    <div>
        <form action="{{ route('userPermissions') }}" method="POST">
            @csrf
            <input name="userId" value="{{ $userInfo->id }}" hidden>
            <table class="table table-bordered table-hover fs-07rem">
                <thead>
                    <tr>
                        <th class="p-2 text-center">Permission</th>
                        <th class="p-2 text-center">Access</th>
                        {{-- <th class="p-2 text-center">View</th>
                    <th class="p-2 text-center">Create</th>
                    <th class="p-2 text-center">Edit</th>
                    <th class="p-2 text-center">Delete</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $item)
                        <tr>
                            <td class="p-2">{{ $item->permission_name }}</td>
                            @if (array_search($item->id, $userPermittedIds) !== false)
                            <td class="p-2 text-center"><input type="checkbox" name="permissions[]"
                                value="{{ $item->id }}" checked/></td>
                            @else
                                <td class="p-2 text-center"><input type="checkbox" name="permissions[]"
                                        value="{{ $item->id }}" /></td>
                            @endif

                            {{-- <td class="p-2 text-center"><input type="checkbox" /></td>
                        <td class="p-2 text-center"><input type="checkbox" /></td>
                        <td class="p-2 text-center"><input type="checkbox" /></td>
                        <td class="p-2 text-center"><input type="checkbox" /></td> --}}
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="p-2 text-center"><button type="submit"
                                class="btn btn-darkblue btn-sm w-100">Save User Permission</button></td>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>
</div>
