<?php


namespace  module\name\LastTaskCommentRequired\Entity;


class Task
{
    public int $id;
    public int $topicId;
    public ?int $closedBy;

    public function __construct(int $id, int $topicId, ?int $closedBy = null) {
        $this->id = $id;
        $this->topicId = $topicId;
        $this->closedBy = $closedBy;
    }
}