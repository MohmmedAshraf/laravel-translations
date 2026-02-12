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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { store } from '@/routes/ltu/source';
import type { Group } from '@/types';

interface AddKeyDialogProps {
    groups: Group[];
    trigger: React.ReactNode;
}

export function AddKeyDialog({ groups, trigger }: AddKeyDialogProps) {
    const [open, setOpen] = React.useState(false);
    const { processing, data, setData, post, reset, errors } = useForm({
        group_id: '',
        key: '',
        value: '',
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

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>{trigger}</DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Add Translation Key</DialogTitle>
                    <DialogDescription>
                        Create a new source translation key.
                    </DialogDescription>
                </DialogHeader>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="grid gap-2">
                        <Label htmlFor="group_id">Group</Label>
                        <Select
                            value={data.group_id}
                            onValueChange={(v) => setData('group_id', v)}
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Select a group" />
                            </SelectTrigger>
                            <SelectContent>
                                {groups.map((g) => (
                                    <SelectItem
                                        key={g.id}
                                        value={g.id.toString()}
                                    >
                                        {g.namespace
                                            ? `${g.namespace}::${g.name}`
                                            : g.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        {errors.group_id && (
                            <p className="text-sm text-destructive">
                                {errors.group_id}
                            </p>
                        )}
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="key">Key</Label>
                        <Input
                            id="key"
                            value={data.key}
                            onChange={(e) => setData('key', e.target.value)}
                            placeholder="e.g., auth.login"
                            required
                        />
                        {errors.key && (
                            <p className="text-sm text-destructive">
                                {errors.key}
                            </p>
                        )}
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="value">Value</Label>
                        <textarea
                            id="value"
                            value={data.value}
                            onChange={(e) => setData('value', e.target.value)}
                            placeholder="Enter source phrase"
                            rows={3}
                            className="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        />
                        {errors.value && (
                            <p className="text-sm text-destructive">
                                {errors.value}
                            </p>
                        )}
                    </div>
                    <DialogFooter>
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Adding...' : 'Add Key'}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
