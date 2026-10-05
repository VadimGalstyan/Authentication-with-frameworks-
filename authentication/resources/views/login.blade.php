<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

</head>
<body>
    @if ($errors->any())
        <ul style="color: red">
            @foreach ($errors->all() as $error )
            <li>{{ $error }}</li>

            @endforeach
        </ul>
    @endif

    <form method="POST" action="/login">
        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password">
        </div>

        <button type="submit">login</button>

    </form>

</body>
</html>