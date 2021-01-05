<?php

namespace App\Message;

use Symfony\Component\Uid\Uuid;

final class RetrieveFacebookLead
{
    private Uuid $uid;

    public function __construct(Uuid $uid)
    {
        $this->uid = $uid;
    }

    public function getUid(): Uuid
    {
        return $this->uid;
    }
}
