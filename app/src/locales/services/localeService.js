const BASE_URL = import.meta.env.VITE_APP_API_URL;

export default class LocaleService {

    constructor(languages = ['en', 'nl']) {
        this.locales = {};
        this.languages = languages;
        this.defaultServer = 'https://budgetbuddy.be:3000';
    }

    async loadLanguages() {
        try {
            await Promise.all(this.languages.map(lang => this.loadLocale(lang)));
        } catch (error) {
            console.error("Failed to load languages:", error);
        }
    }

    async loadLocale(lang) {
        const localUrl = `${this.defaultServer}/src/locales/${lang}/${lang}.json`;
        try {
            this.locales[lang] = await this.fetchJson(localUrl);
        } catch (error) {
            console.error(`Failed to load ${lang} from local, trying server...`, error);
            await this.fetchLocaleFromServer(lang);
        }
    }

    async fetchLocaleFromServer(lang) {
        const serverUrl = `${BASE_URL}/translations`;
        try {
            this.locales[lang] = await this.fetchJson(serverUrl, lang);
        } catch (error) {
            console.error(`Failed to fetch ${lang} locale from server:`, error);
            this.locales[lang] = {};
        }
    }

    async fetchJson(url, lang) {
        const response = await fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Accept-Language': lang
            }
        });
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        return response.json();
    }


    getLocale(lang) {
        return this.locales[lang] || {};
    }
}

