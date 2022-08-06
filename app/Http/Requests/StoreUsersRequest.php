<?php

    namespace App\Http\Requests;

    use App\Models\User;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;
    use Illuminate\Validation\Rules\Password;

    class StoreUsersRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize ()
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, mixed>
         */
        public function rules ()
        {
            $model        = $this->route('user');
            $passwordRule = $model ? [ 'nullable' ] : [ 'required' ];

            return [
                'name'                 => [ 'required', 'string', 'max:255' ],
                'email'                => [ 'bail', 'required', 'email', 'max:255', Rule::unique(User::class)->ignore($model ? $model->id : null) ],
                'password'             => [ 'bail', ...$passwordRule, Password::default() ],
                'passwordConfirmation' => [ 'bail', ...$passwordRule, 'same:password' ],
                'roleId'               => [ 'bail', 'required', 'exists:roles,id' ],
            ];
        }
    }
