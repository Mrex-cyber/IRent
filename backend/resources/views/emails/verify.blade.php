<!DOCTYPE html>
<html>
<head>
    <title>Підтвердження пошти</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">

    <h2>Вітаємо, {{ $user->first_name }}!</h2>

    <p>Ви успішно зареєструвалися в нашій системі.</p>
    
    <p>Щоб активувати акаунт, натисніть на кнопку нижче:</p>

    <a href="{{ $url }}" style="background-color: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
        Підтвердити Email
    </a>

    <p style="margin-top: 20px; font-size: 12px; color: gray;">
        Якщо кнопка не працює, скопіюйте це посилання в браузер:<br>
        {{ $url }}
    </p>

</body>
</html>