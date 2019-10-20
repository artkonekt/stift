<?php
/**
 * Contains the UpdateLabel class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-10-20
 *
 */

namespace Konekt\Stift\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Konekt\Stift\Contracts\Requests\UpdateLabel as UpdateLabelContract;

class UpdateLabel extends FormRequest implements UpdateLabelContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'title'      => 'required|min:1|max:48',
            'color'      => 'nullable|min:1|max:16',
            'project_id' => 'nullable|exists:projects,id'
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
