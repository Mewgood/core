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

namespace Zikula\Bundle\CoreBundle\Composer;

use const JSON_ERROR_NONE;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use function Symfony\Component\String\s;
use Symfony\Contracts\Translation\TranslatorInterface;

class Scanner
{
    private array $jsons = [];

    private array $invalid = [];

    private TranslatorInterface $translator;

    /**
     * Scans and loads composer.json files.
     */
    public function scan(array $paths = [], int $depth = 3, Finder $finder = null): void
    {
        $finder = $finder ?? new Finder();
        $finder->files()
            ->in($paths)
            ->notPath('docs')
            ->notPath('vendor')
            ->notPath('Resources')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->followLinks()
            ->depth('<' . $depth)
            ->name('composer.json')
        ;

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $json = $this->decode($file->getRealPath());
            if (false !== $json) {
                $this->jsons[$json['name']] = $json;
            } else {
                $this->invalid[] = $file->getRelativePath();
            }
        }
    }

    /**
     * Decodes a json string.
     *
     * @return bool|mixed
     */
    public function decode(string $jsonFilePath)
    {
        $base = str_replace('\\', '/', dirname($jsonFilePath));
        $srcPath = dirname(__DIR__, 4) . '/src/';
        $base = s($base)->slice(mb_strlen($srcPath))->toString();

        $json = json_decode(file_get_contents($jsonFilePath), true);
        if (JSON_ERROR_NONE === json_last_error()) {
            // calculate PSR-4 autoloading path for this namespace
            $class = $json['extra']['zikula']['class'];
            $ns = s($class)->beforeLast('\\', true)->toString();
            if (false === isset($json['autoload']['psr-4'][$ns])) {
                return false;
            }
            $json['autoload']['psr-4'][$ns] = $base;
            $json['extra']['zikula']['short-name'] = s($class)->afterLast('\\')->toString();
            $json['extensionType'] = $this->computeExtensionType($json);

            return $json;
        }

        return false;
    }

    private function computeExtensionType(array $json): int
    {
        $types = [
            MetaData::EXTENSION_TYPE_THEME => MetaData::TYPE_THEME,
            MetaData::EXTENSION_TYPE_MODULE => MetaData::TYPE_MODULE,
            MetaData::SYSTEM_TYPE_THEME => MetaData::TYPE_SYSTEM_THEME,
            MetaData::SYSTEM_TYPE_MODULE => MetaData::TYPE_SYSTEM_MODULE,
        ];

        return $types[$json['type']];
    }

    public function getExtensionsMetaData(): array
    {
        $array = [];
        foreach ($this->jsons as $json) {
            $array[$json['name']] = new MetaData($json);
            $array[$json['name']]->setTranslator($this->translator);
        }

        return $array;
    }

    public function getInvalid(): array
    {
        return $this->invalid;
    }

    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
