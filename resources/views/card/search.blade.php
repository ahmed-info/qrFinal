<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Bootstrap css -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj"
      crossorigin="anonymous"
    ></script>
    <!-- my custom css -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}" />
    <title>Labor</title>
  </head>
  <body>
    <!-- Header section -->
    <header>
      <!-- navbar section -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container justify-content-around">
          <a class="navbar-brand" href="#">
            Ministry of labor and social affairs
          </a>
          
          <a href="{{route('myauth.logout')}}">
            <div class="btn btn-danger text-center">Logout</div>
          </a>
          
        </div>
        
      </nav>
    </header>

     <!-- sidenav  -->
     <div class="container sidenav">
      <div>
        <form action="{{ route('search')}}" method="GET">
          <div class="input-group">
            <input type="search" aria-label="Search" class="form-control side-search" name="getSearch" placeholder="ابحث عن شركة"/>
              </div>
              <div class="input-group">
                <button type="submit" class="btn btn-primary btn-search">Search</button>
              </div>
        </form>
          <!--companies names-  -->
        <main class="container ">
          <ul class="company-list">
            @foreach ($companies as $company)
            <li class="text-center"><span>{{$company->company_name}}</span></li>
            <hr>
            @endforeach
            
          </ul>
        </main>
      </div>
    </div>
    <!-- Showecase section -->
    <section class="bg-dark text-light p-5">
      <!-- logo image  -->
      <img
        src="{{asset('img/1-modified.png')}}"
        class="rounded mx-auto d-block"
        width="150"
        height="150"
      />
      <!--flex align of the form and buttons  -->
      <div class="container d-flex w-50 p-5">
        <!-- form container -->
        <div class="container">
            @if (session('message'))
                <div class="alert alert-{{ session('alert-type') }}" role="alert">
                    {{ session('message') }}
                </div>
            @endif
          <form method="GET" action="{{route('getCompany')}}">
            <div class="input-group">
              <input type="search" aria-label="Search" class="form-control" id="getCompany" placeholder="ادخل رقم الشركة" name="getCompany" required/>
              <button type="submit" class="btn btn-primary">Import</button>
            </div>
          </form>
          <br>
          <form action="{{route('index')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <input type="text" aria-label="Search" id="from" name="from" class="form-control" placeholder="اكتب اسم الشركة" required/>
                <button type="submit" class="btn btn-success" name="exportExcel">Export</button>
            </div>
        </form>
        </div>
      </div>
      <!-- Badge section -->
      <div class="container">
        <div class="row">
          <div class="col text-center">
            <button type="button" class="btn btn-warning">
                Completed companies <span class="badge bg-danger">{{$companies->count()}}</span>
            </button>
          </div>
        </div>
      </div>
    </section>
    <div class="companies-tags container p-5">
      <ul>
          @forelse ($companies as $company)
          <li>
                <a href="{{route('card.select', $company->id)}}">
                <span class="badge rounded-pill bg-secondary">
                    {{$company->company_name}}
                </span>
            </a>

            
            </li>
          @empty
              <div>No Company found</div>
          @endforelse
      </ul>
    </div>
    <!-- table section -->
    <div class="section bg-grey p-5 m-5">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Full name</th>
            <th scope="col">Company name</th>
            <th scope="col">Image</th>
            <th scope="col">QR Code</th>

          </tr>
        </thead>
        <tbody>
            @forelse ($cards as $card)
              <tr>
                <th scope="row">{{$card->id}}</th>
                <!-- full name -->
                <td>{{$card->full_name}}</td>
                <!-- company name -->
                <td>{{$card->company_name}}</td>
                <!-- image -->
                <td>
                  <img src="../../../{{ $card->card_img}}" width="100" height="100" class="img-thumbnail"/>
                </td>
                <!-- Qr code -->
                {{-- @include('qrlib') --}}

                {{-- <td>

                    <img src="{{asset('qr_code/'.Str::slug($card->full_name).'.png')}}" alt="">
                                    {!! QrCode::errorCorrection('L')->size(100)->format('png')->encoding('UTF-8')->generate(
                                      mb_convert_encoding($card->qr_code, "UTF-8")
                                    // $card->ss_num.'<br>'.$card->full_name.'<br>'.$card->gender.'<br>'.$card->birth_date.'<br>'.$card->release_date.'<br>'.$card->expiry_date.'<br>'.$card->national_number.'<br>'.$card->mother_name.'<br>'.$card->company_name.'<br>'.$card->location
                                    ,'../public/qr_code/'. Str::slug($card->full_name).'.png')!!}
                </td> --}}
                <td>
                  {!! QrCode::errorCorrection('L')->size(150)->encoding('UTF-8')->generate(mb_convert_encoding($card->qr_code, "UTF-8")) !!}
                </td>
              </tr>
            @empty
                
            @endforelse
            
          {{-- {!!
            require_once "C:/laragon/www/labor/vendor/autoload.php";
            use Fernet\Fernet;
              $key = "ali1ali1ali1ali1";
              $fernet = new Fernet($key);

              $token = $fernet->encode($key);
              //echo $token;
              $message = $fernet->decode($token);
              if ($message === null) {
                  echo 'Token is not valid';
              }
      
          !!} --}}
          
        </tbody>
      </table>
    </div>
  </body>
</html>