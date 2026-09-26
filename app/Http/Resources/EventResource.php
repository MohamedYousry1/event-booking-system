<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CategoryResource;
class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'start_date' => $this->start_date->diffForHumans(),
            'end_date' => $this->end_date->diffForHumans(),
            'capacity' => $this->capacity,
            'available_seats' => $this->available_seats,
            'price' => $this->price,
            'image' => $this->image,
            'status' => $this->status,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'category' => new CategoryResource($this->category)
        ];
    }
}
