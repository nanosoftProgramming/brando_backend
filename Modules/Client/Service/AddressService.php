<?php

namespace Modules\Client\Service;

use Illuminate\Auth\Access\AuthorizationException;
use Modules\Client\App\Models\Address;
use Modules\Client\App\Models\Phone;

class AddressService
{
    public function findAll($filters = [])
    {
        $clientId = auth('client')->id();

        return Address::with(['city.country', 'client', 'phone'])
            ->where('client_id', $clientId)
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        $clientId = auth('client')->id();
        $data['client_id'] = $clientId;


        if (!Address::where('client_id', $clientId)->exists()) {
            $data['default'] = 1;
        }


        if (!empty($data['default'])) {
            Address::where('client_id', $clientId)->update(['default' => 0]);
            $data['default'] = 1;
        }

        $phone = Phone::firstOrCreate(
            ['phone' => $data['phone']],
            ['client_id' => $data['client_id']]
        );

        $data['phone_id'] = $phone->id;
        unset($data['phone']);

        return Address::create($data);
    }

    public function update(Address $address, array $data)
    {
        $clientId = auth('client')->id();
        $data['client_id'] = $clientId;

        if ((int) $address->client_id !== (int) $clientId) {
            throw new AuthorizationException('You are not allowed to update this address.');
        }

        if (isset($data['phone'])) {
            $phone = Phone::firstOrCreate(
                ['phone' => $data['phone']],
                ['client_id' => $data['client_id']]
            );
            $data['phone_id'] = $phone->id;
            unset($data['phone']);
        }


        if (array_key_exists('default', $data) && (bool) $data['default'] === true) {
            Address::where('client_id', $clientId)
                ->where('id', '!=', $address->id)
                ->update(['default' => 0]);
            $data['default'] = 1;
        }


        if (array_key_exists('default', $data) && (bool) $data['default'] === false && (bool) $address->default) {
            $otherDefaultExists = Address::where('client_id', $clientId)
                ->where('id', '!=', $address->id)
                ->where('default', 1)
                ->exists();
            if (!$otherDefaultExists) {
                $data['default'] = 1;
            }
        }

        $address->update($data);

        return $address;
    }

    public function delete(Address $address)
    {
        $clientId = auth('client')->id();

        if ((int) $address->client_id !== (int) $clientId) {
            throw new AuthorizationException('You are not allowed to delete this address.');
        }

        $wasDefault = (bool) $address->default;
        $deleted = $address->delete();

        if ($deleted && $wasDefault) {
            Address::where('client_id', $clientId)->latest('id')->limit(1)->update(['default' => 1]);
        }

        return $deleted;
    }
}
