<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    protected $company_id;

    function __construct($company_id) {
            $this->company_id = $company_id;
    }
    public function collection()
    {
        return User::where('company_id', $this->company_id)
        ->get(['username', 'email', 'admin', 'aktif']);
    }
}
