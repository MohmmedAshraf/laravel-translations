import { useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';
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

interface GroupFormFields {
    name: string;
    namespace: string;
}

interface GroupFormDialogProps {
    trigger: React.ReactNode;
    title: string;
    description: string;
    submitLabel: string;
    submittingLabel: string;
    initialValues?: GroupFormFields;
    onSubmit: (data: GroupFormFields, helpers: { reset: () => void }) => void;
    processing: boolean;
    errors: Partial<Record<keyof GroupFormFields, string>>;
}

function GroupFormContent({
    title,
    description,
    submitLabel,
    submittingLabel,
    data,
    setData,
    onSubmit,
    processing,
    errors,
}: Omit<GroupFormDialogProps, 'trigger' | 'initialValues' | 'onSubmit'> & {
    data: GroupFormFields;
    setData: (key: keyof GroupFormFields, value: string) => void;
    onSubmit: (e: React.FormEvent) => void;
}) {
    return (
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{title}</DialogTitle>
                <DialogDescription>{description}</DialogDescription>
            </DialogHeader>
            <form onSubmit={onSubmit} className="space-y-4">
                <div className="grid gap-2">
                    <Label htmlFor="name">Group Name</Label>
                    <Input
                        id="name"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        placeholder="e.g., auth, messages, validation"
                        required
                    />
                    {errors.name && (
                        <p className="text-sm text-destructive">
                            {errors.name}
                        </p>
                    )}
                </div>
                <div className="grid gap-2">
                    <Label htmlFor="namespace">Namespace (Optional)</Label>
                    <Input
                        id="namespace"
                        value={data.namespace}
                        onChange={(e) => setData('namespace', e.target.value)}
                        placeholder="e.g., vendor/package-name"
                    />
                    {errors.namespace && (
                        <p className="text-sm text-destructive">
                            {errors.namespace}
                        </p>
                    )}
                </div>
                <DialogFooter>
                    <Button type="submit" disabled={processing}>
                        {processing ? submittingLabel : submitLabel}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    );
}

export function AddGroupDialog({
    trigger,
    submitUrl,
}: {
    trigger: React.ReactNode;
    submitUrl: string;
}) {
    const [open, setOpen] = useState(false);
    const { processing, data, setData, post, reset, errors } = useForm({
        name: '',
        namespace: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(submitUrl, {
            onSuccess: () => {
                reset();
                setOpen(false);
            },
        });
    };

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>{trigger}</DialogTrigger>
            <GroupFormContent
                title="Create Group"
                description="Add a new translation group to organize your keys."
                submitLabel="Create Group"
                submittingLabel="Creating..."
                data={data}
                setData={setData}
                onSubmit={handleSubmit}
                processing={processing}
                errors={errors}
            />
        </Dialog>
    );
}

export function EditGroupDialog({
    trigger,
    initialValues,
    submitUrl,
    open,
    onOpenChange,
}: {
    trigger?: React.ReactNode;
    initialValues: GroupFormFields;
    submitUrl: string;
    open?: boolean;
    onOpenChange?: (open: boolean) => void;
}) {
    const { processing, data, setData, put, reset, errors } =
        useForm(initialValues);

    useEffect(() => {
        if (open) {
            reset();
        }
    }, [open, reset]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(submitUrl, {
            onSuccess: () => onOpenChange?.(false),
        });
    };

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            {trigger && <DialogTrigger asChild>{trigger}</DialogTrigger>}
            <GroupFormContent
                title="Edit Group"
                description="Update group settings."
                submitLabel="Save Changes"
                submittingLabel="Saving..."
                data={data}
                setData={setData}
                onSubmit={handleSubmit}
                processing={processing}
                errors={errors}
            />
        </Dialog>
    );
}
