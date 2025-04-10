import './assets/css/reset.css'
import './assets/css/main.css'

import {createApp} from 'vue'
import router from './router'
import App from './App.vue'

import {createI18n} from 'vue-i18n';
import LocaleService from './locales/services/localeService';

const localeService = new LocaleService()
await localeService.loadLanguages();
const en = localeService.getLocale('en');
const nl = localeService.getLocale('nl');


const i18n = createI18n({
    legacy: false,
    locale: 'en',
    messages: {
        en: en,
        nl: nl
    }
});

router.afterEach((to) => {
    const availableLocales = ['en', 'nl'];
    const lang = to.params.lang;
    if (!availableLocales.includes(lang)) return;
    i18n.global.locale.value = lang;
});

const app = createApp(App);
app.use(router);
app.use(i18n);
app.mount('#app');