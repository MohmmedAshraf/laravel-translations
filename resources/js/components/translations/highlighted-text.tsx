import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
} from '@/components/ui/hover-card';
import { DangerTriangle, HashtagCircle, SquareArrowRightUp } from '@/lib/icons';

type TokenType = 'text' | 'param' | 'choice' | 'pipe';

interface Token {
    value: string;
    type: TokenType;
}

const TOKEN_PATTERN =
    /(:([a-zA-Z_][a-zA-Z0-9_]*))|(\{[0-9]+\})|(\[[0-9]+,[0-9*]+\])|(\|)/g;

function tokenize(text: string): Token[] {
    const tokens: Token[] = [];
    let lastIndex = 0;
    let match: RegExpExecArray | null;

    while ((match = TOKEN_PATTERN.exec(text)) !== null) {
        if (match.index > lastIndex) {
            tokens.push({
                value: text.slice(lastIndex, match.index),
                type: 'text',
            });
        }

        if (match[1]) {
            tokens.push({ value: match[1], type: 'param' });
        } else if (match[3]) {
            tokens.push({ value: match[3], type: 'choice' });
        } else if (match[4]) {
            tokens.push({ value: match[4], type: 'choice' });
        } else if (match[5]) {
            tokens.push({ value: match[5], type: 'pipe' });
        }

        lastIndex = match.index + match[0].length;
    }

    if (lastIndex < text.length) {
        tokens.push({ value: text.slice(lastIndex), type: 'text' });
    }

    return tokens;
}

const badgeClasses =
    'inline cursor-default rounded-md border border-secondary bg-secondary px-1.5 py-px font-mono text-xs font-medium text-secondary-foreground';

function DocsLink({
    href,
    children,
}: {
    href: string;
    children: React.ReactNode;
}) {
    return (
        <a
            href={href}
            target="_blank"
            rel="noopener noreferrer"
            className="mt-2 inline-flex items-center gap-1 text-xs text-primary hover:underline"
        >
            {children}
            <SquareArrowRightUp className="size-4" />
        </a>
    );
}

export default function HighlightedText({ text }: { text: string }) {
    const tokens = tokenize(text);

    return (
        <>
            {tokens.map((token, i) => {
                switch (token.type) {
                    case 'param':
                        return (
                            <HoverCard key={i} openDelay={200} closeDelay={100}>
                                <HoverCardTrigger asChild>
                                    <span className={badgeClasses}>
                                        {token.value}
                                    </span>
                                </HoverCardTrigger>
                                <HoverCardContent
                                    className="w-72 p-0"
                                    align="center"
                                    side="top"
                                >
                                    <div className="flex items-start gap-3 p-3">
                                        <div className="flex size-8 shrink-0 items-center justify-center rounded-full bg-amber-500/10">
                                            <DangerTriangle className="size-4 text-amber-500" />
                                        </div>
                                        <div className="space-y-1">
                                            <p className="text-sm font-medium">
                                                Required parameter
                                            </p>
                                            <p className="text-xs leading-relaxed text-muted-foreground">
                                                <code className="rounded bg-muted px-1 py-0.5 font-mono text-foreground">
                                                    {token.value}
                                                </code>{' '}
                                                is dynamically replaced at
                                                runtime. It must appear in your
                                                translation exactly as written.
                                            </p>
                                            <DocsLink href="https://laravel.com/docs/master/localization#replacing-parameters-in-translation-strings">
                                                Laravel docs
                                            </DocsLink>
                                        </div>
                                    </div>
                                </HoverCardContent>
                            </HoverCard>
                        );
                    case 'choice':
                        return (
                            <HoverCard key={i} openDelay={200} closeDelay={100}>
                                <HoverCardTrigger asChild>
                                    <span className={badgeClasses}>
                                        {token.value}
                                    </span>
                                </HoverCardTrigger>
                                <HoverCardContent
                                    className="w-72 p-0"
                                    align="center"
                                    side="top"
                                >
                                    <div className="flex items-start gap-3 p-3">
                                        <div className="flex size-8 shrink-0 items-center justify-center rounded-full bg-blue-500/10">
                                            <HashtagCircle className="size-4 text-blue-500" />
                                        </div>
                                        <div className="space-y-1">
                                            <p className="text-sm font-medium">
                                                Plural selector
                                            </p>
                                            <p className="text-xs leading-relaxed text-muted-foreground">
                                                <code className="rounded bg-muted px-1 py-0.5 font-mono text-foreground">
                                                    {token.value}
                                                </code>{' '}
                                                picks which variant to display
                                                based on the count. Keep all
                                                selectors and pipe separators
                                                intact.
                                            </p>
                                            <DocsLink href="https://laravel.com/docs/master/localization#pluralization">
                                                Laravel docs
                                            </DocsLink>
                                        </div>
                                    </div>
                                </HoverCardContent>
                            </HoverCard>
                        );
                    case 'pipe':
                        return (
                            <span
                                key={i}
                                className="mx-0.5 text-muted-foreground/60"
                            >
                                |
                            </span>
                        );
                    default:
                        return token.value;
                }
            })}
        </>
    );
}
