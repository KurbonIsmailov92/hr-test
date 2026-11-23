export type Locale = 'ru' | 'tg' | 'en';

export interface AuthUser {
    id: string;
    name: string;
    email: string;
    locale: Locale;
    roles: string[];
    permissions: string[];
}

export type PageProps = {
    appName: string;
    locale: Locale;
    locales: Locale[];
    displayTimezone: string;
    auth: { user: AuthUser | null };
    flash: { success?: string | null; status?: string | null; error?: string | null };
};
