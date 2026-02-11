import { useForm } from '@inertiajs/react';
import * as React from 'react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { Flag } from '@/components/ui/flag';
import { Input } from '@/components/ui/input';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { AddCircle, Global, Magnifer } from '@/lib/icons';
import { store, storeCustom } from '@/routes/ltu/languages';

export interface AvailableLanguage {
    id: number;
    code: string;
    name: string;
    native_name: string | null;
    rtl: boolean;
}

export function AddLanguageDialog({
    availableLanguages,
    trigger,
}: {
    availableLanguages: AvailableLanguage[];
    trigger: React.ReactNode;
}) {
    const [open, setOpen] = React.useState(false);
    const [search, setSearch] = React.useState('');
    const [selected, setSelected] = React.useState<Set<number>>(new Set());
    const [tab, setTab] = React.useState('browse');

    const browseForm = useForm({ language_ids: [] as number[] });
    const customForm = useForm({
        code: '',
        name: '',
        native_name: '',
        rtl: false,
    });

    const filtered = React.useMemo(() => {
        if (!search.trim()) return availableLanguages;
        const q = search.toLowerCase();
        return availableLanguages.filter(
            (lang) =>
                lang.name.toLowerCase().includes(q) ||
                lang.code.toLowerCase().includes(q) ||
                (lang.native_name?.toLowerCase().includes(q) ?? false),
        );
    }, [availableLanguages, search]);

    const toggleLanguage = (id: number) => {
        setSelected((prev) => {
            const next = new Set(prev);
            if (next.has(id)) {
                next.delete(id);
            } else {
                next.add(id);
            }
            return next;
        });
    };

    const resetState = () => {
        setSearch('');
        setSelected(new Set());
        setTab('browse');
        browseForm.reset();
        customForm.reset();
        customForm.clearErrors();
    };

    const handleOpenChange = (value: boolean) => {
        setOpen(value);
        if (!value) {
            resetState();
        }
    };

    const handleBrowseSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        browseForm.transform(() => ({
            language_ids: Array.from(selected),
        }));
        browseForm.post(store.url(), {
            onSuccess: () => {
                resetState();
                setOpen(false);
            },
        });
    };

    const handleCustomSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        customForm.post(storeCustom.url(), {
            onSuccess: () => {
                resetState();
                setOpen(false);
            },
        });
    };

    const buttonLabel = () => {
        if (browseForm.processing) return 'Adding...';
        if (selected.size === 0) return 'Select languages to add';
        if (selected.size === 1) return 'Add 1 Language';
        return `Add ${selected.size} Languages`;
    };

    return (
        <Dialog open={open} onOpenChange={handleOpenChange}>
            <DialogTrigger asChild>{trigger}</DialogTrigger>
            <DialogContent className="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Add Language</DialogTitle>
                    <DialogDescription className="sr-only">
                        Browse available languages or create a custom one.
                    </DialogDescription>
                </DialogHeader>

                <Tabs value={tab} onValueChange={setTab}>
                    <TabsList className="h-10 w-full p-1">
                        <TabsTrigger
                            value="browse"
                            className="flex-1 px-3 py-1.5"
                        >
                            <Magnifer className="size-4" />
                            <span className="sm:hidden">Browse</span>
                            <span className="hidden sm:inline">
                                Browse Languages
                            </span>
                        </TabsTrigger>
                        <TabsTrigger
                            value="custom"
                            className="flex-1 px-3 py-1.5"
                        >
                            <AddCircle className="size-4" />
                            <span className="sm:hidden">Custom</span>
                            <span className="hidden sm:inline">
                                Custom Language
                            </span>
                        </TabsTrigger>
                    </TabsList>

                    <TabsContent value="browse">
                        <form
                            onSubmit={handleBrowseSubmit}
                            className="flex h-[min(380px,60vh)] flex-col gap-3"
                        >
                            <div className="relative">
                                <Magnifer className="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
                                <Input
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Search languages..."
                                    className="pl-9"
                                />
                            </div>

                            <div className="flex-1 overflow-y-auto rounded-lg border border-neutral-200 dark:border-neutral-800">
                                {filtered.length === 0 ? (
                                    <div className="flex h-full flex-col items-center justify-center gap-3 p-6">
                                        <div className="flex size-12 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800">
                                            <Global className="size-6 text-muted-foreground/60" />
                                        </div>
                                        <div className="text-center">
                                            <p className="text-sm font-medium">
                                                No languages found
                                            </p>
                                            <p className="mt-1 text-xs text-muted-foreground">
                                                No results for &ldquo;{search}
                                                &rdquo;
                                            </p>
                                        </div>
                                    </div>
                                ) : (
                                    filtered.map((lang) => (
                                        <label
                                            key={lang.id}
                                            className="flex cursor-pointer items-center gap-3 px-3 py-2.5 hover:bg-neutral-50 dark:hover:bg-neutral-800/50"
                                        >
                                            <Checkbox
                                                checked={selected.has(lang.id)}
                                                onCheckedChange={() =>
                                                    toggleLanguage(lang.id)
                                                }
                                            />
                                            <Flag
                                                code={lang.code}
                                                className="size-5 shrink-0"
                                            />
                                            <span className="min-w-0 flex-1 truncate text-sm">
                                                {lang.name}
                                                {lang.native_name &&
                                                    lang.native_name !==
                                                        lang.name &&
                                                    ` (${lang.native_name})`}
                                            </span>
                                            <Badge
                                                variant="outline"
                                                className="font-mono text-[10px]"
                                            >
                                                {lang.code}
                                            </Badge>
                                        </label>
                                    ))
                                )}
                            </div>

                            <Button
                                type="submit"
                                className="w-full"
                                disabled={
                                    selected.size === 0 || browseForm.processing
                                }
                            >
                                {buttonLabel()}
                            </Button>
                        </form>
                    </TabsContent>

                    <TabsContent value="custom">
                        <form
                            onSubmit={handleCustomSubmit}
                            className="flex h-[min(380px,60vh)] flex-col gap-4"
                        >
                            <Field data-invalid={!!customForm.errors.code}>
                                <FieldLabel htmlFor="custom-code">
                                    Code
                                </FieldLabel>
                                <Input
                                    id="custom-code"
                                    value={customForm.data.code}
                                    onChange={(e) =>
                                        customForm.setData(
                                            'code',
                                            e.target.value,
                                        )
                                    }
                                    placeholder="e.g. pt-BR"
                                    aria-invalid={!!customForm.errors.code}
                                />
                                <FieldError>
                                    {customForm.errors.code}
                                </FieldError>
                            </Field>

                            <Field data-invalid={!!customForm.errors.name}>
                                <FieldLabel htmlFor="custom-name">
                                    Name
                                </FieldLabel>
                                <Input
                                    id="custom-name"
                                    value={customForm.data.name}
                                    onChange={(e) =>
                                        customForm.setData(
                                            'name',
                                            e.target.value,
                                        )
                                    }
                                    placeholder="e.g. Portuguese (Brazil)"
                                    aria-invalid={!!customForm.errors.name}
                                />
                                <FieldError>
                                    {customForm.errors.name}
                                </FieldError>
                            </Field>

                            <Field>
                                <FieldLabel htmlFor="custom-native-name">
                                    Native Name
                                </FieldLabel>
                                <Input
                                    id="custom-native-name"
                                    value={customForm.data.native_name}
                                    onChange={(e) =>
                                        customForm.setData(
                                            'native_name',
                                            e.target.value,
                                        )
                                    }
                                    placeholder="e.g. Português (Brasil)"
                                />
                            </Field>

                            <label className="flex cursor-pointer items-center gap-3">
                                <Checkbox
                                    checked={customForm.data.rtl}
                                    onCheckedChange={(checked) =>
                                        customForm.setData(
                                            'rtl',
                                            checked === true,
                                        )
                                    }
                                />
                                <span className="text-sm">
                                    Right-to-left (RTL)
                                </span>
                            </label>

                            <div className="mt-auto">
                                <Button
                                    type="submit"
                                    className="w-full"
                                    disabled={customForm.processing}
                                >
                                    {customForm.processing
                                        ? 'Creating...'
                                        : 'Create Language'}
                                </Button>
                            </div>
                        </form>
                    </TabsContent>
                </Tabs>
            </DialogContent>
        </Dialog>
    );
}
