<!DOCTYPE html>
<html lang="en">
    <head>  
        <meta charset="UTF-8">
        <title>Dashboard</title>
    </head>
    <body>

        <h1>Dashboard</h1>
        <p1>Welcome, {{ $user->name }}</p1>
        <p1>Your email: {{ $user->email }}</p1>

        <form method="POST" action="/logout">
            @csrf
            <button type="submit">Log out</button>
        </form>    
    </body>
</html>