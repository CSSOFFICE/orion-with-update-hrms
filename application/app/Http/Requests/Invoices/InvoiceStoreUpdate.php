<?php

/** --------------------------------------------------------------------------------
 * This middleware class validates input requests for the invoices controller
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Requests\Invoices;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceStoreUpdate extends FormRequest {
    //use App\Http\Requests\TemplateValidation;
    //function update(TemplateValidation $request,

    /**
     * we are checking authorised users via the middleware
     * so just retun true here
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * custom error messages for specific valdation checks
     * @optional
     * @return array
     */
    public function messages() {
        // echo "ll";die;
        //custom error messages
        return [
            'bill_clientid.exists' => __('lang.item_not_found'),
            'bill_categoryid.exists' => __('lang.item_not_found'),
            'bill_projectid.exists' => __('lang.item_not_found'),
            'bill_recurring_duration.required_if' => __('lang.fill_in_all_required_fields'),
        ];
    }

    /**
     * Validate the request
     * @return array
     */
    public function rules() {

        //initialize
        $rules = [];

        /**-------------------------------------------------------
         * [create] only rules
         * ------------------------------------------------------*/
        if ($this->getMethod() == 'POST') {
            $rules += [
                'client_id' => [
                    'required',
                    Rule::exists('clients', 'client_id'),
                ],
                'project_id' => [
                    'nullable',
                    Rule::exists('projects', 'project_id'),
                ],
            ];
        }

        /**-------------------------------------------------------
         * [update] only rules
         * ------------------------------------------------------*/
        if ($this->getMethod() == 'PUT') {
            $rules += [

            ];
        }

        /**-------------------------------------------------------
         * common rules for both [create] and [update] requests
         * ------------------------------------------------------*/
        $rules += [
            'bill_date' => [
                'required',
                'date',
            ],
            'bill_due_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value != '' && request('bill_date') != '' && (strtotime($value) < strtotime(request('bill_date')))) {
                        return $fail(__('lang.due_date_must_be_after_start_date'));
                    }
                }
            ],
            // 'bill_categoryid' => [
            //     'required',
            //     Rule::exists('categories', 'category_id'),
            // ],
            // 'tags' => [
            //     'bail',
            //     'nullable',
            //     'array',
            //     function ($attribute, $value, $fail) {
            //         foreach ($value as $key => $data) {
            //             if (hasHTML($data)) {
            //                 return $fail(__('lang.tags_no_html'));
            //             }
            //         }
            //     },
            // ],
        ];
        //validate
        return $rules;
    }

    /**
     * Deal with the errors - send messages to the frontend
     */
    public function failedValidation(Validator $validator) {

        $errors = $validator->errors();
        $messages = '';
        foreach ($errors->all() as $message) {
            $messages .= "<li>$message</li>";
        }

        abort(409, $messages);
    }
}
