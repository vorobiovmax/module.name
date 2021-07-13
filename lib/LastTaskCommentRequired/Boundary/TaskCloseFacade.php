<?php


namespace module\name\LastTaskCommentRequired\Boundary;


use Bitrix\Main\Loader;
use module\name\LastTaskCommentRequired\Control\ConfigService;
use module\name\LastTaskCommentRequired\Control\MessageRepository;
use module\name\LastTaskCommentRequired\Control\NotificationService;
use module\name\LastTaskCommentRequired\Control\TaskCommentsService;
use module\name\LastTaskCommentRequired\Control\TaskRepository;


class TaskCloseFacade
{
    protected TaskCommentsService $tcs;
    protected ConfigService $cs;
    protected NotificationService $ns;

    public function __construct(TaskCommentsService $tcs, ConfigService $cs, NotificationService $ns) {
        $this->loadModules();

        $this->tcs = $tcs;
        $this->cs = $cs;
        $this->ns = $ns;
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \module\name\LastTaskCommentRequired\Control\TaskNotFoundException
     * @throws \Bitrix\Main\LoaderException
     */
    public function isAllowedToClose(int $taskId, int $modifiedById): bool {
        $isLastAuthor = $this->tcs->isLastCommentAuthor($taskId, $modifiedById);

        if(!$isLastAuthor) {
            $this->ns->showError('Перед закрытием задачи нужно оставить комментарий');
        }

        return $isLastAuthor;
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public function isActive(): bool {
        return $this->cs->getConfig()->isActive;
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    protected function loadModules() {
        Loader::includeModule('tasks');
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \module\name\LastTaskCommentRequired\Control\TaskNotFoundException
     * @throws \Bitrix\Main\LoaderException
     */
    public static function onBeforeTaskUpdateHandler($id, $fields) {
        if($fields['STATUS'] !== \CTasks::STATE_COMPLETED) {
            return true;
        }

        $tcf = new TaskCloseFacade(
            new TaskCommentsService(
                new TaskRepository(),
                new MessageRepository()
            ),
            new ConfigService(),
            new NotificationService()
        );

        return $tcf->isAllowedToClose($id, intval($fields['CLOSED_BY']));
    }
}