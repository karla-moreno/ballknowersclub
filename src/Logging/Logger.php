<?php

  namespace App\Logging;

  use Monolog\Logger as Monolog;
  use Monolog\Level;
  use Monolog\Handler\RotatingFileHandler;
  use Monolog\Processor\WebProcessor;
  use Monolog\Formatter\JsonFormatter;
  use Monolog\Handler\StreamHandler;

  class Logger {
    private static ?Monolog $logger = null;

    public static function instance(): Monolog {
      if (self::$logger === null) {
        self::$logger = new Monolog('app');
        $formatter = new JsonFormatter();
        $stream_handler = new StreamHandler(__DIR__ . "/../../logs/app.json", Level::Debug);

        $stream_handler->setFormatter($formatter);

        $handler = new RotatingFileHandler(__DIR__ . '/../../logs/app.log', 30, Level::Debug);
        self::$logger->pushHandler($handler);
        self::$logger->pushHandler($stream_handler);
        self::$logger->pushProcessor(new WebProcessor());
      }

      return self::$logger;
    }
  }
