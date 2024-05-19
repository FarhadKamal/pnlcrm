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
            background-color: rgba(67, 78, 88, 0.7);
            position: fixed;
        }

        .loginpage-form-container {
            height: 100vh;
            justify-content: center;
            display: flex;
            flex-direction: column;
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
        <img src="{{ asset('images/system/pnl_login_bg.jpg') }}" alt="" class="logInPage-img">
        <div class="logInPage-imgoverlay">
            <div class="loginpage-form-container">
                <center>
                    <h3 class="text-white">PNL HOLDINGS LIMITED</h3>
                </center>
                <form action="#" method="POST" style="margin: 0 auto;" class="bg-white p-2">
                    @csrf
                    <center>
                        <h5 class="mb-1">Log In</h5>
                    </center>
                    @if (session('error'))
                        <center>
                            <kbd class="badge-danger">{{ session('error') }}</kbd>
                        </center>
                    @endif
                    <input type="email" name="loginEmail" class="form-control mb-2" placeholder="Email" required>
                    <input type="password" name="loginPassword" class="form-control mb-2" placeholder="Password"
                        required>
                    <center><button type="submit" class="btn btn-info btn-sm">Log In</button></center>
                </form>
            </div>
        </div>


    </div>
</body>

</html>
