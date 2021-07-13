<?php


namespace module\name\LastTaskCommentRequired\Control;


use module\name\LastTaskCommentRequired\Entity\Config;
use PHPUnit\Framework\TestCase;


class ConfigServiceTest extends TestCase
{
    public function testGetConfigActive(): void {
        $cs = new class extends ConfigService {
            public function getConfig(): Config {
                return new Config(true);
            }
        };

        $cfg = $cs->getConfig();

        $this->assertTrue($cfg->isActive);
    }

    public function testGetConfigNotActive(): void {
        $cs = new class extends ConfigService {
            public function getConfig(): Config {
                return new Config(false);
            }
        };

        $cfg = $cs->getConfig();

        $this->assertFalse($cfg->isActive);
    }
}