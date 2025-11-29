import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { DashboardStats } from '@/types/models';
import { Layers, Eye, Heart, Plus } from 'lucide-react';
import { Button } from '@/components/ui/button';

interface Props {
    stats: DashboardStats;
}

export default function UserDashboard({ stats }: Props) {
    const cards = [
        {
            title: 'My Base Layouts',
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
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-3xl font-bold text-slate-900 dark:text-white">Dashboard</h2>
                        <p className="mt-1 text-sm text-slate-600 dark:text-slate-400">
                            Overview of your base layouts
                        </p>
                    </div>
                    <Link href={route('base-layouts.create')}>
                        <Button>
                            <Plus className="h-4 w-4 mr-2" />
                            Create Base Layout
                        </Button>
                    </Link>
                </div>
            }
        >
            <Head title="Dashboard" />

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
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

            <div className="mt-8 grid grid-cols-1 gap-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Welcome!</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p className="text-slate-600 dark:text-slate-400 mb-4">
                            Share your Clash of Clans base layouts with the community. Create, manage, and track the performance of your base layouts.
                        </p>
                        <Link href={route('base-layouts.index')}>
                            <Button variant="outline">
                                View My Base Layouts
                            </Button>
                        </Link>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
