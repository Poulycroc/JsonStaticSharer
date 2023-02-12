<?php

namespace App\Traits;

use App\Models\Media;

trait HasAttachMedia
{
    public function attachMedia($media)
    {
        return $this->addContent($media);
    }

    public function removeMedia($media)
    {
        return $this->removeContent($media);
    }

    public function getMedias()
    {
        return $this->content([Media::class]);
    }
}
