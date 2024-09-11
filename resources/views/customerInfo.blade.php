@include('layouts.navbar')
<div class="m-2 float-end">
    <a href="{{ route('detailsLog', ['leadId' => $leadInfo->id]) }}" target="_blank"><button
            class="btn btn-darkblue btm-sm fs-07rem p-1">Details Log</button></a>
</div>
<div class="row m-2">
    <div class="col-md-5">
        <table class="table table-bordered fs-08rem">
            <tbody>
                <tr>
                    <td class="p-1">Client SAP ID</td>
                    <td class="p-1">{{ $customerInfo->sap_id }}</td>
                </tr>
                <tr>
                    <td class="p-1">Name</td>
                    <td class="p-1">{{ $customerInfo->customer_name }}</td>
                </tr>
                <tr>
                    <td class="p-1">Group Name</td>
                    <td class="p-1">{{ $customerInfo->group_name }}</td>
                </tr>
                <tr>
                    <td class="p-1">Address</td>
                    <td class="p-1">{{ $customerInfo->address }}</td>
                </tr>
                <tr>
                    <td class="p-1">District</td>
                    <td class="p-1">{{ $customerInfo->district }}</td>
                </tr>
                <tr>
                    <td class="p-1">Division</td>
                    <td class="p-1">{{ $customerInfo->division }}</td>
                </tr>
                <tr>
                    <td class="p-1">Contact Person</td>
                    <td class="p-1">{{ $customerInfo->contact_person }}</td>
                </tr>
                <tr>
                    <td class="p-1">Contact Phone</td>
                    <td class="p-1">{{ $customerInfo->contact_mobile }}</td>
                </tr>
                <tr>
                    <td class="p-1">Contact Email</td>
                    <td class="p-1">{{ $customerInfo->contact_email }}</td>
                </tr>
                <tr>
                    <td class="p-1">TIN</td>
                    @if ($customerInfo->tin)
                        <td class="p-1 text-center"><a
                                href="{{ asset('customerDocument') . '/' . $customerInfo->tin }}"
                                target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                        class="fas fa-eye"></i></button></a>
                            <a href="{{ asset('customerDocument') . '/' . $customerInfo->tin }}" target="_blank"
                                download><button class="btn btn-primary btn-sm p-1"><i
                                        class="fas fa-download"></i></button></a>
                        </td>
                    @else
                        <td class="p-1 text-center">N/A</td>
                    @endif
                </tr>
                <tr>
                    <td class="p-1">BIN</td>
                    @if ($customerInfo->bin)
                        <td class="p-1 text-center"><a
                                href="{{ asset('customerDocument') . '/' . $customerInfo->bin }}"
                                target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                        class="fas fa-eye"></i></button></a>
                            <a href="{{ asset('customerDocument') . '/' . $customerInfo->bin }}" target="_blank"
                                download><button class="btn btn-primary btn-sm p-1"><i
                                        class="fas fa-download"></i></button></a>
                        </td>
                    @else
                        <td class="p-1 text-center">N/A</td>
                    @endif
                </tr>
                <tr>
                    <td class="p-1">Trade License</td>
                    @if ($customerInfo->trade_license)
                        <td class="p-1 text-center"><a
                                href="{{ asset('customerDocument') . '/' . $customerInfo->trade_license }}"
                                target="_blank"><button class="btn btn-info btn-sm p-1"><i
                                        class="fas fa-eye"></i></button></a>
                            <a href="{{ asset('customerDocument') . '/' . $customerInfo->trade_license }}"
                                target="_blank" download><button class="btn btn-primary btn-sm p-1"><i
                                        class="fas fa-download"></i></button></a>
                        </td>
                    @else
                        <td class="p-1 text-center">N/A</td>
                    @endif
                </tr>
                <tr>
                    <td class="p-1">Status</td>
                    @if ($customerInfo->is_active == 1)
                        <td class="p-1 text-center"><small class="badge badge-success">Active</small></td>
                    @else
                        <td class="p-1 text-center"><small class="badge badge-danger">In Active</small></td>
                    @endif
                </tr>
                <tr>
                    <td class="p-1">Assigned Person</td>
                    <td class="p-1">{{ $customerInfo->assignTo->user_name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-7">
        <div class="mb-3 border border-danger p-2 shadow-3">
            @if ($leadInfo->salesLog[count($leadInfo->salesLog) - 1]->log_next == 'Customer Re-Submission')
                <p class="fs-09rem text-danger">{{ $leadInfo->salesLog[count($leadInfo->salesLog) - 1]->log_task }}</p>
            @endif
        </div>
        <center>
            <h6><kbd>Customer Update Form</kbd></h6>
        </center>
        <form action="{{ route('updateCustomerInfo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input name="customerId" value="{{ $customerInfo->id }}" hidden>
            <input name="leadId" value="{{ $leadInfo->id }}" hidden>
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">Client Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control fs-08rem" name="clientName" id="clientName"
                        value="{{ $customerInfo->customer_name }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">Group Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control fs-08rem" name="groupName" id="groupName"
                        value="{{ $customerInfo->group_name }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control fs-08rem" name="clientAddress" id="clientAddress"
                        value="{{ $customerInfo->address }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">Contact Person <span class="text-danger">*</span></label>
                    <input type="text" class="form-control fs-08rem" name="contactPerson" id="contactPerson"
                        value="{{ $customerInfo->contact_person }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">Contact Phone</label>
                    <input type="number" class="form-control fs-08rem" name="contactMobile" id="contactMobile"
                        value="{{ $customerInfo->contact_mobile }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">Contact Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control fs-08rem" name="contactEmail" id="contactEmail"
                        value="{{ $customerInfo->contact_email }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">District <span class="text-danger">*</span></label>
                    <select name="clientDistrict" id="clientDistrict" class="form-control fs-08rem" required>
                        @foreach ($districtList as $item)
                            @if ($item->dist_name == $customerInfo->district)
                                <option value="{{ $item->id }}" selected>{{ $item->dist_name }}</option>
                            @else
                                <option value="{{ $item->id }}">{{ $item->dist_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">Division <span class="text-danger">*</span></label>
                    <select name="clientDivision" id="clientDivision" class="form-control fs-08rem" required>
                        @foreach ($divisionList as $item)
                            @if ($item->div_name == $customerInfo->district)
                                <option value="{{ $item->id }}" selected>{{ $item->div_name }}</option>
                            @else
                                <option value="{{ $item->id }}">{{ $item->div_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">TIN</label>
                    <input name="customerTIN" id="customerTIN" type="file"
                        accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx"
                        class="form-control lh-sm fs-08rem">
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">BIN</label>
                    <input name="customerBIN" id="customerBIN" type="file"
                        accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx"
                        class="form-control lh-sm fs-08rem">
                </div>
                <div class="col-md-4">
                    <label class="form-label fs-08rem m-0">Trade License</label>
                    <input name="customerTL" id="customerTL" type="file"
                        accept="image/png, image/jpeg, image/jpg, .pdf, .doc,.docx"
                        class="form-control lh-sm fs-08rem">
                </div>
            </div>
            <br>
            <div class="mb-5">
                <button type="submit" class="btn btn-darkblue btn-sm w-100 fs-08rem">Update & Re-submit Document
                    Check</button>
            </div>
        </form>
    </div>
</div>
