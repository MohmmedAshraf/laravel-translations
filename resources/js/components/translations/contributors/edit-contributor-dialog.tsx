import { useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Flag } from '@/components/ui/flag';
import { Label } from '@/components/ui/label';
import { MultiSelect } from '@/components/ui/multi-select';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { update } from '@/routes/ltu/contributors';

interface Language {
    id: number;
    code: string;
    name: string;
}

interface Role {
    value: string;
    label: string;
}

interface ContributorItem {
    id: string;
    name: string;
    email: string;
    role: string;
    is_active: boolean;
    languages: Language[];
    [key: string]: unknown;
}

interface EditContributorDialogProps {
    contributor: ContributorItem;
    languages: Language[];
    roles: Role[];
    open: boolean;
    onOpenChange: (open: boolean) => void;
}

export function EditContributorDialog({
    contributor,
    languages,
    roles,
    open,
    onOpenChange,
}: EditContributorDialogProps) {
    const { processing, data, setData, put, errors } = useForm({
        role: contributor.role,
        language_ids: (contributor.languages ?? []).map(
            (l) => l.id,
        ) as number[],
        is_active: contributor.is_active,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(update.url(contributor.id), {
            onSuccess: () => onOpenChange(false),
        });
    };

    const languageOptions = languages.map((lang) => ({
        value: lang.id,
        label: lang.name,
        icon: <Flag code={lang.code} className="size-4" />,
    }));

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Edit {contributor.name}</DialogTitle>
                    <DialogDescription>{contributor.email}</DialogDescription>
                </DialogHeader>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="grid gap-2">
                        <Label htmlFor="edit-role">Role</Label>
                        <Select
                            value={data.role}
                            onValueChange={(v) => setData('role', v)}
                        >
                            <SelectTrigger id="edit-role">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                {roles.map((r) => (
                                    <SelectItem key={r.value} value={r.value}>
                                        {r.label}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        {errors.role && (
                            <p className="text-sm text-destructive">
                                {errors.role}
                            </p>
                        )}
                    </div>
                    {languages.length > 0 && (
                        <div className="grid gap-2">
                            <Label>Languages</Label>
                            <p className="text-xs text-muted-foreground">
                                Leave empty for access to all languages.
                            </p>
                            <MultiSelect
                                options={languageOptions}
                                value={data.language_ids}
                                onChange={(ids) =>
                                    setData('language_ids', ids as number[])
                                }
                                placeholder="Select languages..."
                                emptyMessage="No languages found."
                            />
                        </div>
                    )}
                    <DialogFooter>
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Saving...' : 'Save Changes'}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
