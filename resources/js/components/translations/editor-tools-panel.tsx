import { SimilarKeysTab } from '@/components/translations/similar-keys-tab';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Key } from '@/lib/icons';
import type { SimilarKey } from '@/types';

export const tabTriggerClassName =
    'relative gap-1.5 rounded-none border-b-2 border-transparent px-4 py-2.5 text-xs font-medium text-muted-foreground data-[state=active]:border-primary data-[state=active]:bg-transparent data-[state=active]:text-foreground data-[state=active]:shadow-none';

export interface EditorTab {
    value: string;
    label: string;
    icon: React.ReactNode;
    count?: number;
    content: React.ReactNode;
    contentClassName?: string;
}

interface EditorToolsPanelProps {
    similarKeys: SimilarKey[];
    onApplyTranslation: (text: string) => void;
    defaultTab?: string;
    tabsBefore?: EditorTab[];
    tabsAfter?: EditorTab[];
}

export function EditorToolsPanel({
    similarKeys,
    onApplyTranslation,
    defaultTab = 'similar-keys',
    tabsBefore = [],
    tabsAfter = [],
}: EditorToolsPanelProps) {
    return (
        <Tabs
            defaultValue={defaultTab}
            className="flex min-h-0 flex-1 flex-col gap-0"
        >
            <div className="flex shrink-0 items-center border-b bg-muted/30">
                <TabsList className="h-auto w-full justify-start gap-0 rounded-none bg-transparent p-0">
                    {tabsBefore.map((tab) => (
                        <TabsTrigger
                            key={tab.value}
                            value={tab.value}
                            className={tabTriggerClassName}
                        >
                            {tab.icon}
                            {tab.label}
                            {tab.count !== undefined && (
                                <span className="rounded-full bg-muted px-1.5 text-xs text-muted-foreground tabular-nums">
                                    {tab.count}
                                </span>
                            )}
                        </TabsTrigger>
                    ))}

                    <TabsTrigger
                        value="similar-keys"
                        className={tabTriggerClassName}
                    >
                        <Key className="size-4" />
                        Similar Keys
                        <span className="rounded-full bg-muted px-1.5 text-xs text-muted-foreground tabular-nums">
                            {similarKeys.length}
                        </span>
                    </TabsTrigger>

                    {tabsAfter.map((tab) => (
                        <TabsTrigger
                            key={tab.value}
                            value={tab.value}
                            className={tabTriggerClassName}
                        >
                            {tab.icon}
                            {tab.label}
                            {tab.count !== undefined && (
                                <span className="rounded-full bg-muted px-1.5 text-xs text-muted-foreground tabular-nums">
                                    {tab.count}
                                </span>
                            )}
                        </TabsTrigger>
                    ))}
                </TabsList>
            </div>

            {tabsBefore.map((tab) => (
                <TabsContent
                    key={tab.value}
                    value={tab.value}
                    className={
                        tab.contentClassName ??
                        'flex min-h-0 flex-1 flex-col overflow-y-auto'
                    }
                >
                    {tab.content}
                </TabsContent>
            ))}

            <TabsContent
                value="similar-keys"
                className="flex min-h-0 flex-1 flex-col overflow-y-auto"
            >
                <SimilarKeysTab
                    similarKeys={similarKeys}
                    onUseSuggestion={onApplyTranslation}
                />
            </TabsContent>

            {tabsAfter.map((tab) => (
                <TabsContent
                    key={tab.value}
                    value={tab.value}
                    className={
                        tab.contentClassName ??
                        'flex min-h-0 flex-1 flex-col overflow-y-auto'
                    }
                >
                    {tab.content}
                </TabsContent>
            ))}
        </Tabs>
    );
}
