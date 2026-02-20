<?php

namespace Outhebox\Translations\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Outhebox\Translations\Concerns\DisplayHelper;
use Throwable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

class UpgradeCommand extends Command
{
    use DisplayHelper;

    protected $signature = 'translations:upgrade
        {--cleanup : Drop legacy v1 backup tables after migration}
        {--force : Skip confirmation prompt}';

    protected $description = 'Upgrade from v1 database schema to v2';

    private function connection(): ?string
    {
        return config('translations.database_connection');
    }

    private function schema(): \Illuminate\Database\Schema\Builder
    {
        return Schema::connection($this->connection());
    }

    private function db(): \Illuminate\Database\Connection
    {
        return DB::connection($this->connection());
    }

    public function handle(): int
    {
        $this->displayHeader('Upgrade v1 → v2');

        if ($this->isAlreadyMigrated()) {
            info('Database is already using the v2 schema. No upgrade needed.');

            return self::SUCCESS;
        }

        if ($this->isPartialState()) {
            error('Database is in a partial state — both v1 and v2 tables exist. Please resolve manually.');

            return self::FAILURE;
        }

        if (! $this->isV1Schema()) {
            info('No v1 tables detected. Nothing to upgrade.');

            return self::SUCCESS;
        }

        $this->displayV1Counts();

        if (! $this->option('force') && ! $this->option('no-interaction')) {
            if (! confirm('Proceed with the upgrade?', default: true)) {
                warning('Upgrade cancelled.');

                return self::SUCCESS;
            }
        }

        try {
            $callback = fn () => $this->migrateData();

            $this->input->isInteractive()
                ? spin(callback: $callback, message: 'Migrating data to v2 schema...')
                : $callback();
        } catch (Throwable $e) {
            error('Upgrade failed: '.$e->getMessage());

            return self::FAILURE;
        }

        info('Upgrade completed successfully!');

        if ($this->option('cleanup')) {
            $this->cleanupLegacyTables();
        } else {
            info('Legacy tables preserved as backups. Run with --cleanup to remove them.');
        }

        return self::SUCCESS;
    }

    private function isV1Schema(): bool
    {
        return $this->schema()->hasTable('ltu_phrases')
            && $this->schema()->hasTable('ltu_translation_files')
            && ! $this->schema()->hasTable('ltu_translation_keys');
    }

    private function isAlreadyMigrated(): bool
    {
        return $this->schema()->hasTable('ltu_translation_keys')
            && ! $this->schema()->hasTable('ltu_phrases');
    }

    private function isPartialState(): bool
    {
        return $this->schema()->hasTable('ltu_translation_keys')
            && $this->schema()->hasTable('ltu_phrases');
    }

    private function displayV1Counts(): void
    {
        $counts = [
            ['Languages', $this->db()->table('ltu_languages')->count()],
            ['Translation Files', $this->db()->table('ltu_translation_files')->count()],
            ['Phrases', $this->db()->table('ltu_phrases')->count()],
            ['Contributors', $this->db()->table('ltu_contributors')->count()],
        ];

        if ($this->schema()->hasTable('ltu_invites')) {
            $counts[] = ['Invites', $this->db()->table('ltu_invites')->count()];
        }

        table(['Entity', 'Count'], $counts);
    }

    private function migrateData(): void
    {
        $v1Languages = $this->db()->table('ltu_languages')->get();
        $v1Translations = $this->db()->table('ltu_translations')->get();
        $v1Files = $this->db()->table('ltu_translation_files')->get();
        $v1Phrases = $this->db()->table('ltu_phrases')->get();
        $v1Contributors = $this->db()->table('ltu_contributors')->get();
        $v1Invites = $this->schema()->hasTable('ltu_invites')
            ? $this->db()->table('ltu_invites')->get()
            : collect();

        $this->dropSharedV1Tables();
        $this->createV2Tables();

        $this->db()->transaction(function () use ($v1Languages, $v1Translations, $v1Files, $v1Phrases, $v1Contributors, $v1Invites) {
            $languageMap = $this->migrateLanguages($v1Languages, $v1Translations);
            $groupMap = $this->migrateFiles($v1Files);
            $this->migratePhrases($v1Phrases, $v1Translations, $groupMap, $languageMap);
            $this->migrateContributors($v1Contributors);
            $this->migrateInvites($v1Invites);
        });

        $this->verifyIntegrity($v1Languages, $v1Files, $v1Phrases, $v1Contributors, $v1Invites);
    }

