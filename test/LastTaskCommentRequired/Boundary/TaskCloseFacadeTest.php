<?php


namespace module\name\LastTaskCommentRequired\Boundary;


use module\name\LastTaskCommentRequired\Control\ConfigService;
use module\name\LastTaskCommentRequired\Control\NotificationService;
use module\name\LastTaskCommentRequired\Control\TaskCommentsService;
use module\name\LastTaskCommentRequired\Control\TaskNotFoundException;
use module\name\LastTaskCommentRequired\Entity\Config;
use PHPUnit\Framework\TestCase;


class TaskCloseFacadeTest extends TestCase
{
    public function testIsActive(): void {
        $csMock = $this->createMock(ConfigService::class);
        $tcsMock = $this->createMock(TaskCommentsService::class);
        $nsMock = $this->createMock(NotificationService::class);

        $csMock
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn(new Config(true));

        $tcf = new class($tcsMock, $csMock, $nsMock) extends TaskCloseFacade {
            protected function loadModules() {}
        };

        $this->assertTrue($tcf->isActive());
    }

    public function testIsActiveNotActive(): void {
        $csMock = $this->createMock(ConfigService::class);
        $tcsMock = $this->createMock(TaskCommentsService::class);
        $nsMock = $this->createMock(NotificationService::class);

        $csMock
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn(new Config(false));

        $tcf = new class($tcsMock, $csMock, $nsMock) extends TaskCloseFacade {
            protected function loadModules() {}
        };

        $this->assertFalse($tcf->isActive());
    }

    public function testIsAllowedToCloseTrue(): void {
        $csMock = $this->createMock(ConfigService::class);
        $tcsMock = $this->createMock(TaskCommentsService::class);
        $nsMock = $this->createMock(NotificationService::class);

        $taskId = 1;
        $userId = 1;

        $tcsMock
            ->expects($this->once())
            ->method('isLastCommentAuthor')
            ->with($taskId, $userId)
            ->willReturn(true);

        $nsMock
            ->expects($this->never())
            ->method('showError');

        $tcf = new class($tcsMock, $csMock, $nsMock) extends TaskCloseFacade {
            protected function loadModules() {}
        };

        $allowedToClose = $tcf->isAllowedToClose($taskId, $userId);

        $this->assertTrue($allowedToClose);
    }

    public function testIsAllowedToCloseFalse(): void {
        $csMock = $this->createMock(ConfigService::class);
        $tcsMock = $this->createMock(TaskCommentsService::class);
        $nsMock = $this->createMock(NotificationService::class);

        $taskId = 1;
        $userId = 22;

        $tcsMock
            ->expects($this->once())
            ->method('isLastCommentAuthor')
            ->with($taskId, $userId)
            ->willReturn(false);

        $nsMock
            ->expects($this->once())
            ->method('showError');

        $tcf = new class($tcsMock, $csMock, $nsMock) extends TaskCloseFacade {
            protected function loadModules() {}
        };

        $allowedToClose = $tcf->isAllowedToClose($taskId, $userId);

        $this->assertFalse($allowedToClose);
    }

    public function testIsAllowedToCloseTaskNotFound(): void {
        $csMock = $this->createMock(ConfigService::class);
        $tcsMock = $this->createMock(TaskCommentsService::class);
        $nsMock = $this->createMock(NotificationService::class);

        $taskId = 0;
        $userId = 22;

        $tcsMock
            ->expects($this->once())
            ->method('isLastCommentAuthor')
            ->with($taskId, $userId)
            ->willThrowException(new TaskNotFoundException('Задача не найдена.'));

        $tcf = new class($tcsMock, $csMock, $nsMock) extends TaskCloseFacade {
            protected function loadModules() {}
        };

        $this->expectException(TaskNotFoundException::class);
        $this->expectExceptionMessage('Задача не найдена.');

        $tcf->isAllowedToClose($taskId, $userId);
    }
}