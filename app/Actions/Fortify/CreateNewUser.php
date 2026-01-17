<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            // VALIDASI NIP DIHAPUS
            'nim' => ['required', 'string', 'max:255', 'unique:users'],
            'university' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:64', 'unique:users'],
            'gender' => ['required', 'string', 'in:male,female'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'nim' => $input['nim'],
            'university' => $input['university'],
            'start_date' => $input['start_date'],
            'end_date' => $input['end_date'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'gender' => $input['gender'],
            'address' => $input['address'],
            'city' => $input['city'],
            'group' => 'user',
            'password' => Hash::make($input['password']),
            'raw_password' => $input['password'],
        ]);
    }
}