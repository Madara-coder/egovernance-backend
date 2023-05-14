<?php

namespace App\Repositories;

use App\Models\User;
use App\Traits\ResponseAPI;

class UserRepository extends BaseRepository
{
    use ResponseAPI;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        parent::__construct($user);
        $this->rules = [
            "name"  =>  "required",
            "email" =>  "required",
            "password"   =>  "required|min:6",
            "address"   =>  "required",
            "number"    =>  "required",
        ];
    }
}
