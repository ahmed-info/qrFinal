<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="{{asset('css/dashboard_style.css')}}" rel="stylesheet" />
    <script src="{{asset('js/all.min.js')}}" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container pt-3">
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <form class="form-inline" method="GET" action="{{route('searchEnc')}}">
                    <input class="form-control mr-sm-2" type="search" name="querySearch" value="{{ old('querySearch')}}" placeholder="type string to encrypt" aria-label="Search">
                    <br>
                    <button class="btn btn-primary my-2 my-sm-0" type="submit">Encrypt</button>
                    <textarea class="form-control" rows="5">@if (isset($_GET['querySearch'])){{ $str }}@endif</textarea>
                </form>
            </div>
            
            <div class="col-sm-6 col-md-6">
                <form class="form-inline" method="GET" action="{{route('searchDec')}}">
                    <input class="form-control mr-sm-2" type="search" name="queryDec" value="{{ old('queryDec')}}" placeholder="type encrypt to string" aria-label="Search">
                    <br>
                    <button class="btn btn-primary my-2 my-sm-0" type="submit">Decrypt</button>
                    <textarea class="form-control" rows="5">@if (isset($_GET['queryDec'])){{$strDec}}@endif</textarea>
                </form>
            </div>
        </div>

        

    </div>
</body>
</html>