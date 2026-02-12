<?php

namespace Outhebox\Translations\Contracts;

interface TranslatableUser
{
    public function getTranslationRole(): string;

    public function getTranslationDisplayName(): string;

    public function getTranslationEmail(): string;

    public function getTranslationId(): string;
}
