<?php

namespace App\Http\Resources\Product;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->desc,
            'photo' => url('/product_image', $this->photo),
            'phone' => User::find($this->user_id)->phone
        ];
    }
}
