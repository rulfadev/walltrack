<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <h1>Welcome, <?= session()->get('user')['username'] ?>!</h1>
    <a href="/logout">Logout</a>
</body>

</html>