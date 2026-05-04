<?php

namespace Modules\Driver\Service;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Helpers\UploadHelper;
use Modules\Driver\App\Models\Driver;

class DriverService
{
    use UploadHelper;

    public function findAll($data)
    {
        $query = Driver::available()
            ->with(['branch', 'city', 'restaurant', 'orders'])
            ->orderByDesc('id');

        return getCaseCollection($query, $data);
    }

    public function findById($id)
    {
        return Driver::find($id);
    }

    public function findBy($key, $value)
    {
        return Driver::where($key, $value)->get();
    }

    public function create($data)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'driver');
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return Driver::create($data);
    }

    public function update($id, $data)
    {
        $driver = Driver::findOrFail($id);

        if (request()->hasFile('image')) {
            if ($driver->image) {
                File::delete(public_path('uploads/driver/'.$this->getImageName('driver', $driver->image)));
            }
            $data['image'] = $this->upload(request()->file('image'), 'driver');
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $driver->update($data);

        return $driver;
    }

    public function changePassword($data)
    {
        $driver = auth('driver')->user();
        $driver->update([
            'password' => Hash::make($data['new_password']),
        ]);
    }

    public function updateProfile($data)
    {
        $driver = auth('driver')->user();
        if (request()->hasFile('image')) {
            if ($driver->image) {
                File::delete(public_path('uploads/driver/'.$this->getImageName('driver', $driver->image)));
            }
            $data['image'] = $this->upload(request()->file('image'), 'driver');
        }
        $driver->update($data);
    }

    public function delete($id)
    {
        $driver = Driver::findOrFail($id);

        if ($driver->image) {
            File::delete(public_path('uploads/driver/'.$this->getImageName('driver', $driver->image)));
        }

        return $driver->delete();
    }

    public function toggleActivate(Driver $driver)
    {
        $driver->is_active = ! $driver->is_active;
        $driver->save();

        return $driver;
    }
}
