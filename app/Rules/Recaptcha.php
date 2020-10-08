<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Zttp\Zttp;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // dd('Hello');
        // Дээрхээр бусад хэсэгт юу буцааж байгааг харж болно

        return Zttp::asFormParams()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            'response' => $value, // энэ нь already linked to 'g-recaptcha-response'
            // in validate section, иймээс зүгээр $value гэж оруулна
            // 'remoteip' => $_SERVER['REMOTE_ADDR'] // Үүгээр нь цааш явуулахаар testing дээр алдаа гарах тул өөрөөр
            // хийнэ
            'remoteip' => request()->ip()
        ])->json()['success'];

        // dd($response->json()); // Үүгээр "success" => true гэсэн хариу ирнэ.

        // throw error хийх шаардлагагүйь зүгээр л return хийнэ
        // if (! $response->json()['success']) {
        //     throw new \Exception('Recatcha failed');
        // }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The recaptcha verification failed. Try again.';
    }
}
