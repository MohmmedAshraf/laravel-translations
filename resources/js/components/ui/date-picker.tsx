import { format } from 'date-fns';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Card, CardContent } from '@/components/ui/card';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Calendar as CalendarIcon } from '@/lib/icons';
import { cn } from '@/lib/utils';

interface DatePickerProps {
    value: Date | undefined;
    onChange: (date: Date | undefined) => void;
    placeholder?: string;
    disabled?: boolean;
    maxDate?: Date;
}

export function DatePicker({
    value,
    onChange,
    placeholder = 'Pick a date',
    disabled,
    maxDate,
}: DatePickerProps) {
    const [currentMonth, setCurrentMonth] = useState<Date>(
        value ?? new Date(),
    );

    return (
        <Popover>
            <PopoverTrigger asChild>
                <Button
                    variant="outline"
                    disabled={disabled}
                    className={cn(
                        'w-full justify-start text-left font-normal',
                        !value && 'text-muted-foreground',
                    )}
                >
                    <CalendarIcon className="size-4" />
                    {value ? format(value, 'PPP') : placeholder}
                </Button>
            </PopoverTrigger>
            <PopoverContent className="w-auto p-0" align="start">
                <Card className="w-fit border-0 shadow-none py-2">
                    <CardContent>
                        <Calendar
                            mode="single"
                            selected={value}
                            onSelect={onChange}
                            month={currentMonth}
                            onMonthChange={setCurrentMonth}
                            fixedWeeks
                            disabled={maxDate ? { after: maxDate } : undefined}
                            className="p-0 [--cell-size:--spacing(9.5)]"
                        />
                    </CardContent>
                </Card>
            </PopoverContent>
        </Popover>
    );
}
