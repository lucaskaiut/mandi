<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class CompanyFormRequest extends FormRequest
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
            'nome' => 'required',
            'apelido' => 'required',
            'cnpj' => 'required_without_all:cpf, cnpj',
            'email' => 'required|email',
            'telefone' => 'required',
            'cep' => 'required',
            'endereco' => 'required',
            'numero' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'uf' => 'required',
        ];
    }

    public function messages()
    {

        return $messages = [
            'nome.required' => 'O campo nome é obrigatório',
            'apelido.required' => 'O campo apelido é obrigatório',
            'cnpj.required_without_all' => 'Você deve digitar o CPF ou o CNPJ',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo E-Mail deve ser um formato válido de email',
            'telefone.required' => 'O campo telefone é obrigatório',
            'cep.required' => 'O campo CEP é obrigatório',
            'endereco.required' => 'O campo endereço é obrigatório',
            'numero.required' => 'O campo número é obrigatório',
            'bairro.required' => 'O campo bairro é obrigatório',
            'cidade.required' => 'O campo cidade é obrigatório',
            'uf.required' => 'O campo UF é obrigatório',
        ];

    }
}
