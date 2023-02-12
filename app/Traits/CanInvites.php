<?php

namespace App\Traits;

use App\Models\Groupable;
use App\Models\Invite;
use App\Models\User;
use Event;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait CanInvites
{
    /**
     * Return invites received.
     *
     * @return mixed
     */
    public function invites(): MorphOne
    {
        return $this->morphOne(Invite::class, 'claimer');
    }

    /**
     * Return invitations from the model.
     *
     * @return mixed
     */
    public function invitations()
    {
        return $this->morphOne(Invite::class, 'creator');
    }

    /**
     * Send invite to user or email.
     *
     * @param $emailOrEntity string|Model
     * @param null $type
     *
     * @return Invite|static
     *
     * @throws Exception
     *
     * @internal param $email
     */
    public function sendInvite($emailOrEntity, $type = null, $message = null, $object = null)
    {
        $data = [];

        if (is_string($emailOrEntity)) {
            $data['email'] = $emailOrEntity;
        } elseif (isset($emailOrEntity->email)) {
            if (isset($emailOrEntity->email)) {
                $data['email'] = $emailOrEntity->email;
            }

            $data = [
                'claimer_id' => $emailOrEntity->id,
                'claimer_type' => $emailOrEntity->getMorphClass(),
            ];
        } else {
            throw new Exception('$entity should have an email');
        }

        $data['creator_id'] = $this->id;
        $data['creator_type'] = $this->getMorphClass();

        if ($type) {
            $data['type'] = $type;
        }

        if ($message) {
            $data['message'] = $message;
        }

        if ($object) {
            $data['object_type'] = get_class($object);
            $data['object_id'] = $object->id;
        }

        $invite = Invite::getNewCode($data);

        // Event::fire('invite.sent', [$this, $emailOrEntity, $type]);

        return $invite;
    }
}
