<?php

namespace App;

use App\Zindo\User_Settings\ProfileStatusManager;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['owner_id', 'status'];

    /**
     *
     * Change profile status to private.
     *
     */
    public function private ()
    {
        $this->update([
            'status' => ProfileStatusManager::STATUS_PRIVATE
        ]);
    }

    /**
     *
     * Change profile status to public.
     *
     */
    public function public ()
    {
        $this->update([
            'status' => ProfileStatusManager::STATUS_PUBLIC
        ]);
    }

    /**
     * it can check if the profile is private.
     *
     * @return bool
     */
    public function isPrivate()
    {
        return $this->status == ProfileStatusManager::STATUS_PRIVATE ? true : false;
    }
}
