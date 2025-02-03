import { route } from "ziggy-js"
import { Typography } from "antd"
import { Link } from "@inertiajs/react"
import { PropsWithChildren } from "react"
import ApplicationLogo from "@/Components/ApplicationLogo"

const GuestLayout = ({ children, title, subtitle }: PropsWithChildren<{ title?: string, subtitle?: string }>) => {

    const { Title, Paragraph } = Typography;

    return (
        <div className="flex min-h-screen flex-col items-center justify-center bg-white px-4">
            <Link href={route('ltu.translation.index')} className="flex h-10 max-w-max">
                <ApplicationLogo className="h-10 w-auto fill-current text-gray-500" />
            </Link>

            <div className="mx-auto w-full max-w-sm lg:w-96">
                <div className="flex flex-col items-center">
                    <Title level={2} className="mt-8 !text-2xl">
                        {title || 'Welcome back!'}
                    </Title>

                    {subtitle && (
                        <Paragraph className="mt-4">{subtitle}</Paragraph>
                    )}

                    <div className="w-full">
                        {children}
                    </div>
                </div>
            </div>
        </div>
    );
}

export default GuestLayout
