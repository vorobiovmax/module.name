<?php


namespace module\name\LastTaskCommentRequired\Control;


use module\name\LastTaskCommentRequired\Entity\Message;
use module\name\LastTaskCommentRequired\Entity\Task;
use module\name\LastTaskCommentRequired\Entity\User;
use PHPUnit\Framework\TestCase;


class TaskCommentsServiceTest extends TestCase
{
    public function testGetLastTaskCommentAuthorTaskNotFound(): void {
        $trMock = $this->createMock(TaskRepository::class);
        $mrMock = $this->createMock(MessageRepository::class);

        $taskId = 0;

        $trMock->expects($this->once())->method('findById')
            ->with($taskId)
            ->willReturn(null);

        $this->expectException(TaskNotFoundException::class);
        $this->expectExceptionMessage('Задача не найдена.');

        $tcs = new _TaskCommentsService($trMock, $mrMock);

        $tcs->_getLastTaskCommentAuthor($taskId);
    }

    public function testGetLastTaskCommentAuthorMessageNotFound(): void {
        $trMock = $this->createMock(TaskRepository::class);
        $mrMock = $this->createMock(MessageRepository::class);

        $task = new Task(1, 3);

        $trMock->expects($this->once())->method('findById')
            ->with($task->id)
            ->willReturn($task);

        $mrMock->expects($this->once())->method('findLastByTopicId')
            ->with($task->topicId)
            ->willReturn(null);

        $tcs = new _TaskCommentsService($trMock, $mrMock);

        $lastAuthor = $tcs->_getLastTaskCommentAuthor($task->id);

        $this->assertSame(null, $lastAuthor);
    }

    public function testGetLastTaskCommentAuthor(): void {
        $trMock = $this->createMock(TaskRepository::class);
        $mrMock = $this->createMock(MessageRepository::class);

        $task = new Task(1, 3);
        $msg = new Message(6, 1);
        $user = new User(1);

        $trMock->expects($this->once())->method('findById')
            ->with($task->id)
            ->willReturn($task);

        $mrMock->expects($this->once())->method('findLastByTopicId')
            ->with($task->topicId)
            ->willReturn($msg);

        $tcs = new _TaskCommentsService($trMock, $mrMock);

        $lastAuthor = $tcs->_getLastTaskCommentAuthor($task->id);

        $this->assertSame($user->id, $lastAuthor->id);
    }

    public function testIsLastCommentAuthorNoComments(): void {
        $trMock = $this->createMock(TaskRepository::class);
        $mrMock = $this->createMock(MessageRepository::class);

        $task = new Task(1, 3);
        $userId = 1;

        $trMock->expects($this->once())->method('findById')
            ->with($task->id)
            ->willReturn($task);

        $mrMock->expects($this->once())->method('findLastByTopicId')
            ->with($task->topicId)
            ->willReturn(null);

        $tcs = new TaskCommentsService($trMock, $mrMock);

        $isLast = $tcs->isLastCommentAuthor($task->id, $userId);

        $this->assertSame(false, $isLast);
    }

    public function testIsLastCommentAuthorWrongUser(): void {
        $trMock = $this->createMock(TaskRepository::class);
        $mrMock = $this->createMock(MessageRepository::class);

        $task = new Task(1, 3);
        $userId = 1;

        $trMock->expects($this->once())->method('findById')
            ->with($task->id)
            ->willReturn($task);

        $mrMock->expects($this->once())->method('findLastByTopicId')
            ->with($task->topicId)
            ->willReturn(new Message(10, 2));

        $tcs = new TaskCommentsService($trMock, $mrMock);

        $isLast = $tcs->isLastCommentAuthor($task->id, $userId);

        $this->assertSame(false, $isLast);
    }

    public function testIsLastCommentAuthor(): void {
        $trMock = $this->createMock(TaskRepository::class);
        $mrMock = $this->createMock(MessageRepository::class);

        $task = new Task(1, 3);
        $userId = 1;

        $trMock->expects($this->once())->method('findById')
            ->with($task->id)
            ->willReturn($task);

        $mrMock->expects($this->once())->method('findLastByTopicId')
            ->with($task->topicId)
            ->willReturn(new Message(10, 1));

        $tcs = new TaskCommentsService($trMock, $mrMock);

        $isLast = $tcs->isLastCommentAuthor($task->id, $userId);

        $this->assertSame(true, $isLast);
    }
}

class _TaskCommentsService extends TaskCommentsService {

    public function _getLastTaskCommentAuthor(int $taskId): ?User {
        return $this->getLastTaskCommentAuthor($taskId);
    }
}