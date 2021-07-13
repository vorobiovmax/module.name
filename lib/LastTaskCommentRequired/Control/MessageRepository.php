<?php


namespace module\name\LastTaskCommentRequired\Control;


use Bitrix\Forum\MessageTable;
use Bitrix\Main\ORM\Query\Query;
use module\name\LastTaskCommentRequired\Entity\Message;


class MessageRepository
{
    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function findLastByTopicId(int $topicId): ?Message {
        $q = $this->getQuery()
            ->setSelect(array('ID', 'AUTHOR_ID'))
            ->where('TOPIC_ID', '=', $topicId)
            ->where('SERVICE_TYPE', '=', null)
            ->where('NEW_TOPIC', '=', 'N')
            ->setOrder(array('POST_DATE' => 'DESC'))
            ->setLimit(1);

        $msg = $q->exec()->fetch();

        if(empty($msg)) {
            return null;
        }

        return new Message(
            intval($msg['ID']),
            intval($msg['AUTHOR_ID'])
        );
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getQuery(): Query {
        return MessageTable::query();
    }
}