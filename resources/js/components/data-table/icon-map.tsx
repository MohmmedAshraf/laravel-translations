import { BiLogoPhp } from 'react-icons/bi';
import { RiReactjsFill, RiVuejsFill } from 'react-icons/ri';
import { SiLaravel } from 'react-icons/si';
import {
    AddCircle,
    AltArrowDown,
    AltArrowUp,
    ArchiveDown,
    Bell,
    Bolt,
    Box,
    Calendar,
    Card,
    CheckCircle,
    ClockCircle,
    CloseCircle,
    Code,
    DangerCircle,
    Diff,
    DangerTriangle,
    EyeClosed,
    File,
    DocumentText,
    Flag,
    Folder2,
    Forbidden,
    Global,
    Health,
    Heart,
    Home2,
    Key,
    Link,
    Lock,
    Letter,
    ChatRound,
    Magnifer,
    MinusCircle,
    Pause,
    Play,
    Refresh,
    Scanner,
    Settings,
    Shield,
    Stars,
    Star,
    Tag,
    TrashBin2,
    User,
    Translate,
    UsersGroupRounded,
} from '@/lib/icons';

type IconComponent = React.ComponentType<{ className?: string }>;

const ICON_MAP: Record<string, IconComponent> = {
    activity: Health,
    'alert-circle': DangerCircle,
    'alert-triangle': DangerTriangle,
    archive: ArchiveDown,
    'arrow-down': AltArrowDown,
    'arrow-up': AltArrowUp,
    ban: Forbidden,
    bell: Bell,
    calendar: Calendar,
    check: CheckCircle,
    'check-circle': CheckCircle,
    clock: ClockCircle,
    code: Code,
    'credit-card': Card,
    diff: Diff,
    'eye-off': EyeClosed,
    file: File,
    'file-text': DocumentText,
    flag: Flag,
    folder: Folder2,
    globe: Global,
    heart: Heart,
    home: Home2,
    key: Key,
    link: Link,
    lock: Lock,
    mail: Letter,
    'message-circle': ChatRound,
    minus: MinusCircle,
    package: Box,
    pause: Pause,
    play: Play,
    plus: AddCircle,
    'refresh-cw': Refresh,
    scan: Scanner,
    'scan-search': Magnifer,
    search: Magnifer,
    settings: Settings,
    shield: Shield,
    sparkles: Stars,
    star: Star,
    tag: Tag,
    translate: Translate,
    trash: TrashBin2,
    user: User,
    users: UsersGroupRounded,
    'x-circle': CloseCircle,
    zap: Bolt,
    'scanner-blade': SiLaravel,
    'scanner-vue': RiVuejsFill,
    'scanner-react': RiReactjsFill,
    'scanner-php': BiLogoPhp,
};

const COLOR_MAP: Record<string, string> = {
    green: 'text-green-500',
    red: 'text-red-500',
    blue: 'text-blue-500',
    yellow: 'text-yellow-500',
    amber: 'text-amber-500',
    orange: 'text-orange-500',
    purple: 'text-purple-500',
    pink: 'text-pink-500',
    neutral: 'text-neutral-400',
    gray: 'text-gray-400',
};

export function getIcon(name: string | undefined): IconComponent | undefined {
    if (!name) return undefined;
    return ICON_MAP[name];
}

export function getColorClass(color: string | undefined): string {
    if (!color) return '';
    return COLOR_MAP[color] ?? '';
}

export function renderIcon(
    name: string | undefined,
    color?: string,
    className = 'size-4',
): React.ReactNode {
    if (!name) return null;

    const Icon = getIcon(name);
    if (!Icon) {
        return (
            <svg
                className={`${className} ${getColorClass(color)}`.trim()}
                viewBox="0 0 24 24"
                fill="currentColor"
            >
                <circle cx="12" cy="12" r="4" />
            </svg>
        );
    }

    const colorClass = getColorClass(color);
    return <Icon className={`${className} ${colorClass}`.trim()} />;
}
