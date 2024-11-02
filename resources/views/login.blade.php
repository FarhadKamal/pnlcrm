<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CRM - PNL HOLDINGS LIMITED</title>
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/mdb.min.css') }}">
    <script src="{{ asset('js/mdb.min.js') }}"></script>
    <link href='https://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.16/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.16/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Comfortaa';
            margin: 0;
        }

        .logInPage-img {
            width: 100%;
            height: 100vh;
            position: fixed;
        }

        .logInPage-imgoverlay {
            width: 100%;
            height: 100vh;
            /* background-color: rgba(67, 78, 88, 0.7); */
            /* background: #0B2E41; */
            background-color: #FCFBF4;
            position: fixed;
        }

        .loginpage-form-container {
            /* height: 100vh; */
            justify-content: center;
            display: flex;
            flex-direction: column;
        }

        .crmText {
            color: #0056A1;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 1.2rem;
        }

        .poweredBy {
            position: fixed;
            bottom: 0;
            right: 0;
            margin-right: 2%;
            background: #FFFFFF;
        }

        @media screen and (max-width: 766px) {
            .logInPage-imgoverlay {
                position: absolute;
            }

            .imageRow {
                display: none;
            }

            .crmText {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>

    <div>
        @if (session('error'))
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: '<?= session('error') ?>',
                    showConfirmButton: false,
                    timer: 2000
                })
            </script>
        @endif
    </div>

    <div>
        {{-- <img src="{{ asset('images/system/pnl_login_bg.jpg') }}" alt="" class="logInPage-img"> --}}
        <div class="logInPage-imgoverlay">
            <div class="loginpage-form-container">
                {{-- <img src="{{ asset('images/system/pnlall.png') }}" alt="" width="350"
                        style="position: fixed; right:10%; top:45%"> --}}
                <div class="row">
                    <div class="col-md-4 text-center imageRow" style="margin-top: 11%">
                        <div class="row justify-content-evenly mt-5" style="height: 80%">
                            <div class="col-md-12"><img src="{{ asset('images/system/com/BGFlow.jpg') }}" alt=""
                                    width="180"></div>
                            <div class="col-md-12"><img src="{{ asset('images/system/com/hcp2.png') }}" alt=""
                                    width="120"></div>
                            <div class="col-md-12"><img src="{{ asset('images/system/com/itap.png') }}" alt=""
                                    width="150"></div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <center>
                            <br><br>
                            <img src="{{ asset('images/system/logo3.png') }}" alt="" width="250">
                            <br><br>
                            <h2 style="color: #0056A1; font-family:Verdana, Geneva, Tahoma, sans-serif" class="d-none">
                                PNL HOLDINGS
                                LIMITED
                            </h2>
                        </center>

                        <form action="{{ route('login') }}" method="POST"
                            style="margin: 0 auto;background-color: #0056A1;z-index:1000" class="p-2">
                            @csrf
                            <center>
                                <h5 class="mb-1 text-white p-2">Log In</h5>
                            </center>
                            @if (session('error'))
                                <center>
                                    <kbd class="badge-danger">{{ session('error') }}</kbd>
                                </center>
                            @endif
                            <input type="email" name="loginEmail" class="form-control mb-2" placeholder="Email"
                                required>
                            <input type="password" name="loginPassword" class="form-control mb-2" placeholder="Password"
                                required>
                            <center><button type="submit" class="btn btn-secondary btn-sm w-100 mt-3 mb-3">Log
                                    In</button>
                            </center>
                        </form>
                        <center>
                            <br>
                            <h5 class="crmText">Customer
                                Relationship
                                Management System</h5>
                        </center>
                    </div>
                    <div class="col-md-4 text-center imageRow" style="margin-top: 11%">
                        <div class="row justify-content-evenly mt-5" style="height: 80%">
                            <div class="col-md-12"><img src="{{ asset('images/system/com/panelli.jpg') }}"
                                    alt="" width="200"></div>
                            <div class="col-md-12"><img src="{{ asset('images/system/com/maxwell.jpg') }}"
                                    alt="" width="120"></div>
                            <div class="col-md-12"><img src="{{ asset('images/system/com/firenza2.png') }}"
                                    alt="" width="210"></div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row justify-content-evenly mb-5 imageRow ms-2">
                    <div class="col-md-2 col-5 m-2">
                        <img src="{{ asset('images/system/com/pedrollo.png') }}" alt="" width="180">
                    </div>
                    {{-- <div class="col-md-1 col-5 m-2">
                        <img src="{{ asset('images/system/com/BGFlow.jpg') }}" alt="" width="120">
                    </div>
                    <div class="col-md-1 col-5 m-2">
                        <img src="{{ asset('images/system/com/panelli.jpg') }}" alt="" width="130">
                    </div>
                    <div class="col-md-1 col-5 m-2">
                        <img src="{{ asset('images/system/com/hcp.png') }}" alt="" width="80">
                    </div>
                    <div class="col-md-1 col-5 m-2">
                        <img src="{{ asset('images/system/com/itap.png') }}" alt="" width="100">
                    </div>
                    <div class="col-md-1 col-5 m-2">
                        <img src="{{ asset('images/system/com/maxwell.jpg') }}" alt="" width="80">
                    </div>
                    <div class="col-md-1 col-5 m-2">
                        <img src="{{ asset('images/system/com/firenza.png') }}" alt="" width="120">
                    </div> --}}
                </div>
                <div class="poweredBy p-2">
                    <p>Powered By <a href="https://az-neo.com/" target="_blank">Azneo Limited</a></p>
                </div>
            </div>
        </div>


    </div>
</body>

</html>
