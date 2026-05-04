<?php

namespace Modules\Branch\DTO;

use Illuminate\Support\Facades\Hash;

class BranchManagerDto
{
    public $name;

    public $email;

    public $phone;

    public $password;

    public $restaurant_id;

    public function __construct($request)
    {
        if ($request->get('manager_name')) {
            $this->name = $request->get('manager_name');
        }
        if ($request->get('email')) {
            $this->email = $request->get('email');
        }
        if ($request->get('manager_phone')) {
            $this->phone = $request->get('manager_phone');
        }
        if ($request->get('password')) {
            $this->password = Hash::make($request->get('password'));
        }
        if ($request->get('restaurant_id')) {
            $this->restaurant_id = $request->get('restaurant_id');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->name == null) {
            unset($data['name']);
        }
        if ($this->email == null) {
            unset($data['email']);
        }
        if ($this->phone == null) {
            unset($data['phone']);
        }
        if ($this->password == null) {
            unset($data['password']);
        }

        return $data;
    }
}
