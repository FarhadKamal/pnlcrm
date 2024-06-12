<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRM - PNL HOLDINGS LTD.</title>
    <!-- Material Design Bootstrap -->

    <link rel="stylesheet" href="{{ asset('css/mdb.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="{{ asset('js/mdb.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>

    <script src="{{ asset('js/all.js') }}"></script>
    <link href='https://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet'>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"
        type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.16/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.16/dist/sweetalert2.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    @if (session('error'))
        @foreach (session('error') as $errorMsg)
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "<?php echo $errorMsg; ?>",
                    showConfirmButton: false,
                    timer: 2000
                })
            </script>
        @endforeach
    @endif

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: #0B2E41">
        <!-- Container wrapper -->
        <div class="container-fluid">
            <!-- Navbar brand -->
            <a class="navbar-brand me-2" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/system/logo.png') }}" class="rounded" height="43" alt="PNL Logo"
                    loading="lazy" style="margin-top: -2px;" />
            </a>



            <div class="navbar-toggler">
                <ul class="navbar-nav d-flex flex-row justify-content-evenly">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customerForm') }}"><button
                                class="btn btn-sm create-lead-btn"><strong>New
                                    Customer</strong></button></a>
                    </li>
                </ul>
            </div>

            <button class="navbar-toggler" type="button" aria-expanded="false" aria-label="Toggle navigation"
                onclick="sidebarToggole()">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarButtonsExample">
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink"
                            role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                            Inventory
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="#">Menu 1</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Menu 2</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink"
                            role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                            More
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="#">Outstanding List</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink"
                            role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                            Reports
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="#">Stock Report</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Userwise Sales
                                    Report</a>
                            </li>
                        </ul>
                    </li>
                    {{-- @if (Auth()->user()->is_admin == 1) --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink"
                            role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                            Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="#">Company List</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('users') }}">User</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Designation</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Department</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">System Location</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Zone</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">District</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Division</a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="#">Lead Source</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('permissions') }}">Permissions</a>
                            </li>
                        </ul>
                    </li>
                    {{-- @endif --}}
                </ul>
                <!-- Left links -->

                <div class="d-flex align-items-center">
                    <ul class="navbar-nav">
                        {{-- @if (App\Helpers\Helper::permissionCheck(Auth()->user()->id, 'leadForm')) --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customerForm') }}"><button
                                    class="btn btn-sm create-lead-btn"><strong>New
                                        Customer</strong></button></a>
                        </li>
                        {{-- @endif --}}
                        <li class="nav-item">
                            <p class="bg-white ps-2 pe-2 pt-1 pb-1 rounded mt-2 me-2 fs-08rem"><b>
                                    Irfanul Kalam Chowdhury, <small>Sr Executive</small>
                            </p>
                        </li>

                        <!-- Avatar -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('images/system/avatar.png') }}" class="rounded-circle"
                                    height="30" alt="Profile Image" loading="lazy" />
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="left:-110px">
                                <li>
                                    <a class="dropdown-item" href="#">My profile</a>
                                </li>
                                {{-- <li>
                                <a class="dropdown-item" href="#">Settings</a>
                            </li> --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->

    <div>
        <img src="{{ asset('images/system/droplet.gif') }}" alt="" id="loadingGif">
        <h1 id="loadingText">Loading...</h1>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


</html>
