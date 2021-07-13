<?php


namespace module\name\LastTaskCommentRequired\Control;


use module\name\LastTaskCommentRequired\Entity\User;


class TaskCommentsService
{
    protected TaskRepository $tr;
    protected MessageRepository $mr;

    public function __construct(TaskRepository $tr, MessageRepository $mr) {
        $this->tr = $tr;
        $this->mr = $mr;
    }

    /**
     * @throws TaskNotFoundException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function isLastCommentAuthor(int $taskId, int $userId): bool {
        $author = $this->getLastTaskCommentAuthor($taskId);

        if(empty($author)) {
            return false;
        }

        return $author->id === $userId;
    }

    /**
     * @throws TaskNotFoundException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getLastTaskCommentAuthor(int $taskId): ?User {
        $task = $this->tr->findById($taskId);

        if(empty($task)) {
            throw new TaskNotFoundException('Задача не найдена.');
        }

        $msg = $this->mr->findLastByTopicId($task->topicId);

        if(empty($msg)) {
            return null;
        }

        return new User($msg->authorId);
    }
}