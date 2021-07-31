<?php
namespace App\Helpers;

class Variable
{
    public const SUCCESS = 'Berhasil';
    public const FAILED = 'Gagal';
    public const NOT_FOUND = 'Tidak Ditemukan';

    public const SUCCESS_UPLOAD = 'Berhasil mengupload file';
    public const FAILED_UPLOAD = 'Gagal mengupload file';

    public const FAILED_LOGIN = 'Gagal Login';
    public const LOGIN_NOT_MATCH = 'Kombinasi Email dan Password salah';
    public const PASSWORD_INCORRECT = 'Password Salah';
    public const OLD_PASSWORD_INCORRECT = 'Password Lama Salah';
    public const EMAIL_NOT_REGISTERED = 'Email Tidak Terdaftar';
    public const SUCCESS_SEND_EMAIL_RESET_PWD = 'Email Reset Password Berhasil dikirim';
    public const TOKEN_INVALID = 'Token tidak valid';
    public const SUCCESS_RESET_PASSWORD = 'Berhasil Mengubah Password, silahkan login kembali ke aplikasi.';

    // User (Foto Profil)
    public const USER = 'USER';
    // Gambar Produk
    public const GPDK = 'GAMBARPRODUK';
    // Thumbnail Produk
    public const TPDK = 'THUBNAILPRODUK';
}
