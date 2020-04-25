<?php

namespace Phong\Support\Model\Api\Data;

interface TopicInterface
{
    public function getId();

    public function setId();

    public function getTitle();

    public function setTitle();

    public function getContent();

    public function setContent();

    public function getCreationTime();

    public function setCreationTime();
}