    private function dropSharedV1Tables(): void
    {
        $this->schema()->disableForeignKeyConstraints();
        $this->schema()->drop('ltu_translations');
        $this->schema()->drop('ltu_languages');
        $this->schema()->drop('ltu_contributors');
        $this->schema()->enableForeignKeyConstraints();
    }

    private function createV2Tables(): void
    {
        $this->schema()->create('ltu_languages', function ($table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('native_name')->nullable();
            $table->boolean('rtl')->default(false);
            $table->boolean('is_source')->default(false);
            $table->string('tone')->default('neutral');
            $table->boolean('active')->default(false);
            $table->timestamps();
        });

        $this->schema()->create('ltu_groups', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('namespace')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_format')->default('php');
            $table->timestamps();

            $table->unique(['name', 'namespace']);
        });

        $this->schema()->create('ltu_translation_keys', function ($table) {
            $table->id();
            $table->foreignId('group_id')->constrained('ltu_groups')->cascadeOnDelete();
            $table->string('key');
            $table->text('context_note')->nullable();
            $table->json('parameters')->nullable();
            $table->boolean('is_html')->default(false);
            $table->boolean('is_plural')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['group_id', 'key']);
        });

        $this->schema()->create('ltu_translations', function ($table) {
            $table->id();
            $table->foreignId('translation_key_id')->constrained('ltu_translation_keys')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('ltu_languages')->cascadeOnDelete();
            $table->text('value')->nullable();
            $table->string('status')->default('untranslated');
            $table->boolean('needs_review')->default(false);
            $table->string('translated_by')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->boolean('ai_generated')->default(false);
            $table->string('ai_provider')->nullable();
            $table->timestamps();

            $table->unique(['translation_key_id', 'language_id']);
            $table->index('status');
            $table->index('language_id');
        });

        $this->schema()->create('ltu_contributors', function ($table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('role')->default('translator');
            $table->boolean('is_active')->default(true);
            $table->string('invite_token')->nullable()->unique();
            $table->timestamp('invite_expires_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        $this->schema()->create('ltu_contributor_language', function ($table) {
            $table->id();
            $table->foreignUlid('contributor_id')->constrained('ltu_contributors')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('ltu_languages')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['contributor_id', 'language_id']);
        });

        $this->schema()->create('ltu_import_logs', function ($table) {
            $table->id();
            $table->integer('locale_count');
            $table->integer('key_count');
            $table->integer('new_count')->default(0);
            $table->integer('updated_count')->default(0);
            $table->integer('duration_ms');
            $table->string('triggered_by')->nullable();
            $table->string('source');
            $table->boolean('fresh')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        $this->schema()->create('ltu_export_logs', function ($table) {
            $table->id();
            $table->integer('locale_count');
            $table->integer('file_count');
            $table->integer('key_count');
            $table->integer('duration_ms');
            $table->string('triggered_by')->nullable();
            $table->string('source');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    private function migrateLanguages(Collection $v1Languages, Collection $v1Translations): array
    {
        $sourceLanguageIds = $v1Translations->where('source', true)->pluck('language_id')->unique();
        $now = now();
        $languageMap = [];

        foreach ($v1Languages as $v1Lang) {
            $newId = $this->db()->table('ltu_languages')->insertGetId([
                'code' => $v1Lang->code,
                'name' => $v1Lang->name,
                'rtl' => $v1Lang->rtl,
                'is_source' => $sourceLanguageIds->contains($v1Lang->id),
                'tone' => 'neutral',
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $languageMap[$v1Lang->id] = $newId;
        }

        return $languageMap;
    }

    private function migrateFiles(Collection $v1Files): array
    {
        $now = now();
        $groupMap = [];

        foreach ($v1Files as $v1File) {
            $newId = $this->db()->table('ltu_groups')->insertGetId([
                'name' => $v1File->name,
                'file_format' => $v1File->extension,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $groupMap[$v1File->id] = $newId;
        }

        return $groupMap;
    }

    private function migratePhrases(Collection $v1Phrases, Collection $v1Translations, array $groupMap, array $languageMap): void
    {
        $translationToLanguage = [];
        $sourceTranslationIds = [];

        foreach ($v1Translations as $v1Trans) {
            $translationToLanguage[$v1Trans->id] = $v1Trans->language_id;

            if ($v1Trans->source) {
                $sourceTranslationIds[] = $v1Trans->id;
            }
        }

        $sourcePhrases = $v1Phrases->whereIn('translation_id', $sourceTranslationIds);

        $now = now();
        $keyMap = [];

        $sourcePhrases->chunk(500)->each(function (Collection $chunk) use ($groupMap, &$keyMap, $now) {
            foreach ($chunk as $phrase) {
                $groupId = $groupMap[$phrase->translation_file_id] ?? null;

                if (! $groupId) {
                    continue;
                }

                $compositeKey = $groupId.'|'.$phrase->key;

                if (isset($keyMap[$compositeKey])) {
                    continue;
                }

                $newKeyId = $this->db()->table('ltu_translation_keys')->insertGetId([
                    'group_id' => $groupId,
                    'key' => $phrase->key,
                    'parameters' => $phrase->parameters,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $keyMap[$compositeKey] = $newKeyId;
            }
        });

        $translationInserts = [];

        foreach ($v1Phrases as $phrase) {
            $groupId = $groupMap[$phrase->translation_file_id] ?? null;

            if (! $groupId) {
                continue;
            }

            $v1LangId = $translationToLanguage[$phrase->translation_id] ?? null;

            if (! $v1LangId) {
                continue;
            }

            $v2LangId = $languageMap[$v1LangId] ?? null;

            if (! $v2LangId) {
                continue;
            }

            $compositeKey = $groupId.'|'.$phrase->key;

            if (! isset($keyMap[$compositeKey])) {
                $newKeyId = $this->db()->table('ltu_translation_keys')->insertGetId([
                    'group_id' => $groupId,
                    'key' => $phrase->key,
                    'parameters' => $phrase->parameters,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $keyMap[$compositeKey] = $newKeyId;
            }

            $translationInserts[] = [
                'translation_key_id' => $keyMap[$compositeKey],
                'language_id' => $v2LangId,
                'value' => $phrase->value,
                'status' => $this->mapStatus($phrase->status, $phrase->value),
                'needs_review' => $phrase->status === 'needs_update',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        collect($translationInserts)->chunk(500)->each(function (Collection $chunk) {
            $this->db()->table('ltu_translations')->insert($chunk->toArray());
        });
    }

    private function mapStatus(string $v1Status, ?string $value): string
    {
        if ($v1Status === 'needs_update') {
            return 'needs_review';
        }

        if ($value === null || $value === '') {
            return 'untranslated';
        }

        return 'translated';
    }

    private function migrateContributors(Collection $v1Contributors): void
    {
        $now = now();

        foreach ($v1Contributors as $v1Contributor) {
            $this->db()->table('ltu_contributors')->insert([
                'id' => Str::ulid()->toBase32(),
                'name' => $v1Contributor->name,
                'email' => $v1Contributor->email,
                'password' => $v1Contributor->password,
                'role' => $this->mapRole($v1Contributor->role),
                'is_active' => true,
                'created_at' => $v1Contributor->created_at ?? $now,
                'updated_at' => $v1Contributor->updated_at ?? $now,
            ]);
        }
    }

    private function migrateInvites(Collection $v1Invites): void
    {
        $now = now();

        foreach ($v1Invites as $invite) {
            $this->db()->table('ltu_contributors')->insert([
                'id' => Str::ulid()->toBase32(),
                'name' => $invite->email,
                'email' => $invite->email,
                'role' => $this->mapRole($invite->role),
                'is_active' => false,
                'invite_token' => $invite->token,
                'created_at' => $invite->created_at ?? $now,
                'updated_at' => $invite->updated_at ?? $now,
            ]);
        }
    }

    private function mapRole(?int $v1Role): string
    {
        return match ($v1Role) {
            1 => 'owner',
            2 => 'translator',
            default => 'translator',
        };
    }

    private function verifyIntegrity(
        Collection $v1Languages,
        Collection $v1Files,
        Collection $v1Phrases,
        Collection $v1Contributors,
        Collection $v1Invites,
    ): void {
        $results = [
            ['Languages', $v1Languages->count(), $this->db()->table('ltu_languages')->count()],
            ['Groups (was Files)', $v1Files->count(), $this->db()->table('ltu_groups')->count()],
            ['Translation Keys', '-', $this->db()->table('ltu_translation_keys')->count()],
            ['Translations (was Phrases)', $v1Phrases->count(), $this->db()->table('ltu_translations')->count()],
            ['Contributors', $v1Contributors->count() + $v1Invites->count(), $this->db()->table('ltu_contributors')->count()],
        ];

        table(['Entity', 'v1 Count', 'v2 Count'], $results);
    }

    private function cleanupLegacyTables(): void
    {
        $this->schema()->dropIfExists('ltu_phrases');
        $this->schema()->dropIfExists('ltu_translation_files');
        $this->schema()->dropIfExists('ltu_invites');

        info('Legacy v1 tables removed.');
    }
}
