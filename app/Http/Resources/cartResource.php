<?php

namespace App\Http\Resources;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class cartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'product_id' => Product::find($this->products_id)->id,
            'name' => Product::find($this->products_id)->name,
            'price' => Product::find($this->products_id)->price,
            'img' => Product::find($this->products_id)->img,
        ];
        
    }
}
