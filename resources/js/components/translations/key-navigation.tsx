import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { AltArrowLeft, AltArrowRight } from '@/lib/icons';

interface KeyNavigationProps {
    previousHref: string | null;
    nextHref: string | null;
    backHref: string;
}

export function KeyNavigation({
    previousHref,
    nextHref,
    backHref,
}: KeyNavigationProps) {
    return (
        <div className="flex shrink-0 items-center gap-1">
            <Tooltip>
                <TooltipTrigger asChild>
                    <Button
                        variant="ghost"
                        size="icon"
                        disabled={!previousHref}
                        asChild={!!previousHref}
                    >
                        {previousHref ? (
                            <Link href={previousHref}>
                                <AltArrowLeft className="size-4" />
                            </Link>
                        ) : (
                            <span>
                                <AltArrowLeft className="size-4" />
                            </span>
                        )}
                    </Button>
                </TooltipTrigger>
                <TooltipContent>Previous (Alt+Left)</TooltipContent>
            </Tooltip>
            <Tooltip>
                <TooltipTrigger asChild>
                    <Button
                        variant="ghost"
                        size="icon"
                        disabled={!nextHref}
                        asChild={!!nextHref}
                    >
                        {nextHref ? (
                            <Link href={nextHref}>
                                <AltArrowRight className="size-4" />
                            </Link>
                        ) : (
                            <span>
                                <AltArrowRight className="size-4" />
                            </span>
                        )}
                    </Button>
                </TooltipTrigger>
                <TooltipContent>Next (Alt+Right)</TooltipContent>
            </Tooltip>
            <Separator orientation="vertical" className="mx-1 h-5" />
            <Link href={backHref}>
                <Button variant="ghost" size="sm">
                    Back
                </Button>
            </Link>
        </div>
    );
}
