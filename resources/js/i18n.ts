import { createI18n } from 'vue-i18n';
import ru from './locales/ru.json';
import tg from './locales/tg.json';
import type { Locale } from '@/types';

// Тексты интерфейса хранятся в locales/*.json. Английский подключается добавлением en.json.
export const i18n = createI18n({
    legacy: false,
    locale: 'ru',
    fallbackLocale: 'ru',
    messages: { ru, tg },
});

const available = ['ru', 'tg'];

export function setLocale(locale: Locale | undefined): void {
    if (!locale || !available.includes(locale)) return;
    i18n.global.locale.value = locale as 'ru' | 'tg';
    document.documentElement.lang = locale;
}
