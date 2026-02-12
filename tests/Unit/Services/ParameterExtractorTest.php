<?php

use Outhebox\Translations\Services\Importer\ParameterExtractor;

beforeEach(function () {
    $this->extractor = new ParameterExtractor;
});

it('extracts colon-style parameters', function () {
    $result = $this->extractor->extract('Hello :name, welcome!');

    expect($result)->toContain(':name');
});

it('extracts brace-style parameters', function () {
    $result = $this->extractor->extract('Hello {user}, you have {count} messages.');

    expect($result)->toContain('{user}', '{count}');
});

it('extracts mixed parameter styles', function () {
    $result = $this->extractor->extract('Hello :name, you have {count} items.');

    expect($result)->toContain(':name', '{count}')
        ->and($result)->toHaveCount(2);
});

it('returns empty array for no parameters', function () {
    expect($this->extractor->extract('Hello world'))->toBeEmpty();
});

it('extracts multiple colon parameters', function () {
    $result = $this->extractor->extract(':attribute must be between :min and :max.');

    expect($result)->toContain(':attribute', ':min', ':max')
        ->and($result)->toHaveCount(3);
});

it('deduplicates parameters', function () {
    $result = $this->extractor->extract(':name said hello to :name');

    expect($result)->toContain(':name')
        ->and($result)->toHaveCount(1);
});

it('handles empty string', function () {
    expect($this->extractor->extract(''))->toBeEmpty();
});

it('detects HTML content', function () {
    expect($this->extractor->containsHtml('Click <a href="#">here</a>'))->toBeTrue()
        ->and($this->extractor->containsHtml('<strong>Bold</strong> text'))->toBeTrue();
});

it('does not detect HTML in plain text', function () {
    expect($this->extractor->containsHtml('Hello world'))->toBeFalse()
        ->and($this->extractor->containsHtml('Price is $5 > $3'))->toBeFalse();
});

it('detects simple two-part plural', function () {
    expect($this->extractor->isPlural('one item|many items'))->toBeTrue();
});

it('detects plural with quantity markers', function () {
    expect($this->extractor->isPlural('{0} None|{1} One|[2,*] Many'))->toBeTrue();
});

it('detects plural with parameters', function () {
    expect($this->extractor->isPlural(':count item|:count items'))->toBeTrue()
        ->and($this->extractor->isPlural('{1} :count item|[2,*] :count items'))->toBeTrue();
});

it('detects multi-part plural without markers', function () {
    expect($this->extractor->isPlural('zero|one|many'))->toBeTrue()
        ->and($this->extractor->isPlural('none|one|few|many|other'))->toBeTrue();
});

it('detects plural with range selectors', function () {
    expect($this->extractor->isPlural('[0] None|[1,19] Some|[20,*] Many'))->toBeTrue();
});

it('does not detect plural in regular text', function () {
    expect($this->extractor->isPlural('Hello world'))->toBeFalse()
        ->and($this->extractor->isPlural('Hello | World'))->toBeFalse()
        ->and($this->extractor->isPlural('Option A | Option B | Option C'))->toBeFalse()
        ->and($this->extractor->isPlural('Price is $5'))->toBeFalse();
});

it('handles colon in URLs without false positive', function () {
    $result = $this->extractor->extract('Visit https://example.com');

    expect($result)->not->toContain(':');
});

it('extracts parameters from complex strings', function () {
    $result = $this->extractor->extract('The :attribute must be at least :min characters and less than :max.');

    expect($result)->toHaveCount(3);
});

it('handles brace parameters at string boundaries', function () {
    $result = $this->extractor->extract('{name} is here');

    expect($result)->toContain('{name}');
});

it('excludes numeric pluralization markers like {0} and {1}', function () {
    $result = $this->extractor->extract('{0} No items|{1} :count item|[2,*] :count items');

    expect($result)->toContain(':count')
        ->not->toContain('{0}', '{1}');
});

it('extracts params from plural strings', function () {
    $result = $this->extractor->extract('{0} No :items|{1} :count :item|[2,*] :count :items');

    expect($result)->toContain(':items', ':count', ':item')
        ->not->toContain('{0}', '{1}');
});

it('extracts params with underscores', function () {
    $result = $this->extractor->extract('Hello :first_name :last_name');

    expect($result)->toContain(':first_name', ':last_name')
        ->and($result)->toHaveCount(2);
});

it('extracts params with trailing numbers', function () {
    $result = $this->extractor->extract(':address1 and :address2');

    expect($result)->toContain(':address1', ':address2')
        ->and($result)->toHaveCount(2);
});

it('extracts brace params from plural strings', function () {
    $result = $this->extractor->extract('{0} No {item}|{1} One {item}|[2,*] Many {item}');

    expect($result)->toContain('{item}')
        ->not->toContain('{0}', '{1}');
});

it('does not extract from time-like patterns', function () {
    expect($this->extractor->extract('Available at 10:30 AM'))->toBeEmpty()
        ->and($this->extractor->extract('Open 9:00-17:00'))->toBeEmpty();
});

it('does not extract from mailto links', function () {
    expect($this->extractor->extract('Contact mailto:john@example.com'))->toBeEmpty();
});

it('does not extract from ftp urls', function () {
    expect($this->extractor->extract('Download from ftp://files.example.com'))->toBeEmpty();
});

it('does not extract colon followed by space', function () {
    expect($this->extractor->extract('Note: this is important'))->toBeEmpty()
        ->and($this->extractor->extract('Warning: something happened'))->toBeEmpty();
});

it('extracts params from multi-part plurals', function () {
    $result = $this->extractor->extract('zero :count|one :count|few :count|many :count|other :count');

    expect($result)->toContain(':count')
        ->and($result)->toHaveCount(1);
});
