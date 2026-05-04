<?php

namespace Modules\Order\Service;

use Modules\Order\App\Models\Rate;

class RateService
{
    public function save(array $data): Rate
    {
        $rate = Rate::create($data);
        if (isset($data['products']) && ! empty($data['products'])) {
            foreach ($data['products'] as $product) {
                $rate->products()->create($product);
            }
        }

        return $rate->load('products');
    }
}
