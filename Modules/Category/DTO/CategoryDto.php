<?php

namespace Modules\Category\DTO;

class CategoryDto
{
    public $name;

    public $description;

    public $image;

    public $category_id;

    public function __construct($request)
    {
        if ($request->get('name')) {
            $this->name = $request->get('name');
        }
        if ($request->get('description')) {
            $this->description = $request->get('description');
        }
        if ($request->hasFile('image')) {
            $this->image = $request->file('image');
        }
        if ($request->get('category_id')) {
            $this->category_id = $request->get('category_id');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->name == null) {
            unset($data['name']);
        }
        if ($this->description == null) {
            unset($data['description']);
        }
        if ($this->image == null) {
            unset($data['image']);
        }
        if ($this->category_id == null) {
            unset($data['category_id']);
        }

        return $data;
    }
}
