<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <link rel="shortcut icon" href="{{ url('assets/images/favicon.ico') }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FileManager</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ url(config('filemanager.assets_path') . 'css/filemanager.css') }}">
    
    <script src="{{ url(config('filemanager.assets_path') . 'js/filemanager.js') }}"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        window.onload = function () {
            ko.applyBindings();
        };
    </script>
</head>
<body>
    <filemanager params="url: '{{ uri() }}',
        public_path: '{{ url("/") }}/',
        token: '{{ csrf_token() }}',
        template: 'filemanager-template',
        lang: '{{ App::getLocale() }}',
        type: '{{ Request::has("CKEditor") ? 'CKEditor':(Request::has("backgroundID") ? 'backgroundID': (Request::has("imageID") ? 'imageID':'fieldID')) }}',
        fieldID: '{{ Request::has("CKEditor") ? Request::get("CKEditor"):(Request::has("backgroundID") ? Request::get("backgroundID"):(Request::has("imageID") ? Request::get("imageID"):Request::get("fieldID"))) }}',
        CKEditorFuncNum: '{{ Request::get("CKEditorFuncNum") }}',
        callback: '{{ Request::get("callback") }}'
        "></filemanager>
</body>
</html>
