<?php

namespace Outhebox\Translations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Outhebox\Translations\Concerns\DisplayHelper;
use Outhebox\Translations\Enums\TranslationStatus;
use Outhebox\Translations\Models\Language;
use Outhebox\Translations\Models\TranslationKey;

use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

class StatusCommand extends Command
{
    use DisplayHelper;

    protected $signature = 'translations:status
        {--locale= : Show status for a specific locale only}';

    protected $description = 'Show translation status and progress for each language';

    public function handle(): int
    {
        $this->displayHeader('Status');

        $totalKeys = TranslationKey::query()->count();

        if ($totalKeys === 0) {
            warning('No translation keys found. Run translations:import first.');

            return self::SUCCESS;
        }

        $languages = $this->fetchLanguages();

        if ($languages->isEmpty()) {
            warning('No active languages found.');

            return self::SUCCESS;
        }

        $this->displayStatusTable($languages, $totalKeys);

        return self::SUCCESS;
    }

    private function fetchLanguages(): Collection
    {
        $query = Language::query()
            ->active()
            ->orderBy('code')
            ->withCount([
                'translations as translated_count' => fn ($q) => $q->where('status', TranslationStatus::Translated),
                'translations as approved_count' => fn ($q) => $q->where('status', TranslationStatus::Approved),
                'translations as needs_review_count' => fn ($q) => $q->where('status', TranslationStatus::NeedsReview),
                'translations as untranslated_count' => fn ($q) => $q->where('status', TranslationStatus::Untranslated),
            ]);

        if ($locale = $this->option('locale')) {
            $query->where('code', $locale);
        }

        return $query->get();
    }

    private function displayStatusTable(Collection $languages, int $totalKeys): void
    {
        $rows = $languages->map(fn ($language) => $this->buildLanguageRow($language, $totalKeys))->all();

        info("Total keys: {$totalKeys}");
        $this->newLine();

        $this->table(
            ['Code', 'Name', 'Source', 'Translated', 'Approved', 'Needs Review', 'Untranslated', 'Progress'],
            $rows
        );

        if ($languages->count() > 1) {
            $totalProgress = $languages->sum(fn ($lang) => $this->calculateProgress($lang, $totalKeys));
            $averageProgress = round($totalProgress / $languages->count(), 1);
            $this->newLine();
            info("Overall average progress: {$averageProgress}%");
        }
    }

    private function buildLanguageRow(Language $language, int $totalKeys): array
    {
        $progress = $this->calculateProgress($language, $totalKeys);

        $progressColor = match (true) {
            $progress >= 90 => 'green',
            $progress >= 50 => 'yellow',
            default => 'red',
        };

        return [
            $language->code,
            $language->name,
            $language->is_source ? '<info>Yes</info>' : '',
            $language->translated_count,
            $language->approved_count,
            $language->needs_review_count,
            $language->untranslated_count,
            "<fg={$progressColor}>{$progress}%</>",
        ];
    }

    private function calculateProgress(Language $language, int $totalKeys): float
    {
        $done = $language->translated_count + $language->approved_count;

        return round(($done / $totalKeys) * 100, 1);
    }
}
