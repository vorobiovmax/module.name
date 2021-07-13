<?php


namespace module\name\LastTaskCommentRequired\Entity;


class User
{
    public int $id;

    public function __construct(int $id) {
        $this->id = $id;
    }
}