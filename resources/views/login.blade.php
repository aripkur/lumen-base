
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Login</title>
    <link rel="icon" href="/assets/adminlte/image/icon-app.png" type="image/icon">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/assets/adminlte/plugin/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugin/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/css/adminlte.min.css">
    <link rel="stylesheet" href="/assets/adminlte/css/login.css">

    <script src="/assets/adminlte/plugin/jquery/jquery.min.js"></script>
    <script src="/assets/adminlte/plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/adminlte/js/adminlte.min.js"></script>
</head>
<body class="hold-transition login-page wrapper">
<div class="login-box">

  <div class="card">
        <div class="card-header text-center">
            <h2 class="font-weight-bold">{{ str_replace("-", " ", env('APP_NAME', "RSU Islam Boyolali"))}}</h2>
        </div>
        <div class="card-body">
            <div id="status"></div>
            <form id="form">
                <div class="form-group">
                    <label>Username </label>
                    <input type="text" name="username" class="form-control" placeholder="username">
                </div>
                <div class="form-group">
                    <label>Password </label>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Captcha code </label>
                            <input type="hidden" id="captcha_id" name="captcha_id">
                            <image src="" id="captcha_img" class="border rounded"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Enter Captcha </label>
                            <input type="text" id="captcha" name="captcha" class="form-control" placeholder="">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block" id="submit_btn">Masuk</button>
            </form>
        </div>

  </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        let message = new URLSearchParams(window.location.search).get('message')
        if(message){
            $("#status").addClass("alert alert-danger")
            $("#status").html(message)
        }
        loadCaptchaImage()
    })
    function loadCaptchaImage() {
        $.ajax({
            method: 'GET',
            url: window.origin + '/captcha',
            success: function (result) {
                $("#captcha_id").val(result.data.id);
                $("#captcha").val('');
                $("#captcha_img").attr("src", result.data.file);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if(jqXHR.status == 429){
                    $("#status").addClass("alert alert-danger")
                    $("#status").html(jqXHR.responseJSON?.metadata?.message || "Request terlalu banyak")
                }
            },
        });
    }

    $("#submit_btn").click( e=>{
        e.preventDefault()
        let form =  $("#form").serialize();

        $("#submit_btn").prop("disabled", true)
        $("#submit_btn").text("Proses ...")

        $("#status").removeClass("alert alert-danger")
        $("#status").removeClass("alert alert-success")
        $("#status").html("")

        $.ajax({
            method: 'POST',
            dataType: 'JSON',
            data: form,
            url: window.origin + '/login',
            success: function (result) {
                localStorage.setItem('token', result.data.access_token);

                $("#submit_btn").prop("disabled", false)
                $("#submit_btn").text("Masuk")

                $("#status").addClass("alert alert-success")
                $("#status").html(`Berhasil masuk. <small class="d-block">tunggu anda akan dialihkan ke halaman selanjutnya...</small>`)
                setTimeout(() => {
                    window.location.replace("/")
                }, 500);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#submit_btn").prop("disabled", false)
                $("#submit_btn").text("Masuk")

                $("#status").addClass("alert alert-danger")
                $("#status").html(`Gagal masuk, cek inputan form !`)
                loadCaptchaImage()
            },
        });
    })

</script>
</body>
</html>
