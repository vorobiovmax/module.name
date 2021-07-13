<?php


namespace module\name\LastTaskCommentRequired\Entity;


class Message
{
    public int $id;
    public int $authorId;

    public function __construct(int $id, int $authorId) {
        $this->id = $id;
        $this->authorId = $authorId;
    }
}