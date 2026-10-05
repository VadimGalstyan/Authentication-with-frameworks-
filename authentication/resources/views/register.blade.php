<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>

<body>

    <h1>
        Register
    </h1>

    @if($errors->any())
        <ul style="color: red">
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>

    @endif

    <form method="POST" action="/register">
        @csrf
        <div>
            <label>Name</label>
            <input type="text" name="name" value="{{old('name')}}">
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{old('email')}}">
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password">
        </div>

        <div>
            <label>Password confirmation</label>
            <input type="password" name="password_confirmation">
        </div>

        <button type="submit">Create account</button>
    </form>

</body>
</html>