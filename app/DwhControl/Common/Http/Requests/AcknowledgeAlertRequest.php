<?php

use App\DwhControl\Api\Http\Requests\Request;

class AcknowledgeAlertRequest extends Request
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer'
        ];
    }

}
