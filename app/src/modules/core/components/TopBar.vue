<template>
  <header v-if="!isLoading" class="shadow-md py-5">
    <div class="container mx-auto flex items-center justify-between px-6">

      <div class="flex items-center space-x-20">

        <h1 class="text-xl font-semibold text-gray-800">BudgetBuddy</h1>

        <nav class="flex space-x-4">
          <router-link
              :to="{ name: 'home', params: { lang: $i18n.locale } }" class="text-gray-600 hover:text-blue-500">
            Home
          </router-link>
          <router-link
              v-if="user"
              :to="{ name: 'dashboard', params: { lang: $i18n.locale, month: new Date().getMonth() + 1, year: new Date().getFullYear() } }"
              class="text-gray-600 hover:text-blue-500">
           {{$t('top-bar.my-budget')}}
          </router-link>
          <router-link
              v-if="service.userIsAdmin()"
              :to="{ name: 'admin', params: { lang: $i18n.locale } }"
              class="text-gray-600 hover:text-blue-500">
            Admin
          </router-link>
        </nav>

      </div>

      <div class="flex items-center space-x-4">
        <button v-if="user !== null " type="button">
          <router-link :to="{ name: 'home', params: { lang: $i18n.locale } }"
                       @click="logout"
                       class="text-gray-600 hover:text-blue-500"> {{$t('top-bar.logout')}}
          </router-link>
        </button>

        <button v-if="user === null" type="button">
          <router-link :to="{ name: 'register', params: { lang: $i18n.locale } }"
                       class="text-gray-600 hover:text-blue-500"> {{$t('top-bar.register')}}
          </router-link>
        </button>
        <button v-if="user === null" type="button">
          <router-link :to="{ name: 'login', params: { lang: $i18n.locale } }"
                       class="text-gray-600 hover:text-blue-500"> {{$t('top-bar.login')}}
          </router-link>
        </button>

        <select v-if="!currentPageIsAdminPanel()" @change="changeLanguage"
                class="block appearance-none bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
          <option value="en" :selected="$i18n.locale === 'en'">EN</option>
          <option value="nl" :selected="$i18n.locale === 'nl'">NL</option>
        </select>

      </div>

    </div>
  </header>

</template>

<script>
import {RouterLink} from "vue-router";
import AuthService from "@/modules/auth/services/authService.js";

export default {
  name: "TopBar",
  components: {
    RouterLink
  },
  data() {
    return {
      service: new AuthService(),
      user: null,
      lang: this.$i18n.locale,
      isLoading: false
    }
  },
  async created() {
    this.user = await this.getUserProfile();
  },
  methods: {
    async getUserProfile() {
      this.isLoading = true;
      await this.service.getUserProfile(this.lang);
      if (this.service.getError()) {
        this.isLoading = false;
        return null;
      }
      this.isLoading = false;
      return this.service.response["data"];
    },
    changeLanguage(event) {
      this.$i18n.locale = event.target.value;
      this.$router.push({params: {lang: event.target.value}});
    },
    async logout() {
      try {
        await this.service.logout(this.lang);
        this.user = null;
        this.$router.push({name: 'login', params: {lang: this.lang}});
      } catch (error) {
        console.error('Logout failed:', error);
      }
    },
    currentPageIsAdminPanel() {
      return this.$route.name === 'admin';
    }
  }
}
</script>
