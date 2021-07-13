<?php


namespace module\name\LastTaskCommentRequired\Control;


use Bitrix\Tasks\Internals\TaskTable;
use Bitrix\Main\ORM\Query\Query;
use module\name\LastTaskCommentRequired\Entity\Task;


class TaskRepository
{
    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function findById(int $taskId): ?Task {
        $q = $this->getQuery()
            ->setSelect(array('ID', 'CLOSED_BY', 'FORUM_TOPIC_ID'))
            ->where('ID', '=', $taskId);

        $task = $q->exec()->fetch();

        if(empty($task)) {
            return null;
        }

        $closedBy = isset($task['CLOSED_BY']) ? intval($task['CLOSED_BY']) : null;

        return new Task(
            intval($task['ID']),
            intval($task['FORUM_TOPIC_ID']),
            $closedBy
        );
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getQuery(): Query {
        return TaskTable::query();
    }
}