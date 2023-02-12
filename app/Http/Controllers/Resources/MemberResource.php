<?php

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    protected static $using;

    public static function using($using = null)
    {
        static::$using = $using;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->resource;
        $companyDetails = [];

        if (static::$using !== null && $this->merge(static::$using) !== null) {
            $companyId = $this->merge(static::$using)->data;

            if ($companyId === null) {
                return;
            }
            $company = Company::findOrFail($companyId);

            $companyDetails = [
                'role' => $user->getRole($company),
                'company' => $company,
            ];
        }

        $data = array_merge($companyDetails, [
            'id' => (int) $user->id,
            'name' => (string) $user->firstname.' '.$user->lastname,
            'email' => (string) $user->email,
            'firstname' => (string) $user->firstname,
            'lastname' => (string) $user->lastname,
            'appRole' => $user->appRole(),
        ]);

        return collect($data);
    }
}
