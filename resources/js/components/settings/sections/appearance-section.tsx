import { RotateCcw } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useAccentColor, ACCENT_COLORS } from '@/hooks/use-accent-color';
import { useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';
import {
    ThemeCard,
    LightModePreview,
    DarkModePreview,
    SystemPreview,
} from '../theme-card';

export function AppearanceSection() {
    const { appearance, updateAppearance } = useAppearance();
    const { accentColor, updateAccentColor } = useAccentColor();

    const isDefault = appearance === 'system' && accentColor === 'default';

    const handleReset = () => {
        updateAppearance('system');
        updateAccentColor('default');
    };

    return (
        <div className="h-full overflow-y-auto p-4 md:p-6">
            {/* Themes */}
            <div className="pb-5">
                <h3 className="text-base font-semibold">Themes</h3>
                <p className="mb-4 text-sm text-muted-foreground">
                    Choose your preferred color scheme
                </p>
                <div className="grid grid-cols-3 gap-2 md:gap-4">
                    <ThemeCard
                        label={
                            <>
                                Light
                                <span className="hidden md:inline"> Mode</span>
                            </>
                        }
                        isSelected={appearance === 'light'}
                        onClick={() => updateAppearance('light')}
                        preview={<LightModePreview />}
                    />
                    <ThemeCard
                        label={
                            <>
                                Dark
                                <span className="hidden md:inline"> Mode</span>
                            </>
                        }
                        isSelected={appearance === 'dark'}
                        onClick={() => updateAppearance('dark')}
                        preview={<DarkModePreview />}
                    />
                    <ThemeCard
                        label="System"
                        isSelected={appearance === 'system'}
                        onClick={() => updateAppearance('system')}
                        preview={<SystemPreview />}
                    />
                </div>
            </div>

            <div className="border-t border-border" />

            {/* Accent Colors */}
            <div className="py-5">
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p className="text-sm font-medium">Accent Color</p>
                        <p className="text-sm text-muted-foreground">
                            Customize the primary accent color
                        </p>
                    </div>
                    <div className="flex flex-wrap items-center gap-2">
                        {ACCENT_COLORS.map((color) => (
                            <button
                                key={color.value}
                                onClick={() => updateAccentColor(color.value)}
                                className={cn(
                                    'h-7 w-7 rounded-full transition-all',
                                    accentColor === color.value
                                        ? 'ring-2 ring-offset-2 ring-offset-background'
                                        : 'hover:scale-110',
                                    color.value === 'default' &&
                                        'bg-neutral-900 dark:bg-white',
                                )}
                                style={
                                    color.value !== 'default'
                                        ? ({
                                              backgroundColor: color.value,
                                              '--tw-ring-color': color.value,
                                          } as React.CSSProperties)
                                        : ({
                                              '--tw-ring-color':
                                                  'var(--foreground)',
                                          } as React.CSSProperties)
                                }
                                title={color.name}
                            />
                        ))}
                    </div>
                </div>
            </div>

            <div className="border-t border-border" />

            {/* Reset */}
            <div className="py-5">
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p className="text-sm font-medium">Reset to Default</p>
                        <p className="text-sm text-muted-foreground">
                            Restore system theme and default accent color
                        </p>
                    </div>
                    <Button
                        variant="outline"
                        size="sm"
                        onClick={handleReset}
                        disabled={isDefault}
                        className="w-full md:w-auto"
                    >
                        <RotateCcw className="size-4" />
                        Reset
                    </Button>
                </div>
            </div>
        </div>
    );
}
