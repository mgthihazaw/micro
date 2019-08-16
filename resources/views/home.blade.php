<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Microstack Office</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800,800i,900i&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" >
        
       
        <style>
            
            
            body{
                background-image : url('/images/images.jpg');
                background-repeat : no-repeat;
                background-size : cover;
                background-origin : center;
            }
            element.style {
                
                padding-right: 0px !important;
            }
            .tox-notifications-container{
                display : none !important;
            }
            .btn-xs{
                padding: .3rem .4rem;
                font-size: .775rem;
                line-height: .5;
                border-radius: .2rem;
            }
        </style>
    </head>
    <body class="hold-transition sidebar-mini">
        <div id="app">
            <app-home></app-home>
        </div>
    </body>
    
    <script src="{{ asset('js/app.js') }}"></script>
  
    
</html>