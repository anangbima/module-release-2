<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f6fa; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding: 30px; text-align: center; background-color: #3490dc; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 24px;">Reset Password</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size: 16px; color: #333333;">Halo <strong>{{ $user->name }}</strong>,</p>
                            <p style="font-size: 16px; color: #333333;">
                                Kami menerima permintaan <strong>reset password</strong> untuk akun Anda.
                            </p>
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $url }}" style="padding: 12px 25px; background-color: #3490dc; color: #ffffff; text-decoration: none; font-weight: bold; border-radius: 5px; display: inline-block;">
                                    Reset Password
                                </a>
                            </p>
                            <p style="font-size: 14px; color: #666666;">
                                Jika Anda tidak meminta reset password, abaikan email ini.
                            </p>
                            <p style="font-size: 14px; color: #666666; margin-top: 30px;">
                                Terima kasih,<br>
                                <strong>Tim Desa Digital</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; text-align: center; font-size: 12px; color: #999999; background-color: #f0f0f0;">
                            &copy; {{ date('Y') }} Desa Digital. Semua hak dilindungi.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
