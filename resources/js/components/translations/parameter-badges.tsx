import { Badge } from '@/components/ui/badge';

interface ParameterBadgesProps {
    parameters: string[] | null;
}

export function ParameterBadges({ parameters }: ParameterBadgesProps) {
    if (!parameters || parameters.length === 0) {
        return null;
    }

    return (
        <div className="flex shrink-0 flex-wrap gap-1.5 border-t px-4 py-2">
            {parameters.map((param) => (
                <Badge
                    key={param}
                    variant="outline"
                    className="font-mono text-xs"
                >
                    {param}
                </Badge>
            ))}
        </div>
    );
}
