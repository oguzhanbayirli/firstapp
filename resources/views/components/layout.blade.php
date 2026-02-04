<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@isset($doctitle)
        {{ $doctitle }} | FirstApp
    @else
        FirstApp
    @endisset</title>
    
    <!-- Main stylesheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
    
    <!-- Defer Font Awesome loading -->
    <script defer src="https://use.fontawesome.com/releases/v5.5.0/js/all.js" integrity="sha384-GqVMZRt5Gn7tB9D9q7ONtcp4gtHIUEW/yG7h98J7IpE3kpi+srfFyyB/04OV6pG0" crossorigin="anonymous"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="d-flex flex-column app-min-vh">
    <header class="header-bar mb-3">
      <div class="container d-flex flex-column flex-md-row align-items-center p-3">
        <h4 class="my-0 mr-md-auto font-weight-normal text-center text-md-left"><a href="/" class="text-white">FirstApp</a></h4>
        @auth
        <div class="flex-row my-3 my-md-0 w-100 w-md-auto d-flex justify-content-center justify-content-md-end align-items-center flex-wrap">
          <a href="#" class="text-white mr-2 header-search-icon" title="Search" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-search"></i></a>
          <span class="text-white mr-2 header-chat-icon position-relative" title="Chat" data-toggle="tooltip" data-placement="bottom">
            <i class="fas fa-comment"></i>
            <span class="chat-count-badge"></span>
          </span>
          <a href="/profile/{{ auth()->user()->username }}" class="mr-2 mr-md-3" title="My Profile" data-toggle="tooltip" data-placement="bottom">
            <img class="rounded-circle border border-white header-avatar" src="{{ auth()->user()->avatar }}" alt="Profile" />
          </a>
          <a class="btn btn-sm btn-outline-light mr-2" href="/create-post">
            <i class="fas fa-pencil-alt mr-1"></i><span class="d-none d-sm-inline">Write</span>
          </a>
          <form action="/logout" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-outline-light">
              <i class="fas fa-sign-out-alt mr-1"></i><span class="d-none d-sm-inline">Logout</span>
            </button>
          </form>
        </div>
        @else
        <form action="/login" method="POST" class="mb-0 pt-2 pt-md-0 header-login-form">
            @csrf
          <div class="row align-items-center g-2">
            <div class="col-12 col-sm-6 col-md mr-0 pr-md-0 mb-2 mb-md-0">
              <input name="loginusername" class="form-control form-control-sm input-dark header-login-input" type="text" placeholder="Username" autocomplete="off" />
            </div>
            <div class="col-12 col-sm-6 col-md mr-0 pr-md-0 mb-2 mb-md-0">
              <input name="loginpassword" class="form-control form-control-sm input-dark header-login-input" type="password" placeholder="Password" />
            </div>
            <div class="col-12 col-md-auto">
              <button class="btn btn-primary btn-sm w-100 w-md-auto">Sign In</button>
            </div>
          </div>
        </form>
        @endauth
        
      </div>
    </header>
    <!-- header ends here -->
    <main class="flex-grow-1">
    @if (session()->has('success'))
    <div class="container container--narrow">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{session('success')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
    @endif

    @if (session()->has('failure'))
    <div class="container container--narrow">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i> {{session('failure')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
    @endif

    {{ $slot }}
    </main>

    <!-- footer begins -->
    <footer class="border-top text-center small text-muted py-3 mt-auto">
      <p class="m-0">Copyright &copy; 2026 <a href="/" class="text-muted">FirstApp</a>. All rights reserved.</p>
    </footer>
    @auth
      <div data-username="{{ auth()->user()->username }}" data-avatar="{{ auth()->user()->avatar }}" id="chat-wrapper" class="chat-wrapper shadow border-top border-left border-right chat--visible"></div>
    @endauth
    <!-- Defer non-critical scripts for better performance -->
    <script defer src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
