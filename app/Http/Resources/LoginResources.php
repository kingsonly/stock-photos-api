<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request):Array
    {
        
        if(isset($this->token)){
            return [
                "name" => $this->name,
                "token" => $this->token,
                "email" => $this->email,
                "status" => "success",
                "message" => "user logged in",
            ];
        }else{
            return $this->resource;
        }
        
    }

    

    public function toResponse($request)
    {
        if(isset($this->token)){
            return parent::toResponse($request)->setStatusCode(200);
        }
        return parent::toResponse($request)->setStatusCode(400);
        
    }
}
