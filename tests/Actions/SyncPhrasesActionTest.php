<?php

use Outhebox\TranslationsUI\Actions\SyncPhrasesAction;
use Outhebox\TranslationsUI\Models\Language;
use Outhebox\TranslationsUI\Models\Phrase;
use Outhebox\TranslationsUI\Models\Translation;

it('can sync phrases with same keys in different groups', function () {
    // Setup
    $english = Language::factory()
        ->create([
            'rtl' => false,
            'code' => 'en',
            'name' => 'English',
        ]);

    $dutch = Language::factory()
        ->create([
            'rtl' => false,
            'code' => 'nl',
            'name' => 'Nederlands',
        ]);

    $englishTranslation = Translation::factory()
        ->source()
        ->create([
            'language_id' => $english->id,
        ]);

    $englighAuthorsTitle = Phrase::factory()->create([
        'key' => 'title',
        'translation_id' => $englishTranslation->id,
        'group' => 'authors',
        'value' => 'Authors', // Dutch: Schrijvers
    ]);

    $englighBooksTitle = Phrase::factory()->create([
        'key' => 'title',
        'translation_id' => $englishTranslation->id,
        'group' => 'books',
        'value' => 'Books', // Dutch: Boeken
    ]);

    Phrase::bootHasUuid(); // IDK why this is needed... without this we get an integrity constraint violation for empty uuid.

    // When
    SyncPhrasesAction::execute(
        $englishTranslation,
        'title',
        'Schrijvers',
        'nl',
        'authors.php',
    );

    SyncPhrasesAction::execute(
        $englishTranslation,
        'title',
        'Boeken',
        'nl',
        'books.php',
    );

    // Then
    $dutchTranslation = $dutch->translation;
    expect($dutchTranslation)->toBeInstanceOf(Translation::class);

    $dutchPhrases = $dutchTranslation->phrases;
    expect($dutchPhrases)->toHaveCount(2);

    expect($dutchPhrases[0]->phrase_id)->toEqual($englighAuthorsTitle->id);
    expect($dutchPhrases[0]->key)->toEqual($englighAuthorsTitle->key);
    expect($dutchPhrases[0]->group)->toEqual($englighAuthorsTitle->group);
    expect($dutchPhrases[0]->value)->toEqual('Schrijvers');

    expect($dutchPhrases[1]->phrase_id)->toEqual($englighBooksTitle->id);
    expect($dutchPhrases[1]->key)->toEqual($englighBooksTitle->key);
    expect($dutchPhrases[1]->group)->toEqual($englighBooksTitle->group);
    expect($dutchPhrases[1]->value)->toEqual('Boeken');
});
