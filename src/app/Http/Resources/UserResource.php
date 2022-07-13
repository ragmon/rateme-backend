<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'photos' => PhotoResource::collection($this->photos),
            // TODO: phones collection
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'lang' => $this->lang,
            'twitter' => $this->twitter,
            'github' => $this->github,
            'instagram' => $this->instagram,
            'reddit' => $this->reddit,
            'facebook' => $this->facebook,
            'telegram' => $this->telegram,
        ];
    }
}
