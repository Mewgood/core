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

namespace Zikula\Bundle\FormExtensionBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Workaround for Symfony bug #5906
 * @see https://github.com/symfony/symfony/issues/5906
 * @author b.b3rn4ard
 * @see http://stackoverflow.com/a/28889445/2600812
 * Class NullToEmptyTransformer
 */
class NullToEmptyTransformer implements DataTransformerInterface
{
    /**
     * Does not transform anything.
     *
     * @param string|null $value
     * @return string
     */
    public function transform(mixed $value)
    {
        return $value;
    }

    /**
     * Transforms a null value to an empty string.
     *
     * @param string $value
     * @return string
     */
    public function reverseTransform(mixed $value)
    {
        return $value ?? '';
    }
}
