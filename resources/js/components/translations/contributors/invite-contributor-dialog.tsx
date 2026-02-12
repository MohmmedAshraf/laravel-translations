import { useForm } from '@inertiajs/react';
import * as React from 'react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Flag } from '@/components/ui/flag';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { MultiSelect } from '@/components/ui/multi-select';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { store } from '@/routes/ltu/contributors';

interface Language {
    id: number;
    code: string;
    name: string;
}

interface Role {
    value: string;
    label: string;
}

interface InviteContributorDialogProps {
    languages: Language[];
    roles: Role[];
    trigger: React.ReactNode;
}

export function InviteContributorDialog({
    languages,
    roles,
    trigger,
}: InviteContributorDialogProps) {
    const [open, setOpen] = React.useState(false);
    const { processing, data, setData, post, reset, errors } = useForm({
        email: '',
        name: '',
        role: 'translator',
        language_ids: [] as number[],
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(store.url(), {
            onSuccess: () => {
                reset();
                setOpen(false);
            },
        });
    };

    const languageOptions = languages.map((lang) => ({
        value: lang.id,
        label: lang.name,
        icon: <Flag code={lang.code} className="size-4" />,
    }));

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>{trigger}</DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Invite Contributor</DialogTitle>
                    <DialogDescription>
                        Send an invitation to a new contributor.
                    </DialogDescription>
                </DialogHeader>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="grid gap-2">
                        <Label htmlFor="name">Name</Label>
                        <Input
                            id="name"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            placeholder="Full name"
                            required
                        />
                        {errors.name && (
                            <p className="text-sm text-destructive">
                                {errors.name}
                            </p>
                        )}
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            placeholder="email@example.com"
                            required
                        />
                        {errors.email && (
                            <p className="text-sm text-destructive">
                                {errors.email}
                            </p>
                        )}
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="role">Role</Label>
                        <Select
                            value={data.role}
                            onValueChange={(v) => setData('role', v)}
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Select a role" />
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
                            {processing ? 'Inviting...' : 'Send Invite'}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
