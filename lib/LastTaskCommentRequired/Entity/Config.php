<?php


namespace  module\name\LastTaskCommentRequired\Entity;


class Config
{
    public bool $isActive;

    public function __construct(bool $isActive) {
        $this->isActive = $isActive;
    }
}