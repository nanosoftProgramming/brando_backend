<?php

namespace Modules\Driver\DTO;

use Illuminate\Support\Facades\Hash;

class DriverDto
{
    public $name;

    public $email;

    public $phone;

    public $password;

    public $image;

    public $license_id;

    public $city_id;

    public $latitude;

    public $longitude;

    public $is_active;

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

        if ($request->get('license_id')) {
            $this->license_id = $request->get('license_id');
        }

        // if ($request->get('password')) {
        //     $this->password = Hash::make($request->get('password'));
        // }
        if ($request->get('password')) {
    $this->password = $request->get('password');
}

        if ($request->hasFile('image')) {
            $this->image = $request->file('image');
        }

        if ($request->get('city_id')) {
            $this->city_id = $request->get('city_id');
        }

        if ($request->get('latitude')) {
            $this->latitude = $request->get('latitude');
        }

        if ($request->get('longitude')) {
            $this->longitude = $request->get('longitude');
        }

        if ($request->get('is_active')) {
            $this->is_active = $request->get('is_active');
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

        if ($this->image == null) {
            unset($data['image']);
        }

        if ($this->license_id == null) {
            unset($data['license_id']);
        }

        if ($this->city_id == null) {
            unset($data['city_id']);
        }

        if ($this->latitude == null) {
            unset($data['latitude']);
        }

        if ($this->longitude == null) {
            unset($data['longitude']);
        }

        if ($this->is_active == null) {
            unset($data['is_active']);
        }

        return $data;
    }
}
