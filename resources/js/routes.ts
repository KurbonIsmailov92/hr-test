import type { AuthUser } from '@/types';

// Пути совпадают с routes/*.php. Источник истины: backend; соответствие проверяется тестами.
export const paths = {
    home: '/',
    login: '/login',
    register: '/register',
    forgotPassword: '/forgot-password',
    passwordUpdate: '/reset-password',
    logout: '/logout',
    locale: '/locale',
    candidate: '/candidate',
    portal: '/portal',
    admin: '/admin',
} as const;

/** Стартовая страница пользователя по разрешениям (только для навигации, защита на backend). */
export function homePathFor(user: AuthUser | null): string {
    if (!user) return paths.home;
    if (user.permissions.includes('admin.access')) return paths.admin;
    if (user.permissions.includes('portal.access')) return paths.portal;
    return paths.candidate;
}
