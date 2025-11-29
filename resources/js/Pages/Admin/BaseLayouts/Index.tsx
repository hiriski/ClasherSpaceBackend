import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import { BaseLayout, PaginatedData } from '@/types/models';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { formatDateTime } from '@/lib/utils';
import { ChevronLeft, ChevronRight, Trash2, Eye, Edit } from 'lucide-react';

interface Props {
    baseLayouts: PaginatedData<BaseLayout>;
}

export default function BaseLayoutsIndex({ baseLayouts }: Props) {
    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this base layout?')) {
            router.delete(route('admin.base-layouts.destroy', id));
        }
    };

    return (
        <AppLayout
            header={
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-3xl font-bold text-slate-900 dark:text-white">Base Layouts Management</h2>
                        <p className="mt-1 text-sm text-slate-600 dark:text-slate-400">
                            Manage all base layouts in the system
                        </p>
                    </div>
                </div>
            }
        >
            <Head title="Base Layouts Management" />

            <Card>
                <CardHeader>
                    <CardTitle>All Base Layouts ({baseLayouts.total})</CardTitle>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Type</TableHead>
                                <TableHead>Level</TableHead>
                                <TableHead>Author</TableHead>
                                <TableHead>Views</TableHead>
                                <TableHead>Likes</TableHead>
                                <TableHead>War Base</TableHead>
                                <TableHead>Created</TableHead>
                                <TableHead className="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {baseLayouts.data.map((layout) => (
                                <TableRow key={layout.id}>
                                    <TableCell className="font-medium">{layout.name || 'Untitled'}</TableCell>
                                    <TableCell>
                                        <Badge variant={layout.baseType === 'townhall' ? 'default' : 'secondary'}>
                                            {layout.baseType === 'townhall' ? 'Town Hall' : 'Builder Hall'}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        TH {layout.townHallLevel || layout.builderHallLevel || '-'}
                                    </TableCell>
                                    <TableCell>{layout.user?.name}</TableCell>
                                    <TableCell>{layout.views.toLocaleString()}</TableCell>
                                    <TableCell>{layout.likedCount.toLocaleString()}</TableCell>
                                    <TableCell>
                                        <Badge variant={layout.isWarBase ? 'destructive' : 'outline'}>
                                            {layout.isWarBase ? 'Yes' : 'No'}
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="text-sm text-slate-600">
                                        {formatDateTime(layout.createdAt)}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        <div className="flex justify-end gap-2">
                                            <Link href={route('admin.base-layouts.show', layout.id)}>
                                                <Button variant="outline" size="sm">
                                                    <Eye className="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <Button
                                                variant="destructive"
                                                size="sm"
                                                onClick={() => handleDelete(layout.id)}
                                            >
                                                <Trash2 className="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>

                    {/* Pagination */}
                    <div className="mt-4 flex items-center justify-between">
                        <p className="text-sm text-slate-600 dark:text-slate-400">
                            Showing {baseLayouts.from} to {baseLayouts.to} of {baseLayouts.total} results
                        </p>
                        <div className="flex gap-2">
                            <Link
                                href={`${route('admin.base-layouts.index')}?page=${baseLayouts.current_page - 1}`}
                                preserveState
                            >
                                <Button
                                    variant="outline"
                                    size="sm"
                                    disabled={baseLayouts.current_page === 1}
                                >
                                    <ChevronLeft className="h-4 w-4" />
                                    Previous
                                </Button>
                            </Link>
                            <Link
                                href={`${route('admin.base-layouts.index')}?page=${baseLayouts.current_page + 1}`}
                                preserveState
                            >
                                <Button
                                    variant="outline"
                                    size="sm"
                                    disabled={baseLayouts.current_page === baseLayouts.last_page}
                                >
                                    Next
                                    <ChevronRight className="h-4 w-4" />
                                </Button>
                            </Link>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </AppLayout>
    );
}
