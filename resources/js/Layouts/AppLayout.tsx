import { PropsWithChildren, ReactNode, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { User } from '@/types/models';
import { LayoutDashboard, Users, Layers, Tags, LogOut, Menu, X } from 'lucide-react';
import { Button } from '@/components/ui/button';

export default function AppLayout({ header, children }: PropsWithChildren<{ header?: ReactNode }>) {
    const { auth } = usePage().props as { auth: { user: User } };
    const [sidebarOpen, setSidebarOpen] = useState(false);

    const isAdmin = auth.user.role === 'admin';

    const navigation = isAdmin ? [
        { name: 'Dashboard', href: route('admin.dashboard'), icon: LayoutDashboard },
        { name: 'Users', href: route('admin.users.index'), icon: Users },
        { name: 'Base Layouts', href: route('admin.base-layouts.index'), icon: Layers },
        { name: 'Categories', href: route('admin.categories.index'), icon: Tags },
        { name: 'Tags', href: route('admin.tags.index'), icon: Tags },
    ] : [
        { name: 'Dashboard', href: route('dashboard'), icon: LayoutDashboard },
        { name: 'My Base Layouts', href: route('base-layouts.index'), icon: Layers },
    ];

    return (
        <div className="min-h-screen bg-slate-50 dark:bg-slate-900">
            {/* Sidebar for mobile */}
            {sidebarOpen && (
                <div className="fixed inset-0 z-50 lg:hidden">
                    <div className="fixed inset-0 bg-black/50" onClick={() => setSidebarOpen(false)} />
                    <div className="fixed inset-y-0 left-0 w-64 bg-white dark:bg-slate-800 shadow-lg">
                        <div className="flex h-16 items-center justify-between px-4">
                            <h1 className="text-xl font-bold text-slate-900 dark:text-white">CoC Bases</h1>
                            <button onClick={() => setSidebarOpen(false)}>
                                <X className="h-6 w-6" />
                            </button>
                        </div>
                        <nav className="space-y-1 px-2 py-4">
                            {navigation.map((item) => (
                                <Link
                                    key={item.name}
                                    href={item.href}
                                    className="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700"
                                >
                                    <item.icon className="h-5 w-5" />
                                    {item.name}
                                </Link>
                            ))}
                        </nav>
                    </div>
                </div>
            )}

            {/* Sidebar for desktop */}
            <div className="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
                <div className="flex grow flex-col gap-y-5 overflow-y-auto border-r border-slate-200 bg-white px-6 dark:border-slate-700 dark:bg-slate-800">
                    <div className="flex h-16 shrink-0 items-center">
                        <h1 className="text-xl font-bold text-slate-900 dark:text-white">CoC Bases Admin</h1>
                    </div>
                    <nav className="flex flex-1 flex-col">
                        <ul role="list" className="flex flex-1 flex-col gap-y-7">
                            <li>
                                <ul role="list" className="-mx-2 space-y-1">
                                    {navigation.map((item) => (
                                        <li key={item.name}>
                                            <Link
                                                href={item.href}
                                                className="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-200 dark:hover:bg-slate-700"
                                            >
                                                <item.icon className="h-6 w-6 shrink-0" />
                                                {item.name}
                                            </Link>
                                        </li>
                                    ))}
                                </ul>
                            </li>
                            <li className="-mx-6 mt-auto">
                                <div className="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-slate-900 dark:text-white">
                                    <div className="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                        <span className="text-sm font-medium">{auth.user.name.charAt(0).toUpperCase()}</span>
                                    </div>
                                    <span className="sr-only">Your profile</span>
                                    <div className="flex-1">
                                        <div className="text-sm font-medium">{auth.user.name}</div>
                                        <div className="text-xs text-slate-500 dark:text-slate-400">{auth.user.email}</div>
                                    </div>
                                    <Link href={route('logout')} method="post" as="button">
                                        <LogOut className="h-5 w-5" />
                                    </Link>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div className="lg:pl-64">
                {/* Mobile header */}
                <div className="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:hidden dark:border-slate-700 dark:bg-slate-800">
                    <button
                        type="button"
                        className="-m-2.5 p-2.5 text-slate-700 lg:hidden dark:text-slate-200"
                        onClick={() => setSidebarOpen(true)}
                    >
                        <span className="sr-only">Open sidebar</span>
                        <Menu className="h-6 w-6" />
                    </button>
                    <div className="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        <div className="flex flex-1 items-center">
                            <h1 className="text-lg font-semibold">CoC Bases</h1>
                        </div>
                    </div>
                </div>

                <main className="py-10">
                    <div className="px-4 sm:px-6 lg:px-8">
                        {header && (
                            <header className="mb-8">
                                <div className="mx-auto">
                                    {header}
                                </div>
                            </header>
                        )}
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}
