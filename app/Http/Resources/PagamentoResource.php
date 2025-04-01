<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PagamentoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'invoiceNumber' => $this['invoiceNumber'] ?? null,
            'billingType' => $this['billingType'],
            'invoiceUrl' => $this['invoiceUrl'] ?? null,
            'pixQrCode' => $this['pixQrCode']['encodedImage'] ?? null,
            'pixCopiaCola' => $this['pixQrCode']['payload'] ?? null,
            'value' => $this['value'],
            'status' => $this['status'] ?? 'PENDING'
        ];
    }
}
