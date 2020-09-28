<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Email confirmation</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" >


</head>
<body>
<div id="app">
    <a href="{{request()->getSchemeAndHttpHost()}}/account_confirmation/{{$info['account_token']}}/{{$info['account_id']}}">Please follow  this link to accept invitation </a>
</div>
</body>
</html>
