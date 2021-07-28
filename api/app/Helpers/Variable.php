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
    public const TEST = 'TEST';
    // Companies
    public const COMP = 'COMP';
    // Office
    public const OFFC = 'OFFC';
    // User (Foto Profil)
    public const USER = 'USER';
    // Shift Permission
    public const SHPE = 'SHPE';
    // Lembur Permission
    public const LEPE = 'LEPE';
    // Cuti Permission
    public const CTPE = 'CTPE';
    // Special Checkin
    public const SPCK = 'SPCK';
}
