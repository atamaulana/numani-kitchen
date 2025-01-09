<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #111827;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        .login-container img {
            display: block; /* Jadikan elemen block */
            margin-left: auto; /* Posisikan tengah secara horizontal */
            margin-right: auto; /* Posisikan tengah secara horizontal */
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 0.5rem;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem;
            font-size: 14px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            outline: none;
            box-sizing: border-box;
        }
        .form-input:focus {
            border-color: #EAC117;
        }
        .form-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 0.25rem;
        }
        .form-checkbox {
            margin-right: 0.5rem;
        }
        .form-link {
            font-size: 12px;
            color: #EAC117;
            text-decoration: none;
        }
        .form-link:hover {
            text-decoration: underline;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 14px;
            font-weight: bold;
            color: #ffffff;
            background-color: #D0AA11;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover {
            background-color: #EAC117;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="/images/numani.png" alt="Website Logo" width="200" height="200">
        <h1>Masuk ke Panel Admin Numani Kitchen & Coffee</h1>
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <!-- Session Status -->
            @if(session('status'))
                <div class="form-error">{{ session('status') }}</div>
            @endif

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required autofocus>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" class="form-input" required>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn">Log in</button>
            </div>
        </form>
    </div>
</body>
</html>
