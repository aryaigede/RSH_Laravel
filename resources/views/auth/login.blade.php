<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="{{ asset('css/variable.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
  <!-- Logo -->
  <div class="logo">
  </div>

  <!-- Left side - Login Form -->
  <div class="left">
    <div class="login-box">
      <h1>Sign in to your account</h1>
      
      <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="you@example.com" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required>
        </div>

        @if ($errors->any())
          <div class="error-messages">
            @foreach ($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        @endif

        <button type="submit">Login</button>
      </form>

      <p class="footer-text">
        Tidak punya akun? <a href="#">Daftar</a>
      </p>
    </div>
  </div>

  <!-- Right side - Brand Panel -->
  <div class="right">
    <div class="brand">
      <h2>Praktikum Framework</h2>
      <p>God grant me skill to code.</p>
    </div>
  </div>
</body>
</html>
</html>