<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__) . '/vendor/autoload.php';

if (!class_exists(Dotenv::class)) {
    throw new LogicException('Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.');
}

(new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');

// globally ignore @type annotation. Necessary to be able to use the extended array documentation syntax.
AnnotationReader::addGlobalIgnoredName('type');
