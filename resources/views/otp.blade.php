<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ваш OTP код</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .otp-code {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            margin: 20px 0;
            padding: 10px;
            border: 2px dashed #007bff;
            display: inline-block;
            border-radius: 5px;
        }
        .info {
            font-size: 16px;
            color: #555;
            line-height: 1.5;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        {{ $otp->type !== 'password_reset' ? 'Подтверждение входа' : 'Восстановление пароля' }}
    </div>
    <p class="info">Здравствуйте, <strong>{{ $user->name }}</strong>!</p>

    @if ($otp->type !== 'password_reset')
        <p class="info">Ваш OTP-код для подтверждения email <strong>{{ $user->email }}</strong>:</p>
    @else
        <p class="info">Ваш OTP-код для сброса пароля для email <strong>{{ $user->email }}</strong>:</p>
    @endif

    <div class="otp-code">{{ $otp->code }}</div>

    <p class="info">Код истекает через <strong>{{ \Carbon\Carbon::parse($otp->expires_at)->diffForHumans() }}</strong>.</p>

    <p class="info">Если вы не запрашивали этот код, просто проигнорируйте это письмо.</p>

    <div class="footer">© {{ date('Y') }} Madify. Все права защищены.</div>
</div>
</body>
</html>
