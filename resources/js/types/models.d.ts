export type Role = 'admin' | 'general_user';

export type BaseType = 'townhall' | 'builder';

export interface User {
    id: number;
    name: string;
    username: string;
    email: string;
    emailVerifiedAt: string | null;
    photoUrl: string | null;
    avatarTextColor: string | null;
    gender: 'male' | 'female' | 'none' | null;
    about: string | null;
    dateOfBirthday: string | null;
    status: number;
    role: Role;
    createdAt: string;
    updatedAt: string;
}

export interface BaseLayoutCategory {
    id: number;
    name: string;
    slug: string;
    uiColor: string | null;
    isInitial: boolean;
    createdAt: string;
    updatedAt: string;
}

export interface BaseLayoutTag {
    id: number;
    name: string;
    slug: string;
    isInitial: boolean;
    createdAt: string;
    updatedAt: string;
}

export interface BaseLayout {
    id: number;
    userId: number;
    name: string | null;
    link: string;
    description: string | null;
    townHallLevel: number | null;
    builderHallLevel: number | null;
    baseType: BaseType;
    imageUrls: string | null;
    views: number;
    likedCount: number;
    isWarBase: boolean;
    createdAt: string;
    updatedAt: string;
    user?: User;
    categories?: BaseLayoutCategory[];
    tags?: BaseLayoutTag[];
}

export interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

export interface DashboardStats {
    totalBaseLayouts: number;
    totalViews: number;
    totalLikes: number;
    totalUsers?: number;
}
