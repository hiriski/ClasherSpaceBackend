import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { DashboardStats } from '@/types/models';
import { Users, Layers, Eye, Heart } from 'lucide-react';

interface Props {
    stats: DashboardStats;
}

export default function AdminDashboard({ stats }: Props) {
    const cards = [
        {
            title: 'Total Users',
            value: stats.totalUsers || 0,
            icon: Users,
            color: 'text-blue-600',
            bgColor: 'bg-blue-100',
        },
        {
            title: 'Total Base Layouts',
            value: stats.totalBaseLayouts,
            icon: Layers,
            color: 'text-green-600',
            bgColor: 'bg-green-100',
        },
        {
            title: 'Total Views',
            value: stats.totalViews,
            icon: Eye,
            color: 'text-purple-600',
            bgColor: 'bg-purple-100',
        },
        {
            title: 'Total Likes',
            value: stats.totalLikes,
            icon: Heart,
            color: 'text-red-600',
            bgColor: 'bg-red-100',
        },
    ];

    return (
        <AppLayout
            header={
                <div>
                    <h2 className="text-3xl font-bold text-slate-900 dark:text-white">Admin Dashboard</h2>
                    <p className="mt-1 text-sm text-slate-600 dark:text-slate-400">
                        Overview of your Clash of Clans community
                    </p>
                </div>
            }
        >
            <Head title="Admin Dashboard" />

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {cards.map((card) => {
                    const Icon = card.icon;
                    return (
                        <Card key={card.title}>
                            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle className="text-sm font-medium">{card.title}</CardTitle>
                                <div className={`rounded-full p-2 ${card.bgColor}`}>
                                    <Icon className={`h-4 w-4 ${card.color}`} />
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold">{card.value.toLocaleString()}</div>
                            </CardContent>
                        </Card>
                    );
                })}
            </div>

            <div className="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Welcome Admin!</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p className="text-slate-600 dark:text-slate-400">
                            Manage your Clash of Clans community base layouts, users, categories, and tags from this panel.
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Quick Stats</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-2">
                            <div className="flex justify-between">
                                <span className="text-sm text-slate-600 dark:text-slate-400">Average views per layout:</span>
                                <span className="font-semibold">
                                    {stats.totalBaseLayouts > 0
                                        ? Math.round(stats.totalViews / stats.totalBaseLayouts)
                                        : 0}
                                </span>
                            </div>
                            <div className="flex justify-between">
                                <span className="text-sm text-slate-600 dark:text-slate-400">Average likes per layout:</span>
                                <span className="font-semibold">
                                    {stats.totalBaseLayouts > 0
                                        ? Math.round(stats.totalLikes / stats.totalBaseLayouts)
                                        : 0}
                                </span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
