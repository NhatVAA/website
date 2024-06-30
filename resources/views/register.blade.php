<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" >
    <form class="col-10 mx-auto p-3 border border-primary" method="post" action="{{ route('register') }}">
        <h4>LIÊN HỆ</h4> 
        <div class="mb-3">
            <label>name</label> <input class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label>Email</label> <input class="form-control" name="email" type="email" required>
        </div>
        <div class="mb-3">
            <label>password</label> <textarea class="col-md-9 form-control" type="password" name="password"></textarea>
        </div>
        <div class="mb-3">
            <label>password_confirmed</label> <textarea class="col-md-9 form-control" type="password" name="password_confirmed"></textarea>
        </div>
        <div class="mb-3">
            <label>phoneNumber</label> <input class="form-control" type="int" name="phoneNumber" required>
        </div>
        <div class="mb-3">
            <label>birth</label> <input class="form-control"type="date" name="birth" required>
        </div>
        <div class="mb-3">
            <label>gender</label> <input class="form-control" type="tinyint" name="gender" required>
        </div>
        <div class="mb-3"> @csrf
            <button type="submit" class="btn btn-warning p-2" >Gửi liên hệ</button>
        </div>
    </form> 
</body>
</html>

