<?php

namespace Modules\Client\DTO;

class AddressDto
{
    public $title;

    public $city_id;

    public $latitude;

    public $longitude;

    public $block;

    public $street;

    public $house_number;

    public $notes;

    public $default;

    public $phone;

    public function __construct($request)
    {
        if ($request->get('title')) {
            $this->title = $request->get('title');
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

        if ($request->get('block')) {
            $this->block = $request->get('block');
        }

        if ($request->get('street')) {
            $this->street = $request->get('street');
        }

        if ($request->get('house_number')) {
            $this->house_number = $request->get('house_number');
        }

        if ($request->get('notes')) {
            $this->notes = $request->get('notes');
        }

        if ($request->get('default') !== null) {
            $this->default = $request->get('default');
        }

        if ($request->get('phone')) {
            $this->phone = $request->get('phone');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);

        if ($this->title == null) {
            unset($data['title']);
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

        if ($this->block == null) {
            unset($data['block']);
        }

        if ($this->street == null) {
            unset($data['street']);
        }

        if ($this->house_number == null) {
            unset($data['house_number']);
        }

        if ($this->notes == null) {
            unset($data['notes']);
        }

        if ($this->default === null) {
            unset($data['default']);
        }

        if ($this->phone == null) {
            unset($data['phone']);
        }

        return $data;
    }
}
