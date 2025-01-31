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

namespace Zikula\Bundle\FormExtensionBundle\Event;

use Zikula\Bundle\FormExtensionBundle\FormTypesChoices;

class FormTypeChoiceEvent
{
    public function __construct(private readonly FormTypesChoices $choices)
    {
    }

    public function getChoices(): FormTypesChoices
    {
        return $this->choices;
    }

    public function setChoices(FormTypesChoices $choices): self
    {
        $this->choices = $choices;

        return $this;
    }
}
