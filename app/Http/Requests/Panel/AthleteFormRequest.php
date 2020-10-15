<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class AthleteFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:4|max:128',
            'position' => 'max:16',
            'position' => 'min:4',
            'birth' => 'date_format:"Y-m-d"|required',
            'email' => 'email',
            'number_phone' => 'required',
            'rg' => 'required|numeric',
            'address' => 'required|min:4',
            'number' => 'required',
            'neighborhood' => 'required|min:4',
            'city' => 'required|min:4',
            'uf' => 'required|max:2',
            'height' => 'required',
            'weight' => 'required',
            'phys_restriction' => 'required',
            'body_pain' => 'required',
            'faint' => 'required',
            'bone_injury' => 'required',
            'surgery' => 'required',
            'physical_disability' => 'required',
            'exercise' => 'required',
            'feeding' => 'required',
            'addiction' => 'required',
            'disease' => 'required',
            'family_disease' => 'required',
            'drug' => 'required',
            'recent_pregnancy' => 'required',
        ];

    }

    public function messages(){

        return $messages = [
            'name.required' => 'O campo nome é obrigatório',
            'name.min' => 'O mínimo de caracteres para o campo nome é :min',
            'name.max' => 'O máximo de caracteres para o campo nome é :max',
            'position.max' => 'O máximo de caracteres para o campo posição é :max',
            'position.min' => 'O mínimo de caracteres para o campo posição é :min',
            'birth.required' => 'O campo data de nascimento é obrigatório',
            'birth.date_format' => 'O campo data de nascimento deve ter o formato DD/MM/AAAA',
            'email.email' => 'O campo E-Mail deve ser um formato válido de email',
            'number_phone.required' => 'O campo telefone é obrigatório',
            'rg.required' => 'O campo RG é obrigatório',
            'rg.numeric' => 'O campo RG deve conter apenas números',
            'address.required' => 'O campo rua é obrigatório',
            'address.min' => 'O campo rua deve conter no mínimo :min caracteres',
            'number.required' => 'O campo número é obrigatório',
            'neighborhood.required' => 'O campo bairro é obrigatório',
            'neighborhood.min' => 'O campo bairro deve conter no mínimo :min caracteres',
            'city.required' => 'O campo cidade é obrigatório',
            'city.min' => 'O campo cidade deve conter no mínimo :min caracteres',
            'uf.required' => 'O campo UF é obrigatório',
            'height.required' => 'O campo altura é obrigatório',
            'weight.required' => 'O campo peso é obrigatório',
            'phys_restriction.required' => 'Todos os campos com * são obrigatórios',
            'body_pain.required' => 'Todos os campos com * são obrigatórios',
            'faint.required' => 'Todos os campos com * são obrigatórios',
            'bone_injury.required' => 'Todos os campos com * são obrigatórios',
            'surgery.required' => 'Todos os campos com * são obrigatórios',
            'physical_disability.required' => 'Todos os campos com * são obrigatórios',
            'exercise.required' => 'Todos os campos com * são obrigatórios',
            'feeding.required' => 'Todos os campos com * são obrigatórios',
            'addiction.required' => 'Todos os campos com * são obrigatórios',
            'disease.required' => 'Todos os campos com * são obrigatórios',
            'family_disease.required' => 'Todos os campos com * são obrigatórios',
            'drug.required' => 'Todos os campos com * são obrigatórios',
            'recent_pregnancy.required' => 'Todos os campos com * são obrigatórios',
        ];

    }
}
