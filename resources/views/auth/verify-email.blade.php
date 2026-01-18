<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2>Vui lòng xác thực địa chỉ email của bạn</h2></div>
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Một liên kết xác thực mới đã được gửi đến địa chỉ email của bạn.
                        </div>
                    @endif

                    <p>Trước khi tiếp tục, vui lòng kiểm tra email của bạn để lấy liên kết xác thực.</p>
                    <p>Nếu bạn không nhận được email, hãy nhấn vào nút bên dưới để gửi lại.</p>

                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Gửi lại email xác thực</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>