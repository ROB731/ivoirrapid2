<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Connexion</title>
  <link rel="icon" type="image/png" sizes="32x32" href="https://ivoirrapid.ci/asset/Logo IRN.png" class="logo" alt="Ivoirrapid Logo">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
  <style>
    .divider:after,
    .divider:before {
      content: "";
      flex: 1;
      height: 1px;
      background: #eee;
    }
  </style>
</head>
<body>

  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row d-flex align-items-center justify-content-center h-100">
        <div class="col-md-8 col-lg-7 col-xl-6">
          <img src="{{ asset('asset/logo.png') }}" class="img-fluid" alt="Phone image">
        </div>
        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
          <img src="{{ asset('asset/Logo IRN.png') }}" alt="" class="img-fluid" width="50%" style="margin-left: 90px;">
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email input -->
            <div data-mdb-input-init class="form-outline mb-4">
              <input type="email" id="form1Example13" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus />
              <label class="form-label" for="form1Example13">Email</label>
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
  
            <!-- Password input -->
            <div data-mdb-input-init class="form-outline mb-4">
              <input type="password" id="form1Example23" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required />
              <label class="form-label" for="form1Example23">Mot de passe</label>
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
  
            <!-- Submit button -->
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-lg btn-block" style="background-color: #C1E328">{{ __('Login') }}</button>
          </form>
  
          <!-- Link to reset password -->
          <div class="mt-3">
            <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">
              Mot de passe oublié ?
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>  

</body>
</html>
