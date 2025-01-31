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

namespace Zikula\Bundle\CoreBundle\Twig\Runtime;

use InvalidArgumentException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

class CoreRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Delete a key of an array.
     */
    public function arrayUnset(array $array, string $key): array
    {
        unset($array[$key]);

        return $array;
    }

    public function yesNo($string): string
    {
        if (null !== $string && !in_array($string, [true, false, '', '0', '1'], true)) {
            return $string;
        }

        return (bool) $string ? $this->translator->trans('Yes') : $this->translator->trans('No');
    }

    /**
     * Apply an existing function (e.g. php's `md5`) to a string.
     *
     * @param string|object $subject
     * @return mixed
     */
    public function applyPhp($subject, string $func)
    {
        if (function_exists($func)) {
            return $func($subject);
        }

        return $subject;
    }

    /**
     * Protect a given mail address by finding the text 'x@y' and replacing
     * it with HTML entities. This provides protection against email harvesters.
     */
    public function protectMailAddress(string $string): string
    {
        $string = preg_replace_callback(
            '/(.)@(.)/s',
            static function ($m) {
                return '&#' . sprintf('%03d', ord($m[1])) . ';&#064;&#' . sprintf('%03d', ord($m[2])) . ';';
            },
            $string
        );

        return $string;
    }

    /**
     * Call a php callable with parameters.
     *
     * @return mixed
     */
    public function callFunc(callable $callable, array $parameters = [])
    {
        if (function_exists($callable)) {
            return call_user_func_array($callable, $parameters);
        }
        throw new InvalidArgumentException($this->translator->trans('Function does not exist or is not callable.'));
    }
}
