<?php
/**
 * Contains the CreateProject request class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-07-27
 *
 */


namespace Konekt\Stift\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Konekt\Stift\Contracts\Requests\CreateProject as CreateProjectContract;

class CreateProject extends FormRequest implements CreateProjectContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'id'        => 'sometimes|alpha_dash',
            'name'      => 'required|min:2|max:255',
            'client_id' => 'required|integer'
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }

}