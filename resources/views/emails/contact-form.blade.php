<!DOCTYPE html>
<html>
<head>
    <title>Thư liên hệ mới</title>
</head>
<body>
    <h2>Bạn có một thư liên hệ mới từ website!</h2>
    <p><strong>Từ:</strong> {{ $details['name'] }} ({{ $details['email'] }})</p>
    <p><strong>Chủ đề:</strong> {{ $details['subject'] }}</p>
    <hr>
    <p><strong>Nội dung:</strong></p>
    <p>{{ $details['message'] }}</p>
</body>
</html>