<?php

namespace Modules\Client\DTO;

use Illuminate\Support\Facades\Hash;

class ClientDto
{
    public $name;

    public $email;

    public $phone;

    public $password;

    public $date_of_birth;

    public $auth_id;

    public $auth_type;

    public $image;

    public function __construct($request)
    {
        if ($request->get('name')) {
            $this->name = $request->get('name');
        }
        if ($request->get('email')) {
            $this->email = $request->get('email');
        }
        if ($request->get('phone')) {
            $this->phone = $request->get('phone');
        }
        if ($request->get('date_of_birth')) {
            $this->date_of_birth = $request->get('date_of_birth');
        }
        if ($request->get('password')) {
            $this->password = Hash::make($request->get('password'));
        }
        if ($request->get('auth_id')) {
            $this->auth_id = $request->get('auth_id');
        }
        if ($request->get('auth_type')) {
            $this->auth_type = $request->get('auth_type');
        }
        if ($request->get('image')) {
            $this->image = $request->get('image');
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
        if ($this->date_of_birth == null) {
            unset($data['date_of_birth']);
        }
        if ($this->password == null) {
            unset($data['password']);
        }
        if ($this->auth_id == null) {
            unset($data['auth_id']);
        }
        if ($this->auth_type == null) {
            unset($data['auth_type']);
        }
        if ($this->image == null) {
            unset($data['image']);
        }
        // $data['verify_code'] = rand(1000,9999);
        $data['verify_code'] = 9999;

        return $data;
    }
}
