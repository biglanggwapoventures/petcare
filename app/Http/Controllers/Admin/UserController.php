<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends CRUDController
{
    protected $filterFields = [
        'email' => ['email', '='],
        'role' => ['role', '='],
    ];

    public function __construct(User $model, Request $request)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => $this->validationRules('store', $model, $request),
            'update' => $this->validationRules('update', $model, $request),
        ];
    }

    public function beforeUpdate()
    {
        if (!trim(request()->password)) {
            unset($this->validatedInput['password']);
        }
    }

    private function validationRules($method, $model, $request)
    {
        $rules = [
            'firstname' => ['required'],
            'lastname' => ['required'],
            'contact_number' => ['required'],
            'address' => ['sometimes'],
            'gender' => ['required', Rule::in(['MALE', 'FEMALE'])],
            'role' => ['sometimes', Rule::in(['DOCTOR', 'CUSTOMER', 'STAFF', 'ADMIN'])],
            'role_title' => ['required'],
        ];

        if ($method === 'store') {
            $rules += [
                'username' => ['required', Rule::unique($model->getTable())],
                'email' => ['required', 'email', Rule::unique($model->getTable())],
                'password' => 'required|min:6',
                'password_confirmation' => 'required|same:password',
            ];
        } else {
            $rules += [
                'username' => ['required', Rule::unique($model->getTable())->ignore($request->route('user'))],
                'email' => ['required', 'email', Rule::unique($model->getTable())->ignore($request->route('user'))],
                'password' => 'present|nullable|min:6',
                'password_confirmation' => 'present|nullable|same:password',
            ];
        }

        return $rules;
    }

    public function afterStore($user)
    {
        if (Auth::guest()) {
            Auth::login($user);
        }
    }

    public function beforeIndex($query)
    {
        $status = in_array(request()->status, ['blocked', 'unblocked']) ? request()->status : '';
        $name = trim(request()->name);
        collect($this->filterFields)->each(function ($value, $key) use ($query) {
            if ($filter = request()->{$key}) {
                list($column, $operand) = $value;
                $query->where($column, $operand, $filter);
            }
        });

        $query->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$name}%'")
            ->loginStatus($status)
            ->with('blocked');
    }
}
