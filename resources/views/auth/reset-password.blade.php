<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset your password</title>
    <style>
        .title {
            font-size: 24px;
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        .card {
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .mb-4 {
            margin-bottom: 16px;
        }
        .mb-8 {
            margin-bottom: 32px;
        }
        .input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .input:focus {
            outline: none;
            border-color: #666;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div>
    <h1 class="title">Reset your password</h1>

    <div class="card">
        <form action="{{ route('password.update') }}" method="post">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email --}}
            <div class="mb-4">
                <label for="email">Email</label>
                <input type="text" name="email" value="{{ old('email') }}"
                       class="input @error('email') ring-red-500 @enderror">
                @error('email')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password">Password</label>
                <input type="password" name="password" class="input @error('password') ring-red-500 @enderror">
                @error('password')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-8">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="input @error('password') ring-red-500 @enderror">
            </div>

            {{-- Submit Button --}}
            <button class="btn">Reset Password</button>
        </form>
    </div>
</div>
</body>
</html>
