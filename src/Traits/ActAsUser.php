<?php


namespace Larapie\Core\Traits;


use Illuminate\Support\Facades\Auth;

trait ActAsUser
{
    protected function actAs($user)
    {
        Auth::login($user);
    }

    protected function user()
    {
        return null;
    }
}
